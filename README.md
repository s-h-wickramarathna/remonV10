## Ramon Laravel Version 10
use this to install the project dependencies and packages: `composer install --ignore-platform-req=ext-gd --ignore-platform-req=ext-zip` & package install guideline is put the package name & version in your project `composer.json` file do you want to install and run below provided `composer command`. before executing the `composer command` you must delete your `vendor` folder & `composer.lock` file.
this project can't run `php artisan serve` commands because that project architecture was already designed to run inside a third-party local server application. ex:'xampp', 'ammps'.
Any Other `artisan commands` run perfectly inside the `core folder`
