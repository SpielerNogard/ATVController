#!/bin/bash

IFS=$'\n'
lanip="$(grep -oE '\$lanip = .*;' config.php | tail -1 | sed 's/$lanip = //g;s/;//g;s/^"//;s/"$//')"
adbport="$(grep -oE '\$adbport = .*;' config.php | tail -1 | sed 's/$adbport = //g;s/;//g;s/^"//;s/"$//')"
for i in `cat scripts/ips` ; do
  if [[ $i =~ "{".* ]] ; then
    first=$(echo $i | cut -d '.' -f1 | cut -d '{' -f2)
    last=$(echo $i | cut -d '.' -f3 | cut -d '}' -f1)
    for (( j = $first ; j <= $last ; j++ )) ; do
      ip="$lanip.$j"
      adb start-server > /dev/null 2>&1
      adb connect $ip:$adbport > /dev/null 2>&1
      sleep 1
      adb install -r app/pokemongo.apk > /dev/null 2>&1
      adb install -r app/atlas.apk > /dev/null 2>&1
      adb kill-server > /dev/null 2>&1
    done
  else
    ip="$lanip.$i"
    adb start-server > /dev/null 2>&1
    adb connect $ip:$adbport > /dev/null 2>&1 
    sleep 1
    adb install -r app/pokemongo.apk > /dev/null 2>&1
    adb install -r app/atlas.apk > /dev/null 2>&1:w
    adb kill-server > /dev/null 2>&1
  fi
done
