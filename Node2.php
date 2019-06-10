<?php
$servername = "localhost";
$username = "cerndb";
$password = "passcode";
$dbname = "CernServer";

$conn = mysqli_connect($servername,$username,$password,$dbname);
if(!$conn){
    die("Connection Failed: " . mysqli_error($conn));
}
echo "Connected Successfully";

$sql = "SELECT ID, StartTime, CPURequired, MemoryRequired, TimeRequiredForCompletion FROM Requests WHERE AllocatedNodeName = 'Node2' ";
if($result=mysqli_query($conn,$sql)){
    echo "<p>Running Processes</p>";
    echo "<table>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>Start Time</th>";
    echo "<th>CPU Required</th>";
    echo "<th>Memory Required</th>";
    echo "<th>Time Required</th>";
    echo "</tr>";
    while($row=mysqli_fetch_assoc($result)){
        $curtime = date("y-m-d h:i:s");
        $ReqTime = $row["TimeRequiredForCompletion"];
        $comptime = date("y-m-d h:i:s",strtotime("+".$ReqTime." seconds",strtotime($row["StartTime"])));
        if($curtime<$comptime){
            echo "<tr>";
            echo "<td>".$row["ID"]."</td>";
            echo "<td>".$row["StartTime"]."</td>";
            echo "<td>".$row["CPURequired"]."</td>";
            echo "<td>".$row["MemoryRequired"]."</td>";
            echo "<td>".$row["TimeRequiredForCompletion"]."</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
}

if($result=mysqli_query($conn,$sql)){
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
    while($row=mysqli_fetch_assoc($result)){
        $curtime = date("y-m-d h:i:s");
        $ReqTime = $row["TimeRequiredForCompletion"];
        $comptime = date("y-m-d h:i:s",strtotime("+".$ReqTime." seconds",strtotime($row["StartTime"])));
        if($curtime>$comptime){
            echo "<tr>";
            echo "<td>".$row["ID"]."</td>";
            echo "<td>".$row["StartTime"]."</td>";
            echo "<td>".$row["CPURequired"]."</td>";
            echo "<td>".$row["MemoryRequired"]."</td>";
            echo "<td>".$row["TimeRequiredForCompletion"]."</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
}

$conn->close();
?>