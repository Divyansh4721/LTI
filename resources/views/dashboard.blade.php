<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    You're logged in!
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">

                <div class="card-header">Manage LMS Configuration <button type="button" class="btn btn-primary" style="float: right;"><b>+</b> ADD LMS</button></div>
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-2">
                            <select class="form-select" aria-label="Default select example">
                                  <option selected>Name</option>
                                  <option value="1">Name1</option>
                                  <option value="2">Name2</option>
                                  <option value="3">Name3</option>
                            </select>
                        </div>

                        <div class="col-md-3" style="width:50%">
                            <div class="input-group">
                                  <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search" aria-describedby="search-addon" />
                                  <button type="button" class="btn btn-primary">search</button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-secondary" style="float: right;">RESET</button>
                        </div>
                    </div>
                </div>


                <div class="card-body">

                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">NAME</th>
                          <th scope="col">STATUS</th>
                          <th scope="col">LMS NAME</th>
                          <th scope="col">ORGANISATION ID</th>
                          <th scope="col">LMS DOMAIN URL</th>
                          <th scope="col">CREATED DATE</th>
                      </tr>
                  </thead>
                  <tbody>
                    <tr>
                          <!-- <th scope="row">1</th>
                          <td>Mark</td>
                          <td>Otto</td>
                          <td>@mdo</td> -->
                      </tr>
                  </tbody>
              </table>

          </div>
      </div>
  </div>
</div>
</div>
