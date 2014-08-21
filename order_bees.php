<?php //opening HTML
    $_REL_ = "";
    $_TITLE_ = "Order Packages & Queens - StevesBees.com";
    $_STYLESHEETS_ = array("assets/css/fancyHRandButtons.css",
        "assets/css/order_bees.css");
    require_once(__DIR__.'/assets/common/header.php');
    require_once(__DIR__.'/assets/php/databaseConnect.secret');
    global $db;

    $storeStatusSQL = $db->query("SELECT * FROM StoreStatus");
    if (!$storeStatusSQL)
        die("Failed to connect to database. ".$db->error);

    while ($record = $storeStatusSQL->fetch_assoc())
        $storeStatus[$record['Store']] = $record['Status'];

    if ($storeStatus['Bees'] == 0)
    {
        echo '<p>We have closed this store temporarily for the time being. Please check back later.</p>';

        $_JS_ = array("assets/js/jquery-1.11.1.min.js",
            "assets/js/jquery-ui-1.10.4.custom.min.js",
            "assets/js/order_bees.js");
        require_once(__DIR__.'/assets/common/footer.php'); //closing HTML
        $db->close();

        exit();
    }

    require_once(__DIR__.'/assets/php/classes/BeePrices.php');
    $beePrices = BeePrices::getInstance();

    //create PHP vars
    $SINGLE_PRICE = $beePrices->getSQPackagePrice();
    $DOUBLE_PRICE = $beePrices->getDQPackagePrice();
    $QUEEN_PRICE  = $beePrices->getQueenPrice();

    //create Javascript vars
    echo "
        <script>
            var singlePrice = $SINGLE_PRICE;
            var doublePrice = $DOUBLE_PRICE;
            var queenPrice  = $QUEEN_PRICE;
        </script>";
