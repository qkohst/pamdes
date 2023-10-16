@include('layouts.main.header')
@include('layouts.main.sidebar')
<div class="content">
    <div class="page-inner">

        <div class="card">
            <div class="card-header px-0 py-0 mx-0 my-0" id="headingFilter">
                <button class="btn btn-block btn-primary text-left" type="button" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
                    <i class="fa fa-filter"></i> Filter
                </button>
            </div>
            <div id="collapseFilter" class="collapse" aria-labelledby="headingFilter" data-parent="#accordionExample">
                <form id="form-filter">
                    <div class="card-body py-0">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="filter_nama">Nama</label>
                                    <input type="text" class="form-control" id="filter_nama" name="filter_nama" placeholder="Nama">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="filter_username">Username</label>
                                    <input type="text" class="form-control" id="filter_username" name="filter_username" placeholder="Username">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="filter_status">Status</label>
                                    <select class="form-control select2" id="filter_status" name="filter_status" style="width: 100%;">
                                        <option value="">Semua Status</option>
                                        <option value="1">Aktif</option>
                                        <option value="0">Non Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-sm btn-default" id="btn-reset" type="button" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">RESET</button>
                        <button class="btn btn-sm btn-primary" type="submit" type="button" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">FILTER</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">{{$title}}</h4>
                            <button class="btn btn-primary btn-round btn-sm ml-auto" data-toggle="modal" data-target="#modalAddData">
                                <i class="fa fa-plus mr-1"></i>
                                TAMBAH
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Modal -->
                        <div class="modal fade" id="modalAddData" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <span class="fw-mediumbold">
                                                Add</span>
                                            <span class="fw-light">
                                                Data
                                            </span>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body py-0">
                                        <form>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="input">Example Input</label>
                                                        <input type="text" class="form-control" id="input" placeholder="Input Data">
                                                        <small id="input" class="form-text text-danger">We'll never share your email with anyone else.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="exampleFormControlSelect1">Example select</label>
                                                        <select class="form-control" id="exampleFormControlSelect1">
                                                            <option>1</option>
                                                            <option>2</option>
                                                            <option>3</option>
                                                            <option>4</option>
                                                            <option>5</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="exampleFormControlSelect2">Example select2</label>
                                                        <select class="form-control select2" id="exampleFormControlSelect2" style="width: 100%;">
                                                            <option>1</option>
                                                            <option>2</option>
                                                            <option>3</option>
                                                            <option>4</option>
                                                            <option>5</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="comment">Textarea</label>
                                                        <textarea class="form-control" id="comment" rows="3"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">BATAL</button>
                                        <button type="button" id="addRowButton" class="btn btn-primary btn-sm">SIMPAN</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="table-data" class="datatable display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">Aksi</th>
                                        <th>Nama</th>
                                        <th>Username</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- <tr>
                                        <td>
                                            <div class="form-button-action">
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-success" data-original-title="View">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-primary ml-1" data-original-title="Edit">
                                                    <i class="fa fa-pen"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-danger ml-1" data-original-title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>Tiger Nixon</td>
                                        <td>System Architect</td>
                                        <td>Edinburgh</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-button-action">
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-success" data-original-title="View">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-primary ml-1" data-original-title="Edit">
                                                    <i class="fa fa-pen"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-danger ml-1" data-original-title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>Garrett Winters</td>
                                        <td>Accountant</td>
                                        <td>Tokyo</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-button-action">
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-success" data-original-title="View">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-primary ml-1" data-original-title="Edit">
                                                    <i class="fa fa-pen"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-danger ml-1" data-original-title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>Ashton Cox</td>
                                        <td>Junior Technical Author</td>
                                        <td>San Francisco</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-button-action">
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-success" data-original-title="View">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-primary ml-1" data-original-title="Edit">
                                                    <i class="fa fa-pen"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-danger ml-1" data-original-title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>Cedric Kelly</td>
                                        <td>Senior Javascript Developer</td>
                                        <td>Edinburgh</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-button-action">
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-success" data-original-title="View">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-primary ml-1" data-original-title="Edit">
                                                    <i class="fa fa-pen"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-danger ml-1" data-original-title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>Airi Satou</td>
                                        <td>Accountant</td>
                                        <td>Tokyo</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-button-action">
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-success" data-original-title="View">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-primary ml-1" data-original-title="Edit">
                                                    <i class="fa fa-pen"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-danger ml-1" data-original-title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>Brielle Williamson</td>
                                        <td>Integration Specialist</td>
                                        <td>New York</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-button-action">
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-success" data-original-title="View">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-primary ml-1" data-original-title="Edit">
                                                    <i class="fa fa-pen"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-danger ml-1" data-original-title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>Herrod Chandler</td>
                                        <td>Sales Assistant</td>
                                        <td>San Francisco</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-button-action">
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-success" data-original-title="View">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-primary ml-1" data-original-title="Edit">
                                                    <i class="fa fa-pen"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-danger ml-1" data-original-title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>Rhona Davidson</td>
                                        <td>Integration Specialist</td>
                                        <td>Tokyo</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-button-action">
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-success" data-original-title="View">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-primary ml-1" data-original-title="Edit">
                                                    <i class="fa fa-pen"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-danger ml-1" data-original-title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>Colleen Hurst</td>
                                        <td>Javascript Developer</td>
                                        <td>San Francisco</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-button-action">
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-success" data-original-title="View">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-primary ml-1" data-original-title="Edit">
                                                    <i class="fa fa-pen"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-danger ml-1" data-original-title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>Sonya Frost</td>
                                        <td>Software Engineer</td>
                                        <td>Edinburgh</td>
                                    </tr> -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.main.footer')