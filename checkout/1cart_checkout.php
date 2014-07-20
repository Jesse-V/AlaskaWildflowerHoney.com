<?php
    require_once(__DIR__.'/../assets/anet_php_sdk/AuthorizeNet.php');
    require_once(__DIR__.'/../assets/php/databaseConnect.secret');
    require_once(__DIR__.'/../assets/php/checkout/cart_help_functions.php');
    require_once(__DIR__.'/../assets/php/checkout/authorizeNetVars.secret');
    require_once(__DIR__.'/../assets/php/checkout/checkoutCartFields.php');
    session_start();

    $_REL_ = "../";
    $_TITLE_ = "Cart Checkout - StevesBees.com";
    $_STYLESHEETS_ = array("../assets/css/fancyHRandButtons.css",
        "../assets/css/checkout_form.css",
        "../assets/css/cartTable.css");
    require_once(__DIR__.'/../assets/common/header.php'); //opening HTML


    echo "<h1>Checkout</h1>";

    if (empty($_SESSION))
    {
        echo '<p>
                Oops! You seemed to have reached this page in error, as your cart is currently empty.<br><br>Please visit the <a href="'.__DIR__.'/../order_supplies.php">Supplies page</a> or the <a href="'.__DIR__.'/../order_bees.php">Bees page</a>. Thanks!
            </p>';
    }
    else
    {
        /*
         echo '
            <h2><b> Alert: we are currently doing some maintenance on the payment system. Please wait on proceeding through checkout until we have finished, so check back later. Thank you.
            </b></h2>
            ';
        */

        $total = echoCart($_SESSION['supplies']);
        echoIntroGreeting();
        echoDynamicForm($total, "3order_submit.php");
    }


    $_JS_ = array("../assets/js/jquery-1.11.1.min.js", "../assets/js/checkout_form.js");
    require_once(__DIR__.'/../assets/common/footer.php'); //closing HTML
    $db->close();


    function echoIntroGreeting()
    {
        echo '
            <p>
                Welcome to the checkout. Please complete your order by providing the information below. You can purchase the items in your cart using most major credit/debit cards or by check. For online payment, we use Authorize.net, a popular payment gateway provider. They are compliant with the Payment Card Industry Data Security Standard (PCI DSS) and provide strong SSL certificates to protect your payment information.
            </p>
            <p>
                We offer two methods of payment: electronic via credit/debit cards and checks. Electronic payment is nearly instantaneous and is highly recommended, but if you wish you may mail us a check manually. Please choose your preferred method below, and then fill out any forms that appear. Thank you for shopping with us!
            </p>

            <div class="spacer"></div>

            <div id="paymentChoice">
                <button type="submit" id="payOnline" class="submit">Pay online with a credit or debit card.</button>
                <button type="submit" id="payCheck" class="submit">Pay using check</button>
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

                echo getCheckForm("2order_confirmation.php", "5check_receipt.php");
                echo getCommonFields();

        echo '
            </div>';
    }



    function getCardFields($amount, $fp_sequence, $api_login_id, $transaction_key, $prefill = false)
    {
        $time = time();
        $fp = AuthorizeNetDPM::getFingerprint($api_login_id, $transaction_key, $amount, $fp_sequence, $time);
        $sim = new AuthorizeNetSIM_Form(
            array(
            'x_amount'        => $amount,
            'x_fp_sequence'   => $fp_sequence,
            'x_fp_hash'       => $fp,
            'x_fp_timestamp'  => $time,
            'x_relay_response'=> "TRUE",
            'x_login'         => $api_login_id,
            )
        );

        //$prefill = true; //TEMPORARY!

        return '
            '.$sim->getHiddenFieldString().'
            <fieldset>
                <div>
                    <label>Credit Card Number</label>
                    <input required type="text" class="text" size="16" name="x_card_num" value="'.($prefill ? '6011000000000012' : '').'"></input>
                </div>
                <div>
                    <label>Expiration Date</label>
                    <input required type="text" class="text" size="4" name="x_exp_date" value="'.($prefill ? '04/17' : '').'"></input>
                </div>
                <div id="CCV">
                    <label>CCV <span class="explanation">(three-letter security code on back)</span></label>
                    <input required type="text" class="text" size="4" name="x_card_code" value="'.($prefill ? '782' : '').'"></input>
                </div>
            </fieldset>
            <fieldset>
                <div>
                    <label>First Name on card</label>
                    <input required type="text" class="text" size="15" name="x_first_name" value="'.($prefill ? 'John' : '').'"></input>
                </div>
                <div>
                    <label>Last Name on card</label>
                    <input required type="text" class="text" size="14" name="x_last_name" value="'.($prefill ? 'Doe' : '').'"></input>
                </div>
            </fieldset>
            <fieldset>
                <div>
                    <label>Billing Address</label>
                    <input required type="text" class="text" size="30" name="x_address" value="'.($prefill ? '123 Main Street' : '').'"></input>
                </div>
                <div>
                    <label>City</label>
                    <input required type="text" class="text" size="15" name="x_city" value="'.($prefill ? 'Boston' : '').'"></input>
                </div>
            </fieldset>
            <fieldset>
                <div>
                    <label>State</label>
                    <input required type="text" class="text" size="4" name="x_state" value="AK"></input>
                </div>
                <div>
                    <label>Zip Code</label>
                    <input required type="text" class="text" size="9" name="x_zip" value="'.($prefill ? '02142' : '').'"></input>
                </div>
                <div>
                    <label>Country</label>
                    <input required type="text" class="text" size="5" name="x_country" value="US"></input>
                </div>
            </fieldset>
        ';
    }



    function getCheckForm($confirmDest, $nextDest)
    {
        return '
            <form id="checkForm" method="post" action="'.$confirmDest.'">
                <input type="hidden" name="paymentMethod" value="check">
                <input type="hidden" name="nextDestination" value="'.$nextDest.'">

                <p>
                    Please send check to:<br>
                    Alaska Wildflower Honey<br>
                    7449 S. Babcock Blvd<br>
                    Wasilla, AK 99623
                </p>
                <p>
                    Please press the button below to submit your order. You will have a chance to confirm your order before it is sent to us.
                </p>
            </form>';
    }



    function getCommonFields()
    {
        return '
            <div id="commonFormInfo">
                <div id="recipientInfo">
                    <h3 class="title">Recipient Address</h3>
                    <span class="subtitle">Who are you ordering for? We use this information to enter your order into our system, or update your order from years past.</span>
                </div>

                <fieldset>
                    <div>
                        <label>First Name(s)</label>
                        <input required type="text" class="text" size="25" name="x_ship_to_first_name"></input>
                    </div>
                    <div>
                        <label>Last Name</label>
                        <input required type="text" class="text" size="20" name="x_ship_to_last_name"></input>
                    </div>
                </fieldset>

                <fieldset>
                    <div>
                        <label>Phone numbers. If you have both cell and home numbers, please list both and click the "preferred" button accordingly.</label><br>
                        <table id="phoneTable">
                            <tr>
                                <td>Home:</td>
                                <td><input type="text" class="text" name="homePhone"/></td>
                                <td><input type="radio" name="preferredPhone" value="home"/>Preferred</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Cell:</td>
                                <td><input type="text" class="text" name="cellPhone"/></td>
                                <td><input type="radio" name="preferredPhone" value="cell"/>Preferred</td>
                                <td><input type="checkbox" name="textCapable" value="yes"/>Text Capable</td>
                            </tr>
                        </table>
                    </div>
                </fieldset>

                <fieldset>
                    <div>
                        <label>Email address:</label>
                        <input required type="text" class="text" size="30" name="x_email"/>
                    </div>
                </fieldset>

                <input type="submit" value="Checkout and Complete Purchase" class="submit buy"/>
            </div>';
    }
?>
