<?php //opening HTML
    $_TITLE_ = "Contact Us";
    $_STYLESHEETS_ = array("/assets/css/contact_us.css");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/header.php');
    $_SESSION['customer'] = true;

    require_once("assets/CommonMark.php");
    use League\CommonMark\CommonMarkConverter;
    $converter = new CommonMarkConverter();
?>

    <div class="mapWrapper">
        <a href="assets/images/wasilla-biglake-map.jpg">
            <img class="map" src="assets/images/wasilla-biglake-map.jpg" alt="Map of Big Lake and Wasilla, showing our location and road route."/>
        </a>
        <a href="assets/images/biglake-map.jpg">
            <img class="map" src="assets/images/biglake-map.jpg" alt="Map of Big Lake showing how to get to our location."/>
        </a>
    </div>

    <?php
        $filename = "text/contact_us.txt";
        $contents = fread(fopen($filename, "r"), filesize($filename));
        echo $converter->convertToHtml($contents);
    ?>

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
