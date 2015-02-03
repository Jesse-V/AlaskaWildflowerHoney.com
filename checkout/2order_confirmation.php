<?php
    //receive all the POSTed data, let the customer confirm accuracy before processing


    $_TITLE_ = "Order Confirmation - StevesBees.com";
    $_STYLESHEETS_ = array("/assets/css/fancyHRandButtons.css",
        "/assets/css/checkout_form.css",
        "/assets/css/cartTable.css",
        "/assets/css/order_confirmation.css");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/header.php'); //opening HTML
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/checkout/cartReceiptView.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/inputSanitize.php');


    $input = sanitizeArray($_POST);


    //if the page was reached directly or otherwise with no input
    if (empty($_SESSION) || empty($input))
    {
        echo '<p>Oops! You seemed to have reached this page in error, as your cart is currently empty or not enough information was sent to this page. Please visit the
                <a href="/order_supplies.php">Supplies store</a> or the
                <a href="/order_bees.php">Bees store</a>. Thanks!
            </p>';
    }
    else if (!validInput($input)) //check for missing/invalid required information
    {
        echo '<p>Something went wrong. It looks like you forgot to fill out some necessary information in the checkout page, or your information was otherwise invalid. Please go back and fill out the payment and contact information.
                <br><br>
                <form>
                    <input type="submit" value="Back to Checkout"
                        onClick="history.go(-1); return true;" class="fancy">
                </form>
            </p>';
    }
    else //everything is in order, show receipt and confirmation button
    {
        echo '
        <h1>Order Confirmation</h1>
        <form method="post" action="'.$input['nextDestination'].'">';

        echo "
            <p>
                This is a confirmation of your shopping cart and order information. Please take a moment to review everything before the order goes through. If it all looks good, please hit the confirmation button below. If something needs adjustment, please click your browser's back button or use the Edit Cart button to the right. Thanks again for shopping with us!
            </p>";

        echoCart(); //print cartReceiptView

        //format the phone numbers into very readable formats
        $input['primaryPhone'] = formatPhone($input['primaryPhone']);
        $input['backupPhone']  = formatPhone($input['backupPhone']);

        if ($input['paymentMethod'] == "card")
        { //it's a confirmation of a card checkout

            //save credit card information into the session
            $_SESSION['paymentInfo'] = $input;

            //print billing and shipping information
            echo '
            <table class="paymentContact">
                <tr>
                    <th>Billing</th>
                    <th>Shipping and Contact</th>
                </tr>
                <tr>
                    <td>
                        '.$input['x_card_num'].' ('.$input['x_card_code'].') Exp: '.$input['x_exp_date'].'
                        <br>
                        '.$input['x_first_name'].' '.$input['x_last_name'].'
                        <br>
                        '.$input['x_address'].'
                        <br>
                        '.$input['x_city'].',
                        '.$input['x_state'].'
                        '.$input['x_zip'].'
                    </td>
                    <td>
                        '.getShippingContact($input).'
                    </td>
                </tr>
            </table>';
        }
        else
        { //it's a confirmation of a check

            //save contact information into the session
            $_SESSION['contactInfo'] = $input;

            echo '
                <h3>Shipping and Contact</h3>
                <p>
                    '.getShippingContact($input).'
                </p>';
        }

        echo '
        <p>
            <button type="submit" id="confirm" class="submit fancy">Confirm, this information is accurate.</button>
        </p>
        </form>';
    }



    //closing HTML
    $_JS_ = array("/assets/js/checkout_form.js");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/footer.php');
    $db->close();



    //returns true if all required fields are present and valid
    function validInput($fields)
    {
        if (!isset($fields['paymentMethod']) ||
            ($fields['paymentMethod'] != "check" &&
                $fields['paymentMethod'] != "card"))
            return false;

        if (!isset($fields['nextDestination']))
            return false;

        if (!isset($fields['x_ship_to_first_name']) ||
            strlen($fields['x_ship_to_first_name']) < 2 ||
            strlen($fields['x_ship_to_first_name']) > 40)
            return false;

        if (!isset($fields['x_ship_to_last_name']) ||
            strlen($fields['x_ship_to_last_name']) < 2 ||
            strlen($fields['x_ship_to_last_name']) > 40)
            return false;

        //resolves ticket #40: if payment by card, the phone number is optional
            //but it still needs to be checked
        if ($fields['paymentMethod'] == "check")
        {
            if (strlen($fields['primaryPhone']) > 25)
                return false;
        }
        else if ($fields['paymentMethod'] == "card")
        {
            if (!isset($fields['primaryPhone']) ||
                strlen($fields['primaryPhone']) < 3 ||
                strlen($fields['primaryPhone']) > 25)
                return false;
        }

        //always optional, but check it for sanity
        if (strlen($fields['backupPhone']) > 25)
            return false;

        if (!isset($fields['x_email']) ||
            strlen($fields['x_email']) < 5 ||
            strlen($fields['x_email']) > 50)
            return false;

        return true;
    }



    function formatPhone($number)
    { //https://stackoverflow.com/questions/4708248/formatting-phone-numbers-in-php

        $threeParts = preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '($1) $2-$3', $number);
        $twoParts = preg_replace('~.*(\d{3})[^\d]*(\d{4}).*~', '$1-$2', $number);
        return strlen($number) == 7 ? $twoParts : $threeParts;
    }
?>
