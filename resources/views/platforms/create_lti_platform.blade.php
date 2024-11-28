@extends('layouts.app')
@section('content')

<link href="{{ URL::asset('/css/tooltip.css') }}" rel="stylesheet">
<script type='text/javascript' src="{{ URL::asset('/js/copy.js') }}"></script>
<script type='text/javascript' src="{{ URL::asset('/js/validation.js') }}"></script>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>Add New Platform</strong>
                    <a href="/dashboard">
                        <button type="button" class="btn btn-secondary float-right">
                            <strong>&laquo;</strong> Back To Dashboard</button>
                    </a>
                </div>
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="create_lti_platform" method="POST" enctype="multipart/form-data" name="platform_form" id="platform_form" autocomplete="off">
                        @csrf
                        <div class="form-group row">
                            <label for="mgh_client_id" class="col-sm-3 col-form-label">MGH Client Name</label>
                            <div class="col-sm-8">
                                <select class="form-control selectpicker des webkit-appearance @error('mgh_client_id') is-invalid @enderror" data-show-subtext="false" data-live-search="true" name="mgh_client_id" id="mgh_client_id" required>
                                    <option value="">Select Client</option>
                                    @foreach ($clientData as $client)
                                    <option value="{{$client->client_id}}">{{$client->name}} - {{$client->client_id}}</option>
                                    @endforeach
                                </select>
                                @error('mgh_client_id')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="logo" class="col-sm-3 col-form-label">Logo</label>
                            <div class="col-sm-6">
                                <input type="file" placeholder="Logo" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" value="" onchange="loadFile(event)"/>
                                <p><small> <strong>Note:</strong> Image format must be of type jpg, jpeg, png, svg or gif. Max image size allowed up to 150 KB. Image width should be in range 50px-150px. Image height should be in range 50px-80px.</small></p>
                                @error('logo')
                                    <div class="error_colour">{{ $message }}</div>
                                @enderror
                                <h6 id="error1" class ="display-none text-color-red">
                                    Invalid Image format! Image format must be of type jpg, jpeg, png, svg or gif.
                                </h6>
                                <h6 id="error2" class ="display-none text-color-red">
                                    Image size allowed upto 150KB.
                                </h6>
                                <h6 id="error3" class ="display-none text-color-red">
                                    Image width should be in range 50px-150px. Image height should be in range 50px-80px.
                                </h6>
                            </div>
                            <div class="col-sm-2 display-none" id="show_logo">
                                <img src="{{ URL::asset('/images/default_mgh_logo.png') }}" alt="profile Pic" class="float-left" id="output">
                            </div>
                        </div>
                        <br/>

                        <div class="form-group row">
                            <label for="platfrom_name" class="col-sm-3 col-form-label">Platform Name</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Platform Name" class="form-control @error('platfrom_name') is-invalid @enderror" id="platfrom_name" name="platfrom_name" value="" required />
                                @error('platfrom_name')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br />
                        <div class="form-group row">
                            <label for="issuer" class="col-sm-3 col-form-label">Audience/ Issuer</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Audience/ Issuer" class="form-control @error('issuer') is-invalid @enderror" id="issuer" name="issuer" value="" required />
                                @error('issuer')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br />
                        <div class="form-group row">
                            <label for="client_id" class="col-sm-3 col-form-label">LMS Client ID</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="LMS Client ID" class="form-control @error('client_id') is-invalid @enderror" id="client_id" name="client_id" value="" required />
                                @error('client_id')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br />
                        <div class="form-group row">
                            <label for="public_key" class="col-sm-3 col-form-label">Platform Public key</label>
                            <div class="col-sm-8">
                                <textarea type="text" placeholder="Platform Public key" rows="6" class="form-control @error('public_key') is-invalid @enderror" id="public_key" name="public_key" required>{{$publicKey}}</textarea>
                                @error('public_key')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-1" class="copy-div">
                                <p class="form-control copy-button" id="public_key_copy" data-input_type = "textarea" data-value="{{$publicKey}}">
                                    <img src="{{asset('/images/copy.png')}}">
                                </p>
                            </div>
                        </div>
                        <br/>
                        <div class="form-group row">
                            <label for="private_key" class="col-sm-3 col-form-label">Platform Private key</label>
                            <div class="col-sm-8">
                                <textarea type="text" placeholder="Platform Private key" rows="6" class="form-control @error('private_key') is-invalid @enderror" id="private_key" name="private_key" required>{{$privateKey}}</textarea>
                                @error('private_key')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-1" class="copy-div">
                                <p class="form-control copy-button" id="private_key_copy" data-input_type = "textarea" data-value="{{$privateKey}}">
                                    <img src="{{asset('/images/copy.png')}}">
                                </p>
                            </div>
                        </div>
                        <br />
                        <div class="form-group row">
                            <label for="jwkseturl" class="col-sm-3 col-form-label">JWT key set URL (JKU)</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="JWT key set URL (JKU)" class="form-control @error('jwkseturl') is-invalid @enderror" id="jwkseturl" name="jwkseturl" value="" required />
                                @error('jwkseturl')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br />
                        <div class="form-group row">
                            <label for="access_token" class="col-sm-3 col-form-label">Access token URL</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Access token URL" class="form-control @error('access_token') is-invalid @enderror" id="access_token" name="access_token" value="" required />
                                @error('access_token')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br />
                        <div class="form-group row">
                            <label for="authorization_url" class="col-sm-3 col-form-label">Authorization URL</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Authorization URL" class="form-control @error('authorization_url') is-invalid @enderror" id="authorization_url" name="authorization_url" value="" required />
                                @error('authorization_url')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <br />
                        <div class="form-group row">
                            <label for="tool_jwk_url" class="col-sm-3 col-form-label">Tool JWK URL</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Tool JWK URL" class="form-control @error('tool_jwk_url') is-invalid @enderror" id="tool_jwk_url" name="tool_jwk_url" value="{{$appUrl.'/ltilaunch/jwks'}}" disabled />
                            </div>
                            <div class="col-sm-1" class="copy-div">
                                <p class="form-control copy-button" id="tool_jwk_url_copy" data-input_type = "input" data-value="{{$appUrl.'/ltilaunch/jwks'}}">
                                    <img src="{{asset('/images/copy.png')}}">
                                </p>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="tool_launch_redirect_target_url" class="col-sm-3 col-form-label">Tool Launch/Redirect/Target URL</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Tool Launch/Redirect/Target URL" class="form-control @error('tool_launch_redirect_target_url') is-invalid @enderror" id="tool_launch_redirect_target_url" name="tool_launch_redirect_target_url" value="{{$appUrl.'/ltilaunch'}}" disabled />
                                @error('access_token')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-1" class="copy-div">
                                <p class="form-control copy-button" id="tool_launch_redirect_target_url_copy" data-input_type = "input" data-value="{{$appUrl.'/ltilaunch'}}">
                                    <img src="{{asset('/images/copy.png')}}">
                                </p>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="blackboard_tool_redirect_url" class="col-sm-3 col-form-label">Blackboard: Tool Redirect URL(s)</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Blackboard: Tool Redirect URL(s)" class="form-control @error('Blackboard: Tool Redirect URL(s)') is-invalid @enderror" id="blackboard_tool_redirect_url" name="blackboard_tool_redirect_url" value="{{$appUrl.'/ltilaunch?platform_client_id=<client_id>'}}" disabled />
                                @error('access_token')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-1" class="copy-div">
                                <p class="form-control copy-button" id="blackboard_tool_redirect_copy" data-input_type = "input" data-value="{{$appUrl.'/ltilaunch?platform_client_id=<client_id>'}}">
                                    <img src="{{asset('/images/copy.png')}}">
                                </p>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="tool_oidc_url" class="col-sm-3 col-form-label">Tool OIDC URL</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Tool OIDC URL" class="form-control @error('tool_oidc_url') is-invalid @enderror" id="tool_oidc_url" name="tool_oidc_url" value="{{$appUrl.'/ltilaunch/oidclogin'}}" disabled />
                                @error('authorization_url')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-1" class="copy-div">
                                <p class="form-control copy-button" id="tool_oidc_url_copy" data-input_type = "input" data-value="{{$appUrl.'/ltilaunch/oidclogin'}}">
                                    <img src="{{asset('/images/copy.png')}}">
                                </p>
                            </div>
                        </div>

                        <br /><br />
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <a href="{{ route('platform.index')}}">
                                    <button type="button" class="btn btn-secondary float-right">Cancel</button>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Create New Platform" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection