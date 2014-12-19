<?php //opening HTML
    $_TITLE_ = "Harvest & Processing";
    $_STYLESHEETS_ = array("assets/css/harvest_n_processing.css");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/header.php');
?>

    <h1>This page is under construction</h1>
    <div class="galleria">
        <img src="assets/images/processing/image2971.jpg" alt="image 1">
        <img src="assets/images/processing/image3131.jpg" alt="image 2">
        <img src="assets/images/processing/image3271.jpg" alt="image 3">
    </div>

<?php
    $_JS_ = array("assets/js/jquery-1.11.1.min.js",
        "assets/galleria/galleria-1.3.3.min.js",
        "assets/js/harvest_n_processing.js");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/footer.php'); //closing HTML
?>
