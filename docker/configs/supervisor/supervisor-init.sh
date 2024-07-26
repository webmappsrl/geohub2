#!/bin/bash

# Start the supervisor
sudo supervisorctl
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start horizon