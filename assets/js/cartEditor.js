
//handles the clicking of the trash icon
$("input.trash").click(function() {
    var button = this;

    //tells the cart manager to delete a specific item by its ID and type
    $.ajax({
        url: "/assets/php/ajax/cartManager.php",
        type: "post",
        data: {
            action: "deleteItem",
            table: $(button).closest("table").attr('id'), //determine if supply or bees
            element: $(button).attr('name') //get supply item ID, or bee item string
        }
    })
    .done(function(retVal) {
        if (retVal == "Success") {
            updateTable(button);    //visually remove the item
            updateTotal();          //refresh the total calculation via AJAX
            updateSidebarCartPreview(); //update the sidebar cart preview
        }
        else if (retVal == "Failure") {
            console.log(retVal);
        }
        else {
            console.log(retVal);
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
        type: "get",
        data: {
            action: "getTotal"
        }
    })
    .done(function(retVal) {
        if (retVal.indexOf("$0.00") > -1) //if total is zero
            window.location = "/stevesbees_home.php"; //go back
        else
            $("#cartEditorView .total").prop('outerHTML', retVal); //update total
    })
    .fail(function(info, status) {
        alert("Sorry, an issue was encountered, specifically, " + info.statusText);
    });
}

