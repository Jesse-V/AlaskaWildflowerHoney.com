<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/databaseConnect.secret');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/suppliesPrinter.php');
    global $db;

    //fetch info on whether or not the stores are open or closed
    $storeStatusSQL = $db->query("SELECT * FROM StoreStatus");
    if (!$storeStatusSQL)
        die("A fatal database issue was encountered in orderSupplies.php, storeStatus query. Specifically, ".$db->error);

    //reorganize database information
    while ($record = $storeStatusSQL->fetch_assoc())
        $storeData[$record['Store']] = $record;

    //if the supply store is closed, display the admin's reason why
    if ($storeData['Supplies']['Status'] == 0)
    {
        echo '<p>'.$storeData['Supplies']['CloseText'].'</p>';

        $_JS_ = array();
        require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/footer.php'); //closing HTML
        $db->close();

        exit();
    }

    echo '<div id="supplyOrder">';

    //fetch the list of supplies sections
    $sectionsSQL = $db->query("SELECT * FROM SuppliesSections");
    if (!$sectionsSQL)
        die("A fatal database issue was encountered in orderSupplies.php, Sections query. Specifically, ".$db->error);

    //print each section, organized into collapsable groups
    $groups = queryGroups();
    while ($sectionRecord = $sectionsSQL->fetch_assoc())
        printSection($sectionRecord, $groups);

    $sectionsSQL->close();
?>

    <hr class="fancy">

    <div id="pickupPoint">
        <p>
            We generally don't do mail order for supplies. Instead, our primary distribution point is <a href="contact_us.php">out of our house in Big Lake</a>. Ordering online allows us to gather your order and have it ready to be picked up at <a href="contact_us.php">in Big Lake</a> at your convenience.
        </p>
        <p>
            If you wish to have your items brought to a local beekeeping meeting, into your area, please allow amble time for us to do that. Although we do our best to satisfy all the needs of all of our clients, our schedule may not allow us to gather materials on the same day as beekeeping meetings. When we receive an order, we will put your items on the shelf, sorted by location. Therefore, for example when we make a trip into Anchorage we can grab all of the Anchorage items at once. It will be important that you are able to meet us on our trips to town as it is impractical to deliver directly to your door. All items that we take into town and especially to the beekeeping meetings must be pre-paid.
        </p>

        <p class="options">
            <div class="option">
                <input checked type="radio" name="pickupLoc" value="Big Lake"/>I will pick up my supplies at Big Lake.<br>
            </div>
            <div class="option">
                <input type="radio" name="pickupLoc" value="at the bee meeting"/>I will pick these up at the next SABA beekeepers meeting.
            </div>
            <div class="option">
                <input type="radio" name="pickupLoc" value="along with the bee shipment"/>To be picked up with the bee shipments in April.
            </div>
        </p>
    </div>

    <div class="summary">
        Total for items on this page: $<span id="suppliesTotal">0.00</span>
        <br>
        These items have been added to your shopping cart.
    </div>

</div> <!-- close #supplyOrder div -->
