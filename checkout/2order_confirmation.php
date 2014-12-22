<?php
    $_TITLE_ = "Order Confirmation - StevesBees.com";
    $_STYLESHEETS_ = array("/assets/css/fancyHRandButtons.css",
        "/assets/css/checkout_form.css",
        "/assets/css/cartTable.css",
        "/assets/css/order_confirmation.css");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/header.php'); //opening HTML


    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/checkout/OrderConfirmation.php');


    $_JS_ = array("/assets/js/checkout_form.js");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/footer.php'); //closing HTML
    $db->close();
?>
