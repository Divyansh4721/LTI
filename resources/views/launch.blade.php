@extends('layouts.picker_page_layout')
@section('content')

<div class="preload display-none overlay-content" id="loader">
    <img src="{{ URL::asset('/images/loader5.gif') }}">
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                        <div class="row div-image-logo">
                            <div class="col-md-2">
                                <?php if(!empty($logo)) {
                                    $logo = Storage::disk('s3')->url($logo);
                                    } else {
                                        $logo = '';
                                    }
                                ?>
                                <img src="{{$logo}}" onerror="this.src='/images/default_mgh_logo.png'" alt="profile Pic" class="padding-top-5 img-fluid mx-auto d-block">
                            </div>
                            
                            {{-- Product Details --}}
                            
                            <div class="col-md-2 div-search-filter-clear">
                                <div class="input-group">
                                    <select name="fl_SiteID" id="fl_SiteID" class="form-select-dropdown">
                                        <option value=''>Select Product</option>
                                        @foreach ($productFilters as $products)
                                            <option value="{{$products['SiteId']}}">{{$products['SiteName']}}</option>
                                        @endforeach
                                    </select>
                                    &nbsp;&nbsp;
                                </div>
                            </div>
                            
                            <div class="col-md-2 div-search-filter-clear">
                                <div class="input-group">
                                    <select name="fl_TopLevelContentDisplayName" id="fl_TopLevelContentDisplayName" class="form-select-dropdown">
                                        <option value=''>Select Options</option>
                                        @foreach ($contentTypes as $key => $displayNameEnglish)
                                            <option value="{{$key}}">{{$displayNameEnglish}}</option>
                                        @endforeach
                                    </select>
                                    &nbsp;&nbsp;
                                </div>
                            </div>
                            
                            <div class="col-md-6 div-search-filter-clear">
                                <div class="input-group">
                                    <input type="text" id="search" name="search" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="search-addon" />
                                    <input type="submit" id="searchdata" value="Search" class="btn btn-success" />
                                    &nbsp; &nbsp;
                                    <a id="clear" name="clear" title="Clear Filters" class="btn btn-secondary">Clear Filters</a>
                                </div>
                            </div>
                        </div>
 
                    <input type="hidden" id="request_id" value="{{$requestId}}" name="request_id"/>
                </div>
                
                <div class="load-content" id="load-content">
                </div>

            </div>
            
            <div id="addform" class="display-none"></div>
        </div>
    </div>
</div>
<!-- Scripts -->
<script type="text/javascript">
var institutionId = "{{ \Session::get('instituteId') }}";
var launchContentUrl = "{{ route('launch-content') }}";
</script>
<script src="{{ asset('js/launch.js')}}"></script>
@endsection