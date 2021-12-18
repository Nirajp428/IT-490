#!/bin/bash

# rollback Primary VM
ssh mattv@<IP ADDRESS>
rm -d ~/Desktop/Git/IT-490
cp ~/Desktop/Git/backup/* ~/Desktop/Git/IT-490

sudo rm -d /var/www/html 
sudo cp ~/Desktop/Git/backup/* /var/www/html

# rollback Stand By VM
ssh mattv@<IP ADDRESS>
rm -d ~/Desktop/Git/IT-490
cp ~/Desktop/Git/backup/* ~/Desktop/Git/IT-490

sudo rm -d /var/www/html
sudo cp ~/Desktop/Git/backup/* /var/www/html


