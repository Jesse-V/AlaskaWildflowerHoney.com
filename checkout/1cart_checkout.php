<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/anet_php_sdk/AuthorizeNet.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/databaseConnect.secret');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/checkout/cart_help_functions.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/checkout/authorizeNetVars.secret');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/checkout/checkoutPaymentForms.php');

    $_TITLE_ = "Cart Checkout - StevesBees.com";
    $_STYLESHEETS_ = array("/assets/css/fancyHRandButtons.css",
        "/assets/css/checkout_form.css",
        "/assets/css/cartTable.css");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/header.php'); //opening HTML


    echo "<h1>Checkout</h1>";

    if (empty($_SESSION))
    {
        echo '<p>
                Oops! You seemed to have reached this page in error, as your cart is currently empty.<br><br>Please visit the <a href="/order_supplies.php">Supplies page</a> or the <a href="/order_bees.php">Bees page</a>. Thanks!
            </p>';
    }
    else
    {
        $total = echoCart(); //print cart and total, get total
        echoIntroGreeting(); //print introduction and buttons
        echoDynamicForm($total, "3order_submit.php");
    }


    $_JS_ = array("/assets/js/checkout_form.js");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/footer.php'); //closing HTML
    $db->close();


    function echoIntroGreeting()
    {
        echo '
            <p>
                Welcome to the checkout. Please complete your order by providing the information below. You can purchase the items in your cart using most major credit/debit cards or by check. For online payment, we use <a href="https://www.authorize.net/">Authorize.net</a>, a popular payment gateway provider. They are compliant with the Payment Card Industry Data Security Standard (PCI DSS) and provide strong SSL certificates to protect your payment information.
            </p>
            <p>
                We offer two methods of payment: electronic via credit/debit cards and checks. Electronic payment is nearly instantaneous and is highly recommended, but if you wish you may mail us a check manually. Please choose your preferred method below, and then fill out any forms that appear. Thank you for shopping with us!
            </p>

            <div class="spacer"></div>

            <div id="paymentChoice">
                <button type="submit" id="payOnline" class="submit fancy">Pay online with a credit or debit card.</button>
                <button type="submit" id="payCheck" class="submit fancy">Pay using check</button>
            </div>';
    }



    function echoDynamicForm($total, $nextDest)
    {
        //"'.AuthorizeNetDPM::LIVE_URL.'"
        echo '
            <div id="dynamic">
                <h1>YOU SHOULD NOT NORMALLY SEE THIS. Please enable Javascript or update your browser.</h1>

                <form id="cardForm" method="post" action="2order_confirmation.php">
                    <input type="hidden" name="paymentMethod" value="card">
                    <input type="hidden" name="nextDestination" value="'.$nextDest.'">

                    <p>
                        Please fill out the form below and submit when finished. We need your contact information for entering your order into our system, and your credit card information is used for expedited payments.
                    </p>
                    <h3 id="billingInfo">Billing Information</h3>
                    ';

                    global $api_login_id, $transaction_key;
                    echo getCardFields($total, 123, $api_login_id, $transaction_key);

        echo '
                </form>
                ';

                //print check HTML (methods in checkoutPaymentForms.php)
                echo getCheckForm("2order_confirmation.php", "5check_receipt.php");
                echo getCommonFields();

        echo '
            </div>';
    }
?>
