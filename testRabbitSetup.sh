#!/bin/sh

# add RabbitMQ user
sudo rabbitmqctl add_user IT490User password
sudo rabbitmqctl set_user_tags IT490User administrator
sudo rabbitmqctl set_permissions -p / IT490User ".*" ".*" ".*"


# add vhost and set permissions
sudo rabbitmqctl add_vhost IT490vhost
sudo rabbitmqctl set_permissions -p IT490vhost IT490User ".*" ".*" ".*"


# create exchanges
./rabbitmqadmin declare exchange --vhost=IT490vhost name=frontendexchange type=direct durable=true -u IT490User -p password

./rabbitmqadmin declare exchange --vhost=IT490vhost name=backendexchange type=direct durable=true -u IT490User -p password

./rabbitmqadmin declare exchange --vhost=IT490vhost name=dmzexchange type=direct durable=true -u IT490User -p password

./rabbitmqadmin declare exchange --vhost=IT490vhost name=deployexchange type=direct durable=true -u IT490User -p password

./rabbitmqadmin declare exchange --vhost=IT490vhost name=logexchange type=direct durable=true -u IT490User -p password


# create dead letter exchanges
./rabbitmqadmin declare exchange --vhost=IT490vhost name=dlxfrontendexchange type=direct durable=true -u IT490User -p password

./rabbitmqadmin declare exchange --vhost=IT490vhost name=dlxbackendexchange type=direct durable=true -u IT490User -p password

./rabbitmqadmin declare exchange --vhost=IT490vhost name=dlxdmzexchange type=direct durable=true -u IT490User -p password

# create queues
./rabbitmqadmin declare queue --vhost=IT490vhost name=frontendqueue durable=true -u IT490User -p password

./rabbitmqadmin declare queue --vhost=IT490vhost name=backendqueue durable=true -u IT490User -p password

./rabbitmqadmin declare queue --vhost=IT490vhost name=dmzqueue durable=true -u IT490User -p password

./rabbitmqadmin declare queue --vhost=IT490vhost name=deployqueue durable=true -u IT490User -p password

# create dead letter queues
./rabbitmqadmin declare queue --vhost=IT490vhost name=dlxfrontendqueue durable=true -u IT490User -p password

./rabbitmqadmin declare queue --vhost=IT490vhost name=dlxbackendqueue durable=true -u IT490User -p password

./rabbitmqadmin declare queue --vhost=IT490vhost name=dlxdmzqueue durable=true -u IT490User -p password


# bind exchanges and queues
./rabbitmqadmin --vhost="IT490vhost" declare binding source="frontendexchange" destination_type="queue" destination="frontendqueue" routing_key="*" -u IT490User -p password

./rabbitmqadmin --vhost="IT490vhost" declare binding source="backendexchange" destination_type="queue" destination="backendqueue" routing_key="*" -u IT490User -p password

./rabbitmqadmin --vhost="IT490vhost" declare binding source="dmzexchange" destination_type="queue" destination="dmzqueue" routing_key="*" -u IT490User -p password

./rabbitmqadmin --vhost="IT490vhost" declare binding source="deployexchange" destination_type="queue" destination="deployqueue" routing_key="*" -u IT490User -p password


# logexchange bindings
./rabbitmqadmin --vhost="IT490vhost" declare binding source="logexchange" destination_type="queue" destination="frontendqueue" routing_key="*" -u IT490User -p password

./rabbitmqadmin --vhost="IT490vhost" declare binding source="logexchange" destination_type="queue" destination="backendqueue" routing_key="*" -u IT490User -p password

./rabbitmqadmin --vhost="IT490vhost" declare binding source="logexchange" destination_type="queue" destination="dmzqueue" routing_key="*" -u IT490User -p password

./rabbitmqadmin --vhost="IT490vhost" declare binding source="logexchange" destination_type="queue" destination="deployqueue" routing_key="*" -u IT490User -p password


# bind dead letter exchanges
./rabbitmqadmin --vhost="IT490vhost" declare binding source="dlxfrontendexchange" destination_type="queue" destination="dlxfrontendqueue" routing_key="*" -u IT490User -p password

./rabbitmqadmin --vhost="IT490vhost" declare binding source="dlxbackendexchange" destination_type="queue" destination="dlxbackendqueue" routing_key="*" -u IT490User -p password

./rabbitmqadmin --vhost="IT490vhost" declare binding source="dlxdmzexchange" destination_type="queue" destination="dlxdmzqueue" routing_key="*" -u IT490User -p password


# set Time to Live policy for dead letter queues
./rabbitmqadmin --vhost="IT490vhost" declare policy name="frontendDLX" pattern="^frontendqueue$" apply-to="queues" definition='{"dead-letter-exchange":"dlxfrontendexchange", "message-ttl":4000, "dead-letter-routing-key": "*"}'

./rabbitmqadmin --vhost="IT490vhost" declare policy name="backendDLX" pattern="^backendqueue$" apply-to="queues" definition='{"dead-letter-exchange":"dlxbackendexchange", "message-ttl":4000, "dead-letter-routing-key": "*"}'

./rabbitmqadmin --vhost="IT490vhost" declare policy name="dmzDLX" pattern="^dmzqueue$" apply-to="queues" definition='{"dead-letter-exchange":"dlxdmzexchange", "message-ttl":4000, "dead-letter-routing-key": "*"}'
                              
