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

$sql = "SELECT AllocatedNodeName, CPURequired, MemoryRequired, TimeRequiredForCompletion FROM Requests";
if($result=$conn->query($sql)){
    while($row=$result->fetch_assoc()){
        $curtime = date("y-m-d h:i:s");
        $ReqTime = $row["TimeRequiredForCompletion"];
        $NodeName = $row["AllocatedNoneName"];
        if($curtime>(date_add($row["StartTime"],date_interval_create_from_date_string("$ReqTime seconds")))){
            if($NodeName == "Node1"){
                $NodeCPU[0] += $row["CPURequired"];
                $NodeMemory[0] += $row["MemoryRequired"];
            }
            else if($NodeName == "Node2"){
                $NodeCPU[1] += $row["CPURequired"];
                $NodeMemory[1] += $row["MemoryRequired"];
            }
            else if($NodeName == "Node3"){
                $NodeCPU[2] += $row["CPURequired"];
                $NodeMemory[2] += $row["MemoryRequired"];
            }
            else if($NodeName == "Node4"){
                $NodeCPU[3] += $row["CPURequired"];
                $NodeMemory[3] += $row["MemoryRequired"];
            }
        }
    }
}

$AllocateNode =  "None";

for($x=0;$x<4;$x++){
    if($CPURequired<$NodeCPU[$x] && $MemoryRequired<$NodeMemory[$x]){
        $AllocateNode = "Node"+$x;
        break;
    }
}

$InitialCPU = $NodeCPU[$x];
$InitialMemory = $NodeMemory[$x];

if($AllocateNode != "None"){
    $sql = "INSERT INTO Requests VALUES ($ID, $AllocateNode, $StartTime, $CPURequired, $MemoryRequired, $TimeRequired) ";
    $conn->query($sql) or print("Error performing query: " . $sql . " : " . $conn->error());
    echo "Request sent to $AllocateNode";
    $sql = "UPDATE Nodes SET AvailableCPUs=$InitialCPU-$CPURequired, AvailableMemory=$InitialMemory-$MemoryRequired WHERE NodeName='$AllocateNode'";
    $conn->query($sql) or print("Error performing query: " . $sql . " : " . $conn->error());
}
else{
    echo "Request cannot be handled";
}

$conn->close();
?>