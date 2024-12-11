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
The manager has the following credentials:

Email: test@email.com

Password: 123

To test with other permissions, go to the admin page, create a new user, and set the desired permissions.
