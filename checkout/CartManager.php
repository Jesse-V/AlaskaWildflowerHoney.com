<?php
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Invisible Cart Manager!</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <?php
        if (empty($_POST) || !isset($_POST['format']))
        {
            echo "<script>window.history.back()</script>";
            return;
        }

        if ($_POST['format'] == "supplies")
        {
            if (!isset($_POST['pickupLoc']))
            {
                echo "<script>
                    window.alert(\"You forgot to specify where you'd like to pick up the supplies. You will be redirected back to the form after you press 'OK'.\");
                    window.history.back();
                </script>";

                return;
            }

            $supplies = array();

            for ($index = 1; isset($_POST[$index]); $index++)
            {
                if ($_POST[$index] > 0)
                {
                    $item = array();
                    $item['id'] = $index;
                    $item['quantity'] = $_POST[$index];
                    array_push($supplies, $item);
                }
            }

            $_SESSION['supplies'] = $supplies;
            $_SESSION['suppliesPickup'] = $_POST['pickupLoc'];

            routeAccordingly("../order_bees.php");
        }
        else if ($_POST['format'] == "bees")
        {
            if (!validateInputs())
                return;

            $packageCount = $_POST['singleItalian'] + $_POST['doubleItalian'] + $_POST['singleCarni'] + $_POST['doubleCarni'];

            $beeOrder = array();
            if ($_POST['singleItalian'] > 0)
                $beeOrder['singleItalian'] = $_POST['singleItalian'];
            if ($_POST['doubleItalian'] > 0)
                $beeOrder['doubleItalian'] = $_POST['doubleItalian'];
            if ($_POST['singleCarni'] > 0)
                $beeOrder['singleCarni'] = $_POST['singleCarni'];
            if ($_POST['doubleCarni'] > 0)
                $beeOrder['doubleCarni'] = $_POST['doubleCarni'];

            if ($_POST['ItalianQueens'] > 0)
                $beeOrder['ItalianQueens'] = $_POST['ItalianQueens'];
            if ($_POST['CarniQueens'] > 0)
                $beeOrder['CarniQueens'] = $_POST['CarniQueens'];

            $beeOrder['pickup'] = $_POST['pickupLoc'];
            $beeOrder['destination'] = $_POST['customDest'];
            $beeOrder['notes'] = $_POST['notes'];
            $beeOrder['transCharge'] = getTransportationCharge($beeOrder['pickup'], $packageCount);

            $_SESSION['beeOrder'] = $beeOrder;

            routeAccordingly("../order_supplies.php");
        }
        else
        {
            echo "<script>window.history.back()</script>";
            return;
        }

        ?>

    </head>
    <body>
        Updating your cart and redirecting...
    </body>
</html>

<?php

    function validateInputs()
    {
        if ($_POST['singleItalian'] + $_POST['doubleItalian'] + $_POST['singleCarni'] + $_POST['doubleCarni'] + $_POST['ItalianQueens'] + $_POST['CarniQueens'] == 0)
            $error = "you forgot to order packages or queens. Please specify a preference and resubmit.";
        else if (!isNumber($_POST['singleItalian']) || !isNumber($_POST['doubleItalian']) || !isNumber($_POST['singleCarni']) || !isNumber($_POST['doubleCarni']) || !isNumber($_POST['ItalianQueens']) || !isNumber($_POST['CarniQueens']))
            $error = "you must provide numeric values for your order.";
        else if (!isset($_POST['pickupLoc']))
            $error = "you forgot to specify a pickup location.";
        else if ($_POST['pickupLoc'] == "Other" && strlen($_POST['customDest']) <= 2)
            $error = "a custom destination was selected but it was not specified.";

        if (isset($error))
        {
            echo "<script>
                    window.alert(\"We ran into a problem with your request: $error You will be redirected back to the form after you press 'OK'.\");
                    window.history.back();
                </script>";
            return false;
        }

        return true;
    }


    function getTransportationCharge($destination, $packageCount)
    {
        switch ($destination)
        {
            case 'Anchorage':
            case 'Wasilla':
            case 'Palmer':
            case 'Eagle River':
            case 'Big Lake':
                return 0;

            case 'Soldotna':
                return 5 * $packageCount;

            case 'Homer':
            case 'Healy':
            case 'Nenana':
            case 'Fairbanks':
                return 10 * $packageCount;

            case 'Other':
                return max($packageCount * 5, 10);

            default:
                return 0;
        }
    }



    function routeAccordingly($alternative)
    {
        //if submit contains checkout, then direct to checkout_form
        $redirectURL = strpos($_POST['submit'], 'checkout') !== FALSE ? "checkout_form.php" : $alternative;

        echo "
            <script language=\"javascript\">
                window.location=\"{$redirectURL}\";
            </script>
            <meta http-equiv=\"refresh\" content=\"0;url={$redirectURL}\">
        ";
    }



    function isNumber($str)
    {
        return preg_match("/^\d+$/", $str);
    }
?>
