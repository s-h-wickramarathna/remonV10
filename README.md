 # Ramon Laravel Version 10

1. ## **development Instruction**
- Use this to install the project dependencies and packages: `composer install --ignore-platform-req=ext-gd --ignore-platform-req=ext-zip` & package install guideline is put the package name & version in your project `composer.json` file do you want to install, and run below provided `composer command`.
- before executing the `composer command` you must delete your `vendor` folder & `composer.lock` file.
- this project can't run `php artisan serve` commands because that project architecture was already designed to run inside a third-party local server application.
- ex:'xampp', 'ammps'.
- Any Other `artisan commands` run perfectly inside the `core folder`

2. ## **Authentication Using Sentinel Package**
- After installing the sentinel package to our project, first of all `core/vendor/cartalyst/sentinel/src/Users/EloquentUser.php` go to that root and change the user table name in your database user table name (`users` to `user`) and `protected $fillable` array `email` to `username`.
- After doing these changes go to `core\app\Packages\core\user-management\src\Models\User.php` and copy `employee()`, `employeedetail()`, `role()`, `employee_()`, `customer()` these methods and go `EloquentUser.php`(this root provided below) then paste its bottom of in `EloquentUser class` under the `createPermissions()` method.
