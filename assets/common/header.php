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
        <link rel="stylesheet" type="text/css" href="/assets/css/common.css" />
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
<!--
        <p style="text-align: center; font-size: 1.2em; font-weight: bold; color: white;">We are currently conducting maintenance on this site;<br>please wait on placing any orders until this banner disappears.</p>
-->
        <div class="contentArea">
            <div class="container">
                <div class="left_col">

    <div id="navigation">
        <div class="navItem">
            <img src="/assets/images/Apis Mellifera female.png" alt="Apis Mellifera female, modified from https://commons.wikimedia.org/wiki/File:Apis_mellifera_Female.jpg, taken by The Packer Lab: Bee Tribes of the World"/>
            <div class="title">AWH home</div>
            <a href="/index.php"><span class="link"></span></a>
        </div>
        <div class="navItem">
            <img src="/assets/images/Apis Mellifera female.png" alt="Apis Mellifera female, modified from https://commons.wikimedia.org/wiki/File:Apis_mellifera_Female.jpg, taken by The Packer Lab: Bee Tribes of the World"/>
            <div class="title">Package Bees</div>
            <a href="/stevesbees_home.php"><span class="link"></span></a>
        </div>
        <div class="navItem">
            <img src="/assets/images/Apis Mellifera female.png" alt="Apis Mellifera female, modified from https://commons.wikimedia.org/wiki/File:Apis_mellifera_Female.jpg, taken by The Packer Lab: Bee Tribes of the World"/>
            <div class="title">Supplies</div>
            <a href="/order_supplies.php"><span class="link"></span></a>
        </div>
        <div class="navItem">
            <img src="/assets/images/Apis Mellifera female.png" alt="Apis Mellifera female, modified from https://commons.wikimedia.org/wiki/File:Apis_mellifera_Female.jpg, taken by The Packer Lab: Bee Tribes of the World"/>
            <div class="title">Honey</div>
            <a href="/honey.php"><span class="link"></span></a>
        </div>
        <div class="navItem">
            <img src="/assets/images/Apis Mellifera female.png" alt="Apis Mellifera female, modified from https://commons.wikimedia.org/wiki/File:Apis_mellifera_Female.jpg, taken by The Packer Lab: Bee Tribes of the World"/>
            <div class="title">Services</div>
            <a href="/services.php"><span class="link"></span></a>
        </div>
        <div class="navItem">
            <img src="/assets/images/Apis Mellifera female.png" alt="Apis Mellifera female, modified from https://commons.wikimedia.org/wiki/File:Apis_mellifera_Female.jpg, taken by The Packer Lab: Bee Tribes of the World"/>
            <div class="title">Harvest/Processing</div>
            <a href="/harvest_n_processing.php"><span class="link"></span></a>
        </div>
        <div class="navItem">
            <img src="/assets/images/Apis Mellifera female.png" alt="Apis Mellifera female, modified from https://commons.wikimedia.org/wiki/File:Apis_mellifera_Female.jpg, taken by The Packer Lab: Bee Tribes of the World"/>
            <div class="title">Contact Us</div>
            <a href="/contact_us.php"><span class="link"></span></a>
        </div>
    </div>
    <div id="navFooter">
        <img src="/assets/images/Canadian honeybee thin.jpg" alt="Honeybee in Canada, courtesy Yvan Leduc / Wikimedia."/>
        <div class="attribute">
            <a href="https://creativecommons.org/licenses/by-sa/3.0/deed.en">CC SA</a>, by <a href="https://commons.wikimedia.org/wiki/User:Yvan_leduc">Yvan Leduc</a><br>courtesy <a href="https://commons.wikimedia.org/wiki/File:NKN-2007-07-02_101750b_Honey_Bee_(_in_Canada_)_(Yvan_Leduc_author_for_Wikipedia).jpg">Wikimedia</a>
        </div>
    </div>

                </div>
                <div class="mid_col">
