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

$ID = htmlspecialchars($_POST['ID']);
$CPURequired = htmlspecialchars($_POST['CPURequired']);
$MemoryRequired = htmlspecialchars($_POST['MemoryRequired']);
$TimeRequired = htmlspecialchars($_POST['TimeRequired']);

$StartTime = date("y-m-d h:i:s");

$NodeCPU = array();
$NodeMemory = array();
$sql = "SELECT AvailableCPUs, AvailableMemory FROM Nodes";

$result = mysqli_query($conn,$sql);
for($x=0;$x<4;$x++){
    $row = $result->fetch_assoc();
    $NodeCPU[$x] = $row["AvailableCPUs"];
    $NodeMemory[$x] = $row["AvailableMemory"];
}

$sql = "SELECT AllocatedNodeName, CPURequired, MemoryRequired, TimeRequiredForCompletion FROM Requests";
if($result=mysqli_query($conn,$sql)){
    while($row=mysqli_fetch_assoc($result)){
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
        $AllocateNode = "Node".$x;
        break;
    }
}

$InitialCPU = $NodeCPU[$x];
$InitialMemory = $NodeMemory[$x];

if($AllocateNode != "None"){
    $sql = "INSERT INTO Requests VALUES ('$ID', '$AllocateNode', '$StartTime', $CPURequired, $MemoryRequired, $TimeRequired) ";
    mysqli_query($conn,$sql) or print("Error performing query: " . $sql . " : " . mysqli_error($conn));
    echo "Request sent to $AllocateNode";
    $sql = "UPDATE Nodes SET AvailableCPUs=$InitialCPU-$CPURequired, AvailableMemory=$InitialMemory-$MemoryRequired WHERE NodeName='$AllocateNode'";
    mysqli_query($conn,$sql) or print("Error performing query: " . $sql . " : " . mysqli_error($conn));
}
else{
    echo "Request cannot be handled";
}

mysqli_close($conn);
?>