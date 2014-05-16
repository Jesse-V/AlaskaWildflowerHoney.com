<?php
    session_start();
?>

<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/Main.dwt" codeOutsideHTMLIsLocked="false" -->
    <head>
        <!-- InstanceBeginEditable name="doctitle" -->
        <title>Card Receipt - AlaskaWildflowerHoney.com</title>
        <!-- InstanceEndEditable -->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" href="../stylesheets/main.css" />
        <script type="text/javascript" src="../SpryAssets/SpryAccordion.js"></script>
        <link href="../SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css" />
        <!-- InstanceBeginEditable name="head" -->
        <link rel="stylesheet" type="text/css" href="../stylesheets/cartTable.css" />
        <link rel="stylesheet" type="text/css" href="../stylesheets/card_receipt.css" />
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
                    <h1>Your receipt</h1>

<?php
    if (empty($_GET) || !isset($_GET['rc']) || !isset($_GET['hash']) || empty($_SESSION))
    {
        echo "
            <p>
                Oops! We cannot show you a receipt because not enough information was sent to this page. One possible explanation is that you have already completed the transaction. If you feel that you have reached this message in error, please notify us by sending an email to <a href=\"mailto:jvictors@jessevictors.com?Subject=Receipt%20error\" target=\"_top\">jvictors@jessevictors.com</a> and we will try to address this issue promptly. We apologize for the inconvenience.
            </p>";
    }
    else if ($_GET['rc'] == 1)
    {
        if ($_GET['hash'] == hash("sha256", $md5_setting.$_GET['rc'].$_GET['id'].$md5_setting);)
        {
            echo "
                <p>
                    Thank you! Your payment (transaction ".$_GET['id'].") was successful. Your order has been sent to us and we will process it shortly. Please check your email for receipts.
                </p>
                <p>
                   Thanks for ordering online!
                </p>";

            unset($_SESSION);
            session_destroy();
        }
        else
        {
            echo "<p>
                Oops! Some information sent to this page didn't make sense. This should not happen normally. However, if you feel that you have reached this page in error, (such as during a transaction) please notify us by sending an email to <a href=\"mailto:jvictors@jessevictors.com?Subject=Receipt%20error\" target=\"_top\">jvictors@jessevictors.com</a> and we will try to address this issue promptly. We apologize for the inconvenience.
                </p>";
        }
    }
    else
    {
        echo "Oops! Something went wrong during the transaction. Authorize.net was unable to fully process your card for the following reason: ".htmlentities($_GET['resp']);
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
        <!-- InstanceEndEditable -->
    </body>
<!-- InstanceEnd --></html>

<?php
    $db->close();
?>
