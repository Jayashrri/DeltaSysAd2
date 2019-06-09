<?php
$servername = "localhost";
$username = "root";
$password = "";

$conn = new mysqli($servername,$username,$password,$db);
if($conn->connect_error){
    die("Connection Failed: " . $conn->connect_error);
}
echo "Connected Successfully";


if(!mysqli_select_db($conn,'CernServer')){
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
}


$sql = "SELECT ID, StartTime, CPURequired, MemoryRequired, TimeRequiredForCompletion FROM Requests WHERE AllocatedNodeName = 'Node2' ";
if($result=$conn->query($sql)){
    echo "<p>Running Processes</p>";
    echo "<table>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>Start Time</th>";
    echo "<th>CPU Required</th>";
    echo "<th>Memory Required</th>";
    echo "<th>Time Required</th>";
    echo "</tr>";
    while($row=$result->fetch_assoc()){
        $curtime = date("y-m-d h:i:s");
        $ReqTime = $row["TimeRequiredForCompletion"];
        if($curtime<(date_add($row["StartTime"],date_interval_create_from_date_string("$ReqTime seconds")))){
            echo "<tr>";
            echo "<td>".$row["ID"]."</td>";
            echo "<td>".$row["StartTime"]."</td>";
            echo "<td>".$row["CPURequired"]."</td>";
            echo "<td>".$row["MemoryRequired"]."</td>";
            echo "<td>".$row["TimeRequiredForCompletion"]."</td>";
        }
    }
}

if($result=$conn->query($sql)){
    echo "<br><br><br>";
    echo "<p>History</p>";
    echo "<table>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>Start Time</th>";
    echo "<th>CPU Required</th>";
    echo "<th>Memory Required</th>";
    echo "<th>Time Required</th>";
    echo "</tr>";
    while($row=$result->fetch_assoc()){
        $curtime = date("y-m-d h:i:s");
        $ReqTime = $row["TimeRequiredForCompletion"];
        if($curtime>=(date_add($row["StartTime"],date_interval_create_from_date_string("$ReqTime seconds")))){
            echo "<tr>";
            echo "<td>".$row["ID"]."</td>";
            echo "<td>".$row["StartTime"]."</td>";
            echo "<td>".$row["CPURequired"]."</td>";
            echo "<td>".$row["MemoryRequired"]."</td>";
            echo "<td>".$row["TimeRequiredForCompletion"]."</td>";
        }
    }
}

$conn->close();
?>