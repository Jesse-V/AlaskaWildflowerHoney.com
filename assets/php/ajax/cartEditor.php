<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/databaseConnect.secret');
    global $db;
    session_start();

    if ($_GET['action'] == 'deleteItem')
    {
        if ($_GET['table'] == 'suppliesTable')
        {
            //TODO
            echo $_GET['element'];
        }
        else if ($_GET['table'] == 'beesTable')
        {
            //TODO
            echo $_GET['element'];
        }
    }

    $db->close();
?>
