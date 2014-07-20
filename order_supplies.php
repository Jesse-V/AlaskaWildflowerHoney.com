<?php //opening HTML
    $_REL_ = "";
    $_TITLE_ = "Beekeeping Supplies";
    $_STYLESHEETS_ = array("assets/css/fancyHRandButtons.css",
        "assets/css/order_supplies.css");
    require_once(__DIR__.'/assets/common/header.php');
?>

    <form action="checkout/CartManager.php" method="post" autocomplete="on" name="frmProduct" id="frmProduct" accept-charset="UTF-8">
        <h3>Supplies Store</h3>
        <p>We offer a variety of beekeeping products, ranging from common items such as beehive components, tools, and processing equipment to rarer and specialty items. We carry primarily Mann Lake products, as well as some of our own. We are the largest distributor of beekeeping supplies in the state of Alaska. We hope you will find this store efficient and convenient.</p>

<?php

    require_once(__DIR__.'/assets/php/databaseConnect.secret');
    require_once(__DIR__.'/assets/php/suppliesPrinter.php');
    global $db;

    $sectionsSQL = $db->query("SELECT * FROM SuppliesSections");
    if (!$sectionsSQL)
        die("Failed to connect to database. ".$db->error);

    $groups = queryGroups();

    while ($sectionRecord = $sectionsSQL->fetch_assoc())
        printSection($sectionRecord, $groups);

    $sectionsSQL->close();
?>

        <hr class="fancy">

        <div id="pickupPoint">
            <p>
                We generally don't do mail order for supplies. Instead, our primary distribution point is <a href="contact_us.php">out of our house in Big Lake</a>. Ordering online allows us to gather your order and have it ready to be picked up at <a href="contact_us.php">in Big Lake</a> at your convenience.
            </p>
            <p>
                If you wish to have your items brought to a local beekeeping meeting, into your area, please allow amble time for us to do that. Although we do our best to satisfy all the needs of all of our clients, our schedule may not allow us to gather materials on the same day as beekeeping meetings. When we receive an order, we will put your items on the shelf, sorted by location. Therefore, for example when we make a trip into Anchorage we can grab all of the Anchorage items at once. It will be important that you are able to meet us on our trips to town as it is impractical to deliver directly to your door. All items that we take into town and especially to the beekeeping meetings must be pre-paid.
            </p>

            <p class="options">
                <div class="option">
                    <input checked type="radio" name="pickupLoc" value="Big Lake"/>I will pick up my supplies at Big Lake.<br>
                </div>
                <div class="option">
                    <input type="radio" name="pickupLoc" value="at the bee meeting"/>I will pick these up at the next SABA beekeepers meeting.
                </div>
                <!--
                <div class="option">
                    <input type="radio" name="pickupLoc" value="along with the bee shipment"/>To be picked up with the spring bee shipment.
                </div>
                -->
            </p>
        </div>

        <div class="summary">
            Total for items on this page: $<span id="total">0.00</span>
        </div>

        <input type="hidden" name="format" value="supplies"/>

        <!--
        <p>
            <b>We are currently making change to our checkout process, so we have temporarily disabled it. We should be finished in approximately one hour. Please check back then.</b>
        </p>
        -->

        <!--<input type="submit" name="submit" id="moreBtn" value="Need bees or queens? Click here to save your order and visit the bees page."/>-->
        <input type="submit" name="submit" id="submitBtn" value="Finished? Click here to proceed to checkout."/>

    </form>

<?php
//assets/js/jquery-1.11.1.min.js
$_JS_ = array("assets/js/jquery-1.11.1.min.js", "temp/jquery-ui.js", "assets/js/order_supplies.js");
require_once(__DIR__.'/assets/common/footer.php'); //closing HTML
$db->close();

?>
