#!/bin/bash

if pgrep -f "messenger:consume"
then
    echo "Command already running."
else
    echo "Starting command"
    /opt/alt/php82/usr/bin/php /home/srv51178/domains/tediefootwear.com/public_html/bin/console messenger:consume async -vv
fi
