$("input.trash").click(function() {
    var button = this;

    $.ajax({
        url: "/assets/php/ajax/cartEditor.php",
        data: {
            action: "deleteItem",
            table: $(button).closest("table").attr('id'),
            element: $(button).attr('name')
        }
    })
    .done(function(retVal) {
        if (retVal == "Success") {
            updateTable(button);
            updateTotal();
            updateSidebarCartPreview();
        }
        else if (retVal == "Failure") {
            console.log(retVal);
        }
        else {

        }
    })
    .fail(function(info, status) {
        alert("Sorry, an issue was encountered, specifically, " + info.statusText);
    });
});



function updateTable(button) {
    table = $(button).closest("table");
    row = $(button).closest("tr");

    //remove item's row
    row.hide(300, function() {
        row.remove();

        //remove table if it has no elements besides header
        if (table.find('tr').length == 1) {
            table.hide(500, function() {
                table.remove();
            });
        }
    });
}



function updateTotal() { //updates the cart editor's cart total
    $.ajax({
        url: "/assets/php/ajax/cartEditorView.php",
        data: {
            action: "getTotal"
        }
    })
    .done(function(retVal) {
        if (retVal.indexOf("$0.00") > -1) //if total is zero
            window.location = "/stevesbees_home.php";
        else
            $("#cartEditorView .total").prop('outerHTML', retVal);
    })
    .fail(function(info, status) {
        alert("Sorry, an issue was encountered, specifically, " + info.statusText);
    });
}



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
