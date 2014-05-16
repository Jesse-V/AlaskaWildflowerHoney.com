<?php //opening HTML
    $_REL_ = "";
    $_TITLE_ = "Order Packages & Queens - StevesBees.com";
    $_STYLESHEETS_ = array("stylesheets/fancyHRandButtons.css", "stylesheets/order_bees.css");
    require_once('common/header.php');
?>

<?php

    require_once('scripts/databaseConnect.secret');
    global $db;

    $result = $db->query("SELECT ID, price FROM bees");
    if (!$result)
        die("Failed to connect to database. ".$db->error);

    echo "<script>";

    $prices = array();
    while ($record = $result->fetch_assoc())
    {
        $ID  = $record['ID'];
        $price = $record['price'];
        $prices[$ID] = $price; //convert from MySQL to PHP array variable
        echo "var $ID = $price;"; //convert from MySQL to Javascript variables
    }

    echo "</script>";
?>

    <form action="checkout/CartManager.php" method="post" autocomplete="on" accept-charset="UTF-8">
        <h1>Ready to order bees?<br><span class="subtitle">You've come to the right place.</span></h1>

        <p>We supply two breeds of honeybees, each with their own distinct characteristics. Each package contains four pounds of clean honeybees. I've provided a short discussion below on bee types that I hope will help you choose the type of queen that will work best for you.  While the characteristics that are laid out here are generally how the traits of the queens go, beekeepers will tell you that each queen may not read the instruction manual and she may do a bit differently than what is outlined here. Since the two types of queens that are most readily available are the Italian and the Carniolan, those are the ones I will write about.
        </p>
        <p>I think that it is easier to understand the traits if you look at the root stock of where the bee comes from. Italian queens tend to brood well, no matter what. The large work force brings in a good crop during a nectar flow.  Italian bees are perhaps the most popular bee in commercial and hobby beekeeping.  They come from a Mediterranean climate and like big families.  Because they like big families, they have a reduced tendency to swarm.  Because they have a big brood nest they will also eat everything in sight and then go out to rob the surrounding countryside (including the neighboring hives) when there is a lack of nectar coming in.  The propensity to rob is perhaps their biggest fault because it can make the bees defensive if this type of activity is allowed to go on in the bee yard.  The Italian bees will brood right through a nectar dearth expecting that there will be more flowers blooming soon.  This means that they are not at all careful with their food and can even starve out because they fed all of the reserves to the developing larvae.
        </p>
        <p>If your management style has you skipping hive checks in the mid summer, or are drawing out foundation into comb, are predicting a warm summer, or have no intention on wintering your bees this is the one for you.
        </p>
        <p>Carniolan bees come from the mountains of Europe away from that nice Mediterranean climate.  Spring is short so they have to brood up quickly.  Summer is shorter than the one granted to their Italian cousins.  If they want to make new colonies they have to do it soon; so the tendency to raise a new queen and swarm is much more pronounced than the Italian.  They will forage in cooler weather.  When the nectar flow stops they don't know if the season is over or if it is just a dry spell.  The safest thing to do is to stop raising babies and conserve food so the queen shuts down.  This conservation of food helps make them a good choice for wintering but requires extra management in the summer to increase brood during nectar shortage.  Perhaps it is not in the best interest of the bees in the mountains to start a war over food during the short summer because it damages the health of all the hives.   Whatever the case the Carniolan bee has a low tendency to rob other hives and equipment.
        </p>
        <p>If your management style is regular hive checks for swarming, perhaps a bit of food during the build up period, or you have bees in a cooler climate,  or are thinking about wintering, don't want to worry about covering supers and frames during summer hive checks to prevent robbing this might be the choice.
        </p>
        <p>The Italian bee is popular in the Interior (Fairbanks) area. The Carniolan is popular on the Kenai Peninsula. I run about half and half here at Big Lake.</p>
        <p>Please review the choices below and make your choice. Following your selection, please provide the shipping and delivery information below. This will help expedite your order. Thank you for ordering online!</p>

        <div id="spacer"></div>

        <div class="breed">
            <span class="title">Italian Bees</span>
            <span class="desc sub"></span>
            <div class="add sub">
                <div class="preference">
                    <div class="images">
                        <img src="images/queen-bee.jpg" alt="Queen bee."/>
                    </div>
                    <div class="text">
                        <span>I'd like</span>
                        <input type="number" id="singleItalian" name="singleItalian" min="0" max="200" value="0">
                        <span>single-queen package(s) of Italians at $<?php echo $prices['singleItalian']; ?>/each.</span>
                    </div>
                </div>
                <div class="preference">
                    <div class="images">
                        <img src="images/queen-bee.jpg" alt="Queen bee."/>
                        <img src="images/queen-bee.jpg" alt="Queen bee."/>
                    </div>
                    <div class="text">
                        <span>I'd like</span>
                        <input type="number" id="doubleItalian" name="doubleItalian" min="0" max="200" value="0">
                        <span>double-queen package(s) of Italians at $<?php echo $prices['doubleItalian']; ?>/each.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="breed">
            <span class="title">Carniolan Bees</span>
            <span class="desc sub"></span>
            <div class="add sub">
                <div class="preference">
                    <div class="images">
                        <img src="images/queen-bee.jpg" alt="Queen bee."/>
                    </div>
                    <div class="text">
                        <span>I'd like</span>
                        <input type="number" id="singleCarni" name="singleCarni" min="0" max="200" value="0">
                        <span>single-queen package(s) of Carniolans at $<?php echo $prices['singleCarni']; ?>/each.</span>
                    </div>
                </div>
                <div class="preference">
                    <div class="images">
                        <img src="images/queen-bee.jpg" alt="Queen bee."/>
                        <img src="images/queen-bee.jpg" alt="Queen bee."/>
                    </div>
                    <div class="text">
                        <span>I'd like</span>
                        <input type="number" id="doubleCarni" name="doubleCarni" min="0" max="200" value="0">
                        <span>double-queen package(s) of Carniolans at $<?php echo $prices['doubleCarni']; ?>/each.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="queens">
            <span class="title">Just Queens</span>
            <span class="desc sub">Interested in just queen bees? Here you can order queens without packages, but these queens will be delivered on the same day as packages. If you are ordering packages, please note that packages include one or two queens, and the queens typically transport better this way.</span>
            <div class="add sub">
                <div class="preference text">
                    <span>I'd like</span>
                    <input type="number" name="ItalianQueens" min="0" max="200" value="0">
                    <span>separate Italian queens at $<?php echo $prices['ItalianQueens']; ?>/each.</span>
                </div>
                <div class="preference text">
                    <span>I'd like</span>
                    <input type="number" name="CarniQueens" min="0" max="200" value="0">
                    <span>separate Carniolan queens at $<?php echo $prices['CarniQueens']; ?>/each.</span>
                </div>
            </div>
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

        <div class="contact">
            <span class="title">Contact Information</span>
            <div class="sub">
                <p>
                    <label>First name:</label>
                    <input required type="text" name="firstName"/>
                    <label> Last name:</label>
                    <input required type="text" name="lastName"/>
                </p>
                <p>
                    <label>Phone numbers:</label><br>
                    <table id="phoneTable">
                        <tr>
                            <td>Home:</td>
                            <td><input type="text" name="homePhone"/></td>
                            <td><input type="radio" name="preferredPhone" value="home"/>Preferred</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Cell:</td>
                            <td><input type="text" name="cellPhone"/></td>
                            <td><input type="radio" name="preferredPhone" value="cell"/>Preferred</td>
                            <td><input type="checkbox" name="textCapable" value="yes"/>Text Capable</td>
                        </tr>
                    </table>
                </p>

                <p>
                    <label>Email address:</label>
                    <input required type="text" name="emailAddress"/>
                </p>
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
        <button type="submit" name="submit" class="submit" value="supplies">Need supplies, tools, or bee food?<br>Click here to save your order and visit the supplies store.</button>
        <button type="submit" name="submit" class="submit" value="checkout">Finished? Click here to proceed to checkout.</button>
    </form>

<?php
    $_JS_ = array("scripts/jquery-1.10.2.js", "scripts/order_bees.js");
    require_once('common/footer.php'); //closing HTML

    $db->close();
?>
