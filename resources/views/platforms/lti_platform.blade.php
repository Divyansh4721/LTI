@extends('layouts.app')
@section('content')

<link rel="stylesheet" type="text/css" href="{{asset('/css/bootstrap3.css')}}">
<link href="{{ URL::asset('/css/toggle.css') }}" rel="stylesheet">
<link href="{{ URL::asset('/css/model.css') }}" rel="stylesheet">
<link href="{{ URL::asset('/css/tooltip.css') }}" rel="stylesheet">



<link href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">



 <!-- Datatable Dependency start -->
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.20/b-1.6.1/b-colvis-1.6.1/b-html5-1.6.1/b-print-1.6.1/r-2.2.3/datatables.min.css" /> -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.20/b-1.6.1/b-colvis-1.6.1/b-html5-1.6.1/b-print-1.6.1/r-2.2.3/datatables.min.js"></script>

<!-- Datatable Dependency end -->


<script type='text/javascript' src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script type='text/javascript' src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
<script type='text/javascript' src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script type='text/javascript' src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>

<script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script type='text/javascript' src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>




</head>
<div class="container main-div-width">
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
                Error while creating Platform
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                </div>
            </div>
        @endif

            <div class="card">
                <div class="card-header">
                    <b>Manage LTI Platforms</b>
                    <a href="/create_lti_platform">
                        <button type="button" class="btn btn-primary float-right">
                            <strong>+</strong> Create LTI Platfrom</button>
                            &nbsp; &nbsp; &nbsp;
                    </a>

                        <a href="#" id="myBtn1" class="btn btn-success float-right reportButton" style="display:none"> Report </a>
                            
                </div>
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6 div-width">
                            <div class="input-group">
                                <input id="searchit" type="search"
                                class="form-control rounded" placeholder="Search"
                                aria-label="Search" aria-describedby="search-addon" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body div-min-height">
                    <table id="myTable" class="table table-hover" aria-describedby="LTI Platform">
                        <thead>
                            <tr>
                                <th scope="col">UID</th>
                                <th scope="col">Client Name</th>
                                <th scope="col">LMS Name</th>
                                <th scope="col">Client ID</th>
                                <th scope="col">Audience/ Issuer</th>
                                <th scope="col">Created Date</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="platformlist">
                            @foreach ($platformData as $platform)
                            <?php
                                 $isDeleted = $platform->deleted_at ?? '';
                                 $platformName = $platform->name ?? '';
                                 $platformClientId = $platform->platform_client_id ?? '';
                                 $issuer = $platform->issuer ?? '';
                                 $created_at = $platform->created_at ?? '';
                                 $platformId = $platform->platform_id ?? '';
                                 $clientName = "";
                            ?>
                            @if($isDeleted == NULL || $isDeleted == '')
                            <tr>
                                <td>#{{ 100+$platformId}}</td>
                                @if(count($platform->client) > 0)
                                @foreach($platform->client as $client)
                                <?php
                                    $clientName = $client->name ?? '';
                                ?>
                                @endforeach
                                @endif
                                <td>{{$clientName}}</td>                      
                                <td>{{$platformName}}</td>
                                <td>{{$platformClientId}}</td>
                                <td>{{$issuer}}</td>
                                <td>{{date('d-m-Y', strtotime($created_at))}}</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox"
                                        data-platform_id="{{$platformId}}" class="toggle-class"
                                            type="checkbox" data-onstyle="success" data-offstyle="danger"
                                            data-toggle="toggle" data-on="Active" data-off="InActive"
                                            {{ ($platform->enabled == 1) ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td class="dropdown">
                                    <a class="btn btn-default actionButton"
                                        data-platform_id="{{$platformId}}"
                                        data-toggle="dropdown" href="#">
                                        <img src="{{asset('/images/vertcal_option.png')}}"
                                        height="15px;" height="8px;" class="dropdown float-left"
                                        id="dropdown"
                                        alt="action button">
                                    </a>
                                </td>

                                <ul id="contextMenu{{$platformId}}"
                                class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="/edit_lti_platform/{{$platformId}}">
                                            <img src="{{asset('/images/pencil-square.svg')}}"
                                            height="15px;"
                                            class="float-left" alt="action button">
                                            &nbsp; Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a href="create_lti_key/{{$platformId}}">
                                            <img src="{{asset('/images/key.svg')}}"
                                            height="15px;"
                                            class="float-left" alt="action button">
                                            &nbsp; LTI Keys
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" id="myBtn" class="myBtn" data-client='{{$platformClientId}}'>
                                            <img src="{{asset('/images/key.svg')}}"
                                            height="15px;"
                                            class="float-left" alt="action button">
                                            &nbsp; Tool JWK Key
                                        </a>
                                    </li>
                                    <!-- <li>
                                        <a href="delete_platform/{{$platformId}}" class="delete_confirmation">
                                            <img src="{{asset('/images/delete.png')}}"
                                            height="15px;"
                                            class="float-left" alt="action button">
                                            &nbsp; Delete
                                        </a>
                                    </li> -->
                                </ul>
                            </tr>
                            @endif

                            @endforeach
                            @if (is_array($platformData) && !empty($platformData))
                            <tr>
                                <td colspan="9" class="text-align">No record found</td>
                            </tr>
                            @endif

                        </tbody>
                    </table>
                    <div class="paginate-links">{!! $platformData->links() !!}</div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- The Modal -->
<div id="myModal" class="modal">
  <div class="modal-content">
    <div class="form-group row">
        <div class="col-md-12">
            <div class="col-md-6">
                <h4>Tool JWK Key</h4>
            </div>
            <div class="col-md-3">
            </div>
            <div class="col-md-3">
                <span class="model-close jwkey_close float-right">&times;</span>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-11">
            <textarea type="text" rows="6" class="form-control" id="showkeys" name="showkeys" disabled></textarea>
        </div>
        <div class="col-sm-1" class="copy-div">
            <p class="form-control copy-button" id="copykey" title="Copy it!">
                <img src="{{asset('/images/copy.png')}}">
            </p>
        </div>
    </div>
  </div>
</div>



<!-- The Modal -->
<div id="myModal1" class="modal">

    <div class="container">
<div class="modal-fullscreen">
  <div class="modal-content">
    <div class="form-group row">
        <div class="col-md-12">
            <div class="col-md-11 center">
                <h4>LTI-LMS Details</h4>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn-close report_close" aria-label="Close"></button>
            </div>
        </div>


 <div class="table-responsive">
 
<table id="datatableid" class="table table-bordered" style="width:100%">
  <thead class="" style="background-color:black; color:white">
    <tr>
      <th scope="col">SL NO</th>
      <th scope="col">MGH Client Name</th>
      <th scope="col">Platform Name</th>
      <th scope="col">Audience / Issuer</th>
      <th scope="col">LMS Client ID</th>
      <th scope="col">Platform Public key</th>
      <th scope="col">Platform Private key</th>
      <th scope="col">JWT key set URL</th>
      <th scope="col">Access token URL</th>
      <th scope="col">Authorization URL</th>
      <th scope="col">Deployment ID</th>

    </tr>
  </thead>
  <tbody>
    @foreach ($platformData as $key => $platform)

    <?php
            
         $isDeleted = $platform->deleted_at ?? '';
         $platformName = $platform->name ?? '';
         $platformClientId = $platform->platform_client_id ?? '';
         $issuer = $platform->issuer ?? '';
         $created_at = $platform->created_at ?? '';
         $platformId = $platform->platform_id ?? '';
         $clientName = '';
    ?>  
    <tr>

        <td>{{$key+1}}</td>
        @if(count($platform->client) > 0)
        @foreach($platform->client as $client)
        <?php
            $clientName = $client->name;
        ?>  
        @endforeach
        @endif
        <td>{{$clientName}}</td>
        <td>{{$platformName}}</td>
        <td>{{$issuer}}</td>
        <td>{{$platform->platform_client_id}}</td>
        <td>{{$platform->public_key}}</td>
        <td>{{$platform->private_key}}</td>
        <td>{{$platform->jwkseturl}}</td>
        <td>{{$platform->access_token}}</td>
        <td>{{$platform->authorization_url}}</td>
        @php 
        // $platformKey = \App\Models\PlatformKey::where('platform_id', $platform->platform_id)->first();
        @endphp
        <td></td>
        
    </tr>
    @endforeach
    
  </tbody>
</table>

 </div>

    </div>
    <div class="form-group row">
        <div class="col-sm-11">
            
        </div>
    </div>
  </div>

</div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/lti_platform.js')}}"></script>

@endsection
