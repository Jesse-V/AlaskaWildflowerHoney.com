
//toggle visibility of breed outline writeup
$("#breedWriteup").hide();
$("#breedFAQ, #breedWriteup").click(function() {
    $("#breedWriteup").toggle("blind", 900);
});


//allow clicking on text next to the radio buttons to make the selection
//update pickup box and total based on pickup preference
$("table.pickup").find(".point").click(function() {
    var radioB = $(this).find("input");
    radioB.prop('checked', true);
    changePickupLocation(radioB);
});



function changePickupLocation(radioB) {
    var tChargeEl = $("#transCharge");

    updateSessionOrder();
    updateTransportationMessage(radioB);
    tChargeEl.hide();
    tChargeEl.fadeIn("slow");
}



function updateTransportationMessage(radioB) {
    var tCharge = $("#transCharge");
    tCharge.css("padding-bottom", "0px");

    switch (radioB.val()) {
        case 'Anchorage':
            tCharge.html('<p>No transportation charge for Anchorage.</p>');
            return;

        case 'Wasilla':
            tCharge.html('<p>No transportation charge for Wasilla.</p>');
            return;

        case 'Palmer':
            tCharge.html('<p>No transportation charge for Palmer.</p>');
            return;

        case 'Soldotna':
            tCharge.html('<p>There is a $5/package transporation charge for Soldotna.</p>');
            return;

        case 'Homer':
            tCharge.html('<p class="mediumHeight">There is a $10/package transportation charge for Homer. Our farthest drop point is Soldotna, so half of this charge goes to compensate the beekeeper that drives up from Homer to collect the bees.</p>');
            tCharge.css("padding-bottom", "25px");
            return;

        case 'Eagle River':
            tCharge.html('<p>No transportation charge for Eagle River.</p>');
            return;

        case 'Big Lake':
            tCharge.html('<p>No transportation charge for Big Lake.</p>');
            return;

        case 'Healy':
            tCharge.html('<p>There is a $10/package transporation charge for Healy.</p>');
            return;

        case 'Nenana':
            tCharge.html('<p>There is a $10/package transporation charge for Nenana.</p>');
            return;

        case 'Fairbanks':
            tCharge.html('<p>There is a $10/package transporation charge for Fairbanks.</p>');
            return;

        case 'Other':
            tCharge.html('<p class="tallHeight">If your order requires special handling and needs to be sent to a different location, choose this category. Charges vary depending on the drop-off point. For flights to the Bush or outside Anchorage, there is a $5/package ($10 minimum) drop-off fee.<br>Please provide the destination: <input type="text" name="customDest" style="padding: 2px 5px; 5px; margin-top: 4px;"/> <br> You can also provide additional instructions in the box below.</p>');
            setTimeout(function() { tCharge.find("input[name=customDest]").focus(); }, 250);
            tCharge.css("padding-bottom", "75px");
            return;

        default:
            return;
    }
}



//update client-side total based on quantity update
var numInputs = $(".mid_col input[type=number]");
numInputs.change(handleQuantityUpdate);
numInputs.keyup(handleQuantityUpdate);
updateTransportationMessage($("table.pickup input:checked"));

function handleQuantityUpdate() {
    //same filter as order_supplies.js
    if (this.value == "" || this.value.indexOf('.') > -1)
        this.value = ""; //Chrome/FF have "" if the field contains nonnumerics

    var i = 0;
    while (i < this.value.length && this.value[i] == '0') {
        i++;
    }

    //trim leading zeros, except if the whole thing is zeros
    if (i > 0 && i != this.value.length)
        this.value = this.value.substring(i, this.value.length);

    updateSessionOrder();
    updateTransportationMessage($("table.pickup input:checked"));
}



//AJAX call to update session order en-masse
function updateSessionOrder() {

    var selection = {};
    selection['singleItalian'] = $("input[name=singleItalian]").val();
    selection['doubleItalian'] = $("input[name=doubleItalian]").val();
    selection['singleCarni']   = $("input[name=singleCarni]").val();
    selection['doubleCarni']   = $("input[name=doubleCarni]").val();
    selection['ItalianQueens'] = $("input[name=ItalianQueens]").val();
    selection['CarniQueens']   = $("input[name=CarniQueens]").val();

    var pickup = {};
    pickup['pickupLoc'] = $('input[name=pickupLoc]:checked', 'table.pickup').val();
    pickup['notes'] = $(".notes textarea").val();
    pickup['customLoc'] = $("input[name=customDest]").length == 0 ? "" : $("input[name=customDest]").val();

    //AJAX to update supply order
    $.ajax({
        url: "/assets/php/ajax/cartManager.php",
        data: {
            action: "updateOrder",
            page: "bees",
            selection: selection,
            pickup: pickup
        }
    })
    .done(function(retVal) {

        if (retVal.status == "Success") {
            updateSidebarCartPreview(); //method in cartPreviewUpdater.js

            var summary = $(".summary");
            var total = retVal.subtotal + retVal.transCharge;
            summary.find("#beeSubtotal").html(retVal.subtotal.toFixed(2));
            summary.find("#transTotal").html(retVal.transCharge.toFixed(2));
            summary.find("#beeTotal").html(total.toFixed(2));

            console.log("Bee order successfully updated.");
        }
        else if (retVal.status == "Failure") {
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
