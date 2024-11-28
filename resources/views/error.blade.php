@extends('layouts.picker_page_layout')
@section('content')
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="text-center">
            <h1 class="fw-bold">Error!</h1>
            <p class="fs-3"><span class="text-danger">Opps!</span> An error occured on the page:</p>
            <p class="lead">
                {{$errorMsg}}
            </p>
            <a href="{{route('launch').'?platform_client_id='.$platformClientId}}" class="btn btn-primary">Go Home</a>
        </div>
    </div>
@endsection