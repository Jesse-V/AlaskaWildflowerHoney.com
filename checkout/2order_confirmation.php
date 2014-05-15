<?php
    require_once('../anet_php_sdk/AuthorizeNet.php');
    require_once('../scripts/databaseConnect.secret');
    require_once('../scripts/cart_help_functions.php');
    require_once('authorizeNetVars.secret');

    session_start();
?>

<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/Main.dwt" codeOutsideHTMLIsLocked="false" -->
    <head>
        <!-- InstanceBeginEditable name="doctitle" -->
        <title>Order Confirmation - StevesBees.com</title>
        <!-- InstanceEndEditable -->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" href="../stylesheets/main.css" />
        <script type="text/javascript" src="../SpryAssets/SpryAccordion.js"></script>
        <link href="../SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css" />
        <!-- InstanceBeginEditable name="head" -->
        <link rel="stylesheet" type="text/css" href="../stylesheets/fancyHRandButtons.css" />
        <link rel="stylesheet" type="text/css" href="../stylesheets/checkout_form.css" />
        <link rel="stylesheet" type="text/css" href="../stylesheets/cartTable.css" />
        <!-- InstanceEndEditable -->
    </head>
    <body>
        <div class="bannerArea">
            <div class="container">
                <span class="site_logo">Steve's Bees<br><span class="subtitle">Alaska Wildflower Honey</span></span>
            </div>
        </div>
        <div class="contentArea">
            <div class="container">
                <div class="left_col">
                    <div id="navigation">
                        <div class="navItem">
                            <img src="../images/honeybee.jpg" alt="Tiny single honeybee."/>
                            <div class="title">AWH home</div>
                            <a href="../index.php"><span class="link"></span></a>
                        </div>
                        <div class="navItem">
                            <img src="../images/honeybee.jpg" alt="Tiny single honeybee."/>
                            <div class="title">Package Bees</div>
                            <a href="../stevesbees_home.php"><span class="link"></span></a>
                        </div>
                        <div class="navItem">
                            <img src="../images/honeybee.jpg" alt="Tiny single honeybee."/>
                            <div class="title">Supplies</div>
                            <a href="../order_supplies.php"><span class="link"></span></a>
                        </div>
                        <div class="navItem">
                            <img src="../images/honeybee.jpg" alt="Tiny single honeybee."/>
                            <div class="title">Honey</div>
                            <a href="../honey.php"><span class="link"></span></a>
                        </div>
                        <div class="navItem">
                            <img src="../images/honeybee.jpg" alt="Tiny single honeybee."/>
                            <div class="title">Services</div>
                            <a href="../services.php"><span class="link"></span></a>
                        </div>
                        <div class="navItem">
                            <img src="../images/honeybee.jpg" alt="Tiny single honeybee."/>
                            <div class="title">Harvest/Processing</div>
                            <a href="../harvest_n_processing.php"><span class="link"></span></a>
                        </div>
                        <div class="navItem">
                            <img src="../images/honeybee.jpg" alt="Tiny single honeybee."/>
                            <div class="title">Contact Us</div>
                            <a href="../contact_us.php"><span class="link"></span></a>
                        </div>
                    </div>
                    <!-- InstanceBeginEditable name="content" -->
                </div>
                <div class="mid_col">

                    <h1>Order Confirmation</h1>

<?php

    echo "<br><br><br>";
    print_r($_SESSION);
    echo "<br><br><br>";
    print_r($_POST);
    echo "<br><br><br>";
    print_r($_GET);

    if (empty($_SESSION) || empty($_POST))
    {
        echo 'Oops! You seemed to have reached this page in error, as your cart is currently empty.<br><br>Please visit the <a href="../order_supplies.php">Supplies page</a> or the <a href="../order_bees.php">Bees page</a>. Thanks!';
    }
    else
    {
        echo '
            <p>
                This is a confirmation of your shopping cart and order information. Please take a moment to review everything before the order goes through. If it all looks good, please hit the confirmation button below. Thanks again for shopping with us!
            </p>';

        $total = echoCart();
        echo "<script>var total = $total;</script>";
        echo "<div class=\"total\">Total: $$total</div>";

        $_SESSION['paymentInfo'] = array();
        foreach ($_POST as $key => $cardField)
            $_SESSION['paymentInfo'][$key] = htmlentities(strip_tags($cardField));
//Additional Order Information
        echo '
            <h3>Billing</h3>
            <p>
                '.$_SESSION['paymentInfo']['x_card_num'].' ('.$_SESSION['paymentInfo']['x_card_code'].') Exp: '.$_SESSION['paymentInfo']['x_exp_date'].'
                <br>
                '.$_SESSION['paymentInfo']['x_first_name'].' '.$_SESSION['paymentInfo']['x_last_name'].'
                <br>
                '.$_SESSION['paymentInfo']['x_address'].'
                <br>
                '.$_SESSION['paymentInfo']['x_city'].',
                '.$_SESSION['paymentInfo']['x_state'].'
                '.$_SESSION['paymentInfo']['x_zip'].'
            </p>
            <br>
            <h3>Shipping and Contact</h3>
            <p>
                '.$_SESSION['paymentInfo']['x_ship_to_first_name'].' '.$_SESSION['paymentInfo']['x_ship_to_last_name'].'
                <br>
                Email: '.$_SESSION['paymentInfo']['x_email'].'
                <br>
                Home Phone: '.$_SESSION['paymentInfo']['homePhone'].'
                <br>
                Cell: '.$_SESSION['paymentInfo']['cellPhone'].', texting? '.$_SESSION['paymentInfo']['textCapable'].'
                <br>
                Preferred Phone: '.$_SESSION['paymentInfo']['preferredPhone'].'
            </p>
            <p>
                <button type="submit" id="confirm" class="submit">Confirm, this information is accurate.</button>
            </p>
            ';
/*
            [x_card_num] => asfd
            [x_exp_date] => asdf
            [x_card_code] => asdf
            [x_first_name] => asf
            [x_last_name] => asdf
            [x_address] => asdf
            [x_city] => asdf
            [x_state] => AK
            [x_zip] => asfd
            [x_country] => US

            [x_ship_to_first_name] => asdf
            [x_ship_to_last_name] => asdf
            [homePhone] => asfd
            [preferredPhone] => home
            [cellPhone] => asdf
            [textCapable] => yes
            [x_email] => asdf
*/
        echo "<br><br><br>";
        print_r($_SESSION);
        echo "<br><br><br>";
        print_r($_POST);
        echo "<br><br><br>";
        print_r($_GET);
    }

