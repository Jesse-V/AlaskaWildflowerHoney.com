<?php
    //used to render the HTML for the cart in cartEditor.php

    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/checkout/cart_help_functions.php');
    if (!isset($_SESSION))
        session_start();


    if (!isset($_GET['action']))
    {}
    else if ($_GET['action'] == 'getHTML')
        echo getEditorHTML();
    else if ($_GET['action'] == 'getTotal')
        echo getEditorTotal();
    else if ($_GET['action'] == 'getAll')
    {
        header('Content-Type: application/json');
        echo json_encode(array('html' => getEditorHTML(), 'total' => getEditorTotal()));
    }



    function getEditorHTML()
    {
        $str = "";
        if (isset($_SESSION['supplies']))
        {
            $str .= '<table id="suppliesTable">
                    <tr>
                        <th><b>Beekeeping Supplies</b></th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th></th>
                    </tr>';

            $items = $_SESSION['supplies']->getItems();
            foreach ($items as $item)
            {
                $name = "$item->name_ $item->groupName_";

                $str .= '<tr>
                        <td>'.$name.'</td>
                        <td>'.$item->quantity_.'</td>
                        <td>$'.number_format($item->quantity_ * $item->price_, 2, '.', '').'</td>
                        <td><input type="button" name="'.$item->itemID_.'" class="trash" value=""></td>
                    </tr>';
            }

            $str .= '</table>';
        }

        if (isset($_SESSION['beeOrder']))
        {
            $str .= '<table id="beesTable">
                    <tr>
                        <th><b>Packages of Honeybees</b></th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th></th>
                    </tr>';

            $orderItems = $_SESSION['beeOrder']->getPackageOrder();
            foreach ($orderItems as $key => $item)
            {
                $str .= '<tr>
                        <td>'.$item['name'].'</td>
                        <td>'.$item['quantity'].'</td>
                        <td>$'.number_format($item['quantity'] * $item['price'], 2, '.', '').'</td>
                        <td><input type="button" name="'.$item['id'].'" class="trash" value=""></td>
                    </tr>';
            }

            $str .= '</table>';
        }

        return $str;
    }



    function getEditorTotal()
    {
        return '<div class="total">Total: $'.getCart()['total'].'</div>';
    }
?>
