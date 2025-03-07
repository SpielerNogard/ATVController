<?php

// SCRIPTS TO RUN ON ALL DEVICES

function deviceinfo() {
	echo 
	'<form id="deviceinfo" action="index.php" method ="post">' .
		'<button name="deviceinfo" type="submit" class="btn btn-primary menuButton">Build Info</button>' .
	'</form>';
	if(isset($_POST['deviceinfo'])){
		echo $res=shell_exec('scripts/deviceinfo.sh');
	}
}

function tempbutton() {
	echo
	'<form id="temp" action="index.php" method ="post">' .
		'<button name="temp" type="submit" class="btn btn-primary menuButton">Recollect Temps</button>' .
	'</form>';
	if(isset($_POST['temp'])){
		echo $res=shell_exec('scripts/tempcheck.sh');
	}
}

function rebootbutton() {
	echo 
	'<form id="reboot" action="index.php" method ="post">' . 
		'<button name="reboot" type="submit" class="btn btn-primary menuButton">Reboot ALL</button>' .
	'</form>';
	if(isset($_POST['reboot'])){
		echo $res=shell_exec('scripts/reboot.sh');
	}
}

function vercheck() {
        echo
        '<form id="vercheck" action="index.php" method ="post">' .
                '<button name="vercheck" type="submit" class="btn btn-primary menuButton">Recollect Versions</button>' .
        '</form>';
        if(isset($_POST['vercheck'])){
                echo $res=shell_exec('scripts/vercheck.sh');
        }
}

function anvercheck() {
        echo
        '<form id="anvercheck" action="index.php" method ="post">' .
                '<button name="anvercheck" type="submit" class="btn btn-primary menuButton">Recollect Android Version</button>' .
        '</form>';
        if(isset($_POST['anvercheck'])){
                echo $res=shell_exec('scripts/anvercheck.sh');
        }
}

function upatlas() {
	echo 
	'<form id="upatlas" action="index.php" method ="post">' . 
		'<button name="upatlas" type="submit" class="btn btn-primary menuButton">Update Atlas ALL</button>' .
	'</form>';
	if(isset($_POST['upatlas'])){
		echo $res=shell_exec('scripts/upat.sh');
	}
}

function startbutton() {
	echo 
	'<form id="start" action="index.php" method ="post">' . 
		'<button name="start" type="submit" class="btn btn-primary menuButton">Start Scanning ALL</button>' .
	'</form>';
	if(isset($_POST['start'])){
		echo $res=shell_exec('scripts/start.sh');
	}
}

