@extends('layout')
@section('content')
<div class="row min-vh-80">
    <div class="col-lg-8 col-md-10 col-12 m-auto">
        <h3 class="mt-3 mb-0 text-center">Decypt A Encypted File</h3>
        <p class="lead font-weight-normal opacity-8 mb-7 text-center">You can adjust the encypted by SOPS.</p>
        <div class="card">
            <div class="card-header p-0 position-relative mt-n5 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                    <div class="multisteps-form__progress">
                        <button class="multisteps-form__progress-btn js-active" type="button" title="Product Info">
                            <span>1. Product Info</span>
                        </button>
                        <button class="multisteps-form__progress-btn" type="button" title="Upload File">2. Upload File</button>
                        <!-- <button class="multisteps-form__progress-btn" type="button" title="Socials">3. Socials</button>
                        <button class="multisteps-form__progress-btn" type="button" title="Pricing">4. Pricing</button> -->
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form class="multisteps-form__form" action="{{URL::to('/decrypt-file')}}" method="post">
                    {{ csrf_field() }}
                    <div class="multisteps-form__panel pt-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                        <h5 class="font-weight-bolder">Product Information</h5>
                        <div class="multisteps-form__content">
                            <!-- <div class="row mt-3">
                                <div class="col-12 col-sm-6">
                                    <div class="input-group input-group-dynamic">
                                        <label for="exampleFormControlInput1" class="form-label">Name</label>
                                        <input class="multisteps-form__input form-control" type="text" />
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                    <div class="input-group input-group-dynamic">
                                        <label for="exampleFormControlInput1" class="form-label">Weight</label>
                                        <input class="multisteps-form__input form-control" type="text" />
                                    </div>
                                </div>
                            </div> -->
                            <div class="row">
                                <!-- <div class="col-sm-6">
                                    <label class="mt-4">Description</label>
                                    <p class="form-text text-muted text-xs ms-1 d-inline">
                                        (optional)
                                    </p>
                                    <div id="edit-deschiption" class="h-50">
                                        <p>Some initial <strong>bold</strong> text</p>
                                    </div>
                                </div> -->
                                <div class="col-sm-6 mt-sm-3 mt-5">
                                    <label class="form-control ms-0">Cloud Provider</label>
                                    <select class="form-control" name="choices-cloud" id="choices-cloud">
                                        <option value="aws" selected>AWS</option>
                                        <option value="google">Google Cloud</option>
                                        <!-- <option value="Choice 3">Electronics</option>
                                        <option value="Choice 4">Furniture</option>
                                        <option value="Choice 5">Others</option> -->
                                    </select>
                                    <!-- <label class="form-control ms-0">Sizes</label>
                                    <select class="form-control" name="choices-sizes" id="choices-sizes">
                                        <option value="Choice 1" selected>Medium</option>
                                        <option value="Choice 2">Small</option>
                                        <option value="Choice 3">Large</option>
                                        <option value="Choice 4">Extra Large</option>
                                        <option value="Choice 5">Extra Small</option>
                                    </select> -->
                                </div>
                            </div>
                            <div class="button-row d-flex mt-4">
                                <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next" type="button" title="Next">Next</button>
                            </div>
                        </div>
                    </div>

                    <div class="multisteps-form__panel pt-3 border-radius-xl bg-white" data-animation="FadeIn">
                        <h5 class="font-weight-bolder">File</h5>
                        <div class="multisteps-form__content">
                            <div class="row mt-3">
                                <div class="col-12">
                                    <label class="form-control mb-0">Upload the encrypted file</label>
                                    <div action="{{URL::to('/file-upload')}}" class="form-control border dropzone" id="productYaml"></div>
                                </div>
                            </div>
                            <div class="button-row d-flex mt-0 mt-md-4">
                                <button class="btn bg-gradient-light mb-0 js-btn-prev" type="button" title="Prev">Prev</button>
                                <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-send" type="submit" title="Send">Send</button>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="multisteps-form__panel pt-3 border-radius-xl bg-white" data-animation="FadeIn">
                        <h5 class="font-weight-bolder">Socials</h5>
                        <div class="multisteps-form__content">
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="input-group input-group-dynamic">
                                        <label class="form-label">Shoppify Handle</label>
                                        <input class="multisteps-form__input form-control" type="text" />
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="input-group input-group-dynamic">
                                        <label class="form-label">Facebook Account</label>
                                        <input class="multisteps-form__input form-control" type="text" />
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="input-group input-group-dynamic">
                                        <label class="form-label">Instagram Account</label>
                                        <input class="multisteps-form__input form-control" type="text" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="button-row d-flex mt-4 col-12">
                                    <button class="btn bg-gradient-light mb-0 js-btn-prev" type="button" title="Prev">Prev</button>
                                    <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next" type="button" title="Next">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="multisteps-form__panel pt-3 border-radius-xl bg-white h-100" data-animation="FadeIn">
                        <h5 class="font-weight-bolder">Pricing</h5>
                        <div class="multisteps-form__content mt-3">
                            <div class="row">
                                <div class="col-3">
                                    <div class="input-group input-group-dynamic">
                                        <label class="form-label">Price</label>
                                        <input type="email" class="form-control w-100" id="exampleInputEmail1" aria-describedby="emailHelp" />
                                    </div>
                                </div>
                                <div class="col-4">
                                    <select class="form-control" name="choices-sizes" id="choices-currency">
                                        <option value="Choice 1" selected>USD</option>
                                        <option value="Choice 2">EUR</option>
                                        <option value="Choice 3">GBP</option>
                                        <option value="Choice 4">CNY</option>
                                        <option value="Choice 5">INR</option>
                                        <option value="Choice 6">BTC</option>
                                    </select>
                                </div>
                                <div class="col-5">
                                    <div class="input-group input-group-dynamic">
                                        <label class="form-label">SKU</label>
                                        <input class="multisteps-form__input form-control" type="text" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label class="mt-4 form-label">Tags</label>
                                    <select class="form-control" name="choices-tags" id="choices-tags" multiple>
                                        <option value="Choice 1" selected>In Stock</option>
                                        <option value="Choice 2">Out of Stock</option>
                                        <option value="Choice 3">Sale</option>
                                        <option value="Choice 4">Black Friday</option>
                                    </select>
                                </div>
                            </div>
                            <div class="button-row d-flex mt-0 mt-md-4">
                                <button class="btn bg-gradient-light mb-0 js-btn-prev" type="button" title="Prev">Prev</button>
                                <button class="btn bg-gradient-dark ms-auto mb-0" type="button" title="Send">Send</button>
                            </div>
                        </div>
                    </div> -->
                </form>
            </div>
        </div>
    </div>
</div>
@endsection