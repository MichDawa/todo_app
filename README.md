[SYMFONY BACKEND AFTER PULL]
1. composer install
2. edit DATABASE URL in .env with DATABASE_URL="mysql://[USERNAME HERE]:[PASSWORD HERE]@127.0.0.1:3306/[DB NAME HERE]?serverVersion=mariadb-10.4.32&charset=utf8mb4"
3. start apache and mysql in XAMPP
4. php bin/console doctrine:database:create
5. php bin/console make:migration
6. php bin/console doctrine:migrations:migrate
7. symfony serve, use postman for testing