?>

    <form action="checkout/CartManager.php" method="post" autocomplete="on" accept-charset="UTF-8">
        <h1>Ready to order bees?<br><span class="subtitle">You've come to the right place.</span></h1>

        <p>
            Each package contains four pounds of clean honeybees. We supply two breeds, each with their own distinct characteristics. If you are new to the decision, please click the question below for a brief synopsis of the different types of honeybees. Once you have decided, please make your selection below. Thank you for ordering online!
        </p>
        <div id="breedFAQ">
            Which breed of honeybees is right for me?
        </div>
        <div id="breedWriteup">
            <p>
                If you are new, that's not a problem. I've provided a short discussion below on bee types that I hope will help you choose the type of queen that will work best for you.
            </p>
            <p>
                While the characteristics that are laid out here are generally how the traits of the queens go, beekeepers will tell you that each queen may not read the instruction manual and she may do a bit differently than what is outlined here. Since the two types of queens that are most readily available are the Italian and the Carniolan, those are the ones I will write about.
            </p>
            <p>
                I think that it is easier to understand the traits if you look at the root stock of where the bee comes from. Italian queens tend to brood well, no matter what. The large work force brings in a good crop during a nectar flow. For this reason, Italian bees are perhaps the most popular bee in commercial and hobby beekeeping.  They come from a Mediterranean climate and like big families. The preference of a larger family means that they have a reduced tendency to swarm, but a bigger brood nest also means that they will also eat everything in sight and then go out to rob the surrounding countryside (including the neighboring hives) when there is a lack of nectar coming in. The propensity to rob is perhaps their biggest fault because it can make the bees defensive if this type of activity is allowed to go on in the bee yard. The Italian bees will also brood right through a nectar dearth expecting that there will be more flowers blooming soon.  This means that they are not at all careful with their food and can even starve out because they fed all of the reserves to the developing larvae.
            </p>
            <p>
                <b>If your management style has you skipping hive checks in mid-summer, or are drawing out foundation into comb, are predicting a warm summer, or have no intention on wintering your bees then Italian bees is the one for you.</b>
            </p>
            <p>
                Carniolan bees, in contrast to the Italians, come from the mountains of Europe away from that nice Mediterranean climate. They are used to a short spring so they have to brood up quickly. Summer there is also shorter than the one granted to their Italian cousins. Therefore, if they want to make new colonies they have to do it soon; so the tendency to raise a new queen and swarm is much more pronounced than the Italian. They will forage in cooler weather. However, when the nectar flow stops they don't know if the season is over or if it is just a dry spell, so they prefer the safer move of shutting the queen down and stop raising brood to conserve food. This tendency helps make them a good choice for wintering but requires extra management in the summer to increase brood during nectar shortage. Perhaps it is not in the best interest of the bees in the mountains to start a war over food during the short summer because it damages the health of all the hives. Whatever the case, the Carniolan bee has a low tendency to rob other hives and equipment.
            </p>
            <p>
                <b>If your management style consists of regular hive checks for swarming, perhaps a bit of food during the build-up period, if you keep bees in a cooler climate, if you are thinking about wintering, or if you don't want to worry about covering supers and frames during summer hive checks to prevent robbing, the Carniolan breed might be the choice for you.</b>
            </p>
            <p>
                The Italian bee is popular in the Interior (Fairbanks) area. The Carniolan is popular on the Kenai Peninsula. I run about half and half here at Big Lake.
            </p>
        </div>

        <div id="spacer"></div>

        <div class="breed">
            <span class="title">Italian Bees</span>
            <span class="desc sub"></span>
            <div class="add sub">
                <div class="preference">
                    <div class="text">
                        <span>I'd like</span>
                        <input type="number" id="singleItalian" name="singleItalian" min="0" max="200" value="0">
                        <span>single-queen package(s) of Italians at $<?php echo $SINGLE_PRICE; ?>/each.</span>
                    </div>
                </div>
                <div class="preference">
                    <div class="text">
                        <span>I'd like</span>
                        <input type="number" id="doubleItalian" name="doubleItalian" min="0" max="200" value="0">
                        <span>double-queen package(s) of Italians at $<?php echo $DOUBLE_PRICE; ?>/each.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="breed">
            <span class="title">Carniolan Bees</span>
            <span class="desc sub"></span>
            <div class="add sub">
                <div class="preference">
                    <div class="text">
                        <span>I'd like</span>
                        <input type="number" id="singleCarni" name="singleCarni" min="0" max="200" value="0">
                        <span>single-queen package(s) of Carniolans at $<?php echo $SINGLE_PRICE; ?>/each.</span>
                    </div>
                </div>
                <div class="preference">
                    <div class="text">
                        <span>I'd like</span>
                        <input type="number" id="doubleCarni" name="doubleCarni" min="0" max="200" value="0">
                        <span>double-queen package(s) of Carniolans at $<?php echo $DOUBLE_PRICE; ?>/each.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="queens">
            <span class="title">Just Queens</span>
            <table>
                <tr>
                    <td class="images">
                        <img src="assets/images/queen_and_attendants.jpg" alt="Queen bee."/>
                    </td>
                    <td class="text">
                        <span class="desc sub">Interested in just queen bees? These queens come without packages of honeybees and will be delivered on the same day and to the same location as the regular packages. Keep in mind that packages include one or two queens, and the queens typically transport better this way.</span>
                        <div class="add sub">
                            <div class="preference text">
                                <span>I'd like</span>
                                <input type="number" name="ItalianQueens" min="0" max="200" value="0">
                                <span>separate Italian queens at $<?php echo $QUEEN_PRICE; ?>/each.</span>
                            </div>
                            <div class="preference text">
                                <span>I'd like</span>
                                <input type="number" name="CarniQueens" min="0" max="200" value="0">
                                <span>separate Carniolan queens at $<?php echo $QUEEN_PRICE; ?>/each.</span>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="pickup">
            <span class="title">Shipping/delivery destination point</span>
            <div class="point sub">
                <table>
                    <tr>
                        <td><input type="radio" name="pickupLoc" value="Anchorage"/>Anchorage</td>
                        <td><input type="radio" name="pickupLoc" value="Wasilla"/>Wasilla</td>
                        <td><input type="radio" name="pickupLoc" value="Palmer"/>Palmer</td>
                        <td><input type="radio" name="pickupLoc" value="Soldotna"/>Soldotna</td>
                        <td><input type="radio" name="pickupLoc" value="Homer"/>Homer</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><input type="radio" name="pickupLoc" value="Eagle River"/>Eagle River</td>
                        <td><input type="radio" name="pickupLoc" value="Big Lake"/>Big Lake</td>
                        <td><input type="radio" name="pickupLoc" value="Healy"/>Healy</td>
                        <td><input type="radio" name="pickupLoc" value="Nenana"/>Nenana</td>
                        <td><input type="radio" name="pickupLoc" value="Fairbanks"/>Fairbanks</td>
                        <td><input type="radio" name="pickupLoc" value="Other"/>Other</td>

                    </tr>
                </table>
            </div>
            <div id="transCharge" class="sub">
            <br>
            </div>
        </div>

        <div class="notes">
            <span class="title">Special Notes</span>
            <textarea class="sub" name="notes"></textarea>
        </div>

        <hr class="fancy">

        <div class="summary">
            <table>
                <tr>
                    <td>Subtotal for items on this page:</td>
                    <td>$<span id="subtotal">0.00</span></td>
                </tr>
                <tr>
                    <td>Additional transportation charges:</td>
                    <td>$<span id="transTotal">0.00</span></td>
                </tr>
                <tr>
                    <td>Total for items on this page:</td>
                    <td>$<span id="total">0.00</span></td>
                </tr>
            </table>
        </div>

        <input type="hidden" name="format" value="bees"/>

        <?php
            if ($storeStatus['Supplies'] == 1)
            {
                echo '<button type="submit" name="submit" class="submit" value="supplies">Need supplies, tools, or bee food?<br>Click here to save your order and visit the supplies store.</button>';
            }
        ?>

        <button type="submit" name="submit" class="submit" value="checkout">Finished? Click here to proceed to checkout.</button>
    </form>

<?php
    $_JS_ = array("assets/js/jquery-1.11.1.min.js",
        "assets/js/jquery-ui-1.10.4.custom.min.js",
        "assets/js/order_bees.js");
    require_once(__DIR__.'/assets/common/footer.php'); //closing HTML

    $db->close();
?>
