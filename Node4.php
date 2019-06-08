<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "CernServer";

$conn = new mysqli($servername,$username,$password,$db);
if($conn->connect_error){
    die("Connection Failed: " . $conn->connect_error);
}
echo "Connected Successfully";

$sql = "SELECT ID, StartTime, CPURequired, MemoryRequired, TimeRequiredForCompletion FROM Requests WHERE AllocaterNodeName = 'Node4' ";
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