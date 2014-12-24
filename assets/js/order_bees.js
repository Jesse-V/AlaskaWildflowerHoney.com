
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
    update(radioB);
});



function update(radioB) {
    var tChargeEl = $("#transCharge");

    updateTotals(updateTransCharge(radioB));
    tChargeEl.hide();
    tChargeEl.fadeIn("slow");
}

function updateTotals(transRate) {
    var midColEl = $(".mid_col");

    var sItCount = parseInt($("#singleItalian").val());
    var dItCount = parseInt($("#doubleItalian").val());
    var sCCount = parseInt($("#singleCarni").val());
    var dCCount = parseInt($("#doubleCarni").val());

    var nItQ    = parseInt(midColEl.find("input[name=ItalianQueens]").val());
    var nCarniQ = parseInt(midColEl.find("input[name=CarniQueens]").val());

    var subtotal = sItCount * singlePrice + dItCount * doublePrice;
    subtotal += sCCount * singlePrice + dCCount * doublePrice;
    subtotal += nItQ * queenPrice + nCarniQ * queenPrice;

    var transTotal = (sItCount + dItCount + sCCount + dCCount) * transRate;
    var total = subtotal + transTotal;

    var summary = midColEl.find(".summary");
    summary.find("#beeSubtotal").html(subtotal.toFixed(2));
    summary.find("#transTotal").html(transTotal.toFixed(2));
    summary.find("#beeTotal").html(total.toFixed(2));
}



function updateTransCharge(radioB) {
    var tCharge = $("#transCharge");
    tCharge.css("padding-bottom", "0px");

    switch (radioB.val()) {
        case 'Anchorage':
            tCharge.html('<p>No transportation charge for Anchorage.</p>');
            return 0;

        case 'Wasilla':
            tCharge.html('<p>No transportation charge for Wasilla.</p>');
            return 0;

        case 'Palmer':
            tCharge.html('<p>No transportation charge for Palmer.</p>');
            return 0;

        case 'Soldotna':
            tCharge.html('<p>There is a $5/package transporation charge for Soldotna.</p>');
            return 5;

        case 'Homer':
            tCharge.html('<p class="mediumHeight">There is a $10/package transportation charge for Homer. Our farthest drop point is Soldotna, so half of this charge goes to compensate the beekeeper that drives up from Homer to collect the bees.</p>');
            tCharge.css("padding-bottom", "25px");
            return 10;

        case 'Eagle River':
            tCharge.html('<p>No transportation charge for Eagle River.</p>');
            return 0;

        case 'Big Lake':
            tCharge.html('<p>No transportation charge for Big Lake.</p>');
            return 0;

        case 'Healy':
            tCharge.html('<p>There is a $10/package transporation charge for Healy.</p>');
            return 10;

        case 'Nenana':
            tCharge.html('<p>There is a $10/package transporation charge for Nenana.</p>');
            return 10;

        case 'Fairbanks':
            tCharge.html('<p>There is a $10/package transporation charge for Fairbanks.</p>');
            return 10;

        case 'Other':
            tCharge.html('<p class="tallHeight">If your order requires special handling and needs to be sent to a different location, choose this category. Charges vary depending on the drop-off point. For flights to the Bush or outside Anchorage, there is a $5/package ($10 minimum) drop-off fee.<br>Please provide the destination: <input type=\"text\" name=\"customDest\" id=\"customDest\" style=\"padding: 2px 5px; 5px; margin-top: 4px;\"/> <br> You can also provide additional instructions in the box below.</p>');
            setTimeout(function() { tCharge.find("#customDest").focus(); }, 250);
            tCharge.css("padding-bottom", "75px");
            return 0;

        default:
            return 0;
    }
}



//update client-side total based on quantity update
var numInputs = $(".mid_col input[type=number]");
numInputs.change(handleQuantityUpdate);
numInputs.keyup(handleQuantityUpdate);
updateTotals(updateTransCharge($("table.pickup input:checked")));

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

    updateTotals(updateTransCharge($("table.pickup input:checked")));
}
