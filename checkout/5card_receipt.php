<?php
    require_once('../scripts/email_functions.php');
    session_start();

    $_REL_ = "../";
    $_TITLE_ = "Card Receipt - StevesBees.com";
    $_STYLESHEETS_ = array("../stylesheets/cartTable.css", "../stylesheets/card_receipt.css");
    require_once('../common/header.php'); //opening HTML


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

            sendCardCustomerEmail2($_SESSION['paymentInfo'],
                'Alaska Wildflower Honey <victors@mtaonline.net>',
                "Transaction Complete",
                $firstName,
                $_SESSION['supplies']);

            sendCardDadEmail2($_SESSION['paymentInfo'],
                'AlaskaWildflowerHoney.com <DoNotReply@stevesbees.com>',
                "Online Order Complete for ".$firstName.' '.$lastName,
                $firstName, $lastName,
                $_SESSION['supplies']);

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
        echo "Oops! Something went wrong during the transaction. Authorize.net was unable to fully process your card for the following reason: ".htmlentities($_GET['resp']);
    }


    $_JS_ = array();
    require_once('../common/footer.php'); //closing HTML
    $db->close();
?>
