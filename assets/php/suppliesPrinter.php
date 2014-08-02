<?php

function queryGroups()
{
    global $db;

    $groupSQL = $db->query("SELECT * FROM SuppliesItemGroups");
    if (!$groupSQL)
        die("Failed to connect to database. ".$db->error);

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
        die("Failed to fetch data. ".$db->error);

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
        die("Failed to connect to database. ".$db->error);

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
    if ($subItem)
        echo '
            <tr class="subItem subItem'.$item['groupID'].'" style="display: none;">
                <td></td>
                <td>
                    <div class="subItemName">'.$item['name'].'</div>';
    else
        echo '
            <tr>
                <td></td>
                <td>'.$item['name'];

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

    if ($item['stockStatus'] != 3) //if not out of stock
        echo '<input name="'.$item['itemID'].'" type="number" min="0" value="0">';

    echo '</td>
        </tr>';
}

?>
