#!/bin/bash
# This script should set up a CRON job to run cron.php every 24 hours.
# You need to implement the CRON setup logic here.
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
CRON_CMD="0 0 * * * php $DIR/cron.php"

( crontab -l | grep -v -F "$CRON_CMD" ; echo "$CRON_CMD" ) | crontab -
echo "Cron job installed: runs cron.php every 24 hours."