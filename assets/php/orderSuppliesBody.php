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
    <?php
        $filename = $_SERVER['DOCUMENT_ROOT']."/text/supplies/bottom_footer.txt";
        $contents = fread(fopen($filename, "r"), filesize($filename));
        echo $converter->convertToHtml($contents);
    ?>

        <p class="options">
            <div class="option">
                <input checked type="radio" name="suppliesPickupLoc" value="Big Lake"/>I will pick up my supplies at Big Lake.<br>
            </div>
            <div class="option">
                <input type="radio" name="suppliesPickupLoc" value="at the bee meeting"/>I will pick these up at the next SABA beekeepers meeting.
            </div>
            <div class="option">
                <input type="radio" name="suppliesPickupLoc" value="along with the bee shipment"/>To be picked up with the bee shipments in April.
            </div>
        </p>
    </div>

    <div class="summary">
        Total for items on this page: $<span id="suppliesTotal">0.00</span>
        <br>
        These items have been added to your shopping cart.
    </div>

</div> <!-- close #supplyOrder div -->
