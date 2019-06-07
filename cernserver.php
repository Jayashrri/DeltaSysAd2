#!/usr/bin/php

<?php
$servername = "localhost";
$username = "root";
$password = "";
$sqlscript = "database.sql";

$conn = new mysqli($servername,$username,$password);
if($conn->connect_error){
    die("Connection Failed: " . $conn->connect_error);
}
echo "Connected Successfully";

$templine = "";
$lines = file($sqlscript);

foreach($lines as $line){
    if(substr($line,0,2) == '--' || $line == '')
        continue;
    $templine .= $line;

    if(substr(trim($line),-1,1) == ';'){
        $conn->query($templine) or print("Error performing query: " . $templine . " : " . $conn->error());
        $templine = "";
    }
}

echo "Tables created successfully";
$conn->close();
?>