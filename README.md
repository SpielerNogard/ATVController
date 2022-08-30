# ATVController - WIP - Much to add and fix. 

ATVController for android devices running with RDM > POGO > ATLAS. 

Quick, simple and dirty add controls with a GUI view and MYSQL Storage. 

WARNING. POTENTIAL SECURUITY RISKS INVOLED.
Use at your own risk of course. 
Do not publicly expose the port. 
Local use only.
(On page shell. Unsanitized mysql inputs, This will change over time)

REQUIREMENTS 
Node (express) (node php)
npm install node-php

PM2
npm install pm2 -g

ADB
sudo apt-get install android-tools-adb android-tools-fastboot

MYSQL

THINGS TO EDIT
"public/config.php"

Set this to the starting 3 ranges of your ip
$lanip=0.0.0

Edit the DB info

Enable or Disable wth true=off/false=off
$noScreenshot
$noProxy

You shouldn't need to edit the ADB port

IPS file
"public/scripts/ips"
Edit and make a list with the ip ends of each device to finsh the ip address from config.php file
32
65
132
165

-------------------------------------------------------------------

HOW TO USE

Load the page. (http://localhost:3000) (Change port in ATVController.js)
On first page load it will create the table in the DB you selected.

The table will be blank.
The first option to the run and build the database with your device info hit 

"Build Info" This will take your 'ips' list and do the following
Get the Name, Proxy (if any) and store this infomation the the db along with the local ip from the 'config and ip files set'.

"Get all temps" 
Gets all temps from ALL devices and will build this info to display in the table. 

"Update All Devices"
Will Push the .apks from the folder location apps/.

"Reboot All Devices"
Will Reboot all devices defined in 'ips'.

"Start All Atlas"
Will Start the Atlas Mapping Service on all devices defined in 'ips'.

"Stop All Apps"
Will Stop Atlas Mapping Mervice And Pokemon on all devices defined in 'ips'.

-------------------------------------------------------------------

PER DEVICE CONTROLS

"Reboot"
Reboots device.

"start Atlas"
Start Atlas Mapping Service on device.

"Stop Atlas/Pogo"
Stop both Atlas and Pogo Services on device.

"Update Pokemon"
Installs the "pokemongo.apk" (rename the apk the match this title) app located inside folder /apps to the device.

"Updated Atlas"
Installs the "atlas.apk" (rename the apk the match this title) app located inside /apps folder to the device.

"Update Atlas Config"
Pushes the "atlas.config" located inside /apps folder to the device. (EDIT THIS FILE TO YOUR NEEDS)

"Push eMagisk.zip"
Pushes the "eMagisk.zip" located inside /apps folder to the device.

"Push emagisk.config"
Pushes the "emagisk.config" located inside /apps folder to the device. (EDIT THIS FILE TO YOUR NEEDS)

*** PROXY ***
"Change"
In the text area place your new proxy ip in the format 
IP:PORT
(Does not support username/password proxys yet, whitelist your IP)
 
*** SCREENSHOT***
"Get Screen Shot"
This section only builds on press of the button atm.



-------------------------------------------------------------------

Built by @zero-day-#0001

Scripts provided by @Xerock

 
