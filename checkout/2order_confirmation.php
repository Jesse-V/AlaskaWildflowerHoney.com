<?php
    $_REL_ = "../";
    $_TITLE_ = "Order Confirmation - StevesBees.com";
    $_STYLESHEETS_ = array("../assets/css/fancyHRandButtons.css",
        "../assets/css/checkout_form.css",
        "../assets/css/cartTable.css",
        "../assets/css/order_confirmation.css");
    require_once(__DIR__.'/../assets/common/header.php'); //opening HTML


    require_once(__DIR__.'/../assets/php/checkout/OrderConfirmation.php');


    $_JS_ = array("../assets/js/jquery-1.11.1.min.js", "../assets/js/checkout_form.js");
    require_once(__DIR__.'/../assets/common/footer.php'); //closing HTML
    $db->close();
?>
