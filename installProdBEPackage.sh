#!/bin/bash

#find highest version tar.gz file name
$file = ls -lav /home/chiragkumar/git/deployment/BE | tail -1 | cut -d " " -f 9

# copy tar file to Primary VM
scp -r /home/chiragkumar/git/deployment/BE/$file niraj@10.242.91.160:/home/niraj/Desktop/Git/deployment/BE

ssh niraj@10.242.91.160
cd /home/niraj/Desktop/Git/deployment/BE

# back up IT-490 folder for rollback function
cp /home/niraj/Desktop/Git/IT-490/* /home/niraj/Desktop/Git/backup

#extract files in tar.gz to IT-490 folder
tar -xvf $file -C /home/niraj/Desktop/Git/IT-490

rm $file


# copy tar file to Stand By VM
scp -r /home/chiragkumar/git/deployment/BE/$file niraj@10.242.106.20:/home/niraj/Desktop/Git/deployment/BE


ssh niraj@10.242.106.20
cd /home/niraj/Desktop/Git/deployment/BE

# back up IT-490 folder for rollback function
cp /home/niraj/Desktop/Git/IT-490/* /home/niraj/Desktop/Git/backup


#extract files in tar.gz to IT-490 folder
tar -xvf $file -C /home/niraj/Desktop/Git/IT-490

rm $file
