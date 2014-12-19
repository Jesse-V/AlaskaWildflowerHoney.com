<?php //opening HTML
    $_TITLE_ = "Shopping Cart Editor - Alaska Wildflower Honey";
    $_STYLESHEETS_ = array("/assets/css/cartEditor.css", "/assets/css/fancyHRandButtons.css");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/header.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/checkout/cart_help_functions.php');



    echo '<h1>Your Shopping Cart</h1>';

    if (empty($_SESSION))
    {
        echo '<p>
                Oops! You seemed to have reached this page in error, as your cart is currently empty.<br><br>Please visit the honeybee or beekeeping supplies store, available through the <a href="/stevesbees_home.php">stevesbees.com home page</a>, and find something that interests you. Thanks!
            </p>';
    }
    else
    {
        if (isset($_SESSION['supplies']))
        {
            echo '
                <table id="suppliesTable">
                    <tr>
                        <th><b>Beekeeping Supplies</b></th>
                        <th>Quantity</th>
                        <th>Cost</th>
                        <th></th>
                    </tr>';

            $items = $_SESSION['supplies']->getItems();
            foreach ($items as $key => $item)
            {
                $name = "$item->name_ $item->groupName_";

                echo '<tr>
                        <td>'.$name.'</td>
                        <td>'.$item->quantity_.'</td>
                        <td>$'.number_format($item->quantity_ * $item->price_, 2, '.', '').'</td>
                        <td><input type="button" name="'.$key.'" class="trash" value=""></td>
                    </tr>';
            }

            echo '</table>';
        }

        if (isset($_SESSION['beeOrder']))
        {
            echo '
                <table id="beesTable">
                    <tr>
                        <th><b>Packages of Honeybees</b></th>
                        <th>Quantity</th>
                        <th>Cost</th>
                        <th></th>
                    </tr>';

            $orderItems = $_SESSION['beeOrder']->getPackageOrder();
            foreach ($orderItems as $key => $item)
            {
                echo '<tr>
                        <td>'.$item['name'].'</td>
                        <td>'.$item['quantity'].'</td>
                        <td>$'.number_format($item['quantity'] * $item['price'], 2, '.', '').'</td>
                        <td><input type="button" name="'.$key.'" class="trash" value=""></td>
                    </tr>';
            }

            echo '</table>';
        }

        echo '
            <div class="total">Total: $'.getCart()['total'].'</div>
            <form action="/checkout/1cart_checkout.php">
                <input type="submit" class="fancy" value="Proceed to Checkout">
            </form>';
    }



    $_JS_ = array("/assets/js/jquery-1.11.2.min.js", "/assets/js/cartEditor.js");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/footer.php'); //closing HTML
?>
