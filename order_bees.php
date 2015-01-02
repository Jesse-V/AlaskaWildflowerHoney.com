<?php //opening HTML
    $_TITLE_ = "Order Packages & Queens - StevesBees.com";
    $_STYLESHEETS_ = array("/assets/css/fancyHRandButtons.css",
        "/assets/css/order_bees.css");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/header.php');
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

<?php //inject body
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/orderBeesBody.php');
?>

    <table id="footerNavigation">
        <tr>
            <?php
                //if the supply store is open, show a link to it
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
        <p class="itemsAdded">
            These items have been added to your shopping cart.
        </p>
    </table>
    <div id="footerPic">
        <img src="/assets/images/Apis%20mellifera%2001.jpg" alt="A honeybee visiting a purple flower, courtesy Wikimedia."/>
        <div class="attribute">
            by Jerzy Strzelecki, courtesy <a href="https://commons.wikimedia.org/wiki/File:Apis_mellifera(js)01.jpg">Wikimedia</a>
        </div>
    </div>

<?php
    $_JS_ = array("/assets/js/jquery-ui-1.10.4.custom.min.js",
        "/assets/js/cartPreviewUpdater.js", "/assets/js/order_bees.js");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/footer.php'); //closing HTML

    $db->close();
?>
