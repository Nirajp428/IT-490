#!/bin/bash

# rollback Primary VM
ssh kevin@10.242.172.64
rm -d ~/Desktop/Git/IT-490
cp ~/Desktop/Git/backup/* ~/Desktop/Git/IT-490

# rollback Stand By VM
ssh kevin@10.242.223.100
rm -d ~/Desktop/Git/IT-490
cp ~/Desktop/Git/backup/* ~/Desktop/Git/IT-490

