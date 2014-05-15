customer confirms their order here, they just click OK if everything looks good
Everything they entered on the site is listed here in readable format

<!--<input type="hidden" name="x_description" value="<?php echo getOrderReceiptStr(); ?>"/>-->


<?php

print_r($_SESSION);

function getOrderReceiptStr()
{
    $str = "";

    $beeOrder = $_SESSION['beeOrder'];

    if (isset($beeOrder['singleItalian']))
        $str .= $beeOrder['singleItalian']." I., ";
    if (isset($beeOrder['doubleItalian']))
        $str .= $beeOrder['doubleItalian']." II., ";

    if (isset($beeOrder['singleCarni']))
        $str .= $beeOrder['singleCarni']." C., ";
    if (isset($beeOrder['doubleCarni']))
        $str .= $beeOrder['doubleCarni']." CC., ";

    if (isset($beeOrder['ItalianQueens']))
        $str .= $beeOrder['ItalianQueens']." I. queens, ";
    if (isset($beeOrder['CarniQueens']))
        $str .= $beeOrder['CarniQueens']." C. queens, ";

    $supplyInfo = querySuppliesTable();
    foreach ($_SESSION['supplies'] as &$item)
    {
        $name     = $supplyInfo[$item['id']]['name'];
        $quantity = $item['quantity'];
        $str .= "$quantity $name, ";
    }

    return $str;
}

?>
