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
      sleep .5
      adb connect $ip:$adbport > /dev/null 2>&1
      sleep .5
      temp=$(adb shell cat /sys/class/thermal/thermal_zone0/temp | awk '{print substr($0, 1, length($0)-3)}')
      mysql -u $dbuser -p$dbpass -h $dbhost -D $db -e "UPDATE Devices SET ATVTEMP = '$temp' WHERE ATVLOCALIP = '$ip';"
      adb kill-server > /dev/null 2>&1
    done
  else
    ip="$lanip.$i"
    adb start-server > /dev/null 2>&1
    sleep .5
    adb connect $ip:$adbport > /dev/null 2>&1 
    sleep .5
    temp=$(adb shell cat /sys/class/thermal/thermal_zone0/temp | awk '{print substr($0, 1, length($0)-3)}')
    mysql -u $dbuser -p$dbpass -h $dbhost -D $db -e "UPDATE Devices SET ATVTEMP = '$temp' WHERE ATVLOCALIP = '$ip';"
    adb kill-server > /dev/null 2>&1
  fi
done
adb kill-server > /dev/null 2>&1
