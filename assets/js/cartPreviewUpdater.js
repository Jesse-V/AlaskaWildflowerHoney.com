
//performs AJAX call to update the cart preview in the sidebar
function updateSidebarCartPreview() {
    $.ajax({
        url: "/assets/php/ajax/cartPreviewView.php",
        data: {
            action: "getAll"
        }
    })
    .done(function(retVal) {
        var contents = $(".right_col .shoppingCart .contents");
        if (retVal.total.indexOf('$0.00') >= 0) {
            contents.html('<div class="empty">Currently empty</div>');
        }
        else {
            contents.html(retVal.html + retVal.total +
                '<div class="cartActions">' +
                    '<form action="/checkout/CartEditor.php">' +
                        '<input type="submit" value="Edit Cart">' +
                    '</form>' +
                    '<form action="/checkout/1cart_checkout.php">' +
                        '<input type="submit" value="Proceed to Checkout">' +
                    '</form>' +
                '</div>');
        }
    })
    .fail(function(info, status) {
        console.log("FAIL");
        alert("Sorry, an issue was encountered, specifically, " + info.statusText);
    });
}