function stopbutton() {
	echo 
	'<form id="stop" action="index.php" method ="post">' . 
		'<button name="stop" type="submit" class="btn btn-primary menuButton">Stop Scanning ALL</button>' .
	'</form>';
	if(isset($_POST['stop'])){
		echo $res=shell_exec('scripts/stop.sh');
	}
}
function uppogo() {
	echo 
	'<form id="uppogo" action="index.php" method ="post">' .
                '<button name="uppogo" type="submit" class="btn btn-primary menuButton">Update Pokemon ALL</button>' .
        '</form>';	
	if(isset($_POST['uppogo'])){
		echo $res=shell_exec('scripts/uppogo.sh');
	}
}
function moreToCome() {
	echo
	'<form>' .
		'<button class="btn btn-secondary menuButton">More Soon ➜</button>' .
	'</form>';
	//if(isset($_POST['NotSetYet'])){
	//	echo $res=shell_exec('scripts/stop.sh');
	//}
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
echo '<div class="cssContainer">' . 
	'<table class="table table-dark table-striped">' .
		'<thead class="text-center">' . 
			'<tr>' .
				'<th>ID</th>' .
				'<th>Device Name</th>' .
				'<th>Temp</th>' .
				'<th>Local IP</th>';
	
				if($noProxy === false){
				echo '<th>Proxy IP</th>';
				}
		
				echo '<th>PoGo Version</th>' .
				'<th>Atlas Version</th>' .
				'<th>Android Version</th>' .
				'<th>Controls</th>';
	
				if($noScreenshot === false){
				echo '<th>Screenshot</th>';
				}	
			echo '</tr>' .
		'</thead>';
		echo '<tbody class="text-center">';
		while($rows=$result->fetch_assoc()){
			$id = $rows['ID'];	
			$name = $rows['ATVNAME'];
			$atvtemp = $rows['ATVTEMP'];
			$localip = $rows['ATVLOCALIP'];
			$atvproxy = $rows['ATVPROXYIP'];
			$atvpogover = $rows['ATVPOGOVER'];
			$atvatver = $rows['ATVATVER'];
			$anver = $rows['ANDROIDVER'];
			echo '<tr>' .
				'<td class="align-middle">' . $id . '</td>' .
				'<td class="align-middle">' . $name . '</td>' .
				'<td class="align-middle">' . $atvtemp . '</td>' .
				'<td class="align-middle">' . $localip . '</td>';
				if($noProxy === false){
					echo '<td>' . $atvproxy .
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
							echo "Changing Proxy"; ?>
							
							<script>
								window.location.reload();
							</script>
							<?php
							$conn->close();
						}
					}
					echo '</td>';
				}
				
				
				// Get PoGo Version
				echo '<td class="text-center align-middle"> ' . $atvpogover . '</td>';
				
				// Get Atlas Version
				echo '<td class="align-middle">' . $atvatver . '</td>';

				// Get Android Version
                                echo '<td class="text-center align-middle"> ' . $anver . '</td>';

				echo '<td class="controlTable">'; // Device Options for Users ---
				
					// Reboot Device
					echo
					'<div class="tab">
						<button class="tablink tablinks-' . $name . '" onclick="openTab(event, \'tabGeneral-' . $name .'\', \'' . $name . '\')">General</button>
						<button class="tablink tablinks-' . $name . '" onclick="openTab(event, \'tabAtlas-' . $name .'\', \'' . $name . '\')">Atlas</button>
						<button class="tablink tablinks-' . $name . '" onclick="openTab(event, \'tabAPKs-' . $name .'\', \'' . $name . '\')">APKs</button>
						<button class="tablink tablinks-' . $name . '" onclick="openTab(event, \'tabMisc-' . $name .'\', \'' . $name . '\')">Misc</button>
					</div>';
					
					//General TAB
					echo '<div id="tabGeneral-' . $name .'" class="tabcontent tabcontent-' . $name .'">';
							
							// Get PoGo Version
							echo '<form class="d-inline" id="version-pogo-' . $name . '" action="index.php" method ="post">' .
								'<button name="version-pogo-' . $name . '" type="submit" class="btn btn-primary controlButton">Get Version PoGo</button>' .
                            '</form>';
							if(isset($_POST["version-pogo-$name"])){
								echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
								echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
								$pogver = shell_exec('adb shell dumpsys package com.nianticlabs.pokemongo | grep -E versionName | sed -e "s@    versionName=@@g"');
								$conn = new mysqli($servername, $username, $password, $dbname, $port);
								//Checking for connections
								if ($conn->connect_error) {
										die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
								}else {
										$sql = " UPDATE Devices SET ATVPOGOVER = '$pogver' WHERE ID = $id; ";
										$conn->query($sql);
										echo "Checking PoGo Version";
										$conn->close();
										echo $res=shell_exec('adb kill-server > /dev/null 2>&1'); ?>
										<script>
										window.location.reload();
										</script>
								<?php
								}
							}

							// Get Atlas Version
							echo '<form class="d-inline" id="version-atlas-' . $name . '" action="index.php" method ="post">' .
								'<button name="version-atlas-' . $name . '" type="submit" class="btn btn-primary controlButton">Get Version Atlas</button>' .
                            '</form>';
                            if(isset($_POST["version-atlas-$name"])){
                                echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                                echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                                $atver = shell_exec('adb shell dumpsys package com.pokemod.atlas | grep -E versionName | sed -e "s@    versionName=@@g"');
                                $conn = new mysqli($servername, $username, $password, $dbname, $port);
                                //Checking for connections
                                if ($conn->connect_error) {
                                        die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
                                }else {
                                        $sql = " UPDATE Devices SET ATVATVER = '$atver' WHERE ID = $id; ";
                                        $conn->query($sql);
                                        echo "Checking Atlas Version";
                                        $conn->close();
                                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1'); ?>
                                        <script>
                                        window.location.reload();
                                        </script>
                                <?php
                                }
                            }	
							
							// get Android Version
							echo '<form class="d-inline" id="version-android-' . $name . '" action="index.php" method ="post">' .
								'<button name="version-android-' . $name . '" type="submit" class="btn btn-primary controlButton">Get Version Android</button>' .
                            '</form>';
                            if(isset($_POST["version-android-$name"])){
                                echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                                echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                                $anvers= shell_exec('adb shell getprop ro.build.version.release');
                                $conn = new mysqli($servername, $username, $password, $dbname, $port);
                                //Checking for connections
                                if ($conn->connect_error) {
                                        die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
                                }else {
                                        $sql = " UPDATE Devices SET ANDROIDVER = '$anvers' WHERE ID = $id; ";
                                        $conn->query($sql);
                                        echo "Checking Android Version";
                                        $conn->close();
                                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1'); ?>
                                        <script>
                                        window.location.reload();
                                        </script>
                                <?php
                                }
							}

							// Reboot Single device 
							echo '<form class="d-inline" id="reboot-' . $name . '" action="index.php" method ="post">' .
								'<button name="reboot-' . $name . '" type="submit" class="btn btn-primary controlButton">Reboot</button>' .
							'</form>';
							if(isset($_POST["reboot-$name"])){
								echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
								echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
								echo $res=shell_exec('adb shell reboot > /dev/null 2>&1');
								echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
							}
							// Get Screenshot
							echo
	                        '<form class="d-inline" id="scrshot-' . $name . '" action="index.php" method ="post" align="center">' .
                                '<button name="scrshot-' . $name . '" type="submit" class="btn btn-success controlButton">Get Screenshot!</button>' .
        	                '</form>';
                	        if(isset($_POST["scrshot-$name"])){
                                echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                                echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                                echo $res=shell_exec('adb shell screencap -p /sdcard/screen.png > /dev/null 2>&1');
                                echo $res=shell_exec("adb pull /sdcard/screen.png screenshot/$name.png > /dev/null 2>&1");
                                echo $res=shell_exec("adb shell rm /sdcard/screen.png > /dev/null 2>&1");
                                echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                        	}
					echo '</div>';
							
					//Atlas TAB
					echo '<div id="tabAtlas-' . $name .'" class="tabcontent tabcontent-' . $name .'">';
						
						// Start Atlas
						echo '<form class="d-inline" id="start-' . $name . '" action="index.php" method ="post">' .
							'<button name="start-' . $name . '" type="submit" class="btn btn-success controlButton">Start Atlas</button>' .
						'</form>';
						if(isset($_POST["start-$name"])){
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
							echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
							echo $res=shell_exec('adb shell "am startservice com.pokemod.atlas/com.pokemod.atlas.services.MappingService" > /dev/null 2>&1');
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
						}
						// Stop Pogo & Atlas
						echo '<form class="d-inline" id="stop-' . $name . '" action="index.php" method ="post">' .
							'<button name="stop-' . $name . '" type="submit" class="btn btn-danger controlButton">Stop Atlas</button>' .
						'</form>';
						if(isset($_POST["stop-$name"])){
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
							echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
							echo $res=shell_exec('adb shell "su -c am force-stop com.nianticlabs.pokemongo & am force-stop com.pokemod.atlas" > /dev/null 2>&1');
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
						}
						// Update Atlas Config
						echo '<form class="d-inline" id="config-atlas-' . $name . '" action="index.php" method ="post">' .
							'<button name="update-atlas-' . $name . '" type="submit" class="btn btn-warning controlButton">Push Atlas Config</button>' .
						'</form>';
						if(isset($_POST["config-atlas-$name"])){
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
							echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
							echo $res=shell_exec('adb push app/atlas_config.json /data/local/tmp > /dev/null 2>&1');
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
						}
					echo '</div>';
					
					// APKs TAB
					echo '<div id="tabAPKs-' . $name .'" class="tabcontent tabcontent-' . $name .'">';

						// Update PoGo APK
						echo '<form class="d-inline" id="update-pogo-' . $name . '" action="index.php" method ="post">' .
							'<button name="update-pogo-' . $name . '" type="submit" class="btn btn-primary controlButton">Push PoGo APK</button>' .
						'</form>';
						if(isset($_POST["update-pogo-$name"])){
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
							echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
							echo $res=shell_exec('adb install -r app/pokemongo.apk > /dev/null 2>&1');
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
						}	

						// Update Atlas APK
						echo '<form class="d-inline" id="update-atlas-' . $name . '" action="index.php" method ="post">' .
							'<button name="update-atlas-' . $name . '" type="submit" class="btn btn-primary controlButton">Push Atlas APK</button>' .
						'</form>';
						if(isset($_POST["update-atlas-$name"])){
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
							echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
							echo $res=shell_exec('adb install -r app/atlas.apk > /dev/null 2>&1');
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
						}
					echo '</div>';// End of Device Options Tablerow
					
					// Misc TAB
					echo '<div id="tabMisc-' . $name .'" class="tabcontent tabcontent-' . $name .'">';
						// Push eMagisk.zip to Device
							echo '<form class="d-inline" id="push-emagisk-' . $name . '" action="index.php" method ="post">' .
								'<button name="push-emagisk-' . $name . '" type="submit" class="btn btn-primary controlButton">Push eMagisk.zip</button>' .
							'</form>';
							if(isset($_POST["push-emagisk-$name"])){
								echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
								echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
								echo $res=shell_exec('adb push app/eMagisk.zip /sdcard > /dev/null 2>&1');
								echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
							}
							
						// Push eMagisk Config to Device
							echo '<form class="d-inline" id="config-emagisk-' . $name . '" action="index.php" method ="post">' .
								'<button name="config-emagisk-' . $name . '" type="submit" class="btn btn-primary controlButton">Push eMagisk Config</button>' .
							'</form>';
							if(isset($_POST["config-emagisk-$name"])){
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
							echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
							echo $res=shell_exec('adb push app/emagisk.congig /data/local/tmp > /dev/null 2>&1');
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
							}
					echo '</div>';// End of Device Options Tablerow
					
				echo '</td>';?> 

				<script>
					function openTab(evt, tabName, deviceName) {
						var i, tabcontent, tablinks;
						if(evt.currentTarget.classList.contains("active")){
							document.getElementById(tabName).style.display = "none";
							tablinks = document.getElementsByClassName("tablinks-" + deviceName);
							for (i = 0; i < tablinks.length; i++) {
								tablinks[i].className = tablinks[i].className.replace(" active", "");
							}
						}else {
							tabcontent = document.getElementsByClassName("tabcontent-" + deviceName);
							for (i = 0; i < tabcontent.length; i++) {
								tabcontent[i].style.display = "none";
							}
							tablinks = document.getElementsByClassName("tablinks-" + deviceName);
							for (i = 0; i < tablinks.length; i++) {
								tablinks[i].className = tablinks[i].className.replace(" active", "");
							}
							document.getElementById(tabName).style.display = "block";
							evt.currentTarget.className += " active";
						}
					}
					
					// Get the element with id="defaultOpen" and click on it
					//document.getElementById("defaultOpen").click();
				</script>
				<?php
				if($noScreenshot === false){
					echo '<td class="align-middle">';
					$filename = __DIR__ .'/screenshot/' . $name . '.png';
					if(file_exists($filename)){
						echo 
						'<div class="imageContainer">' .
							'<a href="screenshot/' . $name . '.png" target="_blank" >' .
								'<img src="screenshot/' . $name . '.png" width="25" height="40" />' .
							'</a>' .
						'</div>';
					}
					else{
						echo 'No Screenshot Found.';
					}
					echo '</td>';
				}
			echo '</tr>';
		}
	echo '</tbody>
	</table>
</div>';
}

?>
