<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/checkout/cartReceiptView.php');

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

        if ($input['paymentMethod'] == "card")
        { //it's a confirmation of a card checkout

            //format the phone numbers into very readable formats
            $_SESSION['paymentInfo']['primaryPhone'] = formatPhone($_SESSION['paymentInfo']['primaryPhone']);
            $_SESSION['paymentInfo']['backupPhone'] = formatPhone($_SESSION['paymentInfo']['backupPhone']);

            //save credit card information into the session
            $_SESSION['paymentInfo'] = $input;

            //print billing and shipping information
            $x = $_SESSION['paymentInfo']; //just a smaller variable name
            echo '
            <table class="paymentContact">
                <tr>
                    <th>Billing</th>
                    <th>Shipping and Contact</th>
                </tr>
                <tr>
                    <td>
                        '.$x['x_card_num'].' ('.$x['x_card_code'].') Exp: '.$x['x_exp_date'].'
                        <br>
                        '.$x['x_first_name'].' '.$x['x_last_name'].'
                        <br>
                        '.$x['x_address'].'
                        <br>
                        '.$x['x_city'].',
                        '.$x['x_state'].'
                        '.$x['x_zip'].'
                    </td>
                    <td>
                        '.getShippingContact($x).'
                    </td>
                </tr>
            </table>';
        }
        else
        { //it's a confirmation of a check

            //format the phone numbers into very readable formats
            $primaryPhone = formatPhone($_SESSION['contactInfo']['primaryPhone']);
            $backupPhone = formatPhone($_SESSION['contactInfo']['backupPhone']);
            $input['primaryPhone'] = $_SESSION['contactInfo']['primaryPhone'] = $primaryPhone;
            $input['backupPhone'] = $_SESSION['contactInfo']['backupPhone'] = $backupPhone;

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
            if (strlen($fields['primaryPhone']) > 15)
                return false;
        }
        else if ($fields['paymentMethod'] == "card")
        {
            if (!isset($fields['primaryPhone']) ||
                strlen($fields['primaryPhone']) < 7 ||
                strlen($fields['primaryPhone']) > 15)
                return false;
        }

        //always optional, but check it for sanity
        if (strlen($fields['backupPhone']) > 15)
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
