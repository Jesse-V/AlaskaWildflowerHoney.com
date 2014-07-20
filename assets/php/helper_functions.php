<?php

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



    function sendOrderEmail($customerEmail, $paymentMethod, $total)
    { //TODO: see http://support.godaddy.com/help/article/508/specifying-an-email-address-for-the-php-form-mailer
            //and http://support.godaddy.com/help/article/4490/what-is-the-difference-between-webformmailerphp-and-gdformphp

        //currently using PHP mail function because of Ultimate account with cPanel
        // http://support.godaddy.com/help/article/8960/using-form-mail-with-cpanel-and-plesk-shared-hosting?locale=en&ci=46061
        // http://us1.php.net/manual/en/function.mail.php

        $forStr = getOrderForStr();
        $beeOrderStr = '<pre>'.getBeeOrderStr($paymentMethod, $total).'</pre>';
        $extraQueensStr = getExtraQueensStr();
        $suppliesOrderStr = getSuppliesOrderStr();
        $pickupLocation = $_SESSION['suppliesPickup'];

        $merchantHTML = '
<html>
<head>
  <title>Email from AlaskaWildflowerHoney.com</title>
</head>
<body>
    <h3>General order information</h3>
    <p style="text-indent: 2em;">
        '.$_POST['x_ship_to_first_name'].' '.$_POST['x_ship_to_last_name'].' just submitted an online order. Their number is '.$_POST['homePhone'].', cell phone '.$_POST['cellPhone'].' (text capable: "'.$_POST['textCapable'].'"), with the preferred phone set as "'.$_POST['preferredPhone'].'". Their email address is <a href="mailto:'.$_POST['x_email'].'">'.$_POST['x_email'].'</a>
    </p>

    <h3>Possible bee order</h3>
    <p>
        Total packages ordered: '.sumPackages().'
    </p>
    <p style="text-indent: 2em;">
        '.$beeOrderStr.'
    </p>

    <h3>Possible extra queens</h3>
    <p style="text-indent: 2em;">
        '.$extraQueensStr.'
    </p>

    <h3>Possible supplies order</h3>
    <p style="text-indent: 2em;">
        '.$suppliesOrderStr.'
    </p>

    <h3>Pickup location for any supplies</h3>
    <p style="text-indent: 2em;">
        '.$pickupLocation.'
    </p>

    <h3>Notes</h3>
    <p style="text-indent: 2em;">
        '.$_SESSION['beeOrder']['notes'].'
    </p>

    <h3>Total</h3>
    <p style="text-indent: 2em;">'
        .$total.'. Payment method: '.$paymentMethod.'
    </p>

</body>
</html>
';

        $customerHTML = '
<html>
<head>
  <title>Email from AlaskaWildflowerHoney.com</title>
</head>
<body>
    <h3>Thank you!</h3>
    <p style="text-indent: 2em;">
        Hello '.$_POST['x_ship_to_first_name'].', your online order with us is complete, and your total is '.$total.'. Please make your check out to Alaska Wildflower Honey.
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
</body>
</html>
';

        sendEmail("victors@mtaonline.net", 'AlaskaWildflowerHoney.com <DoNotReply@stevesbees.com>', "Form Submission: Online Order", $merchantHTML);

        if ($paymentMethod == "check")
            sendEmail($_POST['x_email'], 'Alaska Wildflower Honey <victors@mtaonline.net>', "Your online order is complete", $customerHTML);




/*
        $file = $_SERVER['DOCUMENT_ROOT']."/../data/gdform_".date("U");
        $emailFile = fopen($file, "w");
        if (!$emailFile)
            echo "ERROR: the attempt to send email failed!";
        else
        {
            fputs($emailFile, "<GDFORM_VARIABLE NAME=email START>\n$replyTo\nGDFORM_VARIABLE NAME=email END>\n");
            fputs($emailFile, "<GDFORM_VARIABLE NAME=subject START>\n$subject\nGDFORM_VARIABLE NAME=subject END>\n");
            fputs($emailFile, "<GDFORM_VARIABLE NAME=a_ordering_for START>\n$forStr\nGDFORM_VARIABLE NAME=subject END>\n");
            fputs($emailFile, "<GDFORM_VARIABLE NAME=a_payment_method START>\nvia $paymentMethod\nGDFORM_VARIABLE NAME=subject END>\n");
            fputs($emailFile, "<GDFORM_VARIABLE NAME=bee_order START>\n$beeOrderStr\nGDFORM_VARIABLE NAME=bee_order END>\n");
            fputs($emailFile, "<GDFORM_VARIABLE NAME=extra_queens START>\n$extraQueensStr\nGDFORM_VARIABLE NAME=extra_queens END>\n");
            fputs($emailFile, "<GDFORM_VARIABLE NAME=supplies_order START>\n$suppliesOrderStr\nGDFORM_VARIABLE NAME=supplies_order END>\n");
            fputs($emailFile, "<GDFORM_VARIABLE NAME=supplies_pickup START>\n$pickupLocation\nGDFORM_VARIABLE NAME=supplies_order END>\n");
            fputs($emailFile, "<GDFORM_VARIABLE NAME=total START>\n$totalStr\nGDFORM_VARIABLE NAME=total END>\n");
        }

        fclose($emailFile);
        */


    }



    function getOrderForStr()
    {
        return "name: ".$_POST['x_ship_to_first_name'].' '.$_POST['x_ship_to_last_name'].", home #: ".$_POST['homePhone'].", cell #: ".$_POST['cellPhone'].", preferred phone: ".$_POST['preferredPhone'].", text capable: ".$_POST['textCapable'].", email: ".$_POST['x_email'];
    }



    function getBeeOrderStr($paymentMethod, $total)
    {
        $order = $_SESSION['beeOrder'];
        $dest = $_SESSION['beeOrder']['pickup'] == "Other" ? $_SESSION['beeOrder']['destination'] : $_SESSION['beeOrder']['pickup'];

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

        return $str;
    }



    function getExtraQueensStr()
    {
        $order = $_SESSION['beeOrder'];
        return "Italians: ".$order['ItalianQueens']." Carniolans: ".$order['CarniQueens'];
    }



    function getSuppliesOrderStr()
    {
        $names = "";
        $supplyInfo = querySuppliesTable();
        foreach ($_SESSION['supplies'] as &$item)
        {
            $name     = $supplyInfo[$item['id']]['name'];
            $quantity = $item['quantity'];
            $names .= $name." ($quantity), ";
        }

        return $names;
    }



    function todaysDate()
    {
        $date = getdate();
        return $date['mon'].'/'.$date['mday'].'/'.$date['year'].' '.($date['hours'] - 2).':'.$date['minutes'].':'.$date['seconds'];
    }



    function sumPackages()
    {
        $order = $_SESSION['beeOrder'];
        return $order['singleItalian'] + $order['doubleItalian'] + $order['singleCarni'] + $order['doubleCarni'];
    }



    function textCapableStr()
    {
        if ($_POST['textCapable'] === "yes")
            return 'y';
        else
            return 'n';
    }



    function enterIntoDatabase()
    {/*
        require_once('databaseConnect.secret');

        $query = "INSERT INTO `orders`(`firstName`,`lastName`,`IP`,`date`,`singleItalian`,`doubleItalian`,`singleCarni`,`doubleCarni`,`pickupLoc`,`homePhone`,`cellPhone`,`textCapable`,`preferredPhone`,`notes`) VALUES ('".$_POST['firstName']."','".$_POST['lastName']."','".$_SERVER['REMOTE_ADDR']."','".todaysDate()."',".$_POST['singleItalian'].",".$_POST['doubleItalian'].",".$_POST['singleCarni'].",".$_POST['doubleCarni'].",'".$_POST['pickupLoc']."','".$_POST['homePhone']."','".$_POST['cellPhone']."',".textCapable().",'".$_POST['preferredPhone']."','".$_POST['notes']."')";

        $result = $db->query($query);
        if (!$result)
            die("Failed to back up order into database! ".$db->error);*/
    }
?>
