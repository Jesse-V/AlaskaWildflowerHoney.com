<?php
    require_once(__DIR__.'/../classes/SuppliesOrder.php');
    require_once(__DIR__.'/../classes/BeeOrder.php');
    require_once(__DIR__.'/cart_help_functions.php');


    function sendCardCustomerEmail1($contactArray, $from, $subject, $firstName)
    { //receipt email sent before the processing through Authorize.net

        $cartData = getCart();
        $lastFour = substr($contactArray['x_card_num'], -4);
        $html = '
            <html>
                <head>
                    <title>'.$subject.' -- AlaskaWildflowerHoney.com</title>
                    <style type="text/css">
                        '.getTableCSS().'
                        p {
                            text-align: center;
                            font-size: 1.25em;
                        }

                        body {
                            width: 80%;
                            margin: 0px auto;
                        }
                    </style>
                </head>
                <body>
                    <h2>Your Receipt.</h2>
                    <p>
                        Thank you '.$firstName.'! Your order has been sent to us, and your total is $'.$cartData['total'];

        if (isset($_SESSION['beeOrder']) && isset($_SESSION['supplies']))
            echo "We will gather up your items and record your bee order";
        else if (isset($_SESSION['supplies']))
            echo "We will gather up your items";
        else if (isset($_SESSION['beeOrder']))
            echo "We will record your bee order";

                        echo ' once the transaction has been approved on the card ending with '.$lastFour.'. Thank you for ordering online!
                    </p>
                    <p>
                        Your complete order is as follows:
                    </p>
                    <div id="cartWrapper">
                        '.$cartData['html'].'
                        <p class="total">
                            Total: $'.$cartData['total'].'
                        </p>
                    </div>
                </body>
            </html>';

        sendEmail($contactArray['x_email'], $from, $subject, $html);
    }


    function sendCardDadEmail1($contactArray, $from, $subject, $firstName, $lastName)
    { //sent to dad before customer's card is sent through Authorize.net

        $cartData = getCart();
        $html = '
            <html>
                <head>
                    <title>'.$subject.' -- AlaskaWildflowerHoney.com</title>
                    <style type="text/css">
                        '.getTableCSS().'
                        body {
                            width: 80%;
                            margin: 0px auto;
                        }

                        .centered {
                            text-align: center;
                            width: 75%;
                        }
                    </style>
                </head>
                <body>
                    <p>
                        '.$firstName.' '.$lastName.' has just placed an order for the following:
                    </p>
                    <div id="cartWrapper">
                        '.$cartData['html'].'
                        <p class="total">
                            Total: $'.$cartData['total'].'
                        </p>
                    </div>
                    <p class="centered">
                        <b>';

        if (isset($_SESSION['beeOrder']) && isset($_SESSION['supplies']))
            echo "Please record the bee order and gather the items";
        else if (isset($_SESSION['supplies']))
            echo "Please gather the items";
        else if (isset($_SESSION['beeOrder']))
            echo "Please record the bee order";

                        echo ' once the payment has been approved. If it has been, another email will follow shortly.
                        </b>
                    </p>
                    <h3 class="centered">Shipping and Contact Information</h3>
                    <p class="centered">
                        '.getShippingContact($contactArray).'
                    </p>
                </body>
            </html>';

        sendEmail("victors@mtaonline.net", $from, $subject, $html);
    }


    //per ticket #21, if the customer's card goes through they will get an email from Authorize.net, so there's no need to send one ourself


    function sendCardDadEmail2($contactArray, $from, $subject, $firstName, $lastName, $transID)
    { //sent to dad after the customer's card has been approved by Authorize.net

        $cartData = getCart();
        $lastFour = substr($contactArray['x_card_num'], -4);
        $html = '
            <html>
                <head>
                    <title>'.$subject.' -- AlaskaWildflowerHoney.com</title>
                    <style type="text/css">
                        '.getTableCSS().'
                        body {
                            width: 80%;
                            margin: 0px auto;
                        }

                        .centered {
                            text-align: center;
                            width: 75%;
                        }
                    </style>
                </head>
                <body>
                    <p>
                        The transaction for '.$firstName.' '.$lastName.'\'s order was successful and the payment (transaction #'.$transID.') went through on their card ending with '.$lastFour.'.';

        if (isset($_SESSION['beeOrder']) && isset($_SESSION['supplies']))
            echo "The bee order can be recorded and the items prepared for pickup.";
        else if (isset($_SESSION['supplies']))
            echo "The items can be prepared for pickup.";
        else if (isset($_SESSION['beeOrder']))
            echo "The bee order can be recorded.";

        if (isset($_SESSION['beeOrder']))
            echo 'Data stream:<br><pre>'.getDataString("card", $cartData['total']).'</pre><br>';

                        echo ' They ordered the following:
                    </p>
                    <div id="cartWrapper">
                        '.$cartData['html'].'
                        <p class="total">
                            Total: $'.$cartData['total'].'
                        </p>
                    </div>
                    <h3 class="centered">Shipping and Contact Information</h3>
                    <p class="centered">
                        '.getShippingContact($contactArray).'
                    </p>
                </body>
            </html>';

        sendEmail("victors@mtaonline.net", $from, $subject, $html);
    }


    function sendCheckCustomerEmail($contactArray, $from, $subject, $firstName)
    { //sent to customer if they ordered by check

        $cartData = getCart();
        $html = '
            <html>
                <head>
                    <title>'.$subject.' -- AlaskaWildflowerHoney.com</title>
                    <style type="text/css">
                        '.getTableCSS().'
                        p {
                            text-align: center;
                            font-size: 1.25em;
                        }

                        body {
                            width: 80%;
                            margin: 0px auto;
                        }
                    </style>
                </head>
                <body>
                    <h2>Your Receipt.</h2>
                    <p>
                        Thank you '.$firstName.'! Your order has been sent to us, and your total is $'.$cartData['total'].'. Please send your check to<br>
                        Alaska Wildflower Honey<br>
                        7449 S. Babcock Blvd.<br>
                        Wasilla, AK 99623<br>
                    </p>
                    <p>
                        We will hold your order for two weeks, awaiting the arrival of your check. Thank you for ordering online!
                    </p>
                    <p>
                        Your complete order is as follows:
                    </p>
                    <div id="cartWrapper">
                        '.$cartData['html'].'
                        <p class="total">
                            Total: $'.$cartData['total'].'
                        </p>
                    </div>
                </body>
            </html>';

        sendEmail($contactArray['x_email'], $from, $subject, $html);
    }


    function sendCheckDadEmail($contactArray, $from, $subject, $firstName, $lastName)
    { //receipt sent to dad if the customer ordered by check

        $cartData = getCart();
        $html = '
            <html>
                <head>
                    <title>'.$subject.' -- AlaskaWildflowerHoney.com</title>
                    <style type="text/css">
                        '.getTableCSS().'
                        body {
                            width: 80%;
                            margin: 0px auto;
                        }

                        .centered {
                            text-align: center;
                            width: 75%;
                        }
                    </style>
                </head>
                <body>
                    <p>';

        if (isset($_SESSION['beeOrder']))
            echo 'Data stream:<br><pre>'.getDataString("check", $cartData['total']).'</pre><br>';

                        echo $firstName.' '.$lastName.' has just placed an order for the following:
                    </p>
                    <div id="cartWrapper">
                        '.$cartData['html'].'
                        <p class="total">
                            Total: $'.$cartData['total'].'
                        </p>
                    </div>
                    <p class="centered">
                        <b>
                            The payment was by check, so it may take some time for the payment to come in.<br>Hold the items for up to two weeks.
                        </b>
                    </p>
                    <h3 class="centered">Shipping and Contact Information</h3>
                    <p class="centered">
                        '.getShippingContact($contactArray).'
                    </p>
                </body>
            </html>';

        sendEmail("victors@mtaonline.net", $from, $subject, $html);
    }


    function sendFailedCustomerEmail($contactArray, $from, $subject, $firstName, $resp)
    { //alert sent to customer if their card failed

        $cartData = getCart();
        $html = '
            <html>
                <head>
                    <title>'.$subject.' -- AlaskaWildflowerHoney.com</title>
                    <style type="text/css">
                        '.getTableCSS().'
                        p {
                            text-align: center;
                            font-size: 1.25em;
                        }

                        body {
                            width: 80%;
                            margin: 0px auto;
                        }
                    </style>
                </head>
                <body>
                    <h2>'.$subject.'</h2>
                    <p>
                        Sorry '.$firstName.', your card was processed through Authorize.net (our payment processor) but the transaction failed. They sent us the message: "'.$resp.'" The card\'s number may have been mistyped or has expired, the address or ZIP code may not match, or something else is wrong. Please try again or use a different card.
                    </p>
                    <p>
                        Once the transaction goes through, you will receive an email notification and see the approval on the website.
                    </p>
                    <p>
                        You attempted to order the following:
                    </p>
                    <div id="cartWrapper">
                        '.$cartData['html'].'
                        <p class="total">
                            Total: $'.$cartData['total'].'
                        </p>
                    </div>
                </body>
            </html>';

        sendEmail($contactArray['x_email'], $from, $subject, $html);
    }


    function sendFailedDadEmail($contactArray, $from, $subject, $firstName, $lastName, $resp)
    { //alert sent to dad if customer's card failed

        $cartData = getCart();
        $html = '
            <html>
                <head>
                    <title>'.$subject.' -- AlaskaWildflowerHoney.com</title>
                    <style type="text/css">
                        '.getTableCSS().'
                        body {
                            width: 80%;
                            margin: 0px auto;
                        }

                        .centered {
                            text-align: center;
                            width: 75%;
                        }
                    </style>
                </head>
                <body>
                    <h2>'.$subject.'</h2>
                    <p>
                        '.$firstName.' '.$lastName.' tried to pay via credit/debit card, but the transaction failed with the following message: "'.$resp.'" The customer has been notified and may try again.
                    </p>
                    <p>
                        They were trying to order the following:
                    </p>
                    <div id="cartWrapper">
                        '.$cartData['html'].'
                        <p class="total">
                            Total: $'.$cartData['total'].'
                        </p>
                    </div>
                    <h3 class="centered">Shipping and Contact Information</h3>
                    <p class="centered">
                        '.getShippingContact($contactArray).'
                    </p>
                </body>
            </html>';

        sendEmail("victors@mtaonline.net", $from, $subject, $html);
    }


    function sendEmail($to, $from, $subject, $htmlMessage)
    { //sends an HTML email

        $headers = 'MIME-Version: 1.0'."\r\n" .
            'Content-type: text/html; charset=iso-8859-1'."\r\n" .
            "From: $from"."\r\n" .
            "Reply-To: $from"."\r\n" .
            'X-Mailer: PHP/'.phpversion();

        mail($to, $subject, str_replace("\n.", "\n..", $htmlMessage), $headers);
    }


    function getTableCSS()
    {
        //I'm tired of fighting an encoding bug,
        //  so this is an selection of cardTable.css
        return '
            .cartTable {
                width: 100%;
                text-align: center;
                border-collapse: collapse;
                font-size: 1em; /* 16px */
            }

            .cartTable td, .cartTable th {
                background-color: #FFFFE0;
                padding: 5px;
            }

            .cartTable td {
                border: 1px solid #BDB76B;
            }

            .cartTable th:first-child {
                -moz-border-top-left-radius: 30px;
                -webkit-border-top-left-radius: 30px;
                border-top-left-radius: 30px;
                width: 65%;
            }

            .cartTable th:last-child {
                -moz-border-top-right-radius: 30px;
                -webkit-border-top-right-radius: 30px;
                border-top-right-radius: 30px;
            }

            .subtotal {
                text-align: center;
            }';
    }


    function getDataString($paymentType, $total)
    {
        $order = $_SESSION['beeOrder'];
        $dest = $order->getActualDestination();

        $str = todaysDate()."   ws      ".$dest."       ".$order->getSingleItalianCount().' '.$order->getDoubleItalianCount().' '.$order->getSingleCarniolanCount().'   '.$order->getDoubleCarniolanCount()."   ".$order->countPackages();

        /*
        $dest = $_SESSION['beeOrder']['pickup'] == "Other" ?
            $_SESSION['beeOrder']['destination'] : $_SESSION['beeOrder']['pickup'];

        $str = todaysDate()."   ws      ".$order['pickup']."    ".getSuppliesOrderStr()."   ".$order['singleItalian'].' '.$order['doubleItalian'].' '.$order['singleCarni'].'   '.$order['doubleCarni']."   ".sumPackages()."   ".$_POST['x_ship_to_last_name']."   ".$_POST['x_ship_to_first_name']."  ";

        if ($_POST['preferredPhone'] == "cell")
            $str .= $_POST['homePhone']."   **".$_POST['cellPhone']."**";
        else if ($_POST['preferredPhone'] == "home")
            $str .= "**".$_POST['homePhone']."**    ".$_POST['cellPhone'];
        else
            $str .= $_POST['homePhone']."   ".$_POST['cellPhone'];

        $str .= "   ".textCapableStr()."                    ".$_POST['x_email'].'   '.$dest;

        if ($paymentMethod != "check")
            $str .= "   cc  ".$total."  ".todaysDate();

        return $str;*/

        echo $str;
    }


    function todaysDate()
    {
        $date = getdate();
        return $date['mon'].'/'.$date['mday'].'/'.$date['year'].' '.($date['hours'] - 2).':'.$date['minutes'].':'.$date['seconds'];
    }
?>
