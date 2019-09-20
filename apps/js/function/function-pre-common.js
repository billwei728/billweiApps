
/*!
 * functionPreCommon.js v1.0.00
 *
 * Licensed BW © BillWei
 */

// Function - Page Pre-Loading
function preLoading() 
{
    $(window).on("load", function() { 
		$(".loader").fadeOut("slow");
	});
}

// Function - Allow only number & decimal value
function isNumberKey(evt) 
{
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}
