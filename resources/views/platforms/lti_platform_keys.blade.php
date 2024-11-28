@extends('layouts.app')
@section('content')

<link rel="stylesheet" type="text/css" href="{{asset('/css/bootstrap3.css')}}">

</head>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (session('success'))
                <div class="col-sm-12">
                    <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="col-sm-12">
                    <div class="alert alert-danger" role="alert">
                    Error while creating Platform Key
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                    </div>
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <small>Manage LTI Platform Keys For: <strong>{{$platform[0]['name']}}</strong></small>
                    
                    <strong style="float: right;">
                        <a href="/create_lti_platform_keys/{{$id}}">
                            <button type="button" class="btn btn-primary" >
                                <strong>+</strong> Create LTI Platfrom Keys</button>
                        </a>

                        <a href="/dashboard">
                                <button type="button" class="btn btn-secondary">
                                    <strong>&laquo;</strong> Back To Dashboard</button>
                        </a>
                    </strong>

                </div>
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6" style="width:50%;">
                            <div class="input-group">
                                <input id="searchit" type="search"
                                class="form-control rounded" placeholder="Search"
                                aria-label="Search" aria-describedby="search-addon" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="min-height:300px;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" width="20%">Name</th>
                                <th scope="col" width="70%">Deployment ID</th>
                                <th scope="col" width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="platformlist">
                            @foreach ($platformKey as $platform)
                            <tr>
                                <td>{{$platform->name}}</td>
                                <td>{{$platform->deployment_id}}</td>
                                <td>
                                    <a href="/edit_lti_platform_key/{{$platform->id}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                        fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                        </svg>
                                    </a>

                                    <!-- <a href="/delete_lti_platform_key/{{$platform->id}}" class="delete_confirmation">
                                        <img src="{{URL::asset('/images/delete.png')}}" alt="profile Pic"
                                        height="20" width="30" style="float:right; padding-right:10px">
                                    </a> -->
                                </td>
                            </tr>
                            @endforeach
                            @if (is_array($platformKey) && !empty($platformKey))
                            <tr>
                                <td colspan="9" style="text-align: center;">No record found</td>
                            </tr>
                            @endif

                        </tbody>
                    </table>
                    <div style="float:right; padding-top:20px;">{!! $platformKey->links() !!}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/lti_platform_keys.js')}}"></script>

@endsection
