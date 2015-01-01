<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/checkout/emailReceipts.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/checkout/authorizeNetVars.secret');

    $_TITLE_ = "Card Receipt - StevesBees.com";
    $_STYLESHEETS_ = array("/assets/css/fancyHRandButtons.css",
        "/assets/css/cartTable.css");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/header.php'); //opening HTML


    //XSS sanitize, https://stackoverflow.com/questions/1996122/how-to-prevent-xss-with-html-php
    $input = array();
    foreach ($_GET as $key => $value) {
        $input[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }


    echo '<h1>Your receipt</h1>';

    if (empty($input) || !isset($input['rc']) || !isset($input['hash']) || empty($_SESSION))
    { //check to see if we received the expected information
        echo "
            <p>
                Oops! We cannot show you a receipt because not enough information was sent to this page. One possible explanation is that you have already completed the transaction. If you feel that you have reached this message in error, please notify us by sending an email to <a href=\"mailto:jvictors@jessevictors.com?Subject=Receipt%20error\" target=\"_top\">jvictors@jessevictors.com</a> and we will try to address this issue promptly. We apologize for the inconvenience.
            </p>";
    }
    else if ($input['rc'] == 1 || strpos($input['resp'], "TESTMODE") !== false)
    {
        if ($input['hash'] == hash("sha256", $md5_setting.$input['rc'].$input['id'].$md5_setting))
        { //check signature from Authorize.net and from us

            $firstName = $_SESSION['paymentInfo']['x_first_name'];
            $lastName  = $_SESSION['paymentInfo']['x_last_name'];

            echo "
                <p>
                    Thank you ".$firstName."! Your payment (transaction ".$input['id'].") was successful. Your order has been sent to us and we will process it shortly. Please check your email for receipts.
                </p>
                <p>
                   Thanks for ordering online!
                </p>";

            //Authorize.net tells the customer that the card went through, we don't need to do that ourselves

            sendCardDadEmail2($_SESSION['paymentInfo'],
                'AlaskaWildflowerHoney.com <DoNotReply@stevesbees.com>',
                "Online Order Complete for ".$firstName.' '.$lastName,
                $firstName, $lastName, $input['id']);

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
                Oops! Something went wrong during the transaction. Authorize.net was unable to fully process your card for the following reason: \"".htmlentities($input['resp'])."\" The card's number may have been mistyped or has expired, the address or ZIP code may not match, or something else is wrong. Please try again or use a different card. You have also received an email about this.
            </p>";

        $firstName = $_SESSION['paymentInfo']['x_first_name'];
        $lastName  = $_SESSION['paymentInfo']['x_last_name'];

        sendFailedCustomerEmail($_SESSION['paymentInfo'],
            'Alaska Wildflower Honey <victors@mtaonline.net>',
            "The transaction has failed",
            $firstName, htmlentities($input['resp']));

        sendFailedDadEmail($_SESSION['paymentInfo'],
            'AlaskaWildflowerHoney.com <DoNotReply@stevesbees.com>',
            $firstName.' '.$lastName."'s card transaction failed",
            $firstName, $lastName, htmlentities($input['resp']));

        echo '
            <form method="get" action="1cart_checkout.php">
                <button type="submit">Click here to try again.</button>
            </form>';
    }

    echo '
        <form method="get" action="/stevesbees_home.php">
            <button type="submit" class="fancy">Click here to return to the StevesBees.com homepage</button>
        </form>';

    $_JS_ = array();
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/footer.php'); //closing HTML
    $db->close();
?>
