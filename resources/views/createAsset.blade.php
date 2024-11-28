@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>Add New Assets</strong>
                    {{-- <a href="/dashboard">
                        <button type="button" class="btn btn-secondary" style="float: right;">
                            <strong>&laquo;</strong> Back To Login</button>
                    </a> --}}
                </div>
                <div class="card-body">
                    <form action="create_assets" method="POST" enctype="multipart/form-data" name="create_assets" id="create_assets">
                        @csrf
                        <div class="form-group row">
                            <label for="repeate" class="col-sm-3 col-form-label">Insert Count</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="repeate" class="form-control @error('repeate') is-invalid @enderror" id="repeate" name="repeate" value="1" required />
                                @error('repeate')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br />
                        {{-- <div class="form-group row">
                            <label for="asset_id" class="col-sm-3 col-form-label">Asset ID</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Asset ID" class="form-control @error('asset_id') is-invalid @enderror" id="asset_id" name="asset_id" value="" required />
                                @error('asset_id')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br /> --}}
                        <div class="form-group row">
                            <label for="title" class="col-sm-3 col-form-label">Title</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Title" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="" required />
                                @error('title')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br/>
                        <div class="form-group row">
                            <label for="isbn10" class="col-sm-3 col-form-label">isbn10</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="isbn10" class="form-control @error('isbn10') is-invalid @enderror" id="isbn10" name="isbn10" value=""/>
                                @error('isbn10')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br />
                        <div class="form-group row">
                            <label for="isbn13" class="col-sm-3 col-form-label">isbn13</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="isbn13" class="form-control @error('isbn13') is-invalid @enderror" id="isbn13" name="isbn13" value=""/>
                                @error('isbn13')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br />
                        <div class="form-group row">
                            <label for="description" class="col-sm-3 col-form-label">Description</label>
                            <div class="col-sm-8">
                                <textarea type="text" placeholder="description" rows="6" class="form-control @error('description') is-invalid @enderror" id="description" name="description" required></textarea>
                                @error('description')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br />
                        <div class="form-group row">
                            <label for="link_location" class="col-sm-3 col-form-label">Link Location</label>
                            <div class="col-sm-8">
                                <textarea type="text" placeholder="link_location" rows="6" class="form-control @error('link_location') is-invalid @enderror" id="link_location" name="link_location" required></textarea>
                                @error('link_location')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br />
                        <div class="form-group row">
                            <label for="thumbnail_location" class="col-sm-3 col-form-label">Thumbnail Location</label>
                            <div class="col-sm-8">
                                <textarea type="text" placeholder="thumbnail_location" rows="6" class="form-control @error('thumbnail_location') is-invalid @enderror" id="thumbnail_location" name="thumbnail_location" required></textarea>
                                @error('thumbnail_location')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br />
                        <div class="form-group row">
                            <label for="parent_id" class="col-sm-3 col-form-label">parent_id</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="parent_id" class="form-control @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id" value=""/>
                                @error('parent_id')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br/>
                        <br />
                        <div class="form-group row">
                            <label for="content_type" class="col-sm-3 col-form-label">content_type</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="content_type" class="form-control @error('content_type') is-invalid @enderror" id="content_type" name="content_type" value="" required />
                                @error('content_type')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br />
                        <div class="form-group row">
                            <label for="taxonomies" class="col-sm-3 col-form-label">Taxonomies</label>
                            <div class="col-sm-4">
                                <input type="text" placeholder="taxonomy_id" class="form-control @error('taxonomy_id') is-invalid @enderror" id="taxonomy_id" name="taxonomy_id" value=""/>
                                @error('taxonomy_id')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-4">
                                <input type="text" placeholder="item" class="form-control @error('item') is-invalid @enderror" id="item" name="item" value=""/>
                                @error('item')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br />
                        <div class="form-group row">
                            <label for="authors" class="col-sm-3 col-form-label">authors</label>
                            <div class="col-sm-3">
                                <input type="text" placeholder="author_id" class="form-control @error('author_id') is-invalid @enderror" id="author_id" name="author_id" value=""/>
                                @error('author_id')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-3">
                                <input type="text" placeholder="name" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value=""/>
                                @error('name')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-3">
                                <input type="text" placeholder="author_description" class="form-control @error('author_description') is-invalid @enderror" id="author_description" name="author_description" value=""/>
                                @error('author_description')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br />
                        <div class="form-group row">
                            <label for="product_id" class="col-sm-3 col-form-label">product_id</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="product_id" class="form-control @error('product_id') is-invalid @enderror" id="product_id" name="product_id" value=""/>
                                @error('product_id')
                                <div class="error_colour">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br /><br />
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <a href="{{ route('platform.index')}}">
                                    <button type="button" style="float: right;" class="btn btn-secondary">Cancel</button>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">Create New Assets</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection