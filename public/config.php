<?php
// Configure your settings

$lanip = "192.168.0";

$servername = "localhost";
$username = "root";
$password = "yourstrongpassword";
$dbname = "dbname";

//ONLY CHANGE THIS PORT IF YOU NEED TO
$adbport = "5555";

//Enable/Disable features
$noScreenshot = false; // diable with true
$noProxy = false; // diable with true



// DON'T TOUCH ANYTHING BELOW HERE
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check Connection
if (!$conn) {
 die("Connection failed: " . mysqli_connect_error());
}else{

// Create Table
$create_table = "CREATE TABLE IF NOT EXISTS `Devices`
(
`ID` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
`ATVNAME` VARCHAR(50) NULL UNIQUE,
`ATVTEMP` VARCHAR(5) NULL,
`ATVLOCALIP` VARCHAR(50) NULL UNIQUE,
`ATVPROXYIP` VARCHAR(50) NULL,
`ATVATVER` VARCHAR(15) NULL,
`ATVPOGOVER` VARCHAR(15) NULL
);";

$conn->query($create_table);

$conn->close();

}

?>
