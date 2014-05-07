<?php
    $host = "localhost"; //stevesbees.db.2145672.hostedresource.com
    $database = "stevesbees";
    $username = "steve";
    $password = 'FgVAV143!';
    $db = new mysqli($host, $username, $password, $database);

    if ($db->connect_errno)
        die("Failed to Connect to MySQL ".$db->connect_error);
?>
