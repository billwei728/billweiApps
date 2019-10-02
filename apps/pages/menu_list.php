        <div class="bg-white pb-5">
            <form method="post" action="" name="menu_form" id="menu_form" 
                class="needs-validation" novalidate>
                <div class="card mb-3 bg-white rounded">
                    <div class="card-header container-fluid">
                        <div class="row align-items-center h-100">
                            <div class="col-md-9 font-weight-bold float-left">
                                <span class="titleForm"><strong>Menu Listing</strong></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Menu Listing -->
                        <table id="tblMenu" class="table table-striped table-bordered table-hover nowrap" 
                            style="width:100%"></table>
                    </div>
                </div>

                <input type="hidden" class="form-control" id="menu_action" name="menu_action" 
                    value="select" />
                <input type="hidden" class="form-control" id="updRank_action" name="updRank_action" 
                    value="" />
                <input type="hidden" class="form-control" id="action_id" name="action_id" 
                    value="" />

                <div class="btn-group float-right">
                    <button type="button" class="btn btn-outline-secondary rounded mr-1" id="btnEdit" title="Update" onclick="return edit();"><i class="far fa-edit"></i> Edit</button>
                    <button type="button" class="btn btn-outline-danger rounded" id="btnDelete" title="Delete" onclick="return remove();"><i class="far fa-trash-alt"></i> Delete</button>
                    <button type="button" class="btn btn-outline-primary rounded ml-1 mr-2" 
                        id="btnAdd" title="Create" data-toggle="modal" data-target="#modal_form" onclick="return add();"><i class="far fa-plus-square"></i> Create</button>
                </div>
            </form>
        </div>

        <!-- Create Modal -->
        <div class="modal fade" id="modal_form">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title"><span class="titleForm" id="modal_id"><strong>Add New Menu</strong></span></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-wrapper">
                            <form method="post" action="" name="menu_modal" id="menu_modal" class="needs-validation" novalidate>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="module_name_new" class="blockquote-footer">Module <strong class="text-danger">* </strong></label>
                                        <select class="selectpicker show-tick fab" data-header="Select a module" data-live-search="true" data-style="btn-outline-secondary" data-width="100%" data-size="7" id="module_name_new" name="module_name_new" required>
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback" id="err_module_name_new">Please select a valid module.</div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="parent_node_new" class="blockquote-footer">Parent Node <strong class="text-danger">* </strong></label>
                                        <select class="selectpicker show-tick fab" data-header="Select a parent node" data-live-search="true" data-style="btn-outline-secondary" data-width="100%" data-size="7" id="parent_node_new" name="parent_node_new" required>
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback" id="err_parent_node_new">Please select a valid parent node.</div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="menu_name_new" class="blockquote-footer">Menu Name <strong class="text-danger">* </strong></label>
                                        <input type="text" class="form-control" id="menu_name_new" name="menu_name_new" maxlength="20" required />
                                        <div class="invalid-feedback">Please provide a valid name.</div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="menu_url_new" class="blockquote-footer">Navigate URL <strong class="text-danger">* </strong></label>
                                        <input type="text" class="form-control" id="menu_url_new" name="menu_url_new" maxlength="200" aria-describedby="menu_url" required />
                                        <div class="invalid-feedback">Please provide a valid url.</div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="menu_icon_new" class="blockquote-footer">Menu Icon</label>
                                        <input type="text" class="form-control" id="menu_icon_new" name="menu_icon_new" maxlength="30" aria-describedby="menu_icon" />
                                        <!-- <div class="invalid-feedback">Please provide a valid icon.</div> -->
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="menu_idname_new" class="blockquote-footer">Menu ID Name</label>
                                        <input type="text" class="form-control" id="menu_idname_new" name="menu_idname_new" maxlength="20" aria-describedby="menu_idname" />
                                        <!-- <div class="invalid-feedback">Please provide a valid icon.</div> -->
                                    </div>
                                </div>

                                <input type="hidden" class="form-control" id="menu_action_new" name="menu_action" value="" />
                                <input type="hidden" class="form-control" id="row_checked" name="row_checked" value="insert" />
                                <input type="hidden" class="form-control" id="menu_id_new" name="menu_id_new" value="" />
                                <input type="hidden" class="form-control" id="menu_rank_new" name="menu_rank_new" value="" />

                                <div class="btn-group float-right">
                                    <button type="submit" class="btn btn-outline-secondary d-none" id="btnUpdate" title="Update" onclick="return update();">
                                        <i class="fas fa-edit"></i> Update
                                    </button>
                                    <button type="submit" class="btn btn-outline-success ml-1 d-none" id="btnSubmit" title="Search" onclick="return insert();">
                                        <i class="far fa-caret-square-right"></i> Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once(JSFUNCTION . '/pure-js-script-menu.js.php'); ?>
