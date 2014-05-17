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
                We generally don't do mail order for supplies. Instead, our primary distribution point is out of our house in Big Lake. Ordering online allows us to gather your order and have it ready to be picked up at Big Lake at your convenience. It also makes it possible to have someone else pick up your order for you or for us to be able to bring things with us on our occasional trips into town. We are happy to bring supplies with us to your location but our trips are generally unscheduled and irregular. Therefore, the most reliable pickup is in Big Lake.
            </p>

            <p class="options">
                <!--
                <div class="option">
                    <input type="radio" name="pickupLoc" value="at the bee meeting"/>To be picked up at a SABA beekeepers meeting.
                </div>
                -->
                <div class="option">
                    <input checked type="radio" name="pickupLoc" value="Big Lake"/>To be picked up at Big Lake.
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

        <!--<input type="submit" name="submit" id="moreBtn" value="Need bees or queens? Click here to save your order and visit the bees page."/>-->
        <p>
            <b>We are currently reworking our checkout process. Please wait until the 17th before placing any orders. Thanks.</b>
        </p>
        <!--
        <input type="submit" name="submit" id="submitBtn" value="Finished? Click here to proceed to checkout."/>
        -->
    </form>

<?php
    $_JS_ = array("scripts/jquery-1.10.2.js", "scripts/order_supplies.js");
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


    function printSection($sectionRecord, $groups)
    {
        echo '
        <div class="subtitle">'.$sectionRecord['name'].'</div>
        <div class="description">'.$sectionRecord['description'].'</div>
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

        foreach ($items as $item)
        {
            if ($item['groupID'] > 0)
            { //part of a group
                if (!empty($groupedItems[$item['groupID']]))
                { //part of a valid group and group hasn't already been printed
                    $desc = $groups[$item['groupID']]['description'];

                    echo '
                        <tr class="group'.$item['groupID'].'">
                            <td></td>
                            <td>'.$groups[$item['groupID']]['name'];

                    if (strlen($desc) > 0)
                        echo '<br>'.$desc;

                    echo '  </td>
                            <td>v</td>
                            <td>v</td>
                        </tr>';

                    foreach ($groupedItems[$item['groupID']] as $subItem)
                    {
                        $price = $subItem['price'];
                        if (substr($price, -strlen(".00")) === ".00")
                            $price = substr($price, 0, strlen($price) - strlen(".00"));

                        $desc = $groups[$item['groupID']]['description'];

                        echo '
                        <tr class="subItem subItem'.$item['groupID'].'">
                            <td></td>
                            <td>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$subItem['name'];

                        if (strlen($subItem['description']) > 0)
                            echo '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$subItem['description'];

                        echo '
                            </td>
                            <td>$'.$price.'</td>
                            <td><input name="'.$subItem['itemID'].'" type="number" min="0" value="0"></td>
                        </tr>';
                    }

                    unset($groupedItems[$item['groupID']]);
                }
            }
            else
            {
                $price = $item['price'];
                if (substr($price, -strlen(".00")) === ".00")
                    $price = substr($price, 0, strlen($price) - strlen(".00"));

                echo '
                <tr>
                    <td></td>
                    <td>'.$item['name'].'</td>
                    <td>$'.$price.'</td>
                    <td><input name="'.$item['itemID'].'" type="number" min="0" value="0"></td>
                </tr>';
            }
        }

        echo '
            </table>';
    }
?>
