<?php
	require_once('../anet_php_sdk/AuthorizeNet.php');
	require_once('../scripts/databaseConnect.secret');
	require_once('../scripts/helper_functions.php');
    require_once('authorizeNetVars.secret');

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
					if (empty($_GET) || !isset($_GET['rc']) || !isset($_GET['id']) || !isset($_GET['k']) || empty($_SESSION))
					{
						echo "<p>
								Oops! You appear to have reached this page in error. We cannot show you a receipt because not enough information was sent to this page. One possible explanation is that you have already completed the transaction. If you feel that you have reached this message in error, please contact us.</p>";
					}
                    else if ($_GET['rc'] == 1)
					{
						if ($_GET['k'] == hash("sha256", $md5_setting.$_GET['id']))
						{
							$total = echoCart();
							$total *= 1.025;
							$total = round($total, 2);

							echo "<div class=\"total\">+ 2.5% convenience fee</div>";
							echo "<div class=\"total\">Total: $$total</div>";

							$transID = htmlentities($_GET['id']);

							$_POST = $_GET;
							sendOrderEmail($_POST['x_email'], "credit/debit ".$_POST['x_card_type']."card: ".$_POST['x_account_number'].", transaction $transID", "$$total");

							echo "<p>
								Thank you! Your payment was successful. $".$_POST['x_amount']." was charged to your credit/debit card ending in ".$_POST['x_account_number']." (".$_POST['x_card_type']."), transaction ID $transID. Your order has been sent to us. You should also receive an email receipt, though you may also print this page for a physical copy if you prefer.
								</p>
								<p>
									Thank you for ordering online!
								</p>";

							unset($_SESSION);
							session_destroy();
						}
						else
						{
							echo "<p>
								Oops! The transaction signature key does not match the transaction ID. The most likely explanation is that you are not authorized to view this transaction. If you feel that you have reached this page in error, (such as during a transaction) please notify us by sending emails to <a href=\"mailto:victors@mtaonline.net?Subject=Transaction%20query%20error\" target=\"_top\">victors@mtaonline.net</a> and <a href=\"mailto:jvictors@jessevictors.com?Subject=Transaction%20query%20error\" target=\"_top\">jvictors@jessevictors.com</a> and we will try to address this issue promptly. We apologize for the inconvenience.
								</p>";
						}
					}
					else
					{
						echo "Sorry, Authorize.net was unable to fully process the transaction for the following reason: " . htmlentities($_GET['resp']);
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
	/*
	function echoTransactionDetails($transactionId)
	{
		global $api_login_id;
		global $transaction_key;

		//https://developer.authorize.net/api/transaction_details/
		define("AUTHORIZENET_API_LOGIN_ID", $api_login_id);
		define("AUTHORIZENET_TRANSACTION_KEY", $transaction_key);
		define("AUTHORIZENET_SANDBOX", false);

		// Get Settled Batch List
		$request = new AuthorizeNetTD;

		// Get Transaction Details
		print_r($request);
		$response = $request->getTransactionDetails($transactionId);
		print_r($response);
		echo $response->xml->transaction->transactionStatus;
	}*/
?>

<?php
	$db->close();
?>
