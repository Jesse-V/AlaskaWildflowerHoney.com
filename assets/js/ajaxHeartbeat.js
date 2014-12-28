
setInterval(heatbeat, 5 * 60 * 1000); //heartbeat every 5 minutes

//performs a single AJAX pulse to the cartManager, reviving the session
function heatbeat(button) {
    $.ajax({
        url: "/assets/php/ajax/cartManager.php",
        type: "post",
        data: {}
    })
    .done(function(retVal) {
        console.log("AJAX heartbeat complete.");
    });
}

