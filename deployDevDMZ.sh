#!/bin/bash
mkdir -p ~/Desktop/Git/deployment
cd ~/Desktop/Git/deployment
for ((x=1; x<=10000; x++))
do
	# if you don't find the version number from 1 to 10000, create it.
	# i.e. don't find version 1, create it. Next time, don't find version 2, create it, and so on...
	if ! [[ -f "DMZDev$x.tar.gz" ]]
	then
		tar -czvf DMZDev$x.tar.gz ~/Desktop/Git/IT-490/*
		scp DMZDev$x.tar.gz chiragkumar@10.147.17.236:/home/chiragkumar/git/deployment/dmz
		break
	fi
done
