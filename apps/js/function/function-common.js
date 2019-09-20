
/*!
 * functionCommon.js v1.0.00
 *
 * Licensed BW © BillWei
 */

// Function - Retrieve URL Parameter
function getParameterByName(name, url) 
{
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

// Function - Fire Tooltip
function tooltip(ids) 
{
    $(ids).tooltip({title: "Clear", trigger: "hover"});
}

// Function - Erase Input
// function inputErase(ids, intpids) 
// {
//     document.getElementById(ids).addEventListener("click", function() {
//         $(intpids).val('');
//     });
// }
