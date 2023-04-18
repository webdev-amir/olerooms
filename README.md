“Ole Rooms” Build In Laravel Framwork Version 8.12
## Getting Started

## Objective
The purpose of this project is to provide the platform to End-Users to look for the properties and book them


### Prerequisites

Application is built with Laravel 8.0 as php framework and MySQL database is used for db operation.

### Installing

A step by step series of examples that tell you how to get a development env running.
- First clone this project
- Run composer update
- Set configuration in .env file like database setting and your app url
- Run php artisan migrate
- Run php artisan module:migrate
- Run php artisan module:seed
- Run dump autoload
- Run php artisan serve
- Open http://127.0.0.1:8000

### Permission Seeder Migration (Run Following)
- php artisan db:seed --class=CreateAdminUserSeeder
- php artisan module:seed --class=DashboardDatabaseSeeder Dashboard
- php artisan module:seed --class=ConfigurationDatabaseSeeder Configuration
- php artisan module:seed --class=EmailTemplatesPermissionSeederTableSeeder EmailTemplates
- php artisan module:seed --class=RolesDatabaseSeeder Roles
- php artisan module:seed --class=PermissionsDatabaseSeeder Permissions
- php artisan module:seed --class=StaticPagesDatabaseSeeder StaticPages
- php artisan module:seed --class=UsersDatabaseSeeder Users
- php artisan module:seed --class=TestimonialDatabaseSeeder Testimonial
- php artisan module:seed --class=StaticPagesDatabaseSeeder StaticPages
- php artisan module:seed --class=ContactusDatabaseSeeder Contactus
- php artisan module:seed --class=CategoryDatabaseSeeder Category
- php artisan module:seed --class=PaymentDatabaseSeeder Payment
