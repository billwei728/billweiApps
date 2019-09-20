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

        <script type="text/javascript">
            $(function() {
                // Bootstrap 4 DataTable
                var dataSet = <?php echo json_encode($result['module']); ?>;
                var table = $('#tblModule').DataTable( {
                    'responsive': true,
                    'data': dataSet,
                    'columns': [
                        { 'title': "", 'defaultContent': "", 'data': "id", 'render': dataChkBox },
                        { 'title': "No", 'data': "id", 'render': dataId },
                        { 'title': "Module Name", 'data': "name", 'render': dataName },
                        { 'title': "Prefix", 'data': "prefix", 'render': dataPrefix },
                        { 'title': "Rank", 'data': "rank", 'render': dataRank }
                    ],
                    'columnDefs': [
                        { 'targets': [0], 'orderable': false, 'searchable': false, 'width': "5%" },
                        { 'targets': [1], "className": "text-center", 'width': "5%" },
                        { 'targets': [2], 'width': "70%" },
                        { 'targets': [3], 'width': "15%" },
                        { 'targets': [4], 'orderable': false, "className": "text-center", 'width': "5%" }
                    ],
                    'select': {
                        'style': "os",
                        'blurable': true,
                        'selector': "td:first-child"
                    },
                    // 'language': {
                    //     'select': {
                    //         'rows': {
                    //             _: "You have selected %d rows",
                    //             0: "Click a row to select it",
                    //             1: "Only 1 row selected"
                    //         }
                    //     }
                    // },
                    'order': [[ 1, "asc" ]],
                    'select': false
                });
                new $.fn.dataTable.FixedHeader(table);

                // Rank Button
                var dataCount = [];
                $("input[name='module_rank[]']").each(function() {
                    dataCount.push($(this).val());
                });
                $("#btnUp_" + dataCount[0]).remove();
                $("#btnDown_" + dataCount[dataCount.length - 1]).remove();

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
                    return '<div class="custom-control custom-checkbox ml-2" style="margin-top: -15px;"><input type="checkbox" class="custom-control-input" id="row_check_' + row['id'] + '" name="row_check[]" value="' + data + '" required /><label class="custom-control-label" for="row_check_' + row['id'] + '"></label></div>';
                } else {
                    return data;
                }
            }

            function dataId(data, type, row) {
                if (type == "display") {
                    return '<input type="text" class="form-control form-control-sm text-center border-0 shadow-none" id="module_id_' + row['id'] + '" name="module_id[]" value="' + data + '" aria-describedby="inputGroupPrepend" style="background-color: rgba(0, 0, 0, 0); outline: none;" size="2"; />';
                } else {
                    return data;
                }
            }

            function dataName(data, type, row) {
                if (type == "display") {
                    return '<input type="text" class="form-control form-control-sm" maxlength="20" id="module_name_' + row['id'] + '" name="module_name[]" title="module_name_' + row['id'] + '" value="' + data + '" aria-describedby="inputGroupPrepend" required /> <div class="invalid-feedback">Please provide a valid name.</div>';
                } else {
                    return data;
                }
            }

            function dataPrefix(data, type, row) {
                if (type == "display") {
                    return '<input type="text" class="form-control form-control-sm" maxlength="3" id="module_prefix_' + row['id'] + '" name="module_prefix[]" title="module_prefix_' + row['id'] + '" value="' + data + '" aria-describedby="inputGroupPrepend" required /> <div class="invalid-feedback">Please provide a valid prefix.</div>';
                } else {
                    return data;
                }
            }

            function dataRank(data, type, row) {
                if (type == "display") {
                    return '<input type="hidden" class="form-control" id="module_rank_' + row['id'] + '" name="module_rank[]" value="' + data + '" /><button type="button" class="btn btn-outline-none outline-none shadow-none p-0 bg-transparent" id="btnUp_' + row['id'] + '" title="Move Up" style="" onclick="return updRank([' + "'rank_up'," + row['id'] + ']);"><i class="far fa-arrow-alt-circle-up"></i></button><button type="button" class="btn btn-outline-none outline-none shadow-none p-0 bg-transparent" id="btnDown_' + row['id'] + '" title="Move Down" style="" onclick="return updRank([' + "'rank_down'," + row['id'] + ']);"><i class="far fa-arrow-alt-circle-down ml-1"></i></button>';
                    
                } else {
                    return data;
                }
            }

            // Form Update Action
            function update() {
                // console.log($("#tblModule").DataTable().rows('.selected').data()[0]);
                $("#module_action").val("update");
                return true;
            }

            // Form Remove Action
            function remove() {
                $("#module_action").val("delete");
                return true;
            }

            // Form Create Action
            function insert() {
                $("#module_action").val("insert");
                return true;
            }

            // Update Rank
            function updRank(arrAction) {
                var action = arrAction.toString().split(',')[0],
                    id = arrAction.toString().split(',')[1];
                $("#module_action").val("updRank");
                $("#updRank_id").val(id);
                $("#updRank_action").val(action);
                $("#module_form").submit();
            }

            // Module Modal Input CSS
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