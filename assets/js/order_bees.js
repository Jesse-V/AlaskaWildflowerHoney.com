
//toggle visibility of breed outline writeup
$("#breedWriteup").hide();
$("#breedFAQ, #breedWriteup").click(function() {
    $("#breedWriteup").toggle("blind", 900);
});


//allow clicking on text next to the radio buttons to make the selection
//update pickup box and total based on pickup preference
updateTransportationMessage($("table.pickup input:checked"));
$("table.pickup").find(".point").click(function() {
    var radioB = $(this).find("input");
    radioB.prop('checked', true);
    updateTransportationMessage(radioB);
    changePickupLocation(radioB);
});



function changePickupLocation(radioB) {
    var tChargeEl = $("#transCharge");

    queueOrderUpdate();
    tChargeEl.hide();
    tChargeEl.fadeIn("slow");
}



function updateTransportationMessage(radioB) {
    var tCharge = $("#transCharge");

    //autofill radio buttons from session variable
    var checked11 = sessionPickupDate == "11" ? "checked" : "";
    var checked25 = sessionPickupDate == "25" ? "checked" : "";

    switch (radioB.val()) {
        case 'Anchorage':
        case 'Wasilla':
        case 'Palmer':
        case 'Eagle River':
        case 'Big Lake':
            tCharge.html('<p id="dateChoice">' +
                    'Please choose the day you would like your bees to arrive:' +
                    '<br>' +
                    '<span class="option" onclick="choseDate(this); queueOrderUpdate();">' +
                        '<input type="radio" name="dateChoice" value="11" ' +
                            checked11 + '/>' +
                        '<label>April 11th</label>' +
                    '</span>' +
                    '<span class="option" onclick="choseDate(this); queueOrderUpdate();">' +
                        '<input type="radio" name="dateChoice" value="25" ' +
                            checked25 + '/>' +
                        '<label>April 25th</label>' +
                    '</span>' +
                '</p>');
            return;

        case 'Soldotna':
            tCharge.html('<p>There is a $5/package transportation charge for Soldotna. <br><b>Expected arrival date is Saturday, April 18th.</b></p>');
            return;

        case 'Homer':
            tCharge.html('<p class="mediumHeight">There is a $10/package transportation charge for Homer. Our farthest drop point is Soldotna, so half of this charge goes to compensate the beekeeper that drives up from Homer to collect the bees. <br><b>Expected arrival date is Saturday, April 18th.</b></p>');
            return;

        case 'Healy':
            tCharge.html('<p>There is a $10/package transportation charge for Healy. <br><b>Expected arrival date is Saturday, April 18th.</b></p>');
            return;

        case 'Nenana':
            tCharge.html('<p>There is a $10/package transportation charge for Nenana. <br><b>Expected arrival date is Saturday, April 18th.</b></p>');
            return;

        case 'Fairbanks':
            tCharge.html('<p>There is a $10/package transportation charge for Fairbanks. <br><b>Expected arrival date is Saturday, April 18th.</b></p>');
            return;

        case 'Valdez (Copper River Basin)':
        case 'Palmer (Copper River Basin)':
            tCharge.html('<p>Copper River Basin and Valdez bees are distributed by us as far as Palmer. In years past, we have had a beekeeper travel from the Basin to Palmer and pick up all of the bees going in that direction. This has worked well. It saves everyone from having to make the long journey. You should expect to contribute gas money. If you are uncomfortable having someone else transport your bees for you, please select the Palmer option. <br><b>Expected arrival date is Saturday, April 25th.</b></p>');
            return;

        case 'Fairbanks (Delta Junction)':
            tCharge.html('<p>Delta Junction bees are distributed by us as far as Fairbanks. This year, we have had a beekeeper volunteer to drive up to Fairbanks to collect the bees for the Delta area. It will save everyone from having to make the longer journey. You should expect to contribute gas money. If you are uncomfortable having someone else transport your bees for you, please select the Fairbanks option. <br><b>Expected arrival date is Saturday, April 18th.</b></p>');
            return;

        case 'Other':
            tCharge.html('<p class="tallHeight">Please add your final destination to the notes box. If your order requires special handling and needs to be sent to a different location, choose this category. Charges vary depending on the drop-off point. For flights to the Bush or outside Anchorage, there is a $10/package drop-off fee.<br>Please provide the destination: <input type="text" name="customDest" value="' + sessionCustomDest + '" onkeyup="queueOrderUpdate();" style="padding: 2px 5px; 5px; margin-top: 4px;"/> <br> You can also provide additional instructions in the box below. <b>It will be necesssary for you to make all of the flight arrangements and complete any paperwork required by the air carrier.</b> We are dealing with hundreds of packages on the same day that yours need to be dropped off, so it is necessary that everything is prepared for the arrival of the bees at the drop-off point. </p>');
            setTimeout(function() { tCharge.find("input[name=customDest]").focus(); }, 250);
            return;
    }
}



//update client-side total based on quantity update
var numInputs = $(".mid_col input[type=number]");
numInputs.change(handleQuantityUpdate);
numInputs.keyup(handleQuantityUpdate);
handleQuantityUpdate();

function handleQuantityUpdate() {
    if (this.value != undefined)
    {
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
    }

    queueOrderUpdate();
}



//triggers an order update after a timeout, regulating the refresh rate (#44)
var updateCountdownID = 0;
function queueOrderUpdate() {
    clearTimeout(updateCountdownID);
    updateCountdownID = setTimeout(updateSessionOrder, 500);
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
    pickup['customDest'] = $("input[name=customDest]").val();
    pickup['pickupDate'] = $("input[name=dateChoice]:checked").val();

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


//update session order when typing in the notes area
$(".notes textarea").keyup(function() {
    queueOrderUpdate();
});
