#!/bin/bash

#find highest version tar.gz file name
$file = ls -lav /home/chiragkumar/git/deployment/FE | tail -1 | cut -d " " -f 9

# copy tar file to Primary VM
scp -r /home/chiragkumar/git/deployment/FE/$file mattv@<IP ADDRESS>:/home/mattv/Desktop/Git/deployment/FE

ssh mattv@<IP ADDRESS>
cd /home/mattv/Desktop/Git/deployment/FE

# back up IT-490 folder for rollback function
rm -d ~/Desktop/Git/backup
cp /home/mattv/Desktop/Git/IT-490/* /home/mattv/Desktop/Git/backup

#extract files in tar.gz to IT-490 folder
tar -xvf $file -C /home/mattv/Desktop/Git/IT-490

# copy files to /var/www/html for front end
sudo rm -d /var/www/html
sudo cp /home/mattv/Desktop/Git/IT-490/* /var/www/html

rm $file


# copy tar file to Stand By VM
scp -r /home/chiragkumar/git/deployment/FE/$file mattv@<IP ADDRESS>:/home/mattv/Desktop/Git/deployment/FE


ssh mattv@<IP ADDRESS>
cd /home/mattv/Desktop/Git/deployment/FE

# back up IT-490 folder for rollback function
rm -d ~/Desktop/Git/backup
cp /home/mattv/Desktop/Git/IT-490/* /home/mattv/Desktop/Git/backup


#extract files in tar.gz to IT-490 folder
tar -xvf $file -C /home/mattv/Desktop/Git/IT-490

# copy files to /var/www/html for front end
sudo rm -d /var/www/html
sudo cp /home/mattv/Desktop/Git/IT-490/* /var/www/html

rm $file
