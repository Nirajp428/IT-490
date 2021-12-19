#!/bin/bash

cd /home/chiragkumar/git/setup
scp testRabbitSetup.sh rabbitmqadmin keepPrimaryFERunning.service primaryFERabbitMQServer.php mattv@<IP ADDRESS>:/tmp


# primary FE
ssh mattv@<IP ADDRESS>

# install software
sudo apt upgrade
sudo apt install -y php php-amqp vim curl openssh-server openssh-client gufw apache2

# RabbitMQ Setup
cd /tmp
sudo ./testRabbitSetup.sh

# ufw firewall
sudo ufw reset

# http port
sudo ufw allow proto tcp from [<IP ADDRESS>] to any port 80

# https port
sudo ufw allow proto tcp from [<IP ADDRESS>] to any port 443

# ssh/scp port
sudo ufw allow proto tcp from [<IP ADDRESS>] to any port 22

# zerotier port
sudo ufw allow proto udp from [<IP ADDRESS>] to any port 9993

# RabbitMQ port
sudo ufw allow proto tcp from [<IP ADDRESS>] to any port 5672

# systemd
cp /tmp/primaryFERabbitMQServer.php ~/Git/IT-490
sudo cp /tmp/keepPrimaryFERunning.service /etc/systemd/system
sudo systemctl daemon-reload
sudo systemctl start keepPrimaryFERunning.service
sudo systemctl enable keepPrimaryFERunning.service

exit

# stand by FE
cd /home/chiragkumar/git/setup
scp testRabbitSetup.sh rabbitmqadmin keepStandbyFERunning.service standbyFERabbitMQServer.php mattv@<IP ADDRESS>:/tmp

ssh mattv@<IP ADDRESS>

# install software
sudo apt upgrade
sudo apt install -y php php-amqp vim curl openssh-server openssh-client gufw apache2

# RabbitMQ Setup
cd /tmp
sudo ./testRabbitSetup.sh

# ufw firewall
sudo ufw reset

# http port
sudo ufw allow proto tcp from [<IP ADDRESS>] to any port 80

# https port
sudo ufw allow proto tcp from [<IP ADDRESS>] to any port 443

# ssh/scp port
sudo ufw allow proto tcp from [<IP ADDRESS>] to any port 22

# zerotier port
sudo ufw allow proto udp from [<IP ADDRESS>] to any port 9993

# RabbitMQ port
sudo ufw allow proto tcp from [<IP ADDRESS>] to any port 5672


# systemd
cp /tmp/standbyFERabbitMQServer.php ~/Git/IT-490
sudo cp /tmp/keepStandbyFERunning.service /etc/systemd/system
sudo systemctl daemon-reload
sudo systemctl start keepStandbyFERunning.service
sudo systemctl enable keepStandbyFERunning.service
