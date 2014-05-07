// JavaScript Document

var dynamic = $(".mid_col #dynamic");
var cardForm  = dynamic.find("#cardForm");
var checkForm = dynamic.find("#checkForm");
var commonForm = dynamic.find("#commonFormInfo");

cardForm.html(cardForm.html() + "\n" + commonForm.prop('outerHTML'));
checkForm.html(checkForm.html() + "\n" + commonForm.prop('outerHTML'));

var cardFormHTML  = dynamic.find("#cardForm").prop('outerHTML');
var checkFormHTML = dynamic.find("#checkForm").prop('outerHTML');
dynamic.html("");

$(".mid_col #payOnline").click(function() {
    updateDynamic(cardFormHTML);
    $(".mid_col .total").html("Total: $" + total * 1.025);
});


$(".mid_col #payCheck").click(function() {
    updateDynamic(checkFormHTML);
});


function updateDynamic(html)
{
    dynamic.hide();
    dynamic.html(html);
    dynamic.fadeIn("slow");

    $(".mid_col #paymentChoice").html("");
}
