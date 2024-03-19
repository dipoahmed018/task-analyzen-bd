#!/bin/sh

if [ ! -d node_modules ]; then
    su -c "npm install && npm run build" node
else
    su -c "npm run build" node
fi
