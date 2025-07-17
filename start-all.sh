#!/bin/bash
# Start both backend (Laravel) and frontend (Vue.js) servers

# Start Laravel backend
(cd backend && php artisan serve &)

# Start Vue.js frontend
(cd src && npm run serve &)

wait
