#!/bin/bash

if pgrep -f "messenger:consume async" > /dev/null
then
    echo "Command already running."
else
    echo "Starting command"
    /opt/alt/php82/usr/bin/php /home/srv51178/domains/tediefootwear.com/public_html/bin/console messenger:consume async --env=local --no-interaction >> /home/srv51178/domains/tediefootwear.com/public_html/var/log/cron_messenger.log 2>&1 &
fi
