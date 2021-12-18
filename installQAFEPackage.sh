#!/bin/bash

#find highest version tar.gz file name
$file = ls -lav /home/chiragkumar/git/deployment/FE | tail -1 | cut -d " " -f 9

scp -r /home/chiragkumar/git/deployment/FE/$file mattv@<IP ADDRESS>:/home/mattv/Desktop/Git/deployment/FE

ssh mattv@<IP ADDRESS>
cd /home/mattv/Desktop/Git/deployment/FE

#extract files in tar.gz to IT-490 folder
tar -xvf $file -C /home/mattv/Desktop/Git/IT-490

#remove tar.gz file
rm $file

