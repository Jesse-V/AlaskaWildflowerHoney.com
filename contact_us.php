<?php //opening HTML
    $_TITLE_ = "Contact Us";
    $_STYLESHEETS_ = array("/assets/css/contact_us.css");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/header.php');
    $_SESSION['customer'] = true;
?>

    <div class="mapWrapper">
        <a href="assets/images/wasilla-biglake-map.jpg">
            <img class="map" src="assets/images/wasilla-biglake-map.jpg" alt="Map of Big Lake and Wasilla, showing our location and road route."/>
        </a>
        <a href="assets/images/biglake-map.jpg">
            <img class="map" src="assets/images/biglake-map.jpg" alt="Map of Big Lake showing how to get to our location."/>
        </a>
    </div>

    <p>
        Driving directions to our place would be as follows:
        <br><br>
        At mile 5.5 Big Lake road you will come to South Port Marina. Echo Lake Drive is the next left after the entrance to the marina. Go 1.25 miles down Echo Lake Drive; the road will fork, the left is unmarked and the right is labeled "Gondor Rd". Take the unmarked left fork. This road leads down a long straight stretch, and our place is 1.25 miles down the road. There is a locked gate with a big yellow sign that keeps our community private and quiet, so be sure to call us before you come out so that we can make sure that the gate is open. The entrance of our driveway is marked by a beehive, a yellow road-grader, and is straight ahead once you see our neighbor's horse corral.
    </p>
    <p class="address">
        Alaska Wildflower Honey
        <br>7449 S. Babcock Blvd.
        <br>Wasilla, AK 99623
    </p>
    <p class="phone">Phone: (907) 892-6175</p>
    <p class="email">steve@stevesbees.com</p>

    <div class="honeyImgWrapper">
        <img class="honey" src="assets/images/half_pound.gif" alt="Half pound of honey."/>
    </div>

<?php
    $_JS_ = array();
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/footer.php'); //closing HTML
?>
