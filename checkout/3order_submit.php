<?php
    //This page should be invisible to the user. It basically just takes the information from step 2, injects it into hidden fields, sends human-readable forms to both the customer and dad, and then uses Javascript to automatically submit the form.

    //The email should make it clear that the order shouldn't/won't be processed until the transaction completes

    //<input type="hidden" name="x_description" value="<?php echo getOrderReceiptStr();"/>

    require_once(__DIR__.'/../assets/anet_php_sdk/AuthorizeNet.php');
    require_once(__DIR__.'/../assets/php/checkout/email_functions.php');
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Invisible Order Submission</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>

    <body>
        <p>
            Processing your order...
        </p>
        <form name="AuthorizeNetForm" method="post" action="<?php echo AuthorizeNetDPM::LIVE_URL ?>">

        <?php

            if (empty($_SESSION))
            {
                echo "
                    <p>
                        Oops! Something went wrong, or you have reached this page in error. One possible explanation is that you have already submitted your order.
                    </p>";
            }
            else
            {
                foreach ($_SESSION['paymentInfo'] as $key => $value)
                    echo "<input type=\"hidden\" name=\"$key\" value=\"$value\"/>";

                $firstName = $_SESSION['paymentInfo']['x_first_name'];
                $lastName  = $_SESSION['paymentInfo']['x_last_name'];

                sendCardCustomerEmail1($_SESSION['paymentInfo'],
                    'Alaska Wildflower Honey <victors@mtaonline.net>',
                    "Thank you for your order",
                    $firstName);

                sendCardDadEmail1($_SESSION['paymentInfo'],
                    'AlaskaWildflowerHoney.com <DoNotReply@stevesbees.com>',
                    "Online Order Submission, Card - ".$firstName.' '.$lastName,
                    $firstName, $lastName);
            }
        ?>

        <input type="submit" value="Go">

        </form>
    </body>

    <script type="text/javascript">
        document.AuthorizeNetForm.submit();
        //https://stackoverflow.com/questions/4578836/html-post-automatically
        //https://stackoverflow.com/questions/133925/javascript-post-request-like-a-form-submit
    </script>
</html>
