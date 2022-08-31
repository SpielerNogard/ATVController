<?php

// SCRIPTS TO RUN ON ALL DEVICES

function deviceinfo() {
echo '<form id="deviceinfo" action="index.php" method ="post" align="center">';
echo '<input type="submit" value="Build Info" name="deviceinfo" id="button" /></form>';
if(isset($_POST['deviceinfo'])){
echo $res=shell_exec('scripts/deviceinfo.sh');
}
}

function tempbutton() {
echo '<form id="temp" action="index.php" method ="post" align="center">';
echo '<input type="submit" value="Get All Temps" name="temp" id="button"/></form>';
if(isset($_POST['temp'])){
echo $res=shell_exec('scripts/tempcheck.sh');
}
}

function rebootbutton() {
echo '<form id="reboot" action="index.php" method ="post" align="center">';
echo '<input type="submit" value="Reboot All - WIP" name="reboot" id="button"/></form>';
if(isset($_POST['reboot'])){
echo $res=shell_exec('scripts/reboot.sh');
}
}

function updatebutton() {
echo '<form id="update" action="index.php" method ="post" align="center">';
echo '<input type="submit" value="Update All - WIP" name="update" id="button"/></form>';
if(isset($_POST['update'])){
echo $res=shell_exec('scripts/update.sh');
}
}

function startbutton() {
echo '<form id="start" action="index.php" method ="post" align="center">';
echo '<input type="submit" value="Start All - WIP" name="start" id="button"/></form>';
if(isset($_POST['start'])){
echo $res=shell_exec('scripts/start.sh');
}
}

function stopbutton() {
echo '<form id="stop" action="index.php" method ="post" align="center">';
echo '<input type="submit" value="Stop All - WIP" name="stop" id="button"/></form>';
if(isset($_POST['stop'])){
echo $res=shell_exec('scripts/stop.sh');
}
}

// TABLE DATA DISPLAY AND PER DEVICE CONTROLLER

