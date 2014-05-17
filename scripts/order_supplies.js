// JavaScript Document

var that = $(".mid_col");
hideAllGroupItems(0);


function hideAllGroupItems(duration) {
    var allGroupItems = that.find("table .subItem");
    allGroupItems.hide(duration);
}



that.find("table tr").click(function() {
    if (this.className.lastIndexOf("group", 0) === 0) //startsWith
    {
        hideAllGroupItems(750);

        var groupNum = this.className.substring(5);
        var subitems = that.find("table .subItem" + groupNum);
        subitems.show(750);
    }
});



var numInputs = $(".mid_col input[type=number]");
numInputs.change(handlePrefChange);
numInputs.keyup(handlePrefChange);
updateTotal();


function handlePrefChange() {
    if (this.value != this.value.replace(/[^0-9\.]/g, ''))
       this.value = this.value.replace(/[^0-9\.]/g, '');
    if (this.value == "")
        this.value = 0;

    updateTotal();
}



function updateTotal() {
    var total = 0;
    that.find("tr").each(function() {
        var fields = $(this).find("td");
        if (fields.length != 0 && ~$(fields[2]).html().indexOf('$')) {
            var cost = parseFloat($(fields[2]).html().substr(1));
            var quantity = parseInt($($(fields[3]).children()[0]).val());
            total += cost * quantity;
        }
    });

    $("#total").html(total.toFixed(2));
}



var pickup = $("#pickupPoint");
pickup.find(".option").click(function() {
    var radioB = $(this).find("input");
    radioB.prop('checked', true);
});
