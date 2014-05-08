<!DOCTYPE html><html><!-- InstanceBegin template="/Templates/Main.dwt" codeOutsideHTMLIsLocked="false" -->
    <head>
        <!-- InstanceBeginEditable name="doctitle" -->
        <title>Beekeeping Supplies - AlaskaWildflowerHoney.com</title>
        <!-- InstanceEndEditable -->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" href="stylesheets/main.css" />
        <script type="text/javascript" src="SpryAssets/SpryAccordion.js"></script>
        <link href="SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css" />
        <!-- InstanceBeginEditable name="head" -->
        <link rel="stylesheet" type="text/css" href="stylesheets/fancyHRandButtons.css" />
        <link rel="stylesheet" type="text/css" href="stylesheets/order_supplies.css" />

<?php
    require_once('scripts/databaseConnect.secret');

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


    function groupItems($items, $groups, $groupID)
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
?>

        <!-- InstanceEndEditable -->
    </head>
    <body>
        <div class="bannerArea">
            <div class="container">
                <span class="site_logo">Steve's Bees<br><span class="subtitle">Alaska Wildflower Honey</span></span>
            </div>
        </div>
        <div class="contentArea">
            <div class="container">
                <div class="left_col">
                    <div id="navigation">
                        <div class="navItem">
                            <img src="images/honeybee.jpg" alt="Tiny single honeybee."/>
                            <div class="title">AWH home</div>
                            <a href="index.php"><span class="link"></span></a>
                        </div>
                        <div class="navItem">
                            <img src="images/honeybee.jpg" alt="Tiny single honeybee."/>
                            <div class="title">Package Bees</div>
                            <a href="stevesbees_home.php"><span class="link"></span></a>
                        </div>
                        <div class="navItem">
                            <img src="images/honeybee.jpg" alt="Tiny single honeybee."/>
                            <div class="title">Supplies</div>
                            <a href="order_supplies.php"><span class="link"></span></a>
                        </div>
                        <div class="navItem">
                            <img src="images/honeybee.jpg" alt="Tiny single honeybee."/>
                            <div class="title">Honey</div>
                            <a href="honey.php"><span class="link"></span></a>
                        </div>
                        <div class="navItem">
                            <img src="images/honeybee.jpg" alt="Tiny single honeybee."/>
                            <div class="title">Services</div>
                            <a href="services.php"><span class="link"></span></a>
                        </div>
                        <div class="navItem">
                            <img src="images/honeybee.jpg" alt="Tiny single honeybee."/>
                            <div class="title">Harvest/Processing</div>
                            <a href="harvest_n_processing.php"><span class="link"></span></a>
                        </div>
                        <div class="navItem">
                            <img src="images/honeybee.jpg" alt="Tiny single honeybee."/>
                            <div class="title">Contact Us</div>
                            <a href="contact_us.php"><span class="link"></span></a>
                        </div>
                    </div>
                    <!-- InstanceBeginEditable name="content" -->
                    <div class="images">
                        <img src="images/virginia_painting_1.gif" width="302" height="404" alt="Virginia painting a stack of supers 1"/>
                        <div class="desc">My daughter Virginia painting some hives...<br><br>The coolest girl on the planet, and I was lucky to know her.</div>
                        <img src="images/virginia_painting_2.gif" width="302" height="404" alt="Virginia painting a stack of supers 2"/>
                    </div>
                </div>
                <div class="mid_col">
                <form action="checkout/CartManager.php" method="post" autocomplete="on" name="frmProduct" id="frmProduct" accept-charset="UTF-8">
                    <h3>Ordering Online</h3>
                    <p>We generally do not ship mail order, although we do this for those beekeepers who have no other alternative. Mailing items within the state is less expensive than shipment from the lower 48, however when one adds the increased cost of postage to our prices it comes out very similar to direct orders from the states.</p>

                    <h3>Delivery of Supplies</h3>
                    <p>Supplies are usually delivered during our trips delivering bees in the spring. We also routinely deliver supplies to the monthly SABA beekeeping meetings in Eagle River on the 4th Monday of the month. We travel down to the Homer area several times during the summer and can bring supplies with us. Our trips to Anchorage are relatively rare but we do go there on occasion, and each time we find ourselves bringing equipment in. Our trips to Wasilla are much more frequent. The convenience of credit card orders allows beekeepers to purchase supplies for pickup by someone else, delivery on our occasional trips to town or drop off to another location.</p>

<?php
    global $db;

    $sectionsSQL = $db->query("SELECT * FROM SuppliesSections");
    if (!$sectionsSQL)
        die("Failed to connect to database. ".$db->error);

    $groups = queryGroups();

    while ($sectionRecord = $sectionsSQL->fetch_assoc())
    {
        echo '
        <div class="subtitle">'.$sectionRecord['name'].'</div>
        <table>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Price per unit</th>
                <th></th>
            </tr>
            ';

        $items = queryItems($sectionRecord['ID']);
        $groupedItems = groupItems($items, $groups, $groupID);

        foreach ($items as $item)
        {
            if ($item['groupID'] > 0)
            { //part of a group
                if (!empty($groupedItems[$item['groupID']]))
                { //part of a valid group and group hasn't already been printed

                    echo '
                        <tr>
                            <td></td>
                            <td>'.$groups[$item['groupID']]['name'].'<br>'
                                 .$groups[$item['groupID']]['description'].'
                            </td>
                            <td></td>
                            <td></td>
                        </tr>';

                    foreach ($groupedItems[$item['groupID']] as $subItem)
                    {
                        echo '
                        <tr>
                            <td></td>
                            <td>
                                <div class="groupedTD">'
                                    .'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$subItem['name'].'<br>'
                                    .'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$subItem['description'].'
                                </div>
                            </td>
                            <td>
                                <div class="groupedTD">
                                $'.$subItem['price'].'
                                </div>
                            </td>
                            <td>
                                <div class="groupedTD">
                                <input name="'.$subItem['itemID'].'" type="number" min="0" value="0">
                                </div>
                            </td>
                        </tr>';
                    }

                    unset($groupedItems[$item['groupID']]);
                }
            }
            else
            {
                echo '
                <tr>
                    <td></td>
                    <td>'.$item['name'].'</td>
                    <td>$'.$item['price'].'</td>
                    <td><input name="'.$item['itemID'].'" type="number" min="0" value="0"></td>
                </tr>';
            }
        }

        echo '
            </table>';
    }

    $sectionsSQL->close();
