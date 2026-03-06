#!/bin/bash

# Limpyohan ang tanang cache aron mabasahan ang bag-ong configuration
php artisan config:clear
php artisan cache:clear

# MIGRATION FRESH: Kini ang mupapas sa tanang tables (pula nga error kaganina) 
# ug mo-run sa seeder para sa imong dropdown sa usa ka dagan.
php artisan migrate:fresh --seed --force

# I-start ang server
php artisan serve --host=0.0.0.0 --port=${PORT:-10000}