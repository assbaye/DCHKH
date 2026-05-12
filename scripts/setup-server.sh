#!/bin/bash
# Script d'installation initiale sur EC2 Ubuntu 22.04
# Usage : bash scripts/setup-server.sh

set -e

APP_DIR=/var/www/dchkh
REPO_URL=""  # Remplir avec l'URL de ton dépôt GitHub, ex: https://github.com/ton-user/dchkh.git

echo "=== 1. Mise à jour système ==="
sudo apt update && sudo apt upgrade -y

echo "=== 2. Installation PHP 8.2 ==="
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml \
  php8.2-bcmath php8.2-curl php8.2-zip php8.2-gd php8.2-intl php8.2-tokenizer

echo "=== 3. Installation Nginx ==="
sudo apt install -y nginx

echo "=== 4. Installation MySQL ==="
sudo apt install -y mysql-server

echo "=== 5. Installation Composer ==="
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

echo "=== 6. Installation Node.js 20 ==="
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

echo "=== 7. Installation Git ==="
sudo apt install -y git

echo "=== 8. Création base de données ==="
echo "Lance manuellement dans MySQL :"
echo "  CREATE DATABASE dckh_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
echo "  CREATE USER 'dckh'@'localhost' IDENTIFIED BY 'TON_MOT_DE_PASSE';"
echo "  GRANT ALL PRIVILEGES ON dckh_db.* TO 'dckh'@'localhost';"
echo "  FLUSH PRIVILEGES;"

echo "=== 9. Clone du projet ==="
sudo mkdir -p $APP_DIR
sudo chown ubuntu:ubuntu $APP_DIR
git clone $REPO_URL $APP_DIR
cd $APP_DIR

echo "=== 10. Configuration .env ==="
cp .env.example .env
echo "⚠️  Édite le fichier .env avec tes valeurs : nano .env"

echo "=== 11. Installation dépendances ==="
composer install --no-dev --optimize-autoloader
npm ci && npm run build

echo "=== 12. Clé d'application ==="
php artisan key:generate

echo "=== 13. Migrations ==="
php artisan migrate --seed --force

echo "=== 14. Storage link ==="
php artisan storage:link

echo "=== 15. Permissions ==="
sudo chown -R www-data:www-data $APP_DIR/storage $APP_DIR/bootstrap/cache
sudo chmod -R 775 $APP_DIR/storage $APP_DIR/bootstrap/cache
sudo usermod -aG www-data ubuntu

echo "=== 16. Cache Laravel ==="
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== 17. Config Nginx ==="
sudo cp $APP_DIR/scripts/nginx-dchkh.conf /etc/nginx/sites-available/dchkh
sudo ln -sf /etc/nginx/sites-available/dchkh /etc/nginx/sites-enabled/dchkh
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t && sudo systemctl reload nginx

echo ""
echo "✅ Installation terminée ! Accède au site via http://$(curl -s ifconfig.me)"
