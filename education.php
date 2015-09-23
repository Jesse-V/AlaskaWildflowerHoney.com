<?php //opening HTML
    $_TITLE_ = "Education";
    $_STYLESHEETS_ = array("/assets/css/education.css");
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/header.php');
    $_SESSION['customer'] = true;

    require_once("assets/CommonMark.php");
    use League\CommonMark\CommonMarkConverter;
    $converter = new CommonMarkConverter();
?>

    <?php
        $filename = "text/education/home.txt";
        $contents = fread(fopen($filename, "r"), filesize($filename));
        echo $converter->convertToHtml($contents);
    ?>

<?php
    $_JS_ = array();
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/footer.php'); //closing HTML
?>
