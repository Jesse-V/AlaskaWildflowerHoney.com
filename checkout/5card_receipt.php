<?php
    require_once(__DIR__.'/../assets/php/checkout/email_functions.php');
    require_once(__DIR__.'/../assets/php/checkout/authorizeNetVars.secret');
    session_start();

    $_REL_ = "../";
    $_TITLE_ = "Card Receipt - StevesBees.com";
    $_STYLESHEETS_ = array("../assets/css/fancyHRandButtons.css",
        "../assets/css/cartTable.css", "../assets/css/card_receipt.css");
    require_once(__DIR__.'/../assets/common/header.php'); //opening HTML


    echo '<h1>Your receipt</h1>';

    if (empty($_GET) || !isset($_GET['rc']) || !isset($_GET['hash']) || empty($_SESSION))
    {
        echo "
            <p>
                Oops! We cannot show you a receipt because not enough information was sent to this page. One possible explanation is that you have already completed the transaction. If you feel that you have reached this message in error, please notify us by sending an email to <a href=\"mailto:jvictors@jessevictors.com?Subject=Receipt%20error\" target=\"_top\">jvictors@jessevictors.com</a> and we will try to address this issue promptly. We apologize for the inconvenience.
            </p>";
    }
    else if ($_GET['rc'] == 1 || strpos($_GET['resp'], "TESTMODE") !== false)
    {
        if ($_GET['hash'] == hash("sha256", $md5_setting.$_GET['rc'].$_GET['id'].$md5_setting))
        {
            $firstName = $_SESSION['paymentInfo']['x_first_name'];
            $lastName  = $_SESSION['paymentInfo']['x_last_name'];

            echo "
                <p>
                    Thank you ".$firstName."! Your payment (transaction ".$_GET['id'].") was successful. Your order has been sent to us and we will process it shortly. Please check your email for receipts.
                </p>
                <p>
                   Thanks for ordering online!
                </p>";

            //Authorize.net tells the customer that the card went through, we don't need to do that ourselves

            sendCardDadEmail2($_SESSION['paymentInfo'],
                'AlaskaWildflowerHoney.com <DoNotReply@stevesbees.com>',
                "Online Order Complete for ".$firstName.' '.$lastName,
                $firstName, $lastName, $_GET['id']);

            unset($_SESSION);
            session_destroy();
        }
        else
        {
            echo "<p>
                Oops! Some information sent to this page didn't make sense. This should not happen normally. However, if you feel that you have reached this page in error, (such as during a transaction) please notify us by sending an email to <a href=\"mailto:jvictors@jessevictors.com?Subject=Receipt%20error\" target=\"_top\">jvictors@jessevictors.com</a> and we will try to address this issue promptly. We apologize for the inconvenience.
                </p>";
        }
    }
    else
    {
        echo "
            <p>
                Oops! Something went wrong during the transaction. Authorize.net was unable to fully process your card for the following reason: \"".htmlentities($_GET['resp'])."\" The card's number may have been mistyped or has expired, the address or ZIP code may not match, or something else is wrong. Please try again or use a different card. You have also received an email about this.
            </p>";

        $firstName = $_SESSION['paymentInfo']['x_first_name'];
        $lastName  = $_SESSION['paymentInfo']['x_last_name'];

        sendFailedCustomerEmail($_SESSION['paymentInfo'],
            'Alaska Wildflower Honey <victors@mtaonline.net>',
            "The transaction has failed",
            $firstName, htmlentities($_GET['resp']));

        sendFailedDadEmail($_SESSION['paymentInfo'],
            'AlaskaWildflowerHoney.com <DoNotReply@stevesbees.com>',
            $firstName.' '.$lastName."'s card transaction failed",
            $firstName, $lastName, htmlentities($_GET['resp']));

        echo '
            <form method="get" action="1cart_checkout.php">
                <button type="submit">Click here to try again.</button>
            </form>';
    }

    echo '
        <form method="get" action="../stevesbees_home.php">
            <button type="submit">Click here to return to the StevesBees.com homepage</button>
        </form>';

    $_JS_ = array();
    require_once(__DIR__.'/../assets/common/footer.php'); //closing HTML
    $db->close();
?>
