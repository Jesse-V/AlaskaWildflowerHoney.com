<?php
    require_once('../checkout/order/SuppliesOrder.php');
    require_once('cart_help_functions.php');


    function sendCardCustomerEmail1($contactArray, $from, $subject, $firstName, $suppliesObject)
    {
        $total = getCart($suppliesObject)['total'];
        $html = '
            <html>
                <head>
                  <title>'.$subject.' -- AlaskaWildflowerHoney.com</title>
                </head>
                <body>
                    <h3>Thank you!</h3>
                    <p style="text-indent: 2em;">
                        Hello '.$firstName.', thank you for your order. We will gather up your items once the transaction has been approved.
                    </p>
                    <p>
                        Your complete order is as follows:
                    </p>
                    '.getCartEmailHTML($suppliesObject, $total).'
                </body>
            </html>';

        sendEmail($contactArray['x_email'], $from, $subject, $html);
    }


    function sendCardCustomerEmail2($contactArray, $from, $subject, $firstName, $suppliesObject)
    {
        $total = getCart($suppliesObject)['total'];
        $html = '
            <html>
                <head>
                  <title>'.$subject.' -- AlaskaWildflowerHoney.com</title>
                </head>
                <body>
                    <p>
                        The transaction was successful, and we will gather your items as soon as possible. Remember, you\'ve set the pickup point to "'.$suppliesObject->pickupLocation_.'", so come and pick your items up. Thank you for ordering online!
                    </p>
                    <p>
                        Your complete order is as follows:
                    </p>
                    '.getCartEmailHTML($suppliesObject, $total).'
                </body>
            </html>';

        sendEmail($contactArray['x_email'], $from, $subject, $html);
    }


    function sendCardDadEmail1($contactArray, $from, $subject, $firstName, $lastName, $suppliesObject)
    {
        $total = getCart($suppliesObject)['total'];
        $html = '
            <html>
                <head>
                  <title>'.$subject.' -- AlaskaWildflowerHoney.com</title>
                </head>
                <body>
                    <p>
                        '.$firstName.' '.$lastName.' has just placed an order for the following:
                    </p>
                    '.getCartEmailHTML($suppliesObject, $total).'
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


    function sendCardDadEmail2($contactArray, $from, $subject, $firstName, $lastName, $suppliesObject)
    {
        $total = getCart($suppliesObject)['total'];
        $html = '
            <html>
                <head>
                  <title>'.$subject.' -- AlaskaWildflowerHoney.com</title>
                </head>
                <body>
                    <p>
                        The transaction for '.$firstName.' '.$lastName.'\'s order was successful and the payment went through. The items can now be gathered up and prepared for pickup. They ordered the following:
                    </p>
                    '.getCartEmailHTML($suppliesObject, $total).'
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


    function sendCheckDadEmail($contactArray, $from, $subject, $firstName, $lastName, $suppliesObject)
    {
        $total = getCart($suppliesObject)['total'];
        $html = '
            <html>
                <head>
                  <title>'.$subject.' -- AlaskaWildflowerHoney.com</title>
                </head>
                <body>
                    <p>
                        '.$firstName.' '.$lastName.' has just placed an order for the following:
                    </p>
                    '.getCartEmailHTML($suppliesObject, $total).'
                    <p>
                        Pickup location: '.$suppliesObject->pickupLocation_.'
                        <br>
                        <b>
                            The payment was by check, so it may take some time for the payment to come in. Hold the items for up to two weeks.
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


    function sendCheckCustomerEmail($contactArray, $from, $subject, $firstName, $suppliesObject)
    {
        $total = getCart($suppliesObject)['total'];
        $html = '
            <html>
                <head>
                  <title>'.$subject.' -- AlaskaWildflowerHoney.com</title>
                </head>
                <body>
                    <h3>Thank you!</h3>
                    <p style="text-indent: 2em;">
                        Hello '.$firstName.', your online order with us is complete, and your total is $'.$total.'. Please make your check out to Alaska Wildflower Honey.
                    </p>
                    <p>
                        You can send us the check to
                    </p>
                    <p style="width: 300px; text-align: center;">
                        Alaska Wildflower Honey<br>
                        7449 S. Babcock Blvd.<br>
                        Wasilla, AK 99623
                    </p>
                    <p>
                        or give it to us at the next opportunity. We will hold your order for two weeks, awaiting the arrival of your check. Thank you for ordering online!
                    </p>
                    <p>
                        Your complete order is as follows:
                    </p>
                    '.getCartEmailHTML($suppliesObject, $total).'
                </body>
            </html>';

        sendEmail($contactArray['x_email'], $from, $subject, $html);
    }


    function getCartEmailHTML($suppliesObject, $total)
    {
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
            <p>
                Total: $'.$total.'
            </p>';

        return $html;
    }


    function sendEmail($to, $from, $subject, $htmlMessage)
    {
        $headers = 'MIME-Version: 1.0'."\r\n" .
            'Content-type: text/html; charset=iso-8859-1'."\r\n" .
            "From: $from"."\r\n" .
            "Reply-To: $from"."\r\n" .
            'X-Mailer: PHP/'.phpversion();

        mail($to, $subject, str_replace("\n.", "\n..", $htmlMessage), $headers);
    }
?>
