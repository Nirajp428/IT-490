#!/bin/bash

#find highest version tar.gz file name
$file = ls -lav /home/chiragkumar/git/deployment/dmz | tail -1 | cut -d " " -f 9

# copy tar file to Primary VM
scp -r /home/chiragkumar/git/deployment/dmz/$file kevin@10.242.172.64:/home/kevin/Desktop/Git/deployment/dmz

ssh kevin@10.242.172.64
cd /home/kevin/Desktop/Git/deployment/dmz

# back up IT-490 folder for rollback function
cp /home/kevin/Desktop/Git/IT-490/* /home/kevin/Desktop/Git/backup

#extract files in tar.gz to IT-490 folder
tar -xvf $file -C /home/kevin/Desktop/Git/IT-490

rm $file


# copy tar file to Stand By VM
scp -r /home/chiragkumar/git/deployment/dmz/$file kevin@10.242.223.100:/home/kevin/Desktop/Git/deployment/dmz


ssh kevin@10.242.223.100
cd /home/kevin/Desktop/Git/deployment/dmz

# back up IT-490 folder for rollback function
cp /home/kevin/Desktop/Git/IT-490/* /home/kevin/Desktop/Git/backup


#extract files in tar.gz to IT-490 folder
tar -xvf $file -C /home/kevin/Desktop/Git/IT-490

rm $file

