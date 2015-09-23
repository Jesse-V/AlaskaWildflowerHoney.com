<?php //opening HTML
    $_TITLE_ = "August - Education";
    $_STYLESHEETS_ = array('/assets/css/education_documents.css');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/header.php');
    $_SESSION['customer'] = true;

    require_once($_SERVER['DOCUMENT_ROOT']."/assets/CommonMark.php");
    use League\CommonMark\CommonMarkConverter;
    $converter = new CommonMarkConverter();

    $filename = "home.txt";
    $contents = fread(fopen($filename, "r"), filesize($filename));
    echo $converter->convertToHtml($contents);

    $_JS_ = array();
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/common/footer.php'); //closing HTML
?>
