<?php //opening HTML
    $_TITLE_ = "Shopping Cart Editor - Alaska Wildflower Honey";
    $_STYLESHEETS_ = array("/assets/css/cartEditor", "/assets/css/cartTable.css");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/header.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/checkout/cart_help_functions.php');


    echo "<h1>Shopping Cart Editor</h1>";

    if (empty($_SESSION))
    {
        echo '<p>
                Oops! You seemed to have reached this page in error, as your cart is currently empty.<br><br>Please visit the honeybee or beekeeping supplies store, available through the <a href="/stevesbees_home.php">stevesbees.com home page</a>, and find something that interests you. Thanks!
            </p>';
    }
    else
    {
        $total = echoCart($_SESSION['supplies']);
        //echoDynamicForm($total, "3order_submit.php");
    }


    $_JS_ = array("/assets/js/jquery-1.11.2.min.js",
        "/assets/js/checkout_form.js");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/footer.php'); //closing HTML
    $db->close();




    $_JS_ = array();
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/footer.php'); //closing HTML
?>
