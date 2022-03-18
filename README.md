<p align="center">
  <img src="https://i.imgur.com/aFwou9b.jpg" />
</p>

<p align="center"><b>A Open source extreme simple task manager backend writen in Laravel</b></p>

## Features
- All CRUD methods ready in route '/tasks'
- Simple and readable routes
- Migrations ready for you connect to any database
- TDD ready for use and learn.

## Techs
- Laravel Framework
- PHP

## Requirements
- PHP 7.3+ installed on your machine
- Composer installed on your machine

## How to install
- Clone this repository on any folder that you want
- Open your terminal on the same folder and type...
```
composer install
```

After a while, all dependencies for run this project will be installed
- Now you just need to start a development server typing
```
php artisan serve
```
**Remember, you need to configure your database on generated .env file**
- After configure your database on .env you just need to type on terminal...
```
php artisan migrate 
```
**'tasks' table created on your database? all done!**

## Testing
- tests are located in /tests/Unit/Http/Controllers/Api
- To run testing and see the assertions just type on your terminal...
```
php artisan test
```
Why not *composer test*? 
<br />
R - All https tests made uses the package *Illuminate\Testing\Fluent\AssertableJson* from **Fluent JSON Laravel Testing**

## How to contribute?
- You can look in TODO list above and realize one needed task
- You can find bugs or dangerous code 
- You can open an issue ticket on 'issues' tab
- Feel free to make this project better in any aspects
```
After contribute, you just need to submit an pull request.
```
## TODO LIST.

- [x] Tasks Routes
- [ ] User Profile Routes
- [ ] More details in Tasks
