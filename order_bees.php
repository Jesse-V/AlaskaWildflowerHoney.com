<?php //opening HTML
    $_TITLE_ = "Order Packages & Queens - StevesBees.com";
    $_STYLESHEETS_ = array("/assets/css/fancyHRandButtons.css",
        "/assets/css/order_bees.css");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/header.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/databaseConnect.secret');
    global $db;

    $storeStatusSQL = $db->query("SELECT * FROM StoreStatus");
    if (!$storeStatusSQL)
        die("A fatal database issue was encountered in orderBees.php, storeStatus query. Specifically, ".$db->error);

    while ($record = $storeStatusSQL->fetch_assoc())
        $storeData[$record['Store']] = $record;

    if ($storeData['Bees']['Status'] == 0)
    {
        echo '<p>'.$storeData['Bees']['CloseText'].'</p>';

        $_JS_ = array("/assets/js/jquery-ui-1.10.4.custom.min.js",
            "/assets/js/order_bees.js");
        require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/footer.php'); //closing HTML
        $db->close();

        exit();
    }

    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/classes/BeePrices.php');
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

    <div id="introLeft">
        <h1>Ready to order bees?<br><span class="subtitle">You've come to the right place.</span></h1>
    </div>
    <div id="introRight">
        <img src="/assets/images/Apis%20mellifera%2008.jpg" alt="A honeybee visiting a pink flower, courtesy Wikimedia."/>
        <div class="attribute">
            by Jerzy Strzelecki, courtesy <a href="https://commons.wikimedia.org/wiki/File:Apis_mellifera(js)08.jpg">Wikimedia</a>
        </div>
    </div>

    <p>
        Each package contains four pounds of clean honeybees. We supply two breeds, each with their own distinct characteristics. If you are new to the decision, please click the question below for a brief synopsis of the different types of honeybees. Once you have decided, please make your selection below. Thank you for ordering online!
    </p>

    <div id="breedFAQ">
        Which breed of honeybees is right for me?
    </div>
    <div id="breedWriteup">
        <p class="intro">
            If you are new, that's not a problem. I've provided a short outline below on bee types that I hope will help you choose between two common breeds of honeybees. While the characteristics that are generally how the traits of the breed go, not every queen may get the message and the hive may be a bit differently than what is outlined here.
        </p>
        <div class="breedDesc">
            <p class="breedIntro">
                Italian bees come from a Mediterranean climate and like big families.
            </p>
            <ul>
                <li><b>They tend to brood well, no matter what.</b> The large work force brings in a good crop during a nectar flow. For this reason, Italian bees are perhaps the most popular bee in commercial and hobby beekeeping.</li>
                <li><b>They have a reduced tendency to swarm.</b></li>
                <li><b>They have large food consumption</b> and will eat everything in sight and then go out to rob the surrounding countryside (including the neighboring hives) when there is a lack of nectar coming in. This is perhaps their biggest fault because it can make them defensive in the bee yard.</li>
                <li><b>They will also brood right through a nectar famine</b>, expecting that there will be more flowers blooming soon. This means that they are not careful with their food and can even starve out because they fed all of the reserves to the developing larvae.
            </ul>
            <div class="conclusion">
                Therefore,
                <ol>
                    <li>If your management style has you skipping hive checks in mid-summer, or</li>
                    <li>If you are are drawing out foundation into comb, or</li>
                    <li>If are predicting a warm summer.</li>
                </ol>
                then Italian bees might be the ones for you.
            </div>
        </div>
        <div class="breedDesc">
            <p class="breedIntro">
                Carniolan bees, in contrast to the Italians, come from the mountains of Europe.
            </p>
            <ul>
                <li><b>They are used to a short spring</b> so they want to brood up quickly. Summer there is also shorter than the one granted to their Italian cousins.</li>
                <li><b>They have a pronounced tendency to raise a new queen and swarm.</b></li>
                <li><b>They will forage in cooler weather.</b></li>
                <li><b>During nectar feminines, they tend to shut the queen down and stop raising brood to conserve food.</b> They don't know if the season is over or if it is just a dry spell, so this is the safer move.</li>
                <li><b>They are a good choice for wintering.</b></li>
                <li><b>They require extra management and feeding in the summer</b> to increase brood during nectar shortage.</li>
                <li><b>They have a low tendency to rob</b> other hives and equipment.</li>
            </ul>
            <div class="conclusion">
                Therefore,
                <ol>
                    <li>If your management style consists of regular hive checks for swarming, perhaps giving a bit of food during the build-up period, or</li>
                    <li>If you keep bees in a cooler climate, or</li>
                    <li>If you are thinking about wintering, or</li>
                    <li>If you don't want to worry about covering supers and frames to prevent robbing,</li>
                </ol>
                then the Carniolan breed might be the choice for you.
            </div>
        </div>
        <p class="intro">
            The Italian bee is popular in the Interior (Fairbanks) area. The Carniolan is popular on the Kenai Peninsula. I run about half and half here at Big Lake.
        </p>
    </div>

    <div id="spacer"></div>

    <table class="order">
        <tr>
            <th>Package Type</th>
            <th>Price</th>
            <th></th>
        </tr>
        <tr>
            <td>Italian single-queen package</td>
            <td>$<?php echo $SINGLE_PRICE; ?></td>
            <td><input type="number" id="singleItalian" name="singleItalian" min="0" max="200" value="0"></td>
        </tr>
        <tr>
            <td>Italian double-queen package</td>
            <td>$<?php echo $DOUBLE_PRICE; ?></td>
            <td><input type="number" id="doubleItalian" name="doubleItalian" min="0" max="200" value="0"></td>
        </tr>
        <tr>
            <td>Carniolan single-queen package</td>
            <td>$<?php echo $SINGLE_PRICE; ?></td>
            <td><input type="number" id="singleCarni" name="singleCarni" min="0" max="200" value="0"></td>
        </tr>
        <tr>
            <td>Carniolan double-queen package</td>
            <td>$<?php echo $DOUBLE_PRICE; ?></td>
            <td><input type="number" id="doubleCarni" name="doubleCarni" min="0" max="200" value="0"></td>
        </tr>
    </table>

    <table id="queenDesc">
        <tr>
            <td>
                <img src="/assets/images/queen_and_attendants.jpg" alt="Queen bee."/>
            </td>
            <td>
                <div class="sectionTitle">Queen Bees</div>
                <p>Interested in just queen bees? These queens come without packages of honeybees and will be delivered along with the regular packages. Keep in mind that packages include one or two queens, and the queens typically transport better this way.</p>
            </td>
        </tr>
    </table>

    <table class="order">
        <tr>
            <th>Individual Queen Breed</th>
            <th>Price</th>
            <th></th>
        </tr>
        <tr>
            <td>Separate Italian queen</td>
            <td>$<?php echo $QUEEN_PRICE; ?></td>
            <td><input type="number" name="ItalianQueens" min="0" max="200" value="0"></td>
        </tr>
        <tr>
            <td>Separate Carniolan queen</td>
            <td>$<?php echo $QUEEN_PRICE; ?></td>
            <td><input type="number" name="CarniQueens" min="0" max="200" value="0"></td>
        </tr>
    </table>

    <div class="sectionTitle pickupTitle">Pickup Location</div>
    <table class="pickup">
        <tr>
            <th>Mat-Su Valley</th>
            <th>Kenai Peninsula</th>
            <th>Central Alaska</th>
        </tr>
        <tr>
            <td>
                <div class="point">
                    <input type="radio" name="pickupLoc" value="Anchorage" checked/>
                    <label>Anchorage</label>
                </div>
                <div class="point">
                    <input type="radio" name="pickupLoc" value="Wasilla"/>
                    <label>Wasilla</label>
                </div>
                <div class="point">
                    <input type="radio" name="pickupLoc" value="Palmer"/>
                    <label>Palmer</label>
                </div>
                <div class="point">
                    <input type="radio" name="pickupLoc" value="Eagle River"/>
                    <label>Eagle River</label>
                </div>
                <div class="point">
                    <input type="radio" name="pickupLoc" value="Big Lake"/>
                    <label>Big Lake</label>
                </div>
            </td>
            <td>
                <div class="point">
                    <input type="radio" name="pickupLoc" value="Homer"/>
                    <label>Homer</label>
                </div>
                <div class="point">
                    <input type="radio" name="pickupLoc" value="Soldotna"/>
                    <label>Soldotna</label>
                </div>
                <div class="point">
                    <input type="radio" name="pickupLoc" value="Other"/>
                    <label>(Other)</label>
                </div>
            </td>
            <td>
                <div class="point">
                    <input type="radio" name="pickupLoc" value="Fairbanks"/>
                    <label>Fairbanks</label>
                </div>
                <div class="point">
                    <input type="radio" name="pickupLoc" value="Healy"/>
                    <label>Healy</label>
                </div>
                <div class="point">
                    <input type="radio" name="pickupLoc" value="Nenana"/>
                    <label>Nenana</label>
                </div>
                <div class="point">
                    <input type="radio" name="pickupLoc" value="Other"/>
                    <label>(Other)</label>
                </div>
            </td>
        </tr>
    </table>
    <div id="transCharge">
        <br>
    </div>

    <div class="notes">
        <span class="title">Special Notes</span>
        <textarea name="notes"></textarea>
    </div>

    <hr class="fancy">

    <div class="summary">
        <table>
            <tr>
                <td>Subtotal for items on this page:</td>
                <td>$<span id="beeSubtotal">0.00</span></td>
            </tr>
            <tr>
                <td>Additional transportation charges:</td>
                <td>$<span id="transTotal">0.00</span></td>
            </tr>
            <tr>
                <td>Total for items on this page:</td>
                <td>$<span id="beeTotal">0.00</span></td>
            </tr>
        </table>
    </div>

    <table id="footerNavigation">
        <tr>
            <?php
                if ($storeData['Supplies']['Status'] == 1)
                {
                    echo '
                        <td>
                            <form action="/order_supplies.php">
                                <input type="submit" class="fancy" value="Add Beekeeping Supplies">
                            </form>
                        </td>';
                }
            ?>
            <td>
                <form action="/checkout/1cart_checkout.php">
                    <input type="submit" class="fancy" value="Proceed to Checkout">
                </form>
            </td>
        </tr>
    </table>

    <div id="footerPic">
        <img src="/assets/images/Apis%20mellifera%2001.jpg" alt="A honeybee visiting a purple flower, courtesy Wikimedia."/>
        <div class="attribute">
            by Jerzy Strzelecki, courtesy <a href="https://commons.wikimedia.org/wiki/File:Apis_mellifera(js)01.jpg">Wikimedia</a>
        </div>
    </div>

<?php
    $_JS_ = array("/assets/js/jquery-ui-1.10.4.custom.min.js",
        "/assets/js/order_bees.js");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/footer.php'); //closing HTML

    $db->close();
?>
