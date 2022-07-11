# Overview

---

- [Installing](#installing)

<a name="installing"></a>
## Installing

### Configuring .env
Go go the repository's folder, then:
´´´bash
cp .env.example .env
´´´

### Running Composer
´´´bash
composer install
´´´

## Permissions
give 777 permissions to your user for the "Storage" folder recursively

### Configuring database
Access the mysql docker machine, then:
´´´mysql
create database endotera;
´´´

### Laravel commands
Access the laravel docker machine, then:
´´´bash
php artisan key:generate
´´´

´´´bash
php artisan migrate --seed
´´´