?>

                    <hr class="fancy">

                    <div id="pickupPoint">
                        <p>
                            We generally don't do mail order for supplies. Instead, we deliver locally. We often bring supplies with us on our trips into town. Please call or email us to arrange for this. We travel to the peninsula several times in the summer and in and out of Wasilla often. Where would you like to pick up your items?
                        </p>

                        <p class="options">
                            <div class="option">
                                <input type="radio" name="pickupLoc" value="at the bee meeting"/>To be picked up at a SABA beekeepers meeting.
                            </div>
                            <div class="option">
                                <input type="radio" name="pickupLoc" value="Big Lake"/>To be picked up at Big Lake.
                            </div>
                            <div class="option">
                                <input type="radio" name="pickupLoc" value="along with the bee shipment"/>To be picked up with the spring bee shipment.
                            </div>
                        </p>
                    </div>

                    <div class="summary">
                        Total for items on this page: $<span id="total">0.00</span>
                    </div>

                    <input type="hidden" name="format" value="supplies"/>

                    <input type="submit" name="submit" id="moreBtn" value="Need bees or queens? Click here to save your order and visit the bees page."/>
                    <input type="submit" name="submit" id="submitBtn" value="Finished? Click here to proceed to checkout."/>
                </form>

                </div>
                <div class="right_col">
                    <!-- InstanceEndEditable -->
                    <div id="SpryAccordion1" class="Accordion" tabindex="0">
                        <div class="AccordionPanel">
                            <div class="AccordionPanelTab">
                                <span class="accordion_340_tab">Alaska Wildflower Honey</span>
                            </div>
                            <div class="AccordionPanelContent">
                                <div class="acontent">
                                    <p>Alaska Wildflower Honey is a family-owned beekeeping operation in south-central Alaska. We supply honey, wax, pollen, and other bee products; services such as honey extracting and filtering; and other products such as packages of honeybees, and beekeeping supplies.</p>
                                </div>
                            </div>
                        </div>
                        <div class="AccordionPanel">
                            <div class="AccordionPanelTab">
                                <span class="accordion_340_tab">Steve's Bees</span>
                            </div>
                            <div class="AccordionPanelContent">
                                <div class="acontent">
                                    <p>Steve's Bees is a part of Alaska Wildflower Honey. We import and distribute packages of honeybees and beekeeping supplies to beekeepers in southern Alaska.</p>
                                </div>
                            </div>
                        </div>
                        <div class="AccordionPanel">
                            <div class="AccordionPanelTab">
                                <span class="accordion_340_tab">Contact Us</span>
                            </div>
                            <div class="AccordionPanelContent">
                                <div class="acontent">
                                    <p>
                                        <a href="mailto:steve@stevesbees.com">steve@stevesbees.com</a>
                                    </p>
                                    <p>
                                        Alaska Wildflower Honey<br>
                                        7449 S. Babcock Blvd<br>
                                        Wasilla, AK 99623
                                    </p>
                                    <p>
                                        (907) 892-6175
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="AccordionPanel">
                            <div class="AccordionPanelTab">
                                <span class="accordion_340_tab">Related Links</span>
                            </div>
                            <div class="AccordionPanelContent">
                                <div class="acontent">
                                    <ul>
                                        <li>
                                            <a href="http://www.bz-bee.com/aboutus.html">John Foster Apiaries</a>
                                            <p>John Foster is our supplier of bees. He has 15,000 beehives, and 17 people working for him, so he has a fairly large operation.</p>
                                        </li>
                                        <li>
                                            <a href="http://www.mannlakeltd.com/">Mann Lake Ltd</a>
                                            <p>Mann Lake Ltd., our beekeeping supplies supplier, sells many beekeeping products. Anything that you can think of that is used in beekeeping in any way, they've probably got it.</p>
                                        </li>
                                        <li>
                                            <a href="http://www.sababeekeepers.com/">SABA</a>
                                            <p>South-central Alaska Beekeepers Association (SABA) is the association for beekeepers of all ages in south-central Alaska.</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clear_both"></div>
            </div>
        </div>
        <div class="footerArea">
            <div class="container">
                <p>&copy; 2014 Alaska Wildflower Honey</p>
            </div>
        </div>
        <script type="text/javascript">
        <!--
            var SpryAccordion1 = new Spry.Widget.Accordion("SpryAccordion1", {useFixedPanelHeights:false, defaultPanel:-1});
        //-->
        </script>
        <!-- InstanceBeginEditable name="scripts" -->
        <script src="scripts/jquery-1.10.2.js"></script>
        <script src="scripts/order_supplies.js"></script>
        <!-- InstanceEndEditable -->
    </body>
<!-- InstanceEnd --></html>
<?php
    $db->close();
?>
