#!/bin/bash


user=$(whoami)
# create a SSH key pair
cat /dev/zero | ssh-keygen -q -f ~/$user/.ssh/id_rsa -t rsa -b 4096 -N ""

#Prod DMZ server
ssh-copy-id kevin@10.242.172.64


#cat ~/.ssh/id_rsa.pub | ssh remote_username@server_ip_address "mkdir -p ~/.ssh && chmod 700 ~/.ssh && cat >> ~/.ssh/authorized_keys && chmod 600 ~/.ssh/authorized_keys"