?>

                </div>
                <div class="right_col">
                    <!-- InstanceEndEditable -->
                    <div id="SpryAccordion1" class="Accordion" tabindex="0">
                        <div class="AccordionPanel">
                            <div class="AccordionPanelTab">
                                <span class="accordion_340_tab">Alaska Wildflower Honey</span>
                            </div>
                            <div class="AccordionPanelContent">
                                <div class="acontent">
                                    <p>Alaska Wildflower Honey is a family-owned beekeeping operation in south-central Alaska. We supply honey, wax, pollen, and other bee products; services such as honey extracting and filtering; and other products such as packages of honeybees, and beekeeping supplies.</p>
                                </div>
                            </div>
                        </div>
                        <div class="AccordionPanel">
                            <div class="AccordionPanelTab">
                                <span class="accordion_340_tab">Steve's Bees</span>
                            </div>
                            <div class="AccordionPanelContent">
                                <div class="acontent">
                                    <p>Steve's Bees is a part of Alaska Wildflower Honey. We import and distribute packages of honeybees and beekeeping supplies to beekeepers in southern Alaska.</p>
                                </div>
                            </div>
                        </div>
                        <div class="AccordionPanel">
                            <div class="AccordionPanelTab">
                                <span class="accordion_340_tab">Contact Us</span>
                            </div>
                            <div class="AccordionPanelContent">
                                <div class="acontent">
                                    <p>
                                        <a href="mailto:steve@stevesbees.com">steve@stevesbees.com</a>
                                    </p>
                                    <p>
                                        Alaska Wildflower Honey<br>
                                        7449 S. Babcock Blvd<br>
                                        Wasilla, AK 99623
                                    </p>
                                    <p>
                                        (907) 892-6175
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="AccordionPanel">
                            <div class="AccordionPanelTab">
                                <span class="accordion_340_tab">Related Links</span>
                            </div>
                            <div class="AccordionPanelContent">
                                <div class="acontent">
                                    <ul>
                                        <li>
                                            <a href="http://www.bz-bee.com/aboutus.html">John Foster Apiaries</a>
                                            <p>John Foster is our supplier of bees. He has 15,000 beehives, and 17 people working for him, so he has a fairly large operation.</p>
                                        </li>
                                        <li>
                                            <a href="http://www.mannlakeltd.com/">Mann Lake Ltd</a>
                                            <p>Mann Lake Ltd., our beekeeping supplies supplier, sells many beekeeping products. Anything that you can think of that is used in beekeeping in any way, they've probably got it.</p>
                                        </li>
                                        <li>
                                            <a href="http://www.sababeekeepers.com/">SABA</a>
                                            <p>South-central Alaska Beekeepers Association (SABA) is the association for beekeepers of all ages in south-central Alaska.</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clear_both"></div>
            </div>
        </div>
        <div class="footerArea">
            <div class="container">
                <p>&copy; 2014 Alaska Wildflower Honey</p>
            </div>
        </div>
        <script type="text/javascript">
        <!--
            var SpryAccordion1 = new Spry.Widget.Accordion("SpryAccordion1", {useFixedPanelHeights:false, defaultPanel:-1});
        //-->
        </script>
        <!-- InstanceBeginEditable name="scripts" -->
        <script src="../scripts/jquery-1.10.2.js"></script>
        <script src="../scripts/checkout_form.js"></script>
        <!-- InstanceEndEditable -->
    </body>
<!-- InstanceEnd --></html>

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

?>
