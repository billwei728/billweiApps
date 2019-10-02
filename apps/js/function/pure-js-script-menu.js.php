
<script type="text/javascript">
// <!-- Pure Js Script - Menu -->
$(function() {
    var dataSet = <?php echo json_encode($result['menu']); ?>;
    var table = $('#tblMenu').DataTable( {
        'responsive': true,
        'data': dataSet,
        'columns': [
            { 'title': "", 'defaultContent': "", 'data': "id", 'render': dataChkBox },
            { 'title': "No", 'data': "rowid" },
            { 'title': "Module", 'data': "module" },
            { 'title': "P. Node", 'data': "parent" },
            { 'title': "Menu Name", 'data': "name" },
            { 'title': "Navigate URL", 'data': "url" },
            { 'title': "Icon", 'data': "icon" },
            { 'title': "ID Name", 'data': "idname" },
            { 'title': "Rank", 'data': "rank", 'render': dataRank }
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

    // Select Populate
    var optModule = <?php echo json_encode($result['optModule']); ?>;
    var selectModule = document.getElementById("module_name_new");
    populateSelOpt(optModule, selectModule);
    selectOnChange("module_name_new", selectModule);

    var optParent = <?php echo json_encode($result['optParent']); ?>;
    var selectParent = document.getElementById("parent_node_new");
    populateSelOpt(optParent, selectParent);
    selectOnChange("parent_node_new", selectParent);

    // Refresh Select Picker
    $("#module_name_new").selectpicker('refresh').trigger('change');
});

// Datatable Checkbox Render
function dataChkBox(data, type, row) {
    if (type == "display") {
        return '<div class="custom-control custom-checkbox ml-2"><input type="checkbox" class="custom-control-input" id="row_check_' + data + '" name="row_check[]" value="' + row['rowid'] + '" required /><label class="custom-control-label" for="row_check_' + data + '"></label></div>';
    } else {
        return data;
    }
}

function dataRank(data, type, row) {
    if (type == "display") {
        var rank = row['rank'].split("_")[1];
        if ("up" == rank) {
            return '<button type="button" class="btn btn-outline-none outline-none shadow-none p-0 bg-transparent" id="btnUp_' + row['id'] + '" title="Move Up" onclick="return updRank([' + "'rank_up'," + row['id'] + ']);"><i class="far fa-arrow-alt-circle-up"></i></button>';
        } else if ("down" == rank) {
            return '<button type="button" class="btn btn-outline-none outline-none shadow-none p-0 bg-transparent" id="btnDown_' + row['id'] + '" title="Move Down" onclick="return updRank([' + "'rank_down'," + row['id'] + ']);"><i class="far fa-arrow-alt-circle-down"></i></button>';
        } else if ("no" == rank) {
            return '<button type="button" class="btn btn-outline-none outline-none shadow-none p-0 bg-transparent" id="btnNo_' + row['id'] + '" title="No Rank");"><i class="far fa-times-circle"></i></button>';
        } else {
            return '<button type="button" class="btn btn-outline-none outline-none shadow-none p-0 bg-transparent" id="btnUp_' + row['id'] + '" title="Move Up" onclick="return updRank([' + "'rank_up'," + row['id'] + ']);"><i class="far fa-arrow-alt-circle-up"></i></button><button type="button" class="btn btn-outline-none outline-none shadow-none p-0 bg-transparent" id="btnDown_' + row['id'] + '" title="Move Down" onclick="return updRank([' + "'rank_down'," + row['id'] + ']);"><i class="far fa-arrow-alt-circle-down ml-1"></i></button>';
        }
    } else {
        return data;
    }
}

// Button Update Action
function edit() {
    var rowID = rowCheck().split("||")[0],
        rowSelected = parseInt(rowCheck().split("||")[1]),
        table = $('#tblMenu').DataTable(),
        tableData = table.row(rowID-1).data();

    if (0 === rowSelected) {
        rowCheckAlert();
    } else {
        $("#row_checked").val(tableData["id"]);
        $("#menu_id_new").val(tableData["id"]);
        $("#menu_rank_new").val(tableData["rank"]);
        $('select[name=module_name_new]').val(tableData["module"]);
        $('select[name=parent_node_new]').val(tableData["parent"]);
        $("#err_module_name_new").removeClass("d-block");
        $("#err_parent_node_new").removeClass("d-block");
        $('.selectpicker').selectpicker('refresh');
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
    $("#menu_modal").find("input[type=text], textarea, select").val("");
    $('.selectpicker').selectpicker('refresh');
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
        rowSelected = parseInt(rowCheck().split("||")[1]),
        table = $('#tblMenu').DataTable(),
        tableData = table.row(rowID-1).data();

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
                $("#action_id").val(tableData["id"]);
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
    $("#action_id").val(id);
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

// Populate Select Options
function populateSelOpt(data, select) {
    var option,
        datalength = Object.keys(data).length;
    $.map(data, function(val, key) { 
        option = document.createElement('option');
        option.setAttribute('value', val);
        option.appendChild(document.createTextNode(key));
        select.appendChild(option); 
    });
}

// Alert Onchange Select Value
function selectOnChange(ids, select) {
    $("#" + ids).on('change', function(event) {
        $("#err_" + ids).removeClass("d-block");
    });
}
</script>
