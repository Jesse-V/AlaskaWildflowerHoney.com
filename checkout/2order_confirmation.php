<?php
    require_once('../anet_php_sdk/AuthorizeNet.php');
    require_once('../scripts/databaseConnect.secret');
    require_once('../scripts/cart_help_functions.php');
    require_once('authorizeNetVars.secret');
    session_start();

    $_REL_ = "../";
    $_TITLE_ = "Order Confirmation - StevesBees.com";
    $_STYLESHEETS_ = array("../stylesheets/fancyHRandButtons.css", "../stylesheets/checkout_form.css", "../stylesheets/cartTable.css", "../stylesheets/order_confirmation.css");
    require_once('../common/header.php'); //opening HTML

    if (empty($_SESSION) || empty($_POST) || !isset($_POST['nextDestination']) || !isset($_POST['paymentMethod']))
    {
        echo 'Oops! You seemed to have reached this page in error, as your cart is currently empty.<br><br>Please visit the <a href="../order_supplies.php">Supplies page</a> or the <a href="../order_bees.php">Bees page</a>. Thanks!';
    }
    else
    {
        echo '
        <h1>Order Confirmation</h1>
        <form method="post" action="'.$_POST['nextDestination'].'">';

        echo "
            <p>
                This is a confirmation of your shopping cart and order information. Please take a moment to review everything before the order goes through. If it all looks good, please hit the confirmation button below. If something needs adjustment, please click your browser's back button. Thanks again for shopping with us!
            </p>";

        echoCart($_SESSION['supplies']);

        if ($_POST['paymentMethod'] == "card")
        { //it's a confirmation of a card checkout

            $_SESSION['paymentInfo'] = array();
            foreach ($_POST as $key => $cardField)
                $_SESSION['paymentInfo'][$key] = htmlentities(strip_tags($cardField));

            $_SESSION['paymentInfo']['homePhone'] = formatPhone($_SESSION['paymentInfo']['homePhone']);
            $_SESSION['paymentInfo']['cellPhone'] = formatPhone($_SESSION['paymentInfo']['cellPhone']);

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

            $_SESSION['contactInfo'] = array();
            foreach ($_POST as $key => $contactField)
                $_SESSION['contactInfo'][$key] = htmlentities(strip_tags($contactField));

            $homePhone = formatPhone($_SESSION['contactInfo']['homePhone']);
            $cellPhone = formatPhone($_SESSION['contactInfo']['cellPhone']);
            $_POST['homePhone'] = $_SESSION['contactInfo']['homePhone'] = $homePhone;
            $_POST['cellPhone'] = $_SESSION['contactInfo']['cellPhone'] = $cellPhone;

            echo '
                <h3>Shipping and Contact</h3>
                <p>
                    '.getShippingContact($_POST).'
                </p>';
        }

        echo '
        <p>
            <button type="submit" id="confirm" class="submit">Confirm, this information is accurate.</button>
        </p>
        </form>';
    }


    $_JS_ = array("../scripts/jquery-1.10.2.js", "../scripts/checkout_form.js");
    require_once('../common/footer.php'); //closing HTML
    $db->close();


    function getOrderReceiptStr()
    {
        $str = "";

        $beeOrder = $_SESSION['beeOrder'];

        if (isset($beeOrder['singleItalian']))
            $str .= $beeOrder['singleItalian']." I., ";
        if (isset($beeOrder['doubleItalian']))
            $str .= $beeOrder['doubleItalian']." II., ";

        if (isset($beeOrder['singleCarni']))
            $str .= $beeOrder['singleCarni']." C., ";
        if (isset($beeOrder['doubleCarni']))
            $str .= $beeOrder['doubleCarni']." CC., ";

        if (isset($beeOrder['ItalianQueens']))
            $str .= $beeOrder['ItalianQueens']." I. queens, ";
        if (isset($beeOrder['CarniQueens']))
            $str .= $beeOrder['CarniQueens']." C. queens, ";

        $supplyInfo = querySuppliesTable();
        foreach ($_SESSION['supplies'] as &$item)
        {
            $name     = $supplyInfo[$item['id']]['name'];
            $quantity = $item['quantity'];
            $str .= "$quantity $name, ";
        }

        return $str;
    }


    function formatPhone($number)
    { //https://stackoverflow.com/questions/4708248/formatting-phone-numbers-in-php

        $threeParts = preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '($1) $2-$3', $number);
        $twoParts = preg_replace('~.*(\d{3})[^\d]*(\d{4}).*~', '$1-$2', $number);
        return strlen($number) == 7 ? $twoParts : $threeParts;
    }
?>
