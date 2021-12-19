#!/bin/bash
cd /home/chiragkumar/git/setup
scp keepPrimaryDMZRunning.service primaryDMZRabbitMQServer.php kevin@10.242.172.64:/tmp

# primary DMZ
ssh kevin@10.242.172.64

# install software
sudo apt upgrade
sudo apt-get install -y php php-amqp vim curl openssh-server openssh-client php-curl gufw

# ufw firewall
sudo ufw reset

# curl port
sudo ufw allow proto tcp from [10.242.172.64] to any port 465

# ssh/scp port
sudo ufw allow proto tcp from [10.242.172.64] to any port 22

# zerotier port
sudo ufw allow proto udp from [10.242.172.64] to any port 9993

# RabbitMQ port
sudo ufw allow proto tcp from [10.242.172.64] to any port 5672



# systemd
cp /tmp/primaryDMZRabbitMQServer.php ~/Git/IT-490
sudo cp keepPrimaryDMZRunning.service /etc/systemd/system
sudo systemctl daemon-reload
sudo systemctl start keepPrimaryDMZRunning.service
sudo systemctl enable keepPrimaryDMZRunning.service


exit

# stand by DMZ
cd /home/chiragkumar/git/setup
scp keepStandbyDMZRunning.service standbyDMZRabbitMQServer.php kevin@10.242.223.100:/tmp

ssh kevin@10.242.223.100

# install software
sudo apt upgrade
sudo apt-get install -y php php-amqp vim curl openssh-server openssh-client php-curl gufw


# ufw firewall
sudo ufw reset

# curl port
sudo ufw allow proto tcp from [10.242.223.100] to any port 465

# ssh/scp port
sudo ufw allow proto tcp from [10.242.223.100] to any port 22

# zerotier port
sudo ufw allow proto udp from [10.242.223.100] to any port 9993

# RabbitMQ port
sudo ufw allow proto tcp from [10.242.223.100] to any port 5672


# systemd
cp /tmp/standbyDMZRabbitMQServer.php ~/Git/IT-490
sudo cp keepStandbyDMZRunning.service /etc/systemd/system
sudo systemctl daemon-reload
sudo systemctl start keepStandbyDMZRunning.service
sudo systemctl enable keepStandbyDMZRunning.service

