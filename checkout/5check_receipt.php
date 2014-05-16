<?php
    require_once('../anet_php_sdk/AuthorizeNet.php');
    require_once('../scripts/databaseConnect.secret');
    require_once('../scripts/helper_functions.php');
    session_start();

    $_REL_ = "../";
    $_TITLE_ = "Check Receipt - StevesBees.com";
    $_STYLESHEETS_ = array("../stylesheets/check_receipt.css", "../stylesheets/cartTable.css");
    require_once('../common/header.php'); //opening HTML


    echo '<h1>Your receipt</h1>';

    if (empty($_SESSION))
    {
        echo "
            <p>
                Oops! We cannot show you a receipt because not enough information was sent to this page. One possible explanation is that you have already completed the transaction. If you feel that you have reached this message in error, please notify us by sending an email to <a href=\"mailto:jvictors@jessevictors.com?Subject=Receipt%20error\" target=\"_top\">jvictors@jessevictors.com</a> and we will try to address this issue promptly. We apologize for the inconvenience.
            </p>";
    }
    else
    {
        $total = echoCart();
        echo "<div class=\"total\">Total: $$total</div>";
        sendOrderEmail($_POST['x_email'], "check", "$$total");

        echo "
            <p>
                Thank you! Your order has been sent to us. Please send check to<br>
                Alaska Wildflower Honey<br>
                7449 S. Babcock Blvd.<br>
                Wasilla, AK 99623<br>
            </p>
            <p>
                If possible, please send a print-out of this page along with your check. We will hold your order for two weeks, awaiting the arrival of your check. This page is your only electronic receipt.<br>Thank you for ordering online!
            </p>";

        unset($_SESSION);
        session_destroy();
    }


    $_JS_ = array();
    require_once('../common/footer.php'); //closing HTML
    $db->close();
?>
