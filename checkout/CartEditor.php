<?php //opening HTML
    $_TITLE_ = "Shopping Cart Editor - Alaska Wildflower Honey";
    $_STYLESHEETS_ = array("/assets/css/cartEditor.css", "/assets/css/fancyHRandButtons.css");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/header.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/ajax/cartEditorView.php');


    echo '<h1>Your Shopping Cart</h1>';

    if (empty($_SESSION))
    {
        echo '<p>
                Oops! You seemed to have reached this page in error, as your cart is currently empty.<br><br>Please visit the honeybee or beekeeping supplies store, available through the <a href="/stevesbees_home.php">stevesbees.com home page</a>, and find something that interests you. Thanks!
            </p>';
    }
    else
    {
        echo '<div id="cartEditorView">'.getEditorHTML().getEditorTotal().'</div>
            <form action="/checkout/1cart_checkout.php">
                <input type="submit" class="fancy" value="Proceed to Checkout">
            </form>';
    }



    $_JS_ = array("/assets/js/jquery-1.11.2.min.js",
        "/assets/js/jquery-ui-1.10.4.custom.min.js",
        "/assets/js/cartEditor.js");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/footer.php'); //closing HTML
?>
