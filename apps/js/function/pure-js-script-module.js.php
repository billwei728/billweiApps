
<script type="text/javascript">
// <!-- Pure Js Script - Module -->
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
    $("#btnDown_" + dataCount[0] + " i").removeClass("ml-1");
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
        return '<div class="custom-control custom-checkbox ml-2"><input type="checkbox" class="custom-control-input" id="row_check_' + row['id'] + '" name="row_check[]" value="' + data + '" required /><label class="custom-control-label" for="row_check_' + row['id'] + '"></label></div>';
    } else {
        return data;
    }
}

function dataId(data, type, row) {
    if (type == "display") {
        return '<input type="text" class="form-control form-control-sm text-center border-0 shadow-none" id="module_id_' + row['id'] + '" name="module_id[]" value="' + data + '" aria-describedby="inputGroupPrepend" style="background-color: rgba(0, 0, 0, 0); outline: none;" size="2"; readonly />';
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
