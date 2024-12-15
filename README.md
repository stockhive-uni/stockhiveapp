# Using StockHive

## Cloning
Clone the project files to your xampp htdocs.
```sh
git clone https://github.com/stockhive-uni/stockhiveapp && cd stockhiveapp/
```

## Setup
### Installing packages
Run the following commands in the terminal:
```sh
npm install
```
```sh
composer install
```

### Getting the db
- Start phpmyadmin, ensure the port is 3306.
- Run the following command in the terminal:
```sh
php artisan migrate
```

## Running
To start the project, run the following commands:
```sh
npm run dev
```
```sh
php artisan serve
```

or, you can run this one command:
```sh
npx concurrently "npm run dev" "php artisan serve"
```

## Using the app
The system has 4 stores by default. Each store has one of each of the following users

- Manager
- Salesperson
- Purchaser
- Stocker
- WarehouseOperator
- Optimiser
- Admin

All accounts have the same password ("123") and have emails in the format "{Role Name}{Store ID}@email.com". Examples:

- Manager1@email.com
- Manager2@email.com
- Manager3@email.com
- Manager4@email.com
- WarehouseOperator1@email.com
- Salesperson4@email.com