#Laravel 4 Bootstrap 3 Starter Site

This is a cut-down version of the excellent [Laravel 4 Bootstrap Starter Site](https://github.com/andrew13/Laravel-4-Bootstrap-Starter-Site)

I created this starter app as I needed a simple framework for an internal database-backed Web application with users, roles, and of course Laravel - but no public facing Web pages. 

I also wanted to roll my sleeves up and experiment with Laravel and Bootstrap.

I really like what [Andrew](http://andrewelkins.com/) and the other contributors have done with [Laravel 4 Bootstrap Starter Site](https://github.com/andrew13/Laravel-4-Bootstrap-Starter-Site), but there were also a couple of things I wanted to do differently.

Here's a description of the differences from the original starter site:

## Differences

* I've removed [Basset](https://github.com/jasonlewis/basset) (for the moment at least) and so there is no asset management system. Jason Lewis' asset management package is superb - but this starter app will be used to create management and utility applications, with little custom styling and JavaScript. In other words, once forms, layout, and the Bootstrap styles have been applied, there won't be any further changes to asset files and so an asset management system isn't as important to the application as it would be for a large public facing Web site. If projects evolve - then I may put it back.

* I've used the compiled Bootstrap .css and .js files.

* I've kept the excellent [Bilal](https://github.com/bllim/laravel4-datatables-package) datatables package (although there are still a few wrinkles to iron out with table formatting in Bootstrap 3).

* I've removed Colorbox pop-ups and followed a controller pattern that I've used on Rails applications in the past. I'm not a big fan of popping up windows for forms or CRUD operations - in particular for delete operations. All controllers (well all except the UserController.php) uses the Route::resource RESTful controller methods, with the addition of a delete method that shows the record to be deleted before it's deleted/destroyed. Take a look at `routes.php`, and `WidgetController.php` for examples.

* Following from the contoller pattern above, views also follow a fixed pattern, with `_details.blade.php` and `_form.blade.php` used for both show, and delete as well as create and update. Also look at the `Widget.php` model and views for examples.

* I've created a simplified Bootstrap 3 Navbar - with an Admin dropdown menu that appears if you login as admin.

* I've continued to use [Confide](https://github.com/Zizaco/confide) for authentication and [Entrust](https://github.com/Zizaco/entrust) for authorization- although still digging here.

##Screenshots

[![Home](http://downloads.58bits.com/lv4bs3/lv4bs3_01_s.png "Home page")](http://downloads.58bits.com/lv4bs3/lv4bs3_01.png)
[![Users](http://downloads.58bits.com/lv4bs3/lv4bs3_02_s.png "User managment")](http://downloads.58bits.com/lv4bs3/lv4bs3_02.png)
[![Widgets](http://downloads.58bits.com/lv4bs3/lv4bs3_03_s.png "Widget management")](http://downloads.58bits.com/lv4bs3/lv4bs3_03.png)
[![Login](http://downloads.58bits.com/lv4bs3/lv4bs3_04_s.png "Login page")](http://downloads.58bits.com/lv4bs3/lv4bs3_04.png)
[![Widget](http://downloads.58bits.com/lv4bs3/lv4bs3_05_s.png "Create widget page")](http://downloads.58bits.com/lv4bs3/lv4bs3_05.png)




##Requirements and Installation

Follow the excellent instructions over at [Laravel 4 Bootstrap Starter Site](https://github.com/andrew13/Laravel-4-Bootstrap-Starter-Site). Once the migrations are run -  Admin:admin, User:user accounts will be created.

The current app/config/local/database.php file expects a MySQL database connection with a database named: lv4bs3_www, user: lv4bs3 and password: test. Update these to suit your own dev setup.

You can use the local PHP server to run the application.

`php artisan serve --port=4000`

The app/config/local/mail.php file has been set to `'pretend' => true` - so no SMTP settings are required.

-----
## License

This is free software distributed under the terms of the MIT license

