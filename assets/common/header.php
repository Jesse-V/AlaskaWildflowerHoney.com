<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/classes/SuppliesOrder.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/classes/BeeOrder.php');
    if(!isset($_SESSION))
        session_start();
?>

<!DOCTYPE html>
<html>
    <head>
    <?php
        echo '
        <title>'.$_TITLE_.' - AlaskaWildflowerHoney.com</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" href="/assets/css/main.css" />
        <script type="text/javascript" src="/assets/spry/SpryAccordion.js"></script>
        <link href="/assets/spry/SpryAccordion.css" rel="stylesheet" type="text/css" />';

        foreach ($_STYLESHEETS_ as $sheet)
            echo '<link rel="stylesheet" type="text/css" href="'.$sheet.'" />';
    ?>
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
<?php
    echo '
    <div class="navItem">
        <img src="/assets/images/honeybee_cropped.jpg" alt="Tiny single honeybee."/>
        <div class="title">AWH home</div>
        <a href="/index.php"><span class="link"></span></a>
    </div>
    <div class="navItem">
        <img src="/assets/images/honeybee_cropped.jpg" alt="Tiny single honeybee."/>
        <div class="title">Package Bees</div>
        <a href="/stevesbees_home.php"><span class="link"></span></a>
    </div>
    <div class="navItem">
        <img src="/assets/images/honeybee_cropped.jpg" alt="Tiny single honeybee."/>
        <div class="title">Supplies</div>
        <a href="/order_supplies.php"><span class="link"></span></a>
    </div>
    <div class="navItem">
        <img src="/assets/images/honeybee_cropped.jpg" alt="Tiny single honeybee."/>
        <div class="title">Honey</div>
        <a href="/honey.php"><span class="link"></span></a>
    </div>
    <div class="navItem">
        <img src="/assets/images/honeybee_cropped.jpg" alt="Tiny single honeybee."/>
        <div class="title">Services</div>
        <a href="/services.php"><span class="link"></span></a>
    </div>
    <div class="navItem">
        <img src="/assets/images/honeybee_cropped.jpg" alt="Tiny single honeybee."/>
        <div class="title">Harvest/Processing</div>
        <a href="/harvest_n_processing.php"><span class="link"></span></a>
    </div>
    <div class="navItem">
        <img src="/assets/images/honeybee_cropped.jpg" alt="Tiny single honeybee."/>
        <div class="title">Contact Us</div>
        <a href="/contact_us.php"><span class="link"></span></a>
    </div>';
?>
                    </div>
                </div>
                <div class="mid_col">
