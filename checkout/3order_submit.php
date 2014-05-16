<?php
    //This page should be invisible to the user. It basically just takes the information from step 2, injects it into hidden fields, sends human-readable forms to both the customer and dad, and then uses Javascript to automatically submit the form.

    //The email should make it clear that the order shouldn't/won't be processed until the transaction completes

    //https://stackoverflow.com/questions/4578836/html-post-automatically
    //https://stackoverflow.com/questions/133925/javascript-post-request-like-a-form-submit
    //<input type="hidden" name="x_description" value="<?php echo getOrderReceiptStr();"/>


    require_once('../anet_php_sdk/AuthorizeNet.php');
    //require_once('../scripts/databaseConnect.secret');
    //require_once('../scripts/cart_help_functions.php');
    //require_once('authorizeNetVars.secret');

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
        //<form name="AuthorizeNetForm" method="post" action="4relay_response.php">
            if (empty($_SESSION))
            {
                echo "<p>Oops! You seemed to have reached this page in error.</p>"
            }
            else
            {
                //TODO: SEND EMAILS HERE
                foreach ($_SESSION['paymentInfo'] as $key => $value)
                    echo "<input type=\"hidden\" name=\"$key\" value=\"$value\"/>";
            }

        ?>

        <input type="submit" value="HiddenGo">

        </form>
    </body>

    <script type="text/javascript">
        document.AuthorizeNetForm.submit();
    </script>

</html>

<?php

?>







