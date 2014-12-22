
//setInterval(heatbeat, 30000); //30 seconds

function heatbeat(button) {
    $.ajax({
        url: "/assets/php/ajax/cartManager.php",
        data: {}
    })
    .done(function(retVal) {
        console.log("AJAX heartbeat complete.");
    });
}

