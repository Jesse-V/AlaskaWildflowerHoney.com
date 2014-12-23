<?php

function queryGroups()
{
    global $db;

    $groupSQL = $db->query("SELECT * FROM SuppliesItemGroups");
    if (!$groupSQL)
        die("A fatal database issue was encountered in suppliesPrinter.php, Groups query. Specifically, ".$db->error);

    $groups = array();
    while ($record = $groupSQL->fetch_assoc())
        $groups[$record['ID']] = $record;

    $groupSQL->close();
    return $groups;
}


function queryItems($sectionID)
{
    global $db;

    $itemSQL = $db->query("SELECT * FROM Supplies WHERE sectionID=$sectionID ORDER BY itemID");
    if (!$itemSQL)
        die("A fatal database issue was encountered in suppliesPrinter.php, Items query. Specifically, ".$db->error);

    $items = array();
    while ($record = $itemSQL->fetch_assoc())
        array_push($items, $record);

    $itemSQL->close();
    return $items;
}


function groupItems($items, $groups)
{
    $groupedItems = array();
    foreach ($groups as $groupID => $group)
    {
        $groupedItems[$groupID] = array();
        foreach ($items as $item)
            if ($item['groupID'] == $groupID)
                array_push($groupedItems[$groupID], $item);
    }

    return $groupedItems;
}


function queryInventoryStatus()
{
    global $db;

    $statusSQL = $db->query("SELECT * FROM InventoryStatus");
    if (!$statusSQL)
        die("A fatal database issue was encountered in suppliesPrinter.php, Status query. Specifically, ".$db->error);

    $invStatus = array();
    while ($record = $statusSQL->fetch_assoc())
        $invStatus[$record['ID']] = $record['status'];

    $statusSQL->close();
    return $invStatus;
}


function printSection($sectionRecord, $groups)
{
    echo '
    <div class="subtitle">'.$sectionRecord['name'].'</div>
    <div class="sectionDesc">'.$sectionRecord['description'].'</div>
    <table>
        <tr>
            <th></th>
            <th>Name</th>
            <th>Price per unit</th>
            <th></th>
        </tr>
        ';

    $items = queryItems($sectionRecord['ID']);
    $groupedItems = groupItems($items, $groups);
    $invStatus = queryInventoryStatus();

    foreach ($items as $item)
    {
        if ($item['groupID'] > 0)
        { //part of a group
            if (!empty($groupedItems[$item['groupID']]))
            { //part of a valid group and group hasn't already been printed

                echo '
                    <tr class="group'.$item['groupID'].'">
                        <td></td>
                        <td>'.$groups[$item['groupID']]['name'];

                $desc = $groups[$item['groupID']]['description'];
                if (strlen($desc) > 0)
                    echo '<div class="description">'.$desc.'</div>';

                echo '  </td>
                        <td><span class="dropdownNotice">Types & Sizes</span></td>
                        <td></td>
                    </tr>';

                foreach ($groupedItems[$item['groupID']] as $subItem)
                    echoItem($subItem, $invStatus, true);

                unset($groupedItems[$item['groupID']]);
            }
        }
        else
            echoItem($item, $invStatus);
    }

    echo '
        </table>';
}


function echoItem($item, $invStatus, $subItem = false)
{
    $quantity = 0; //zero, unless the customer has previously selected it
    if (isset($_SESSION['supplies']))
        foreach ($_SESSION['supplies']->getItems() as $sessionItem)
            if ($sessionItem->itemID_ == $item['itemID'])
                $quantity = $sessionItem->quantity_;

    if ($subItem)
    {
        if ($quantity > 0) //if so, show rather than hide (issue #43)
            echo '<tr class="subItem subItem'.$item['groupID'].'">';
        else
            echo '<tr class="subItem subItem'.$item['groupID'].'" style="display: none;">';

        echo '<td></td>
                <td>
                    <div class="subItemName">'.$item['name'].'</div>';
    }
    else
    {
        echo '
            <tr>
                <td></td>
                <td>'.$item['name'];
    }

    //print description or special inventory
    $desc = $item['description'];
    if ($item['stockStatus'] == 1 && strlen($desc) > 0)
        echo '<div class="description">'.$desc.'</div>';
    else if ($item['stockStatus'] > 1)
        echo '<div class="description stockStatus">'.$invStatus[$item['stockStatus']].'</div>';

    $price = $item['price'];
    if (substr($price, -strlen(".00")) === ".00")
        $price = substr($price, 0, strlen($price) - strlen(".00"));

    echo '</td>
            <td>$'.$price.'</td>
            <td>';

    //show if in stock, show non-zero quantity if already ordered
    if ($item['stockStatus'] != 3) //if not out of stock
        echo '<input name="'.$item['itemID'].'" type="number" min="0" value="'.$quantity.'">';

    echo '</td>
        </tr>';
}

?>
