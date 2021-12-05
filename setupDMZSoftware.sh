#!/bin/bash


# install software for the DMZ Dev VM
ssh -t kevin@10.147.17.106 "sudo apt upgrade"
ssh -t kevin@10.147.17.106 "sudo apt install -y git php php-amqp vim curl php-curl"



user=$(whoami)
# create a SSH key pair
cat /dev/zero | ssh-keygen -q -f ~/$user/.ssh/id_rsa -t rsa -b 4096 -N ""

#Dev deployment server
ssh-copy-id "IT490 Server Deployment"@10.147.17.236
