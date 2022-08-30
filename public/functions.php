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

function newtable() {
include("config.php");

//MYSQLI CONNECTION

$conn = new mysqli($servername, $username, $password, $dbname);
// Checking for connections
if ($conn->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
}
$sql = " SELECT * FROM Devices; ";
$result = $conn->query($sql);
$conn->close();
?>

<!--START OF TABLE INFO -->
<center><table>
	<tr>
	<th>ID</th>
        <th>Device Name</th>
        <th>Temp</th>
	<th>Local IP</th>
	<?php
        if($noProxy === false){
        ?>
	<th>Proxy IP</th>
	<?php
	}
	?>
	<th>App Versions</th>
	<th>Controls</th>
	<?php
        if($noScreenshot === false){
        ?>
	<th>Screenshot</th>
	<?php
	}	
	?>
	</tr>
              	<?php
                while($rows=$result->fetch_assoc())
		{
                ?>
		<tr>
		<td><?php echo $rows['ID'];?></td>
                <?php
                $id = $rows['ID'];
                ?>
                <td><?php echo $rows['ATVNAME'];?></td>
                <?php
                $name = $rows['ATVNAME'];
                ?>
                <td><?php echo $rows['ATVTEMP'];?></td>
                <?php
                $atvtemp = $rows['ATVTEMP'];
                ?>
                <td><?php echo $rows['ATVLOCALIP'];?></td>
		<?php                
                $localip = $rows['ATVLOCALIP'];
        	if($noProxy === false){
        	?>
                <td>
		<?php echo $rows['ATVPROXYIP']; ?>
                <form id="proxy" action="index.php" method="post">
                <textarea name="proxy-<?php echo "$name"; ?>" placeholder="IP:PORT" rows="1" style="resize:none"></textarea><br>
                <input type="submit" value="Change" />
		</form>
		<?php
		if(isset($_POST["proxy-$name"])){
		      $text = $_POST["proxy-$name"];
		      echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                      echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                      echo $res=shell_exec("adb shell settings put global http_proxy $text > /dev/null 2>&1");
		      echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
		      	$conn = new mysqli($servername, $username, $password, $dbname);
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
			//$conn->close();
			}
		}
		?>
                </td>
		<?php
		}
		?>
		<td>
		<?php
		echo 'Atlas Version:<br>';
		echo $rows['ATVATVER'];
		echo '<br>';
		echo 'Pogo Version:<br>';
		echo $rows['ATVPOGOVER']; 
		echo '<br>';
		?>
		<form id='ver-<?php echo "$name" ?>' action='index.php' method ='post' align='center'>
                <input type='submit' value='Get Versions' name='ver-<?php echo "$name" ?>' id='tablebutton'/></form>
                <?php
                if(isset($_POST["ver-$name"])){
                      echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                      echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                      $atver = shell_exec('adb shell dumpsys package com.pokemod.atlas | grep -E versionName | sed -e "s@    versionName=@@g"');
		      $pogver = shell_exec('adb shell dumpsys package com.nianticlabs.pokemongo | grep -E versionName | sed -e "s@    versionName=@@g"');
		      $conn = new mysqli($servername, $username, $password, $dbname);
                      //Checking for connections
                      if ($conn->connect_error) {
                      die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
                      }else{
                      $sql = " UPDATE Devices SET ATVATVER = '$atver', ATVPOGOVER = '$pogver'  WHERE ID = $id; ";
                      $conn->query($sql);
                      echo "Checking Versions";
                      ?>
                        <script>
                        window.location.reload();
                        </script>
                        <?php
                        //$conn->close();
                      }
		echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
		}
                ?>
		</td>
		<td>
		<form id='reboot-<?php echo "$name" ?>' action='index.php' method ='post' align='center'>
                <input type='submit' value='Reboot' name='reboot-<?php echo "$name" ?>' id='tablebutton'/></form>
		<?php
		if(isset($_POST["reboot-$name"])){
			echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
			echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
			echo $res=shell_exec('adb shell reboot > /dev/null 2>&1');
			echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
		}
		?>
                <form id='start-<?php echo "$name" ?>' action='index.php' method ='post' align='center'>
                <input type='submit' value='Start Atlas' name='start-<?php echo "$name" ?>' id='tablebutton'/></form>
                <?php
                if(isset($_POST["start-$name"])){
			echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
			echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                        echo $res=shell_exec('adb shell "am startservice com.pokemod.atlas/com.pokemod.atlas.services.MappingService" > /dev/null 2>&1');
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
		}
                ?>
                <form id='stop-<?php echo "$name" ?>' action='index.php' method ='post' align='center'>
                <input type='submit' value='Stop Atlas/Pogo' name='stop-<?php echo "$name" ?>' id='tablebutton'/></form>
                <?php
                if(isset($_POST["stop-$name"])){
		 	echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                        echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                        echo $res=shell_exec('adb shell "su -c am force-stop com.nianticlabs.pokemongo & am force-stop com.pokemod.atlas" > /dev/null 2>&1');
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
		}
                ?>
                <form id='uppoke-<?php echo "$name" ?>' action='index.php' method ='post' align='center'>
                <input type='submit' value='Update Pokemon' name='uppoke-<?php echo "$name" ?>' id='tablebutton'/></form>
                <?php
                if(isset($_POST["uppoke-$name"])){
			echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                        echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                        echo $res=shell_exec('adb install -r app/pokemongo.apk > /dev/null 2>&1');
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
		}
		?>
		<form id='upat-<?php echo "$name" ?>' action='index.php' method ='post' align='center'>
                <input type='submit' value='Update Atlas' name='upat-<?php echo "$name" ?>' id='tablebutton'/></form>
                <?php
                if(isset($_POST["upat-$name"])){
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                        echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                        echo $res=shell_exec('adb install -r app/atlas.apk > /dev/null 2>&1');
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                }
		?>
		<form id='upatcon-<?php echo "$name" ?>' action='index.php' method ='post' align='center'>
                <input type='submit' value='Update Atlas Config' name='upatcon-<?php echo "$name" ?>' id='tablebutton'/></form>
                <?php
                if(isset($_POST["upatcon-$name"])){
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                        echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                        echo $res=shell_exec('adb push app/atlas_config.json /data/local/tmp > /dev/null 2>&1');
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                }
		?>
		<form id='puemag-<?php echo "$name" ?>' action='index.php' method ='post' align='center'>
                <input type='submit' value='Push eMagisk.zip' name='puemag-<?php echo "$name" ?>' id='tablebutton'/></form>
                <?php
                if(isset($_POST["puemag-$name"])){
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                        echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                        echo $res=shell_exec('adb push app/eMagisk.zip /sdcard > /dev/null 2>&1');
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                }
                ?>
		<form id='puemagcon-<?php echo "$name" ?>' action='index.php' method ='post' align='center'>
                <input type='submit' value='Push emagisk.config' name='puemagcon-<?php echo "$name" ?>' id='tablebutton'/></form>
                <?php
                if(isset($_POST["update-$name"])){
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                        echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                        echo $res=shell_exec('adb push app/emagisk.congif /data/local/tmp > /dev/null 2>&1');
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                }
		?>
		</td>
		<?php
		if($noScreenshot === false){
		?>
		<td>
		<img src='screenshot/<?php echo "$name"; ?>.png' width='100' height='160'>
		<form id='scrshot-<?php echo "$name" ?>' action='index.php' method ='post' align='center'>
                <input type='submit' value='Get Screen Shot' name='scrshot-<?php echo "$name" ?>' id='tablebutton'/></form>
                <?php
		if(isset($_POST["scrshot-$name"])){
                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                        echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
			echo $res=shell_exec('adb shell screencap -p /sdcard/screen.png > /dev/null 2>&1');
			echo $res=shell_exec("adb pull /sdcard/screen.png screenshot/$name.png > /dev/null 2>&1");
			echo $res=shell_exec("adb shell rm /sdcard/screen.png > /dev/null 2>&1");
			echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                }
                ?>
                </td>
		<?php
		}
		?>
		</tr>
		<?php
                }
                ?>
</table></center>
<?php
}

?>
