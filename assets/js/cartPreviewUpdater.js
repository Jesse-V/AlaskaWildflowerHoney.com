
//performs AJAX call to update the cart preview in the sidebar
function updateSidebarCartPreview() {
    $.ajax({
        url: "/assets/php/ajax/cartPreviewView.php",
        data: {
            action: "getAll"
        }
    })
    .done(function(retVal) {
        var emptyDiv = $(".right_col .shoppingCart .empty");
        if (emptyDiv.length == 1) { //if cart empty
            emptyDiv.prop('outerHTML', retVal.html + retVal.total +
                '<div class="cartActions">' +
                    '<form action="/checkout/CartEditor.php">' +
                        '<input type="submit" value="Edit Cart">' +
                    '</form>' +
                    '<form action="/checkout/1cart_checkout.php">' +
                        '<input type="submit" value="Proceed to Checkout">' +
                    '</form>' +
                '</div>');
        }
        else { //if cart has items, then just update them
            $(".right_col .shoppingCart table").prop('outerHTML', retVal.html);
            $(".right_col .shoppingCart .total").prop('outerHTML', retVal.total);
        }
        //$(".right_col .shoppingCart .empty").prop('outerHTML', retVal.html + retVal.total);
    })
    .fail(function(info, status) {
        console.log("FAIL");
        alert("Sorry, an issue was encountered, specifically, " + info.statusText);
    });
}
