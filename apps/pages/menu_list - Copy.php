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
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Add New Menu</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-wrapper">
                            <form method="post" action="" name="menu_modal" id="menu_modal" class="needs-validation" novalidate>
                                <div class="form-row">
                                    <div class="form-group col-md-6 mb-5 pl-0 pr-1">
                                        <label for="module_name_new" class="form-label">Module</label>
                                        <input type="text" class="form-input form-control" id="module_name_new" name="module_name_new" maxlength="2" aria-describedby="module_name" required />
                                        <div class="invalid-feedback">Please provide a valid prefix.</div>
                                    </div>
                                    <div class="form-group col-md-6 mb-5 pl-0">
                                        <label for="parent_node_new" class="form-label">Parent Node</label>
                                        <input type="text" class="form-input form-control" id="parent_node_new" name="parent_node_new" maxlength="1" aria-describedby="parent_node" required />
                                        <div class="invalid-feedback">Please provide a valid number.</div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12 mb-5 pl-0">
                                        <label for="menu_name_new" class="form-label">Menu Name</label>
                                        <input type="text" class="form-input form-control" id="menu_name_new" name="menu_name_new" maxlength="20" aria-describedby="menu_name" required />
                                        <div class="invalid-feedback">Please provide a valid name.</div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12 mb-5 pl-0">
                                        <label for="menu_url_new" class="form-label">Navigate URL</label>
                                        <input type="text" class="form-input form-control" id="menu_url_new" name="menu_url_new" maxlength="200" aria-describedby="menu_url" required />
                                        <div class="invalid-feedback">Please provide a valid url.</div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 mb-5 pl-0">
                                        <label for="menu_icon_new" class="form-label">Menu Icon</label>
                                        <input type="text" class="form-input form-control" id="menu_icon_new" name="menu_icon_new" maxlength="30" aria-describedby="menu_icon" />
                                        <!-- <div class="invalid-feedback">Please provide a valid icon.</div> -->
                                    </div>
                                    <div class="form-group col-md-6 mb-5 pl-0">
                                        <label for="menu_idname_new" class="form-label">Menu ID Name</label>
                                        <input type="text" class="form-input form-control" id="menu_idname_new" name="menu_idname_new" maxlength="20" aria-describedby="menu_idname" />
                                        <!-- <div class="invalid-feedback">Please provide a valid icon.</div> -->
                                    </div>
                                </div>

                                <input type="hidden" class="form-control" id="menu_action_new" name="menu_action" value="insert" />
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

        <script type="text/javascript">
            $(function() {
                // Bootstrap 4 DataTable
                var dataSet = <?php echo json_encode($result['menu']); ?>;
                var table = $('#tblMenu').DataTable( {
                    'responsive': true,
                    'data': dataSet,
                    'columns': [
                        { 'title': "", 'defaultContent': "", 'data': "id", 'render': dataChkBox },
                        { 'title': "No", 'data': "id", 'render': dataId },
                        { 'title': "Module", 'data': "module", 'render': dataModule },
                        { 'title': "P. Node", 'data': "parent", 'render': dataParent },
                        { 'title': "Menu Name", 'data': "name", 'render': dataName },
                        { 'title': "Navigate URL", 'data': "url", 'render': dataUrl },
                        { 'title': "Icon", 'data': "icon", 'render': dataIcon },
                        { 'title': "ID Name", 'data': "idname", 'render': dataIdName },
                        { 'title': "Rank", 'data': "rank", 'render': dataRank }
                    ],
                    'columnDefs': [
                        { 'targets': [0], 'orderable': false, 'searchable': false, 'width': "5%" },
                        { 'targets': [1], 'orderable': false, "className": "text-center", 'width': "5%" },
                        { 'targets': [2, 3], 'orderable': false, 'width': "5%" },
                        { 'targets': [4], 'width': "20%" },
                        { 'targets': [5], 'width': "35%" },
                        { 'targets': [6], 'width': "10%" },
                        { 'targets': [7], 'width': "15%" },
                        { 'targets': [8], 'orderable': false, "className": "text-center",'width': "10%" }
                    ],
                    'select': {
                        'style': "os",
                        'blurable': true,
                        'selector': "td:first-child"
                    },
                    'order': [[ 1, "asc" ]],
                    'select': false
                });
                new $.fn.dataTable.FixedHeader(table);

                // Rank Button
                var dataCount = [];
                $("input[name='menu_id[]']").each(function() {
                    dataCount.push($(this).val());
                });
                $("#btnUp_" + dataCount[0]).remove();
                $("#btnDown_" + dataCount.length).remove();

                // Enter Key to Fire Button Event
                body.addEventListener("keyup", function(event) {
                    if (event.keyCode === 13) {
                        event.preventDefault();
                        document.getElementById("btnAdd").click();
                    }
                });
            });

            function dataChkBox(data, type, row) {
                if (type == "display") {
                    return '<div class="custom-control custom-checkbox ml-2" style="margin-top: -15px;"><input type="checkbox" class="custom-control-input" id="row_check_' + data + '" name="row_check[]" value="' + data + '" required /><label class="custom-control-label" for="row_check_' + data + '"></label></div>';
                } else {
                    return data;
                }
            }

            function dataId(data, type, row) {
                if (type == "display") {
                    return '<input type="text" class="form-control form-control-sm text-center border-0 shadow-none" id="menu_id_' + data + '" name="menu_id[]" value="' + data + '" style="background-color: rgba(0, 0, 0, 0); outline: none;" size="2"; />';
                } else {
                    return data;
                }
            }

            function dataModule(data, type, row) {
                if (type == "display") {
                    return '<input type="text" class="form-control form-control-sm" maxlength="20" id="menu_module_' + row['id'] + '" name="menu_module[]" title="menu_module_' + data + '" value="' + data + '" required /> <div class="invalid-feedback">Please provide a valid name.</div>';
                } else {
                    return data;
                }
            }

            function dataParent(data, type, row) {
                if (type == "display") {
                    return '<input type="text" class="form-control form-control-sm" maxlength="5" id="menu_parent_' + row['id'] + '" name="menu_parent[]" title="menu_parent_' + data + '" value="' + data + '" required /> <div class="invalid-feedback">Please provide a valid name.</div>';
                } else {
                    return data;
                }
            }

            function dataName(data, type, row) {
                if (type == "display") {
                    return '<input type="text" class="form-control form-control-sm" maxlength="20" id="menu_name_' + row['id'] + '" name="menu_name[]" title="menu_name_' + data + '" value="' + data + '" required /> <div class="invalid-feedback">Please provide a valid name.</div>';
                } else {
                    return data;
                }
            }

            function dataUrl(data, type, row) {
                if (type == "display") {
                    return '<input type="text" class="form-control form-control-sm" maxlength="100" id="menu_url_' + row['id'] + '" name="menu_url[]" title="menu_url_' + data + '" value="' + data + '" required /> <div class="invalid-feedback">Please provide a valid url.</div>';
                } else {
                    return data;
                }
            }

            function dataIcon(data, type, row) {
                if (type == "display") {
                    return '<input type="text" class="form-control form-control-sm" maxlength="30" id="menu_icon_' + row['id'] + '" name="menu_icon[]" title="menu_icon_' + data + '" value="' + data + '" required /> <div class="invalid-feedback">Please provide a valid url.</div>';
                } else {
                    return data;
                }
            }

            function dataIdName(data, type, row) {
                if (type == "display") {
                    return '<input type="text" class="form-control form-control-sm" maxlength="30" id="menu_idname_' + row['id'] + '" name="menu_idname[]" title="menu_idname_' + data + '" value="' + data + '" required /> <div class="invalid-feedback">Please provide a valid url.</div>';
                } else {
                    return data;
                }
            }

            function dataRank(data, type, row) {
                if (type == "display") {
                    return '<input type="hidden" class="form-control" id="menu_rank_' + row['id'] + '" name="menu_rank[]" value="' + data + '" /><button type="button" class="btn btn-outline-none outline-none shadow-none p-0 bg-transparent" id="btnUp_' + row['id'] + '" title="Move Up" style="" onclick="return updRank([' + "'rank_up'," + row['id'] + ']);"><i class="far fa-arrow-alt-circle-up"></i></button><button type="button" class="btn btn-outline-none outline-none shadow-none p-0 bg-transparent" id="btnDown_' + row['id'] + '" title="Move Down" style="" onclick="return updRank([' + "'rank_down'," + row['id'] + ']);"><i class="far fa-arrow-alt-circle-down ml-1"></i></button>';
                    
                } else {
                    return data;
                }
            }

            // Form Update Action
            function update() {
                $("#menu_action").val("update");
                rowCheck();
                return true;
            }

            // Form Remove Action
            function remove() {
                $("#menu_action").val("delete");
                rowCheck();
                return true;
            }

            // Form Create Action
            function insert() {
                $("#menu_action").val("insert");
                return true;
            }

            // Update Rank
            function updRank(arrAction) {
                var action = arrAction.toString().split(',')[0],
                    id = arrAction.toString().split(',')[1];
                $("#menu_action").val("updRank");
                $("#updRank_id").val(id);
                $("#updRank_action").val(action);
                $("#menu_form").submit()
            }

            function rowCheck() {
                $('input[name="row_check[]"]').each(function() {
                    var rowID = $(this).val();
                    if ($(this).is(":checked")) {
                    } else {
                        $(this).removeAttr("name");
                        $('#menu_id_' + rowID).removeAttr("name");
                        $('#menu_module_' + rowID).removeAttr("name");
                        $('#menu_parent_' + rowID).removeAttr("name");
                        $('#menu_name_' + rowID).removeAttr("name");
                        $('#menu_url_' + rowID).removeAttr("name");
                        $('#menu_icon_' + rowID).removeAttr("name");
                        $('#menu_idname_' + rowID).removeAttr("name");
                        $('#menu_rank_' + rowID).removeAttr("name");
                    }
                });
            }

            // Menu Modal Input CSS
            $('input').focus(function(){
                $(this).parents('.form-group').addClass('focused');
            });

            $('input').blur(function(){
                var inputValue = $(this).val();
                if ( inputValue == "" ) {
                    $(this).removeClass('filled');
                    $(this).parents('.form-group').removeClass('focused');  
                } else {
                    $(this).addClass('filled');
                }
            })
        </script>