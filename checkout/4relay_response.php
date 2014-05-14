This page should be posted on one of my USU machines. This is transparent to the user

<!DOCTYPE html>
<html>
    <head>
        <title>Relay Response</title>

<?php

    require_once('anet_php_sdk/AuthorizeNet.php');
    $redirect_url = "https://www.alaskawildflowerhoney.com/checkout/card_receipt.php";
    require_once('authorizeNetVars.secret');

    $response = new AuthorizeNetSIM($api_login_id, $md5_setting);
    if ($response->isAuthorizeNet())
    {
        $approvedGet =
            '&id='.$response->transaction_id.
            '&k='.hash("sha256", $md5_setting.$response->transaction_id).
            '&x_ship_to_first_name='.$_POST['x_ship_to_first_name'].
            '&x_ship_to_last_name='.$_POST['x_ship_to_last_name'].
            '&homePhone='.$_POST['homePhone'].
            '&preferredPhone='.$_POST['preferredPhone'].
            '&cellPhone='.$_POST['cellPhone'].
            '&textCapable='.$_POST['textCapable'].
            '&x_email='.$_POST['x_email'].
            '&x_card_type='.$_POST['x_card_type'].
            '&x_account_number='.$_POST['x_account_number'].
            '&x_amount='.$_POST['x_amount'];

        if ($response->approved)
            $redirect_url .= '?rc=1'.$approvedGet;
        else
            $redirect_url .= '?rc='.$response->response_code.'&resp='.$response->response_reason_text;

        //redirect user back to Stevesbees
        echo "
            <script language=\"javascript\">
                window.location=\"$redirect_url\";
            </script>
            <meta http-equiv=\"refresh\" content=\"0;url=$redirect_url\">

            </head>
            <body>
                <p>Transaction approved. Redirecting you...</p>
            </body>
            ";
    }
    else
    {
        echo "
            </head>
            <body>
                <p>
                    An error occurred during processing. The response signature did not match, suggesting that there was a communications issue or that the request was not from Authorize.net, our payment gateway provider. Please notify us of this error by sending emails to <a href=\"mailto:victors@mtaonline.net?Subject=Transaction%20query%20error\" target=\"_top\">victors@mtaonline.net</a> and <a href=\"mailto:jvictors@jessevictors.com?Subject=Transaction%20query%20error\" target=\"_top\">jvictors@jessevictors.com</a> and we will try to address this issue promptly. We apologize for the inconvenience.
                </p>
            </body>
            ";
    }
?>

</html>
