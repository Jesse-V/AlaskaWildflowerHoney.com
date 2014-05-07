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
		<title>Checkout - StevesBees.com</title>
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

                	<h1>Checkout</h1>

                    <?php
						$total = echoCart();
                        echo "<script>var total = $total;</script>";
						echo "<div class=\"total\">Total: $$total</div>";
					?>

                    <!--
                    <h2><b> Alert: we are currently doing some maintenance on the payment system. Please wait on proceeding through checkout until we have finished, so check back later. Thank you. </b></h2>
                    -->

                    <p>Welcome to the checkout. Please complete your order by providing the information below. You can purchase the items in your cart using most major credit/debit cards or by check. For online payment, we use Authorize.net, a popular payment gateway provider. They are compliant with the Payment Card Industry Data Security Standard (PCI DSS) and provide strong SSL certificates to protect your payment information. The SSL certificate that we use on this website uses some of the strongest cryptography and encryption procedures available to protect your information as well.</p>
                    <p>Electronic payment is nearly instantaneous and allows us to process your order quickly, but carries a 2.5% convenience fee to cover the costs of the payment service. Alternatively, you may choose to pay via check, which avoids the 2.5% fee. However, this requires you to mail in your payment manually. Please choose your method of payment below, and then fill out any forms that appear. Thank you for shopping with us!
                    </p>

                    <div class="spacer"></div>

                    <div id="paymentChoice">
                        <button type="submit" id="payOnline" class="submit">Pay online with a credit or debit card.</button>
                        <button type="submit" id="payCheck" class="submit">Pay using check</button>
                    </div>

                    <div id="dynamic">
                    	<h1>YOU SHOULD NOT NORMALLY SEE THIS. Please enable Javascript or update your browser.</h1>

                        <form id="cardForm" method="post" action="<?php echo AuthorizeNetDPM::LIVE_URL ?>">
                            <p>Please fill out the form below and submit when finished. We need your contact information for entering your order into our system, and your credit card information is used for expedited payments.</p>
                            <h3 id="billingInfo">Billing Information</h3>

                            <?php
                                $target = "https://www.alaskawildflowerhoney.com/checkout/relay_response.php";
                                $fp_sequence = "123"; //invoice number
								$convenience_fee = 1.025;
								$total *= $convenience_fee;
                                echo getCardFields($total, $fp_sequence, $target, $api_login_id, $transaction_key);
                            ?>
                        </form>

                        <form id="checkForm" method="post" action="check_receipt.php">
                            <input type="hidden" name="paymentMethod" value="checkOrCash">
                            <p>
                                Please send check to:<br>
                                Alaska Wildflower Honey<br>
                                7449 S. Babcock Blvd<br>
                                Wasilla, AK 99623
                            </p>
                            <p>
                            	Please press the button below to complete your order. You will be shown a printable receipt, and your order will be sent to us.
                            </p>
                        </form>

                        <div id="commonFormInfo">
                        	<div id="recipientInfo">
                                <h3 class="title">Recipient Address</h3>
                                <span class="subtitle">Who are you ordering for? We use this information to enter your order into our system, or update your order from years past.</span>
                            </div>

                            <fieldset>
                                <div>
                                    <label>First Name(s)</label>
                                    <input required type="text" class="text" size="25" name="x_ship_to_first_name"></input>
                                </div>
                                <div>
                                    <label>Last Name</label>
                                    <input required type="text" class="text" size="20" name="x_ship_to_last_name"></input>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div>
                                    <label>Phone numbers. If you have both cell and home numbers, please list both and click the "preferred" button accordingly.</label><br>
                                    <table id="phoneTable">
                                        <tr>
                                            <td>Home:</td>
                                            <td><input type="text" class="text" name="homePhone"/></td>
                                            <td><input type="radio" name="preferredPhone" value="home"/>Preferred</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Cell:</td>
                                            <td><input type="text" class="text" name="cellPhone"/></td>
                                            <td><input type="radio" name="preferredPhone" value="cell"/>Preferred</td>
                                            <td><input type="checkbox" name="textCapable" value="yes"/>Text Capable</td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div>
                                    <label>Email address:</label>
                                    <input required type="text" class="text" size="30" name="x_email"/>
                                </div>
                            </fieldset>

                            <input type="hidden" name="x_description" value="<?php echo getOrderReceiptStr(); ?>"/>

                            <input type="submit" value="Checkout and Complete Purchase" class="submit buy"/>
                        </div>
                    </div>
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
	$db->close();
?>
