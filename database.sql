CREATE DATABASE CernServer;
USE CernServer;

CREATE USER 'cerndb'@'localhost' IDENTIFIED BY 'passcode';
GRANT ALL ON CernServer.* TO 'cerndb'@'localhost';

CREATE TABLE Nodes(
    NodeName VARCHAR(5),
    NumberofCPUs INT,
    AvailableCPUs INT,
    MemorySize FLOAT,
    AvailableMemory FLOAT
);
CREATE TABLE Requests(
    ID VARCHAR(5),
    AllocatedNodeName VARCHAR(5),
    StartTime DATETIME,
    CPURequired INT,
    MemoryRequired FLOAT,
    TimeRequiredForCompletion INT
);

INSERT INTO Nodes VALUES ('Node1','5','5','100','100'),('Node2','10','10','150','150'),('Node3','15','15','200','200'),('Node4','20','20','250','250');
COMMIT;