<!-- this is the Authorize.net callback and is itself transparent to the user -->

<!DOCTYPE html>
<html>
    <head>
        <title>Relay Response</title>

<?php

require_once('anet_php_sdk/AuthorizeNet.php');
require_once('authorizeNetVars.secret');

$response = new AuthorizeNetSIM($api_login_id, $md5_setting);
if ($response->isAuthorizeNet())
{
    $redirectURL = "https://www.alaskawildflowerhoney.com/checkout/card_receipt.php";
    if ($response->approved)
    {
        $rc = 1;
        $id = $response->transaction_id;
        $hash = hash("sha256", $md5_setting.$rc."%*%".$id.$md5_setting);
        $redirectURL .= "?rc=$rc&id=$id&hash=$hash");
    }
    else
    {
        $rc = $response->response_code;
        $resp = $response->response_reason_text;
        $hash = hash("sha256", $md5_setting.$rc."*%*".$id.$md5_setting);
        $redirectURL .= "?rc=$rc&resp=$resp&hash=$hash";
    }


    echo '
        <script language="javascript">
            window.location="$redirectURL";
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
        /*
    require_once('../checkout/order/SuppliesOrder.php');
    session_start();

    print_r($_GET);
    echo "<br><br>";
    print_r($_POST);
    echo "<br><br>";
    print_r($_SESSION);
    echo "<br><br>";*/
?>

</html>
