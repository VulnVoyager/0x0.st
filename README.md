Обязательно создать в /database database.sqlite

# Установка зависимостей
composer install

# Генерация ключа приложения
php artisan key:generate

# Запуск миграций
php artisan migrate

# Создание директории для загрузок
mkdir -p storage/app/uploads

# Настройка прав
chmod -R 775 storage bootstrap/cache

# Запуск через Docker
docker-compose up -d

# Или запуск локально
php artisan serve