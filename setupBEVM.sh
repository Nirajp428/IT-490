#!/bin/bash

cd /home/chiragkumar/git/setup
scp testRabbitSetup.sh rabbitmqadmin keepPrimaryBERunning.service primaryBERabbitMQServer.php niraj@10.242.91.160:/tmp


# primary BE
ssh niraj@10.242.91.160

# install software
sudo apt upgrade

wget -O- https://packages.erlang-solutions.com/ubuntu/erlang_solutions.asc | sudo apt-key add -

echo "deb https://packages.erlang-solutions.com/ubuntu focal contrib" | sudo tee /etc/apt/sources.list.d/rabbitmq.list

sudo apt-get install -y erlang php php-amqp vim curl openssh-server openssh-client php-curl gufw rabbitmq-server software-properties-common apt-transport-https mysql-server mysql-client php-mysqli

sudo rabbitmq-plugins enable rabbitmq_management


# RabbitMQ Setup
cd /tmp
sudo ./testRabbitSetup.sh


# ufw firewall
sudo ufw reset

# mysql port
sudo ufw allow proto tcp from [10.242.91.160] to any port 3306

# keepalived port
sudo ufw allow proto tcp from [10.242.91.160] to any port 25
sudo ufw allow to 224.0.0.18

# http port
sudo ufw allow proto tcp from [10.242.91.160] to any port 80

# https port
sudo ufw allow proto tcp from [10.242.91.160] to any port 443

# ssh/scp port
sudo ufw allow proto tcp from [10.242.91.160] to any port 22

# zerotier port
sudo ufw allow proto udp from [10.242.91.160] to any port 9993

# RabbitMQ port
sudo ufw allow proto tcp from [10.242.91.160] to any port 5672



# systemd
cp /tmp/primaryBERabbitMQServer.php ~/Git/IT-490
sudo cp /tmp/keepPrimaryBERunning.service /etc/systemd/system
sudo systemctl daemon-reload
sudo systemctl start keepPrimaryBERunning.service
sudo systemctl enable keepPrimaryBERunning.service


exit

# stand by BE
cd /home/chiragkumar/git/setup
scp testRabbitSetup.sh rabbitmqadmin keepStandbyBERunning.service standbyBERabbitMQServer.php niraj@10.242.106.20:/tmp

ssh niraj@10.242.106.20

# install software
sudo apt upgrade

wget -O- https://packages.erlang-solutions.com/ubuntu/erlang_solutions.asc | sudo apt-key add -

echo "deb https://packages.erlang-solutions.com/ubuntu focal contrib" | sudo tee /etc/apt/sources.list.d/rabbitmq.list

sudo apt-get install -y erlang php php-amqp vim curl openssh-server openssh-client php-curl gufw rabbitmq-server software-properties-common apt-transport-https mysql-server mysql-client php-mysqli

sudo rabbitmq-plugins enable rabbitmq_management


# RabbitMQ Setup
cd /tmp
sudo ./testRabbitSetup.sh


# ufw firewall
sudo ufw reset

# mysql port
sudo ufw allow proto tcp from [10.242.106.20] to any port 3306

# keepalived port
sudo ufw allow proto tcp from [10.242.106.20] to any port 25
sudo ufw allow to 224.0.0.18

# http port
sudo ufw allow proto tcp from [10.242.106.20] to any port 80

# https port
sudo ufw allow proto tcp from [10.242.106.20] to any port 443

# ssh/scp port
sudo ufw allow proto tcp from [10.242.106.20] to any port 22

# zerotier port
sudo ufw allow proto udp from [10.242.106.20] to any port 9993

# RabbitMQ port
sudo ufw allow proto tcp from [10.242.106.20] to any port 5672
sudo ufw reset


# systemd
cp /tmp/standbyBERabbitMQServer.php ~/Git/IT-490
sudo cp /tmp/keepStandbyBERunning.service /etc/systemd/system
sudo systemctl daemon-reload
sudo systemctl start keepStandbyBERunning.service
sudo systemctl enable keepStandbyBERunning.service
