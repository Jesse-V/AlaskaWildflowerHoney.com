
                </div>
                <div class="right_col">
                    <div id="storeNav">
                        <form action="/order_bees.php">
                            <input type="submit" value="Honeybee Store">
                        </form>
                        <form action="/order_supplies.php">
                            <input type="submit" value="Supplies Store">
                        </form>
                    </div>
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
                                        <a href="mailto:victors@mtaonline.net">victors@mtaonline.net</a>
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
                    <div class="shoppingCart">
                        <div class="title">Shopping Cart</div>
                        <div class="contents">
    <?php
        if (!isset($_SESSION['supplies']) && !isset($_SESSION['beeOrder']))
        {
            echo '<div class="empty">Currently empty</div>';
        }
        else
        {
            //display preview of shopping cart
            require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/ajax/cartPreviewView.php');
            echo getPreviewHTML();
            echo getPreviewTotal();

            //open cart action control div
            echo '<div class="cartActions">';

            //don't show "Edit Cart" if already in cart editor
            if (strpos($_SERVER['PHP_SELF'], 'CartEditor.php') === false)
            {
                echo '
                    <form action="/checkout/CartEditor.php">
                        <input type="submit" value="Edit Cart">
                    </form>';
            }

            //don't show "proceed to checkout" if already in cart checkout
            if (strpos($_SERVER['PHP_SELF'], '1cart_checkout.php') === false)
            {
                echo '
                    <form action="/checkout/1cart_checkout.php">
                        <input type="submit" value="Proceed to Checkout">
                    </form>';
            }

            //close cart action control div
            echo '</div>';
        }
    ?>
                        </div> <!-- close cart content -->
                    </div> <!-- close shopping cart -->
                </div> <!-- close right_col -->
                <div class="clear_both"></div>
            </div>
        </div>
        <div class="footerArea">
            <div class="container">
                <p>&copy; 2014 Alaska Wildflower Honey</p>
            </div>
        </div>
        <script type="text/javascript">
            var SpryAccordion1 = new Spry.Widget.Accordion("SpryAccordion1", {useFixedPanelHeights:false, defaultPanel:-1});
        </script>
        <script src="/assets/js/jquery-1.11.2.min.js"></script>
        <script src="/assets/js/ajaxHeartbeat.js"></script>
        <?php
            foreach ($_JS_ as $jsSource)
                echo '<script src="'.$jsSource.'"></script>';
        ?>
    </body>
</html>
