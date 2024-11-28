@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <b>Update Platform key</b>
                    <a href="/create_lti_key/{{$platformKey['platform_id']}}">
                        <button type="button" class="btn btn-secondary" style="float: right;">
                            <strong>&laquo;</strong> Back To Platform Key</button>
                    </a>
                </div>
                <div class="card-body">
                    <form action="/edit_lti_platform_key/{{$platformKey['id']}}"
                    method="POST" enctype="multipart/form-data" name="platform_form" autocomplete="off">
                        @csrf
                        <input type="hidden" id="platform_id" name="platform_id"
                        value="{{$platformKey['platform_id']}}" />
                                
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-8">
                                <input type="text" required placeholder="Name"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{$platformKey['name']}}" />
                                @error('name')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br />
                        <div class="form-group row">
                            <label for="deployment_id" class="col-sm-3 col-form-label">Deployment ID</label>
                            <div class="col-sm-8">
                                <input type="text" required placeholder="Deployment ID"
                                class="form-control @error('deployment_id') is-invalid @enderror"
                                id="deployment_id" name="deployment_id" value="{{$platformKey['deployment_id']}}" />
                                @error('deployment_id')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br /><br />
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <a href="/create_lti_key/{{$platformKey['platform_id']}}">
                                    <button type="button" style="float: right;"
                                    class="btn btn-secondary">Cancel</button>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection