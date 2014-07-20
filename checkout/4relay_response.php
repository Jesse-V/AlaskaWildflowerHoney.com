<!-- this is the Authorize.net callback and is itself transparent to the user -->

<!DOCTYPE html>
<html>
    <head>
        <title>Relay Response</title>

<?php
    require_once(__DIR__.'/../assets/php/anet_php_sdk/AuthorizeNet.php');
    require_once(__DIR__.'/../assets/php/checkout/authorizeNetVars.secret');

    $response = new AuthorizeNetSIM($api_login_id, $md5_setting);
    if ($response->isAuthorizeNet())
    {
        $rc   = $response->response_code;
        $id   = $response->transaction_id;
        $resp = $response->approved ? "approved" : $response->response_reason_text;
        $hash = hash("sha256", $md5_setting.$rc.$id.$md5_setting);

        $redirectURL  = "https://www.alaskawildflowerhoney.com/checkout/5card_receipt.php";
        $redirectURL .= "?rc=$rc&id=$id&resp=$resp&hash=$hash";

        echo '
            <script language="javascript">
                window.location="'.$redirectURL.'";
            </script>
            <meta http-equiv="refresh" content="0;url='.$redirectURL.'">
        </head>
        <body>
            <p>Transaction processed. Redirecting you...</p>
        </body>';
    }
    else
    {
        echo '
        </head>
        <body>
            <p>
                Oops! An error occurred during the transaction, or you reached this page in error. The error code is 27. Please notify us by sending an email to <a href="mailto:jvictors@jessevictors.com?Subject=Transaction%20query%20error" target="_top">jvictors@jessevictors.com</a> and we will take care of it. Thanks!
            </p>
        </body>';
    }
?>

</html>
