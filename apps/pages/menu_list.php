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
                                        <label for="module_name_new" class="blockquote-footer">Module</label>
                                        <input type="text" class="form-control" id="module_name_new" name="module_name_new" maxlength="2" aria-describedby="module_name" required />
                                        <div class="invalid-feedback">Please provide a valid prefix.</div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="parent_node_new" class="blockquote-footer">Parent Node</label>
                                        <input type="text" class="form-control" id="parent_node_new" name="parent_node_new" maxlength="1" aria-describedby="parent_node" required />
                                        <div class="invalid-feedback">Please provide a valid number.</div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="menu_name_new" class="blockquote-footer">Menu Name</label>
                                        <input type="text" class="form-control" id="menu_name_new" name="menu_name_new" maxlength="20" aria-describedby="menu_name" required />
                                        <div class="invalid-feedback">Please provide a valid name.</div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="menu_url_new" class="blockquote-footer">Navigate URL</label>
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

        <script type="text/javascript">
            $(function() {
                // Bootstrap 4 DataTable
                var dataSet = <?php echo json_encode($result['menu']); ?>;
                var table = $('#tblMenu').DataTable( {
                    'responsive': true,
                    'data': dataSet,
                    'columns': [
                        { 'title': "", 'defaultContent': "", 'data': "id", 'render': dataChkBox },
                        { 'title': "No", 'data': "id" },
                        { 'title': "Module", 'data': "module" },
                        { 'title': "P. Node", 'data': "parent" },
                        { 'title': "Menu Name", 'data': "name" },
                        { 'title': "Navigate URL", 'data': "url" },
                        { 'title': "Icon", 'data': "icon" },
                        { 'title': "ID Name", 'data': "idname" },
                        { 'title': "Rank", 'data': "rank" }
                    ],
                    'columnDefs': [
                        { 'targets': [0, 1, 2, 3], "className": "text-center", 'width': "5%" },
                        { 'targets': [4], 'width': "20%" },
                        { 'targets': [5], 'width': "35%" },
                        { 'targets': [6], 'width': "10%" },
                        { 'targets': [7], 'width': "15%" },
                        { 'targets': [8], "className": "text-center",'width': "10%" }
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
                // var dataCount = [];
                // $("input[name='menu_id[]']").each(function() {
                //     dataCount.push($(this).val());
                // });
                // $("#btnUp_" + dataCount[0]).remove();
                // $("#btnDown_" + dataCount.length).remove();

                // Enter Key to Fire Button Event
                body.addEventListener("keyup", function(event) {
                    if (event.keyCode === 13) {
                        event.preventDefault();
                        document.getElementById("btnAdd").click();
                    }
                });

                // Only Allow One Checkbox Checked
                $('input[type="checkbox"]').on('change', function() {
                    $('input[type="checkbox"]').not(this).prop('checked', false);
                });
            });

            // Datatable Checkbox Render
            function dataChkBox(data, type, row) {
                if (type == "display") {
                    return '<div class="custom-control custom-checkbox ml-2" style="margin-top: -15px;"><input type="checkbox" class="custom-control-input" id="row_check_' + data + '" name="row_check[]" value="' + data + '" required /><label class="custom-control-label" for="row_check_' + data + '"></label></div>';
                } else {
                    return data;
                }
            }

            // Button Update Action
            function edit() {
                var table = $('#tblMenu').DataTable(),
                    tableData,
                    rowID = rowCheck().split("||")[0],
                    rowSelected = parseInt(rowCheck().split("||")[1]);

                if (0 === rowSelected) {
                    rowCheckAlert();
                } else {
                    tableData = table.row(rowID-1).data();
                    $("#row_checked").val(rowID);
                    $("#menu_id_new").val(tableData["id"]);
                    $("#menu_rank_new").val(tableData["rank"]);
                    $("#module_name_new").val(tableData["module"]).parents(".form-group").addClass("focused");
                    $("#parent_node_new").val(tableData["parent"]).parents(".form-group").addClass("focused");
                    $("#menu_name_new").val(tableData["name"]).parents(".form-group").addClass("focused");
                    $("#menu_url_new").val(tableData["url"]).parents(".form-group").addClass("focused");
                    $("#menu_icon_new").val(tableData["icon"]).parents(".form-group").addClass("focused");
                    $("#menu_idname_new").val(tableData["idname"]).parents(".form-group").addClass("focused");
                    $("#modal_id").text("Edit Menu");
                    $("#btnSubmit").addClass("d-none");
                    $("#btnUpdate").removeClass("d-none");
                    $("#modal_form").modal("toggle");
                }
            }

            // Button Add Action
            function add() {
                $("#menu_modal").find("input[type=text], textarea").val("");
                $("#modal_id").text("Add New Menu");
                $("#btnSubmit").removeClass("d-none");
                $("#btnUpdate").addClass("d-none");
            }

            // Form Create Action
            function update() {
                $("#menu_action_new").val("update");
                return true;
            }

            // Form Create Action
            function insert() {
                $("#menu_action_new").val("insert");
                return true;
            }

            // Form Remove Action
            function remove() {
                var rowID = rowCheck().split("||")[0],
                    rowSelected = parseInt(rowCheck().split("||")[1]);

                if (0 === rowSelected) {
                    rowCheckAlert();
                } else {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.value) {
                            $("#menu_action").val("delete");
                            $("#menu_form").submit();
                        }
                    });
                }
            }

            // Update Rank
            function updRank(arrAction) {
                var action = arrAction.toString().split(',')[0],
                    id = arrAction.toString().split(',')[1];
                $("#menu_action").val("updRank");
                $("#updRank_id").val(id);
                $("#updRank_action").val(action);
                $("#menu_form").submit();
            }

            // Check Checkbox
            function rowCheck() {
                var rowID,
                    rowSelected = 0;
                $('input[name="row_check[]"]').each(function() {
                    if ($(this).is(":checked")) {
                        rowID = $(this).val();
                        rowSelected++
                    }
                });
                return rowID + "||" + rowSelected;
            }

            function rowCheckAlert() {
                Swal.fire({
                    type: "error",
                    title: "Oops...",
                    text: "No row(s) selected, please select one row to edit!",
                    animation: false,
                    customClass: {
                        popup: 'animated tada'
                    }
                });
            }

            // Menu Modal Input CSS
            $('input').focus(function() {
                $(this).parents('.form-group').addClass('focused');
            });

            $('input').blur(function() {
                var inputValue = $(this).val();
                if ( inputValue == "" ) {
                    $(this).removeClass('filled');
                    $(this).parents('.form-group').removeClass('focused');  
                } else {
                    $(this).addClass('filled');
                }
            })
        </script>