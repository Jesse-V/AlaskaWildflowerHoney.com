
var dynamic = $(".mid_col #dynamic");
var cardForm  = dynamic.find("#cardForm");
var checkForm = dynamic.find("#checkForm");
var commonForm = dynamic.find("#commonFormInfo");

//append the common form (contact info) to both payment forms
cardForm.html(cardForm.html() + "\n" + commonForm.prop('outerHTML'));
checkForm.html(checkForm.html() + "\n" + commonForm.prop('outerHTML'));

//save the HTML for the card and check forms
var cardFormHTML  = dynamic.find("#cardForm").prop('outerHTML');
var checkFormHTML = dynamic.find("#checkForm").prop('outerHTML');
dynamic.html(""); //visually remove all fields


//show the card form HTML if the card button is selected
$(".mid_col #payOnline").click(function() {
    updateDynamic(cardFormHTML);
});


//show the check form HTML if the check button is selected
$(".mid_col #payCheck").click(function() {
    updateDynamic(checkFormHTML);
});


//slowly replace the payment selection area with the given form HTML
function updateDynamic(html)
{
    dynamic.hide();
    dynamic.html(html);
    dynamic.fadeIn("slow");

    $(".mid_col #paymentChoice").html("");
}


//copy the customer's first and last name from billing (card) to shipping (common)
function copyName()
{
    var first = $("input[name=x_first_name]").val();
    var last  = $("input[name=x_last_name]").val();
    $("input[name=x_ship_to_first_name]").val(first);
    $("input[name=x_ship_to_last_name]").val(last);
}
