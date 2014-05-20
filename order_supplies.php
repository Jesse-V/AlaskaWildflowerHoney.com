<?php //opening HTML
    $_REL_ = "";
    $_TITLE_ = "Beekeeping Supplies";
    $_STYLESHEETS_ = array("stylesheets/fancyHRandButtons.css", "stylesheets/order_supplies.css");
    require_once('common/header.php');
?>

    <form action="checkout/CartManager.php" method="post" autocomplete="on" name="frmProduct" id="frmProduct" accept-charset="UTF-8">
        <h3>Supplies Store</h3>
        <p>We offer a variety of beekeeping products, ranging from common items such as beehive components, tools, and processing equipment to rarer and specialty items. We carry primarily Mann Lake products, as well as some of our own. We are the largest distributor of beekeeping supplies in the state of Alaska. We hope you will find this store efficient and convenient.</p>

<?php

    require_once('scripts/databaseConnect.secret');
    global $db;

    $sectionsSQL = $db->query("SELECT * FROM SuppliesSections");
    if (!$sectionsSQL)
        die("Failed to connect to database. ".$db->error);

    $groups = queryGroups();

    while ($sectionRecord = $sectionsSQL->fetch_assoc())
        printSection($sectionRecord, $groups);

    $sectionsSQL->close();
?>

        <hr class="fancy">

        <div id="pickupPoint">
            <p>
                We generally don't do mail order for supplies. Instead, our primary distribution point is <a href="contact_us.php">out of our house in Big Lake</a>. Ordering online allows us to gather your order and have it ready to be picked up at Big Lake at your convenience. It also makes it possible to have someone else pick up your order for you or for us to be able to bring things with us on our occasional trips into town. We are happy to bring supplies with us to your location but our trips are generally unscheduled and irregular. Therefore, the most reliable pickup is <a href="contact_us.php">in Big Lake</a>.
            </p>
            <p>
                If you wish to have your items brought to a local beekeeping meeting, into your area, please allow amble time for us to do that. Although we do our best to satisfy all the needs of all of our clients, our schedule may not allow us to gather materials on the same day as beekeeping meetings or just prior to one of our trips into town. When we receive an order, we will put your items on the shelf, sorted by location. Therefore, for example when we make a trip into Anchorage we can grab all of the Anchorage items at once. It will be important that you are able to meet us on our trips to town as it is impractical to deliver directly to your door. All items that we take into town and especially to the beekeeping meetings must be pre-paid.
            </p>

            <p class="options">
                <div class="option">
                    <input checked type="radio" name="pickupLoc" value="Big Lake"/>I will pick up my supplies at Big Lake.<br>
                </div>
                <div class="option">
                    <input type="radio" name="pickupLoc" value="at the bee meeting"/>I will pick these up at the next SABA beekeepers meeting.
                </div>
                <!--
                <div class="option">
                    <input type="radio" name="pickupLoc" value="along with the bee shipment"/>To be picked up with the spring bee shipment.
                </div>
                -->
            </p>
        </div>

        <div class="summary">
            Total for items on this page: $<span id="total">0.00</span>
        </div>

        <input type="hidden" name="format" value="supplies"/>

        <!--
        <p>
            <b>We are currently making change to our checkout process, so we have temporarily disabled it. We should be finished in approximately one hour. Please check back then.</b>
        </p>
        -->

        <!--<input type="submit" name="submit" id="moreBtn" value="Need bees or queens? Click here to save your order and visit the bees page."/>-->
        <input type="submit" name="submit" id="submitBtn" value="Finished? Click here to proceed to checkout."/>

    </form>

<?php

$_JS_ = array("scripts/jquery-1.11.1.min.js", "scripts/order_supplies.js");
require_once('common/footer.php'); //closing HTML
$db->close();


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
            <th>Image</th>
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
            <tr class="subItem subItem'.$item['groupID'].'">
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
