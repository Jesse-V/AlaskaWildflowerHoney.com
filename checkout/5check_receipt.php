<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/anet_php_sdk/AuthorizeNet.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/databaseConnect.secret');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/checkout/cartReceiptView.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/checkout/emailReceipts.php');

    $_TITLE_ = "Check Receipt - StevesBees.com";
    $_STYLESHEETS_ = array("/assets/css/fancyHRandButtons.css",
        "/assets/css/cartTable.css", "/assets/css/check_receipt.css");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/header.php'); //opening HTML


    echo '<h1>Your receipt</h1>';

    if (empty($_SESSION))
    {
        echo "<p>
                Sorry, something went wrong and we are unable to show you a receipt.<br>This most likely explanation is that you have already completed the transaction.
            </p><br>";
    }
    else
    {
        $total = getCart()['total'];

        $firstName = $_SESSION['contactInfo']['x_ship_to_first_name'];
        $lastName  = $_SESSION['contactInfo']['x_ship_to_last_name'];

        echo '
            <p>
                Thank you '.$firstName.'! Your order has been sent to us. Please send your check for $'.$total.' to<br>
                Alaska Wildflower Honey<br>
                7449 S. Babcock Blvd.<br>
                Wasilla, AK 99623<br>
            </p>
            <p>
                We will hold your order for two weeks, awaiting the arrival of your check.
            </p>
            <p>
                You will shortly receive an email receipt of your order. Thank you for ordering online!
            </p><br>';

        sendCheckCustomerEmail($_SESSION['contactInfo'],
            'Alaska Wildflower Honey <victors@mtaonline.net>',
            "Your online order is complete",
            $_SESSION['contactInfo']['x_ship_to_first_name']);

        sendCheckDadEmail($_SESSION['contactInfo'],
            'AlaskaWildflowerHoney.com <DoNotReply@stevesbees.com>',
            "Online Order Submission, Check - ".$firstName.' '.$lastName,
            $firstName, $lastName);

        unset($_SESSION);
        session_destroy();
    }

    echo '
        <form method="get" action="/stevesbees_home.php">
            <button type="submit" class="fancy">Click here to return to the StevesBees.com homepage</button>
        </form>';


    $_JS_ = array();
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/footer.php'); //closing HTML
    $db->close();
?>
