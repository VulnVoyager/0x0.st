<!DOCTYPE html>
<html>
<head>
    <title>Хранилище файлов</title>
    <style>
        body { font-family: monospace; margin: 40px; line-height: 1.6; }
        pre { background: #f5f5f5; padding: 15px; overflow-x: auto; }
        h1 { border-bottom: 2px solid #333; }
        .section { margin: 30px 0; }
    </style>
</head>
<body>
    <h1>ХРАНИЛИЩЕ ФАЙЛОВ</h1>
    <p>Временный сервис хостинга файлов</p>
    
    <div class="section">
        <h2>Загрузка файлов</h2>
        <p>Отправьте HTTP POST запрос с multipart/form-data:</p>
        <pre>curl -F'file=@ваш_файл.txt' {{ url('/') }}</pre>
    </div>

    <div class="section">
        <h2>Управление файлами</h2>
        <p>Используйте заголовок X-Delete, возвращаемый после загрузки, чтобы удалить файлы:</p>
        <pre>curl {{ url('/delete/ваш_токен_удаления') }}</pre>
    </div>

    <div class="section">
        <h2>Хранение файлов</h2>
        <p>Файлы автоматически удаляются через 30-365 дней в зависимости от размера файла.</p>
        <p>Максимальный размер файла: 512 МБ</p>
    </div>

    <div class="section">
        <h2>Примеры</h2>
        <pre>
# Загрузка файла
curl -F'file=@документ.pdf' {{ url('/') }} -v

# Скачивание файла
curl {{ url('/file/abcd1234') }}

# Удаление файла
curl {{ url('/delete/ваш_токен_удаления') }}
        </pre>
    </div>
</body>
</html>