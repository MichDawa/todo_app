[SYMFONY BACKEND AFTER PULL]
1. composer install
2. composer require symfony/orm-pack
3. composer require doctrine/doctrine-migrations-bundle
4. edit DATABASE URL in .env with DATABASE_URL="mysql://[USERNAME HERE]:[PASSWORD HERE]@127.0.0.1:3306/[DB NAME HERE]?serverVersion=mariadb-10.4.32&charset=utf8mb4"
5. start apache and mysql in XAMPP
6. php bin/console doctrine:database:create
7. php bin/console make:migration
8. php bin/console doctrine:migrations:migrate
9. symfony serve
10. Use postman for testing
