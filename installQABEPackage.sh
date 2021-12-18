#!/bin/bash

#find highest version tar.gz file name
$file = ls -lav /home/chiragkumar/git/deployment/BE | tail -1 | cut -d " " -f 9

scp -r /home/chiragkumar/git/deployment/BE/$file niraj@192.168.193.224:/home/niraj/Desktop/Git/deployment/BE

ssh niraj@192.168.193.224
cd /home/niraj/Desktop/Git/deployment/BE

#extract files in tar.gz to IT-490 folder
tar -xvf $file -C /home/niraj/Desktop/Git/IT-490
rm $file

