# Установка зависимостей
composer install

# Генерация ключа приложения
php artisan key:generate

# Запуск миграций
php artisan migrate

# Настройка прав
chmod -R 775 storage bootstrap/cache

# Запуск через Docker
docker-compose up -d

# Или запуск локально
php artisan serve