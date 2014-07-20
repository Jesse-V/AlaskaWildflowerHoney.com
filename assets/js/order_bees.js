// JavaScript Document

var pickup = $(".pickup");
pickup.find("table td").click(function() {
    var radioB = $(this).find("input");
    radioB.prop('checked', true);

    update(radioB);
});

//update($('input[name=pickupLoc]:checked', '.pickup table'));
//$(pickup.find("table td")[0]).find("input")

function update(radioB) {
    var tChargeEl = pickup.find("#transCharge");

    tChargeEl.hide();
    var transRate = updateTransCharge(radioB);
    tChargeEl.fadeIn("slow");

    updateTotals(transRate);
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
    summary.find("#subtotal").html(subtotal.toFixed(2));
    summary.find("#transTotal").html(transTotal.toFixed(2));
    summary.find("#total").html(total.toFixed(2));
}



function updateTransCharge(radioB) {
    var tCharge = pickup.find("#transCharge");

    switch (radioB.val()) {
        case 'Anchorage':
            tCharge.html("<p>No transportation charge for Anchorage.</p>");
            return 0;

        case 'Wasilla':
            tCharge.html("<p>No transportation charge for Wasilla.</p>");
            return 0;

        case 'Palmer':
            tCharge.html("<p>No transportation charge for Palmer.</p>");
            return 0;

        case 'Soldotna':
            tCharge.html("<p>There is a $5/package transporation charge for Soldotna.</p>");
            return 5;

        case 'Homer':
            tCharge.html("<p>There is a $10/package transportation charge for Homer. Our farthest drop point is Soldotna, so half of this charge goes to compensate the beekeeper that drives up from Homer to collect the bees.</p>");
            return 10;

        case 'Eagle River':
            tCharge.html("<p>No transportation charge for Eagle River.</p>");
            return 0;

        case 'Big Lake':
            tCharge.html("<p>No transportation charge for Big Lake.</p>");
            return 0;

        case 'Healy':
            tCharge.html("<p>There is a $10/package transporation charge for Healy.</p>");
            return 10;

        case 'Nenana':
            tCharge.html("<p>There is a $10/package transporation charge for Nenana.</p>");
            return 10;

        case 'Fairbanks':
            tCharge.html("<p>There is a $10/package transporation charge for Fairbanks.</p>");
            return 10;

        case 'Other':
            tCharge.html("<p>If your order requires special handling and needs to be sent to a different location, choose this category. Charges vary depending on the drop-off point. For flights to the Bush or outside Anchorage, there is a $5/package ($10 minimum) drop-off fee.<br>Please provide the destination: <input type=\"text\" name=\"customDest\" id=\"customDest\" style=\"padding: 2px 5px; 5px; margin-top: 4px;\"/> <br> You can also provide additional instructions in the box below.</p>");
            setTimeout(function() { tCharge.find("#customDest").focus(); }, 250);
            return 0;

        default:
            return 0;
    }
}



var numInputs = $(".mid_col input[type=number]");
numInputs.change(handlePrefChange);
numInputs.keyup(handlePrefChange);

function handlePrefChange() {
    if (this.value != this.value.replace(/[^0-9\.]/g, ''))
       this.value = this.value.replace(/[^0-9\.]/g, '');
    if (this.value == "")
        this.value = 0;

    updateTotals(updateTransCharge($(".pickup table td input:checked")));
}


$("#breedWriteup").hide();
$("#breedFAQ, #breedWriteup").click(function() {
    $("#breedWriteup").toggle("blind", 900);
});