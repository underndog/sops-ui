// Get the CSRF token from the meta tag
let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Dropzone configuration
Dropzone.options.productYaml = {
    paramName: "file", // The name that will be used to transfer the file
    maxFilesize: 2, // Maximum file size in MB
    acceptedFiles: ".yaml,.yml", // Accepted file types
    headers: {'X-CSRF-TOKEN': csrfToken},
    init: function() {

        // var myDropzone = this;

        this.on("success", function(file, response) {
            // Handle the successful upload response here
            console.log("File successfully uploaded:", response);
        });
        this.on("error", function(file, errorMessage) {
            // Handle the error response here
            console.log("File upload error:", errorMessage);
        });
    }
};