        <div class="bg-white pb-5">
            <form method="post" action="" name="account_form" id="account_form" 
                class="needs-validation" novalidate>
                <div class="card mb-3 bg-white rounded">
                    <div class="card-header container-fluid">
                        <div class="row align-items-center h-100">
                            <div class="col-md-9 font-weight-bold float-left">
                                <span class="titleForm"><strong>Account Listing</strong></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Account Listing -->
                        <table id="tblAccount" class="table table-striped table-bordered table-hover nowrap" 
                            style="width:100%"></table>
                    </div>
                </div>

                <input type="hidden" class="form-control" id="account_action" name="account_action" 
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
                        <h4 class="modal-title"><span class="titleForm" id="modal_id"><strong>Add New Detail</strong></span></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-wrapper">
                            <form method="post" action="" name="account_modal" id="account_modal" class="needs-validation" novalidate>
                                <div class="form-group">
                                    <div class="mb-5">
                                        <label for="account_type_new" class="form-label">Account Type</label>
                                        <input type="text" class="form-input form-control" id="account_type_new" name="account_type_new" maxlength="30" aria-describedby="account_name" required />
                                        <div class="invalid-feedback">Please provide a valid type.</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="mb-5">
                                        <label for="account_id_new" class="form-label">Account ID</label>
                                        <input type="text" class="form-input form-control" id="account_id_new" name="account_id_new" maxlength="30" aria-describedby="account_name" required />
                                        <div class="invalid-feedback">Please provide a valid type.</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="mb-5">
                                        <label for="account_pass_new" class="form-label">Account Password</label>
                                        <input type="text" class="form-input form-control" id="account_pass_new" name="account_pass_new" maxlength="30" aria-describedby="account_name" required />
                                        <div class="invalid-feedback">Please provide a valid type.</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="mb-5">
                                        <label for="account_remark_new" class="form-label">Account Remark</label>
                                        <input type="text" class="form-input form-control" id="account_remark_new" name="account_remark_new" maxlength="50" aria-describedby="account_prefix_new" required />
                                        <div class="invalid-feedback">Please provide a valid id.</div>
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" id="account_action_new" name="account_action" value="insert" />
                                <div class="btn-group float-right">
                                    <button type="submit" class="btn btn-outline-success" id="btnSubmit" title="Search" onclick="return insert();">
                                        <i class="far fa-caret-square-right"></i> Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once(JSFUNCTION . '/pure-js-script-account.js.php'); ?>
        