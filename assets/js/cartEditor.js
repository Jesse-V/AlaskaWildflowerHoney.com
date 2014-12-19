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
        //TODO
        console.log(retVal);
    })
    .fail(function(temp, status) {
        alert(status);
    });
});
