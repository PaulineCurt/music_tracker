#!/bin/bash
# symfony console doctrine:database:drop --force
# symfony console doctrine:database:create 
# symfony console lexik:jwt:generate-keypair --overwrite
# symfony console doctrine:fixtures:load --no-interaction
symfony composer install --no-interaction 
symfony console doctrine:migrations:migrate --no-interaction
symfony server:start --no-tls --allow-all-ip
