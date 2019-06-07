CREATE DATABASE CernServer;
USE CernServer;

CREATE TABLE Nodes(
    NodeName VARCHAR(5),
    NumberofCPUs INT,
    AvailableCPUs INT,
    MemorySize FLOAT,
    AvailableMemory FLOAT
);
CREATE TABLE Requests(
    ID CHAR(5),
    AllocatedNodeName VARCHAR(5),
    StartTime DATETIME,
    CPURequired INT,
    MemoryRequired FLOAT,
    TimeRequiredForCompletion INT
);

