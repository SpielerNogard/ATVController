#!/bin/bash

IFS=$'\n'
lanip="$(grep -oE '\$lanip = .*;' config.php | tail -1 | sed 's/$lanip = //g;s/;//g;s/^"//;s/"$//')"
dbhost="$(grep -oE '\$servername = .*;' config.php | tail -1 | sed 's/$servername = //g;s/;//g;s/^"//;s/"$//')"
dbuser="$(grep -oE '\$username = .*;' config.php | tail -1 | sed 's/$username = //g;s/;//g;s/^"//;s/"$//')"
dbpass="$(grep -oE '\$password = .*;' config.php | tail -1 | sed 's/$password = //g;s/;//g;s/^"//;s/"$//')"
db="$(grep -oE '\$dbname = .*;' config.php | tail -1 | sed 's/$dbname = //g;s/;//g;s/^"//;s/"$//')"
adbport="$(grep -oE '\$adbport = .*;' config.php | tail -1 | sed 's/$adbport = //g;s/;//g;s/^"//;s/"$//')"
adb kill-server > /dev/null 2>&1
for i in `cat scripts/ips` ; do
  if [[ $i =~ "{".* ]] ; then
    first=$(echo $i | cut -d '.' -f1 | cut -d '{' -f2)
    last=$(echo $i | cut -d '.' -f3 | cut -d '}' -f1)
    for (( j = $first ; j <= $last ; j++ )) ; do
      ip="$lanip.$j"
      adb start-server > /dev/null 2>&1
      sleep 1
      adb connect $ip:$adbport > /dev/null 2>&1
      sleep 1
      name=$(adb shell cat /data/local/tmp/atlas_config.json | grep -oP '"deviceName": *"\K[^"]*')
      pip=$(adb shell settings list global | grep "global_http_proxy_host" | cut -d '=' -f2)
      pipp=$(adb shell settings list global | grep "global_http_proxy_port" | cut -d '=' -f2)
      fpip="$pip:$pipp"
      sleep 1
      mysql -u $dbuser -p$dbpass -h $dbhost -D $db -e "INSERT INTO Devices (ATVNAME, ATVLOCALIP, ATVPROXYIP) VALUES ('$name', '$ip', '$fpip') ON DUPLICATE KEY UPDATE ATVNAME = '$name';"
      adb kill-server > /dev/null 2>&1
    done
  else
    adb start-server > /dev/null 2>&1
    sleep 1
    ip="$lanip.$i"
    adb connect $ip:$adbport > /dev/null 2>&1
    sleep 1
    name=$(adb shell cat /data/local/tmp/atlas_config.json | grep -oP '"deviceName": *"\K[^"]*')
    pip=$(adb shell settings list global | grep "global_http_proxy_host" | cut -d '=' -f2)
    pipp=$(adb shell settings list global | grep "global_http_proxy_port" | cut -d '=' -f2)
    fpip="$pip:$pipp"
    sleep 1
    mysql -u $dbuser -p$dbpass -h $dbhost -D $db -e "INSERT INTO Devices (ATVNAME, ATVLOCALIP, ATVPROXYIP) VALUES ('$name', '$ip', '$fpip') ON DUPLICATE KEY UPDATE ATVNAME = '$name';"
    adb kill-server
  fi
done
adb kill-server > /dev/null 2>&1
