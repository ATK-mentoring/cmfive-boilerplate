#!/bin/bash

# ================================================================
# Prepare and install cmfive
# ================================================================

set -e

cd /var/www/html

#Clear cache
rm -f cache/config.cache

#if SKIP_CMFIVE_AUTOSETUP is defined, exit
if [ "$SKIP_CMFIVE_AUTOSETUP" = true ]; then
    echo "Skipping setup"
    #Let container know that everything is finished
    touch /home/cmfive/.cmfive-installed
    exit 0
fi

echo "Setting up cmfive"

#Copy the config template if config.php doens't exist
if [ ! -f config.php ]; then
    echo "Installing config.php"
    cp /bootstrap/config.default.php config.php
fi

#Allow all permissions to the folders
echo "Setting permissions"
chmod ugo=rwX -R cache/ storage/ uploads/

#Setup Cmfive
echo "Running cmfive.php actions"
echo
if [ ! -f /var/www/html/system/web.php ]; then
    echo "System missing, installing core"
    php cmfive.php install core
fi
php cmfive.php seed encryption
php cmfive.php install migrations
php cmfive.php seed admin Admin Admin dev@2pisoftware.com admin admin

#Let container know that everything is finished
echo "Cmfive setup complete"
touch /home/cmfive/.cmfive-installed
