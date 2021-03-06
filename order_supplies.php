<?php //opening HTML
    $_TITLE_ = "Beekeeping Supplies";
    $_STYLESHEETS_ = array("/assets/css/fancyHRandButtons.css",
        "/assets/css/order_supplies.css");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/header.php');
    $_SESSION['customer'] = true;

    require_once("assets/CommonMark.php");
    use League\CommonMark\CommonMarkConverter;
    $converter = new CommonMarkConverter();
?>

    <div id="introLeft">
    <?php
        $filename = "text/supplies/top_introduction.txt";
        $contents = fread(fopen($filename, "r"), filesize($filename));
        echo $converter->convertToHtml($contents);
    ?>
    </div>

    <div id="introRight">
        <img src="/assets/images/Bee boxes on pallet.jpg" alt="Bee boxes on a pallet, courtesy Flickr."/>
        <div class="attribute">
            by Jessica Reeder, courtesy <a href="https://www.flickr.com/photos/32917625@N02/3614389353">Flickr</a> & <a href="https://commons.wikimedia.org/wiki/File:Bee_boxes_at_an_organic_farm.jpg">Wikimedia</a>
        </div>
    </div>

<?php   //inject body
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/orderSuppliesBody.php');
?>

    <table id="footerNavigation">
        <tr>
            <?php
                //if the bee store is open, show a link to it
                if ($storeData['Bees']['Status'] == 1)
                {
                    echo '  <td>
                                <form action="/order_bees.php">
                                    <input type="submit" class="fancy" value="Add Bees or Queens">
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

<?php
    $_JS_ = array("/assets/js/cartPreviewUpdater.js", "/assets/js/order_supplies.js");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/footer.php'); //closing HTML
    $db->close();

?>
