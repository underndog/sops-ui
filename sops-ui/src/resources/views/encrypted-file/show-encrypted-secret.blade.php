@section('content')
<div class="row mt-4">
    <div class="col-lg-9 col-12 mx-auto position-relative">
        <div class="card">
            <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-dark shadow text-center border-radius-xl mt-n4 me-3 float-start">
                    <i class="material-icons opacity-10">event</i>
                </div>
                <h6 class="mb-0">Encrypted Secret.</h6>
            </div>
            <div class="card-body pt-2">
                <!-- <div class="input-group input-group-dynamic">
                    <label for="projectName" class="form-label">Project Name</label>
                    <input type="text" class="form-control" id="projectName" />
                </div> -->
                <label class="mt-4">Description</label>
                <p class="form-text text-muted ms-1">
                    the secret will be encrypted by SOPS and KMS (AWS)
                </p>
                <div id="editor">
                    <!-- <p>Hello World!</p>
                    <p>Some initial <strong>bold</strong> text</p>
                    <p><br /></p> -->
                    
                    <pre>{{ $yaml_encrypted }}</pre>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <button type="button" name="button" onclick="window.location='{{ url('/') }}'" class="btn bg-gradient-dark m-0 ms-2">Work with new Secret</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection