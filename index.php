<?php //opening HTML
    $_TITLE_ = "Home";
    $_STYLESHEETS_ = array("/assets/css/index.css");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/header.php');
    $_SESSION['customer'] = true;

    require_once("assets/CommonMark.php");
    use League\CommonMark\CommonMarkConverter;
    $converter = new CommonMarkConverter();
?>

    <div class="left half">

    <?php
        $filename = "text/homepage/left_side.txt";
        $contents = fread(fopen($filename, "r"), filesize($filename));
        echo $converter->convertToHtml($contents);
    ?>

    </div>
    <div class="right half">
        <div class="right_image">
            <img src="assets/images/hives_in_grass.gif" alt="Five beehives in our beeyard, tucked away behind grass."/>
            <p>Our hives behind our house on Fish Creek</p>
        </div>

        <?php
            $filename = "text/homepage/right_side.txt";
            $contents = fread(fopen($filename, "r"), filesize($filename));
            echo $converter->convertToHtml($contents);
        ?>

    </div>

<?php
    $_JS_ = array();
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/footer.php'); //closing HTML
?>
