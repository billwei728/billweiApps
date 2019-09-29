        <div class="bg-white pb-5">
            <form method="post" action="" name="module_form" id="module_form" 
                class="needs-validation" novalidate>
                <div class="card mb-3 bg-white rounded">
                    <div class="card-header container-fluid">
                        <div class="row align-items-center h-100">
                            <div class="col-md-9 font-weight-bold float-left">
                                <span class="titleForm"><strong>Module Listing</strong></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Module Listing -->
                        <table id="tblModule" class="table table-striped table-bordered table-hover nowrap" 
                            style="width:100%"></table>
                    </div>
                </div>

                <input type="hidden" class="form-control" id="module_action" name="module_action" 
                    value="select" />
                <input type="hidden" class="form-control" id="updRank_action" name="updRank_action" 
                    value="" />
                <input type="hidden" class="form-control" id="updRank_id" name="updRank_id" 
                    value="" />
                
                <div class="btn-group float-right">
                    <button type="submit" class="btn btn-outline-secondary rounded mr-1" id="btnUpdate" title="Update" onclick="return update();"><i class="far fa-edit"></i> Update</button>
                    <button type="submit" class="btn btn-outline-danger rounded" id="btnDelete" title="Delete" onclick="return remove();"><i class="far fa-trash-alt"></i> Delete</button>
                    <button type="button" class="btn btn-outline-primary rounded ml-1 mr-2" 
                        id="btnAdd" title="Create" data-toggle="modal" data-target="#modal_add"><i class="far fa-plus-square"></i> Create</button>
                </div>
            </form>
        </div>

        <!-- Create Modal -->
        <div class="modal fade" id="modal_add">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title"><span class="titleForm" id="modal_id"><strong>Add New Module</strong></span></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-wrapper">
                            <form method="post" action="" name="module_modal" id="module_modal" class="needs-validation" novalidate>
                                <div class="form-group">
                                    <div class="mb-5">
                                        <label for="module_name_new" class="form-label">Module Name</label>
                                        <input type="text" class="form-input form-control" id="module_name_new" name="module_name_new" maxlength="20" aria-describedby="module_name" required />
                                        <div class="invalid-feedback">Please provide a valid name.</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="mb-5">
                                        <label for="module_prefix_new" class="form-label">Module Prefix</label>
                                        <input type="text" class="form-input form-control" id="module_prefix_new" name="module_prefix_new" maxlength="2" aria-describedby="module_prefix_new" required />
                                        <div class="invalid-feedback">Please provide a valid prefix.</div>
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" id="module_action_new" name="module_action" value="insert" />
                                <div class="btn-group float-right">
                                    <button type="submit" class="btn btn-outline-success" id="btnSubmit" title="Search" onclick="return insert();">
                                        <i class="far fa-caret-square-right"></i> Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <!-- <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" title="Close">
                            <i class="far fa-window-close"></i> Close
                        </button>
                    </div> -->
                </div>
            </div>
        </div>

        <?php include_once(JSFUNCTION . '/pure-js-script-module.js.php'); ?>
        