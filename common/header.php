<!DOCTYPE html>
<html>
    <head>
    <?php
        echo '
        <title>'.$_TITLE_.' - AlaskaWildflowerHoney.com</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" href="'.$_REL_.'stylesheets/main.css" />
        <script type="text/javascript" src="'.$_REL_.'SpryAssets/SpryAccordion.js"></script>
        <link href="'.$_REL_.'SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css" />';

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
        <img src="'.$_REL_.'images/honeybee.jpg" alt="Tiny single honeybee."/>
        <div class="title">AWH home</div>
        <a href="'.$_REL_.'index.php"><span class="link"></span></a>
    </div>
    <div class="navItem">
        <img src="'.$_REL_.'images/honeybee.jpg" alt="Tiny single honeybee."/>
        <div class="title">Package Bees</div>
        <a href="'.$_REL_.'stevesbees_home.php"><span class="link"></span></a>
    </div>
    <div class="navItem">
        <img src="'.$_REL_.'images/honeybee.jpg" alt="Tiny single honeybee."/>
        <div class="title">Supplies</div>
        <a href="'.$_REL_.'order_supplies.php"><span class="link"></span></a>
    </div>
    <div class="navItem">
        <img src="'.$_REL_.'images/honeybee.jpg" alt="Tiny single honeybee."/>
        <div class="title">Honey</div>
        <a href="'.$_REL_.'honey.php"><span class="link"></span></a>
    </div>
    <div class="navItem">
        <img src="'.$_REL_.'images/honeybee.jpg" alt="Tiny single honeybee."/>
        <div class="title">Services</div>
        <a href="'.$_REL_.'services.php"><span class="link"></span></a>
    </div>
    <div class="navItem">
        <img src="'.$_REL_.'images/honeybee.jpg" alt="Tiny single honeybee."/>
        <div class="title">Harvest/Processing</div>
        <a href="'.$_REL_.'harvest_n_processing.php"><span class="link"></span></a>
    </div>
    <div class="navItem">
        <img src="'.$_REL_.'images/honeybee.jpg" alt="Tiny single honeybee."/>
        <div class="title">Contact Us</div>
        <a href="'.$_REL_.'contact_us.php"><span class="link"></span></a>
    </div>';
?>
                    </div>
                </div>
                <div class="mid_col">
