#cp .env.example .env

# Executa o Composer via Docker para instalar as dependências
docker run --rm -u "$(id -u):$(id -g)" -e COMPOSER_HOME=/tmp -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php84-composer:latest composer install --ignore-platform-reqs

# --- CORREÇÃO DO GIT SAFE DIRECTORY ---
# Verifica se o Git local rejeita a pasta atual por problemas de dono (ownership)
# Se rejeitar, aplica a correção automaticamente antes de subir o Sail
if ! git status > /dev/null 2>&1; then
    echo "⚠️ Ajustando safe.directory no Git para evitar erros de propriedade..."
    git config --global --add safe.directory /var/www/html
fi
# --------------------------------------

# Sobe o ambiente e finaliza a configuração do Laravel
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
npm install