function devicetable() {
include("config.php");

//MYSQLI CONNECTION

$conn = new mysqli($servername, $username, $password, $dbname, $port);
// Checking for connections
if ($conn->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
}
$sql = " SELECT * FROM Devices; ";
$result = $conn->query($sql);
$conn->close();

//START OF TABLE INFO
echo '<center><table>' .
	'<tr>' .
	'<th>ID</th>' .
        '<th>Device Name</th>' .
        '<th>Temp</th>' .
	'<th>Local IP</th>';

        if($noProxy === false){
	echo '<th>Proxy IP</th>';
	}
	
	echo '<th>App Versions</th>' .
		'<th>Controls</th>';

        if($noScreenshot === false){
	echo '<th>Screenshot</th>';
	}	
	echo '</tr>';
                while($rows=$result->fetch_assoc()){
		$id = $rows['ID'];	
		$name = $rows['ATVNAME'];
		$atvtemp = $rows['ATVTEMP'];
		$localip = $rows['ATVLOCALIP'];
		$atvproxy = $rows['ATVPROXYIP'];
		$atvpogover = $rows['ATVPOGOVER'];
		$atvatver = $rows['ATVATVER'];
		echo '<tr>' .
		'<td>' .
		"$id" .
		'</td>' .
	        '<td>' .
		"$name" .
		'</td>' .
                '<td>' .
		"$atvtemp" .
		'</td>' .
                '<td>' .
		"$localip" .
		'</td>';
        	if($noProxy === false){
                echo '<td>' .
		"$atvproxy" .
                '<form id="proxy" action="index.php" method="post">' .
                '<textarea name="proxy-' . $name . '" placeholder="IP:PORT" rows="1" style="resize:none"></textarea><br>' .
                '<input type="submit" value="Change" />' .
		'</form>';
		if(isset($_POST["proxy-$name"])){
		      $text = $_POST["proxy-$name"];
		      echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                      echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                      echo $res=shell_exec("adb shell settings put global http_proxy $text > /dev/null 2>&1");
		      echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
		      	$conn = new mysqli($servername, $username, $password, $dbname, $port);
		        // Checking for connections
			if ($conn->connect_error) {
    			die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
			}else{	
			$sql = " UPDATE Devices SET ATVPROXYIP = '$text' WHERE ID = $id; ";
			$conn->query($sql);
			echo "Changing Proxy";
			?>
			<script>
			window.location.reload();
			</script>
			<?php
			$conn->close();
			}
		}
                echo '</td>';
		}
		
		echo '<td>';
		echo 'Atlas Version:<br>';
		echo "$atvpogover";
		echo '<br>';
		echo 'Pogo Version:<br>';
		echo "$atvatver"; 
		echo '<br>';
		echo '<form id="ver-' . $name . '" action="index.php" method ="post" align="center">' .
                '<input type="submit" value="Get Versions" name="ver-' . $name . '" id="tablebutton"/></form>';
                if(isset($_POST["ver-$name"])){
                      echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                      echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                      $atver = shell_exec('adb shell dumpsys package com.pokemod.atlas | grep -E versionName | sed -e "s@    versionName=@@g"');
		      $pogver = shell_exec('adb shell dumpsys package com.nianticlabs.pokemongo | grep -E versionName | sed -e "s@    versionName=@@g"');
		      $conn = new mysqli($servername, $username, $password, $dbname, $port);
                      //Checking for connections
                      if ($conn->connect_error) {
                      die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
                      }else{
                      $sql = " UPDATE Devices SET ATVATVER = '$atver', ATVPOGOVER = '$pogver'  WHERE ID = $id; ";
                      $conn->query($sql);
                      echo "Checking Versions";
		      $conn->close();
		      echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
			?>
                        <script>
                        window.location.reload();
                        </script>
                      <?php
                      }
		}
		echo '</td>' .
		'<td>' .
		'<form id="reboot-' . $name . '" action="index.php" method ="post" align="center">' .
                '<input type="submit" value="Reboot" name="reboot-' . $name . '" id="tablebutton"/></form>';
		if(isset($_POST["reboot-$name"])){
			echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
			echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
			echo $res=shell_exec('adb shell reboot > /dev/null 2>&1');
			echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
		}
		
		echo '<form id="start-' . $name . '" action="index.php" method ="post" align="center">' .
                '<input type="submit" value="Start Atlas" name="start-' . $name . '" id="tablebutton"/></form>';
                if(isset($_POST["start-$name"])){
			echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
			echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                        echo $res=shell_exec('adb shell "am startservice com.pokemod.atlas/com.pokemod.atlas.services.MappingService" > /dev/null 2>&1');
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
		}
		echo '<form id="stop-' . $name . '" action="index.php" method ="post" align="center">' .
                '<input type="submit" value="Stop Atlas/Pogo" name="stop-' . $name . '" id="tablebutton"/></form>';
                if(isset($_POST["stop-$name"])){
		 	echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                        echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                        echo $res=shell_exec('adb shell "su -c am force-stop com.nianticlabs.pokemongo & am force-stop com.pokemod.atlas" > /dev/null 2>&1');
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
		}
		echo '<form id="uppoke-' . $name . '" action="index.php" method ="post" align="center">' .
                '<input type="submit" value="Update Pokemon" name="uppoke-' . $name . '" id="tablebutton"/></form>';
                if(isset($_POST["uppoke-$name"])){
			echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                        echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                        echo $res=shell_exec('adb install -r app/pokemongo.apk > /dev/null 2>&1');
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
		}
		echo '<form id="upat-' . $name . '" action="index.php" method ="post" align="center">' .
                '<input type="submit" value="Update Atlas" name="upat-' . $name . '" id="tablebutton"/></form>';
                if(isset($_POST["upat-$name"])){
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                        echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                        echo $res=shell_exec('adb install -r app/atlas.apk > /dev/null 2>&1');
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                }
		echo '<form id="upatcon-' . $name . '" action="index.php" method ="post" align="center">' .
                '<input type="submit" value="Update Atlas Config" name="upatcon-' . $name . '" id="tablebutton"/></form>';
                if(isset($_POST["upatcon-$name"])){
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                        echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                        echo $res=shell_exec('adb push app/atlas_config.json /data/local/tmp > /dev/null 2>&1');
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                }
		echo '<form id="puemag-' . $name . '" action="index.php" method ="post" align="center">' .
                '<input type="submit" value="Push eMagisk.zip" name="puemag-' . $name . '" id="tablebutton"/></form>';
                if(isset($_POST["puemag-$name"])){
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                        echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                        echo $res=shell_exec('adb push app/eMagisk.zip /sdcard > /dev/null 2>&1');
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                }
		echo '<form id="puemagcon-' . $name . '" action="index.php" method ="post" align="center">' .
                '<input type="submit" value="Push emagisk.config" name="puemagcon-' . $name . '" id="tablebutton"/></form>';
                if(isset($_POST["puemagcon-$name"])){
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                        echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                        echo $res=shell_exec('adb push app/emagisk.congif /data/local/tmp > /dev/null 2>&1');
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                }
		echo '</td>';
		if($noScreenshot === false){
		echo '<td>' .
		'<img src="screenshot/' . $name . '.png" width="100" height="160">' .
		'<form id="scrshot-' . $name . '" action="index.php" method ="post" align="center">' .
                '<input type="submit" value="Get Screenshot" name="scrshot-' . $name . '" id="tablebutton"/></form>';
		if(isset($_POST["scrshot-$name"])){
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                        echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
			echo $res=shell_exec('adb shell screencap -p /sdcard/screen.png > /dev/null 2>&1');
			echo $res=shell_exec("adb pull /sdcard/screen.png screenshot/$name.png > /dev/null 2>&1");
			echo $res=shell_exec("adb shell rm /sdcard/screen.png > /dev/null 2>&1");
			echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                }
                echo '</td>';
		}
		echo '</tr>';
                }
	echo '</table></center>';
}

?>
