#!/bin/bash

# rollback Primary VM
ssh niraj@10.242.91.160
rm -d ~/Desktop/Git/IT-490
cp ~/Desktop/Git/backup/* ~/Desktop/Git/IT-490

# rollback Stand By VM
ssh niraj@10.242.106.20
rm -d ~/Desktop/Git/IT-490
cp ~/Desktop/Git/backup/* ~/Desktop/Git/IT-490

