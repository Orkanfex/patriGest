#cp .env.example .env

docker run --rm -u "$(id -u):$(id -g)" -e COMPOSER_HOME=/tmp -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php84-composer:latest composer install --ignore-platform-reqs

./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
npm install
