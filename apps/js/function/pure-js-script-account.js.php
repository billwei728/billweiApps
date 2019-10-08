
<script type="text/javascript">
// <!-- Pure Js Script - Account -->
$(function() {
    // Bootstrap 4 DataTable
    var dataSet = <?php echo json_encode($result['account']); ?>;
    var table = $('#tblAccount').DataTable( {
        'responsive': true,
        'data': dataSet,
        'columns': [
            { 'title': "", 'defaultContent': "", 'data': "id", 'render': dataChkBox },
            { 'title': "No", 'data': "id", 'render': dataNo },
            { 'title': "Account Type", 'data': "name", 'render': dataName },
            { 'title': "ID", 'data': "userid", 'render': dataId },
            { 'title': "Password", 'data': "password", 'render': dataPass },
            { 'title': "Remark", 'data': "remark", 'render': dataRemark },
            { 'title': "Rank", 'data': "rank", 'render': dataRank }
        ],
        'columnDefs': [
            { 'targets': [0], 'orderable': false, 'searchable': false, 'width': "3%" },
            { 'targets': [1], "className": "text-center", 'width': "5%" },
            { 'targets': [2], 'width': "25%" },
            { 'targets': [3, 4, 5], 'width': "20%" },
            { 'targets': [6], 'orderable': false, "className": "text-center", 'width': "5%" }
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
    $("input[name='account_rank[]']").each(function() {
        dataCount.push($(this).val());
    });

    $("#btnUp_" + dataCount[0]).remove();
    $("#btnDown_" + dataCount[0] + " i").removeClass("ml-1");
    $("#btnDown_" + dataCount[dataCount.length - 1]).remove();

    // Enter Key to Fire Button Event
    var dataLength = dataCount.length;
    var arrname = [],
        arrid = [],
        arrpass = [],
        arrremark = [];
    for (var i = 1; i <= dataLength; i++) {
        arrname.push("account_name_copy_" + i);
        arrid.push("account_id_copy_" + i);
        arrpass.push("account_pass_copy_" + i);
        arrremark.push("account_remark_copy_" + i);
    }
    for (var x = 0; x < dataLength; x++) {
        (function () {
            var name = arrname[x];
            document.getElementById(arrname[x]).addEventListener("click", function() { copyToClipboard(name); }, false);
            var idname = arrid[x];
            document.getElementById(arrid[x]).addEventListener("click", function() { copyToClipboard(idname); }, false);
            var pass = arrpass[x];
            document.getElementById(arrpass[x]).addEventListener("click", function() { copyToClipboard(pass); }, false);
            var remark = arrremark[x];
            document.getElementById(arrremark[x]).addEventListener("click", function() { copyToClipboard(remark); }, false);
        }()); // immediate invocation
    }

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

function dataNo(data, type, row) {
    if (type == "display") {
        return '<input type="text" class="form-control form-control-sm text-center border-0 shadow-none" id="account_no_' + row['id'] + '" name="account_no[]" value="' + data + '" aria-describedby="inputGroupPrepend" style="background-color: rgba(0, 0, 0, 0); outline: none;" size="2"; readonly />';
    } else {
        return data;
    }
}

function dataName(data, type, row) {
    if (type == "display") {
        return '<div class="input-group"><input type="text" class="form-control form-control-sm py-3" maxlength="50" id="account_name_' + row['id'] + '" name="account_name[]" title="account_name_' + row['id'] + '" value="' + data + '" aria-describedby="inputGroupPrepend" required /> <div class="invalid-feedback">Please provide a valid name.</div><div class="input-group-append"><button type="button" class="btn btn-outline-secondary p-1 px-2 click-me" id="account_name_copy_' + row['id'] + '" data-clipboard-target="#account_name_' + row['id'] + '"><i class="far fa-copy"></i></button></div></div>';
    } else {
        return data;
    }
}

function dataId(data, type, row) {
    if (type == "display") {
        return '<div class="input-group"><input type="text" class="form-control form-control-sm py-3" maxlength="50" id="account_id_' + row['id'] + '" name="account_id[]" title="account_id_' + row['id'] + '" value="' + data + '" aria-describedby="inputGroupPrepend" required /> <div class="invalid-feedback">Please provide a valid id.</div><div class="input-group-append"><button type="button" class="btn btn-outline-secondary p-1 px-2" id="account_id_copy_' + row['id'] + '" data-clipboard-target="#account_id_' + row['id'] + '"><i class="far fa-copy"></i></button></div></div>';
    } else {
        return data;
    }
}

function dataPass(data, type, row) {
    if (type == "display") {
        return '<div class="input-group"><input type="password" class="form-control form-control-sm py-3" maxlength="50" id="account_pass_show_' + row['id'] + '" name="account_pass[]" title="account_pass_' + row['id'] + '" value="' + data + '" aria-describedby="inputGroupPrepend" required /><input type="text" class="w-0" id="account_pass_' + row['id'] + '" value="' + data + '" /> <div class="invalid-feedback">Please provide a valid name.</div><div class="input-group-append"><button type="button" class="btn btn-outline-secondary p-1 px-2" id="account_pass_copy_' + row['id'] + '" data-clipboard-target="#account_pass_' + row['id'] + '"><i class="far fa-copy"></i></button></div></div>';
    } else {
        return data;
    }
}

function dataRemark(data, type, row) {
    if (type == "display") {
        return '<div class="input-group"><input type="text" class="form-control form-control-sm py-3" maxlength="50" id="account_remark_' + row['id'] + '" name="account_remark[]" title="account_remark_' + row['id'] + '" value="' + data + '" aria-describedby="inputGroupPrepend" required /> <div class="invalid-feedback">Please provide a valid name.</div><div class="input-group-append"><button type="button" class="btn btn-outline-secondary p-1 px-2" id="account_remark_copy_' + row['id'] + '" data-clipboard-target="#account_remark_' + row['id'] + '"><i class="far fa-copy"></i></button></div></div>';
    } else {
        return data;
    }
}

function dataRank(data, type, row) {
    if (type == "display") {
        return '<input type="hidden" class="form-control" id="account_rank_' + row['id'] + '" name="account_rank[]" value="' + data + '" /><button type="button" class="btn btn-outline-none outline-none shadow-none p-0 bg-transparent" id="btnUp_' + row['id'] + '" title="Move Up" style="" onclick="return updRank([' + "'rank_up'," + row['id'] + ']);"><i class="far fa-arrow-alt-circle-up"></i></button><button type="button" class="btn btn-outline-none outline-none shadow-none p-0 bg-transparent" id="btnDown_' + row['id'] + '" title="Move Down" style="" onclick="return updRank([' + "'rank_down'," + row['id'] + ']);"><i class="far fa-arrow-alt-circle-down ml-1"></i></button>';
        
    } else {
        return data;
    }
}

// Form Update Action
function update() {
    // console.log($("#tblaccount").DataTable().rows('.selected').data()[0]);
    $("#account_action").val("update");
    return true;
}

// Form Remove Action
function remove() {
    $("#account_action").val("delete");
    return true;
}

// Form Create Action
function insert() {
    $("#account_action").val("insert");
    return true;
}

// Update Rank
function updRank(arrAction) {
    var action = arrAction.toString().split(',')[0],
        id = arrAction.toString().split(',')[1];
    $("#account_action").val("updRank");
    $("#updRank_id").val(id);
    $("#updRank_action").val(action);
    $("#account_form").submit();
}

// Account Modal Input CSS
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
