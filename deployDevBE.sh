#!/bin/bash
mkdir -p ~/Desktop/Git/deployment
cd ~/Desktop/Git/deployment
for ((x=1; x<=10000; x++))
do
	# if you don't find the version number from 1 to 10000, create it.
	# i.e. don't find version 1, create it. Next time, don't find version 2, create it, and so on...
	if ! [[ -f "BEDev$x.tar.gz" ]]
	then
		tar -czvf BEDev$x.tar.gz ~/Desktop/Git/IT-490/*
		scp BEDev$x.tar.gz deploymentserver@10.147.17.236:/home/chiragkumar/git/deployment/BE
		break
	fi
done
