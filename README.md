# Wstępna konfiguracja:
 - zmiana nazwy pliku .env.example na .env
 - wygenerowanie klucza zgodnie z: https://github.com/spatie/laravel-google-calendar
 - skopiowanie klucza do storage/google-calendar/service-account-credentials.json'
 - zmienić hasło i usera do bazy danych 
 - dodać dane do konta pocztowego

# Instalacja docker:
 - cmd: composer install
 - cmd: npm install
 - cmd: ./vendor/bin/sail up
 - w kontenerze laravela: php artisan migrate --seed (można zmienić maila usera wcześniej hasło: password)

# Instalacja lokalna:
 - cmd: composer install
 - cmd: npm install
 - cmd: npm run dev
 - cmd2: php artisan migrate --seed
 - cmd2: php artisan serve

# Strony:
- Logowanie /login
- Zarządzanie taskami: /tasks
