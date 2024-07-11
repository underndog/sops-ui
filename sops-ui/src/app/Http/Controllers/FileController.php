<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Symfony\Component\Yaml\Yaml;

use Session;

class FileController extends Controller
{
    public function file_upload(Request $request){
        $request->validate([
            'file' => 'required|file|max:2048',
        ]);
        $get_file = $request->file('file');

        if ($get_file){
            $get_file_name = $get_file->getClientOriginalName();
            $name_file = current(explode('.',$get_file_name));
            $new_file =  $name_file.rand(0,99).'.'.$get_file->getClientOriginalExtension();
            $full_name_file = $new_file.'.'.$get_file->getClientOriginalExtension();
            $get_file->move('public/uploads/yaml',$full_name_file);
            Session::put('uploaded_filename',$full_name_file);
            return "Upload file ".$full_name_file." successfully";
        }
    }

    public function encrypt_file(Request $request) {
        // Validate the incoming request data
        $request->validate([
            'keys.*' => 'required|string',
            'values.*' => 'required|string',
        ]);

        $keys = $request->input('keys');
        $values = $request->input('values');

        // // Debug: Print the $values to check
        // dd($values);

        // Process each key-value pair
        $dataItems = [];
        for ($i = 0; $i < count($keys); $i++) {
            $dataItems[$keys[$i]] = base64_encode($values[$i]);
        }

        // // Create the YAML structure
        // $yamlData = [
        //     'apiVersion' => 'v1',
        //     'kind' => 'Secret',
        //     'metadata' => [
        //         'name' => 'my-secret',
        //         'namespace' => 'default',
        //     ],
        //     'data' => $dataItems,
        // ];

        // // Convert the array to YAML
        // $yamlContent = Yaml::dump($yamlData);

        // Get the parsed YAML from the session
        $parsedYaml = Session::get('parsed_yaml', []);

        // Update the data section in the parsed YAML
        $parsedYaml['data'] = $dataItems;

        // Convert the updated YAML content back to string
        $yamlContent = Yaml::dump($parsedYaml);

        // Save the updated YAML content to a file
        $filePath = 'public/uploads/yaml/encrypted-'.Session::get('uploaded_filename');
        file_put_contents($filePath, $yamlContent);

        try {
            $sopsGuardiansURL = env('SOPS_GUARDIANS_URL', 'http://localhost:9999');
            // Send the request to the external service
            $response = Http::asMultipart()->post($sopsGuardianURL.'/encrypt-file', [
                [
                    'name' => 'name',
                    'contents' => Session::get('uploaded_filename') // This is the value for the 'name' field
                ],
                [
                    'name' => 'yaml-file',
                    'contents' => fopen($filePath, 'r'),
                    'filename' => Session::get('uploaded_filename'),
                    'headers' => [
                        'Content-Type' => 'text/plain' // Set the content type for the file part specifically
                    ]
                ],
                [
                    'name' => 'kms-arn',
                    'contents' => $kms_arn = Session::get('kms_arn'),
                ]

            ]);
            // Check if the request was successful
            if ($response->successful()) {
                $responseData = $response->json(); // Get the response data as an array
                $yamlEncrypted = $responseData['data']; // Extract the 'data' field containing the YAML content

                // echo $yamlContent;
                $yamlEncryptedView  = view('encrypted-file.show-encrypted-secret')->with('yaml_encrypted',$yamlEncrypted);


                return view('layout')->with('encrypted-file.show-encrypted-secret', $yamlEncryptedView);
            } else {
                return response()->json(['error' => 'Failed to decrypt the file'], $response->status());
            }
        } catch (\Exception $e) {
            // Handle any errors
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }

        // Return a response to the user
        return redirect()->back()->with('success', 'Secret file updated successfully.');
    }

    public function decrypt_file(Request $request) {
        // Specify the path to your local file
        $file_path = 'public/uploads/yaml/'.Session::get('uploaded_filename');
        $uploaded_filename = Session::get('uploaded_filename');

        // Parse the YAML file
        $yamlContents = Yaml::parseFile($file_path);

        // Extract the arn value
        $arn = $yamlContents['sops']['kms'][0]['arn'] ?? 'ARN not found';
        Session::put('kms_arn',$arn);


        // Ensure the file exists
        if (!file_exists($file_path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        try {
            $sopsGuardiansURL = env('SOPS_GUARDIANS_URL', 'http://localhost:9999');
            // Send the request to the external service
            $response = Http::asMultipart()->post($sopsGuardiansURL.'/decrypt-file', [
                [
                    'name' => 'name',
                    'contents' => $uploaded_filename // This is the value for the 'name' field
                ],
                [
                    'name' => 'yaml-file',
                    'contents' => fopen($file_path, 'r'),
                    'filename' => $uploaded_filename,
                    'headers' => [
                        'Content-Type' => 'text/plain' // Set the content type for the file part specifically
                    ]
                ]
            ]);
            // Check if the request was successful
            if ($response->successful()) {
                $responseData = $response->json(); // Get the response data as an array
                $yamlContent = $responseData['data']; // Extract the 'data' field containing the YAML content

                // Parse the YAML content using Symfony YAML component
                $parsedYaml = Yaml::parse($yamlContent);

                // Save the parsed YAML content in the session for future use
                Session::put('parsed_yaml', $parsedYaml);

                // Iterate over each item in the data section
                $dataItems = [];
                if (isset($parsedYaml['data']) && is_array($parsedYaml['data'])) {
                    foreach ($parsedYaml['data'] as $key => $value) {
                        // Store each key-value pair in an array
                        $dataItems[] = ["key" => $key, "value" => $value];
                    }
                }

                $manager_items  = view('encrypted-file.adjust-secret')->with('data_items',$dataItems);

                // Return the key-value pairs as a JSON response
                // return response()->json($dataItems);
                return view('layout')->with('encrypted-file.adjust-secret', $manager_items);
            } else {
                return response()->json(['error' => 'Failed to decrypt the file'], $response->status());
            }
        } catch (\Exception $e) {
            // Handle any errors
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }

        // Session::put('uploaded_filename',null);

        // // Return the YAML content
        // return response($yamlContent, 200)->header('Content-Type', 'text/plain');
    }
}