<?php
    require_once('cart_help_functions.php');


    function sendCustomerEmail($to, $from, $subject, $firstName, $suppliesObject)
    {
        $cart = getCart($suppliesObject);
        $html = '
            <html>
                <head>
                  <title>Email from AlaskaWildflowerHoney.com</title>
                </head>
                <body>
                    <h3>Thank you!</h3>
                    <p style="text-indent: 2em;">
                        Hello '.$firstName.', your online order with us is complete, and your total is '.$cart['total'].'. Please make your check out to Alaska Wildflower Honey.
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
                        or give it to us at the next opportunity. Thank you for your business!
                    </p>
                    <p>
                        Your order is as follows:
                    </p>
                    '.$cart['html'].'
                </body>
            </html>';

        sendEmail($to, $from, $subjet, $html);
    }

    function sendEmail($to, $from, $subject, $htmlMessage)
    {
        $headers = 'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
            "From: $from" . "\r\n" .
            "Reply-To: $from" . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, str_replace("\n.", "\n..", $htmlMessage), $headers);
    }
?>
