#!/bin/bash

#find highest version tar.gz file name
$file = ls -lav /home/chiragkumar/git/deployment/dmz | tail -1 | cut -d " " -f 9

ssh kevin@192.168.193.247 "
mkdir /home/kevin/Desktop/Git/deployment/dmz
mkdir /home/kevin/Desktop/Git/IT-490
"

scp -r /home/chiragkumar/git/deployment/dmz/$file kevin@192.168.193.247:/home/kevin/Desktop/Git/deployment/dmz

ssh kevin@192.168.193.247 "
cd /home/kevin/Desktop/Git/deployment/dmz
#extract files in tar.gz to IT-490 folder
tar -xvf $file -C /home/kevin/Desktop/Git/IT-490

cd /home/kevin/Desktop/Git/deployment/dmz
rm $file
"
