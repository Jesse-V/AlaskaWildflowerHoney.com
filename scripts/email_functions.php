<?php
    require_once('../checkout/order/SuppliesOrder.php');
    require_once('cart_help_functions.php');


    function sendCardCustomerEmail1($contactArray, $from, $subject, $firstName, $suppliesObject)
    { //receipt email sent before the processing through Authorize.net

        $total = getCart($suppliesObject)['total'];
        $lastFour = substr($contactArray['x_card_num'], -4);
        $html = '
            <html>
                <head>
                    <title>'.$subject.' -- AlaskaWildflowerHoney.com</title>
                    <style type="text/css">
                        '.file_get_contents('../stylesheets/cartTable.css', TRUE).'
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
                        Thank you '.$firstName.'! Your order has been sent to us, and your total is $'.$total.'. We will gather up your items once the transaction has been approved on the card ending with '.$lastFour.' Thank you for ordering online!
                    </p>
                    <p>
                        Pickup location: '.$suppliesObject->pickupLocation_.'
                    </p>
                    <p>
                        Your complete order is as follows:
                    </p>
                    <div id="cartWrapper">
                        '.getCartEmailHTML($suppliesObject, $total).'
                    </div>
                </body>
            </html>';

        sendEmail($contactArray['x_email'], $from, $subject, $html);
    }


    function sendCardDadEmail1($contactArray, $from, $subject, $firstName, $lastName, $suppliesObject)
    { //sent to dad before customer's card is sent through Authorize.net

        $total = getCart($suppliesObject)['total'];
        $html = '
            <html>
                <head>
                    <title>'.$subject.' -- AlaskaWildflowerHoney.com</title>
                    <style type="text/css">
                        '.file_get_contents('../stylesheets/cartTable.css', TRUE).'
                    </style>
                </head>
                <body>
                    <p>
                        '.$firstName.' '.$lastName.' has just placed an order for the following:
                    </p>
                    <div id="cartWrapper">
                        '.getCartEmailHTML($suppliesObject, $total).'
                    </div>
                    <p>
                        Pickup location: '.$suppliesObject->pickupLocation_.'
                        <br>
                        <b>
                            Please gather the items once the payment has been approved. If it has been, another email will follow shortly.
                        </b>
                    </p>
                    <h3>Shipping and Contact Information</h3>
                    <p>
                        '.getShippingContact($contactArray).'
                    </p>
                </body>
            </html>';

        sendEmail("victors@mtaonline.net", $from, $subject, $html);
    }


    //per ticket #21, if the customer's card goes through they will get an email from Authorize.net, so there's no need to send one ourself


    function sendCardDadEmail2($contactArray, $from, $subject, $firstName, $lastName, $suppliesObject)
    { //sent to dad after the customer's card has been approved by Authorize.net

        $total = getCart($suppliesObject)['total'];
        $html = '
            <html>
                <head>
                    <title>'.$subject.' -- AlaskaWildflowerHoney.com</title>
                    <style type="text/css">
                        '.file_get_contents('../stylesheets/cartTable.css', TRUE).'
                    </style>
                </head>
                <body>
                    <p>
                        The transaction for '.$firstName.' '.$lastName.'\'s order was successful and the payment went through. The items can now be gathered up and prepared for pickup. They ordered the following:
                    </p>
                    <div id="cartWrapper">
                        '.getCartEmailHTML($suppliesObject, $total).'
                    </div>
                    <p>
                        Pickup location: '.$suppliesObject->pickupLocation_.'
                    </p>
                    <h3>Shipping and Contact Information</h3>
                    <p>
                        '.getShippingContact($contactArray).'
                    </p>
                </body>
            </html>';

        sendEmail("victors@mtaonline.net", $from, $subject, $html);
    }


    function sendCheckCustomerEmail($contactArray, $from, $subject, $firstName, $suppliesObject)
    { //sent to customer if they ordered by check

        $total = getCart($suppliesObject)['total'];
        $html = '
            <html>
                <head>
                    <title>'.$subject.' -- AlaskaWildflowerHoney.com</title>
                    <style type="text/css">
                        '.file_get_contents('../stylesheets/cartTable.css', TRUE).'
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
                        Thank you '.$firstName.'! Your order has been sent to us, and your total is $'.$total.'. Please send your check to<br>
                        Alaska Wildflower Honey<br>
                        7449 S. Babcock Blvd.<br>
                        Wasilla, AK 99623<br>
                    </p>
                    <p>
                        Pickup location: '.$suppliesObject->pickupLocation_.'
                    </p>
                    <p>
                        We will hold your order for two weeks, awaiting the arrival of your check. Thank you for ordering online!
                    </p>
                    <p>
                        Your complete order is as follows:
                    </p>
                    <div id="cartWrapper">
                        '.getCartEmailHTML($suppliesObject, $total).'
                    </div>
                </body>
            </html>';

        sendEmail($contactArray['x_email'], $from, $subject, $html);
    }


    function sendCheckDadEmail($contactArray, $from, $subject, $firstName, $lastName, $suppliesObject)
    { //receipt sent to dad if the customer ordered by check

        $total = getCart($suppliesObject)['total'];
        $html = '
            <html>
                <head>
                    <title>'.$subject.' -- AlaskaWildflowerHoney.com</title>
                    <style type="text/css">
                        '.file_get_contents('../stylesheets/cartTable.css', TRUE).'
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
                        '.getCartEmailHTML($suppliesObject, $total).'
                    </div>
                    <p>
                        Pickup location: '.$suppliesObject->pickupLocation_.'
                    </p>
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

        $html = '
            <html>
                <head>
                    <title>'.$subject.' -- AlaskaWildflowerHoney.com</title>
                    <style type="text/css">
                        '.file_get_contents('../stylesheets/cartTable.css', TRUE).'
                        body {
                            width: 80%;
                            margin: 0px auto;
                        }
                    </style>
                </head>
                <body>
                    <h2>'.$subject.'</h2>
                    <p>
                        '.$firstName.', your card was processed through Authorize.net, our payment processor, but the transaction failed. They sent the us the message: '.$resp.' The card\'s number may be invalid or has expired, the address or ZIP code may not match, or something else is wrong. Please try again or use a different card.
                    </p>
                    <p>
                        If the transaction goes through, you will receive an email notification and see the approval on the website.
                    </p>
                    <p>
                        You ordered the following:
                    </p>
                    <div id="cartWrapper">
                        '.getCartEmailHTML($suppliesObject, $total).'
                    </div>
                </body>
            </html>';

        sendEmail($contactArray['x_email'], $from, $subject, $html);
    }


    function sendFailedDadEmail($contactArray, $from, $subject, $firstName, $lastName, $resp)
    { //alert sent to dad if customer's card failed

        $html = '
            <html>
                <head>
                    <title>'.$subject.' -- AlaskaWildflowerHoney.com</title>
                    <style type="text/css">
                        '.file_get_contents('../stylesheets/cartTable.css', TRUE).'
                        body {
                            width: 80%;
                            margin: 0px auto;
                        }
                    </style>
                </head>
                <body>
                    <h2>'.$subject.'</h2>
                    <p>
                        '.$firstName.' '.$firstName.' tried to pay via credit/debit card, but the transaction failed with the following message: '.$resp.' The customer has been notified and may try again.
                    </p>
                    <p>
                        They were trying to order the following:
                    </p>
                    <div id="cartWrapper">
                        '.getCartEmailHTML($suppliesObject, $total).'
                    </div>
                </body>
            </html>';

        sendEmail($contactArray['x_email'], $from, $subject, $html);
    }


    function getCartEmailHTML($suppliesObject, $total)
    { //HTML of the customer's cart, prepped for an email

        $html = '
            <h2>Supplies</h2>
            <table id="cartTable">
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>';

        foreach ($suppliesObject->getItems() as $item)
        {
            $price = $item->price_ * $item->quantity_;
            $html .= "
                <tr>
                    <td>
                        $item->name_ $item->groupName_
                    </td>
                    <td>$item->quantity_</td>
                    <td>$$price</td>
                </tr>";
        }

        $html .=   '
            </table>
            <p class="total">
                Total: $'.$total.'
            </p>';

        return $html;
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
?>
