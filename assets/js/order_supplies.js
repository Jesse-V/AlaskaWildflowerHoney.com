
var that = $(".mid_col");

//hide all items that aren't in the desired group or are non-zero
function hideAllGroupItems(duration, desiredGroupID) {
    var subItems = that.find("table .subItem");
    for (var i = 0; i < subItems.length; i++) {
        var inputVal = $($(subItems[i]).find("input")[0]).val();
        if (!$(subItems[i]).hasClass("subItem" + desiredGroupID)
            && (inputVal == '0' || inputVal == undefined))
            $(subItems[i]).hide(duration);
    }
}


//hide all other item groups, then expose the one the customer wants
that.find("table tr").click(function() {
    if (this.className.lastIndexOf("group", 0) === 0) //startsWith
    {
        var groupNum = this.className.substring(5);
        hideAllGroupItems(750, groupNum);

        //expose the items in the group the customer wants
        var subitems = that.find("table .subItem" + groupNum);
        subitems.show(750);
    }
});


//take action whenever a quantity input changes
var numInputs = $(".mid_col input[type=number]");
numInputs.change(handleQuantityUpdate);
numInputs.keyup(handleQuantityUpdate);
updateTotal();


//filter input, update the total based on updated selection
function handleQuantityUpdate() {
    if (this.value == "" || this.value.indexOf('.') > -1)
        this.value = ""; //Chrome/FF have "" if the field contains nonnumerics

    var i = 0;
    while (i < this.value.length && this.value[i] == '0') {
        i++;
    }

    //trim leading zeros, except if the whole thing is zeros
    if (i > 0 && i != this.value.length)
        this.value = this.value.substring(i, this.value.length);

    queueOrderUpdate();
    updateTotal(); //TODO: AJAX-powered subtotal calculation?
}


//triggers an order update after a timeout, regulating the refresh rate (#44)
var updateCountdownID = 0;
function queueOrderUpdate() {
    clearTimeout(updateCountdownID);
    updateCountdownID = setTimeout(updateSessionOrder, 250);
}


//AJAX call to update session order en-masse
function updateSessionOrder() {
    var inputs = $(".mid_col input[type=number]");

    //identify itemIDs and quantities of selected items
    var selection = {};
    for (i = 0; i < inputs.length; i++)
        if (inputs[i].value != 0)
            selection[$(inputs[i]).prop('name')] = $(inputs[i]).val();

    if (Object.keys(selection).length > 0) //https://stackoverflow.com/questions/5223/length-of-a-javascript-object-that-is-associative-array
    {
        //AJAX to update supply order
        $.ajax({
            url: "/assets/php/ajax/cartManager.php",
            data: {
                action: "updateOrder",
                page: "supplies",
                selection: selection,
                pickupLoc: $('input[name=pickupLoc]:checked', '#pickupPoint').val()
            }
        })
        .done(function(retVal) {
            if (retVal == "Success") {
                updateSidebarCartPreview(); //method in cartPreviewUpdater.js
                console.log("Supplies order successfully updated.");
            }
            else if (retVal == "Failure") {
                console.log(retVal); //TODO
            }
            else {
                console.log(retVal); //TODO
            }
        })
        .fail(function(info, status) {
            alert("Sorry, an issue was encountered, specifically, " + info.statusText);
        });
    }
}


//recalculate the total
function updateTotal() {
    var total = 0;
    that.find("#supplyOrder tr").each(function() {
        var fields = $(this).find("td");
        if (fields.length != 0 && ~$(fields[2]).html().indexOf('$'))
        {
            var cost = parseFloat($(fields[2]).html().substr(1));
            var children = $(fields[3]).children();

            if (children.length > 0) //if it has a checkbox
            {
                var quantity = parseInt($(children[0]).val());
                total += cost * quantity;
            }
        }
    });

    $("#suppliesTotal").html(total.toFixed(2));
}


//allow clicking on text next to the radio buttons to make the selection
var pickup = $("#pickupPoint");
pickup.find(".option").click(function() {
    var radioB = $(this).find("input");
    radioB.prop('checked', true);
});
