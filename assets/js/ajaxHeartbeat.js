
setInterval(heatbeat, 5 * 60 * 1000); //heartbeat every 5 minutes

function heatbeat(button) {
    $.ajax({
        url: "/assets/php/ajax/cartManager.php",
        data: {}
    })
    .done(function(retVal) {
        console.log("AJAX heartbeat complete.");
    });
}

