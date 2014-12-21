
//performs AJAX call to update the cart preview in the sidebar
function updateSidebarCartPreview() {
    $.ajax({
        url: "/assets/php/ajax/cartPreviewView.php",
        data: {
            action: "getAll"
        }
    })
    .done(function(retVal) {
        $(".right_col .shoppingCart table").prop('outerHTML', retVal.html);
        $(".right_col .shoppingCart .total").prop('outerHTML', retVal.total);
    })
    .fail(function(info, status) {
        alert("Sorry, an issue was encountered, specifically, " + info.statusText);
    });
}
