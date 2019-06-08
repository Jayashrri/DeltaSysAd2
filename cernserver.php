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

if(!mysqli_select_db('CernServer')){
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

$sql = "USE CernServer";
$conn->query($sql) or print("Error performing query: " . $sql . " : " . $conn->error());

$ID = htmlspecialchars($_POST['ID']);
$CPURequired = htmlspecialchars($_POST['CPURequired']);
$MemoryRequired = htmlspecialchars($_POST['MemoryRequired']);
$TimeRequired = htmlspecialchars($_POST['TimeRequired']);

$StartTime = date("y-m-d h:i:s");

$NodeCPU = array();
$NodeMemory = array();
$sql = "SELECT AvailableCPUs, AvailableMemory FROM Nodes";

$result = $conn->query($sql);
for($x=0;$x<4;$x++){
    $row = $result->fetch_assoc();
    $NodeCPU[$x] = $row["AvailableCPUs"];
    $NodeMemory[$x] = $row["AvailableMemory"];
}

$AllocateNode =  "None";

for($x=0;$x<4;$x++){
    if($CPURequired<$NodeCPU[$x] && $MemoryRequired<$NodeMemory[$x]){
        $AllocateNode = "Node"+$x;
        break;
    }
}

if($AllocateNode != "None"){
    $sql = "INSERT INTO Requests VALUES ($ID, $AllocateNode, $StartTime, $CPURequired, $MemoryRequired, $TimeRequired) ";
    $conn->query($sql) or print("Error performing query: " . $templine . " : " . $conn->error());
    echo "Request sent to $AllocateNode";
}
else{
    echo "Request cannot be handled";
}

$conn->close();
?>