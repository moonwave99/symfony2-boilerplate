#Symfony boilerplate

This is my Symfony boilerplate featuring setup for all the bundles I use more often.

## Installation

* Extract the [archive](https://github.com/moonwave99/symfony2-boilerplate/zipball/master) into desired folder [e.g. `/var/www/symfony-boilerplate/`];
* Be sure Symfony requirements are met by pointing your browser to [`http://localhost/symfony-boilerplate/web/config.php`](http://localhost/symfony-boilerplate/web/config.php);
* Hit the `Configure your Symfony Application Online` link;
* `app/config/parameters.yml` file will be created - be sure it looks like the following, beside `secret` value being different of course:

[notice the two latest line - `webmaster` and `analytics`]

	parameters:
	    database_driver:   pdo_mysql
	    database_host:     localhost
	    database_port:     ~
	    database_name:     yourDatabaseName
	    database_user:     root
	    database_password: ~

	    mailer_transport:  smtp
	    mailer_host:       localhost
	    mailer_user:       ~
	    mailer_password:   ~

	    locale:            en
	    secret:            2b95358d1a3c2584d123i7360dc96d6c331a7732
	    webmaster:         webmaster@somedomain.com
	    analytics:         UA-XXXXXXX-X

Now you need to perform a couple of command line tasks - go into installation folder then:

**Install vendors** [via [Composer](14)]:

	$ php composer.phar update

**Create schema for User entity:**

	$ php app/console doctrine:schema:create

**Create a superadmin user with username `admin` and password `admin`:**

	$ php app/console fos:user:create admin admin@admin.com admin --super-admin
	
**Create a basic user with username `user` and password `user` [just in order to have some data to show into the admin section]:**

	$ php app/console fos:user:create user user@user.com user
	
If everything went right, you can safely point to [`http://localhost/symfony-boilerplate/web/app_dev.php`](http://localhost/symfony-boilerplate/web/app_dev.php) to see the homepage.

You may login with created credentials at [`http://localhost/symfony-boilerplate/web/app_dev.php/login`](http://localhost/symfony-boilerplate/web/app_dev.php/login), and if you are logged as `admin` you may have a peek at admin tools at [`http://localhost/symfony-boilerplate/web/app_dev.php/admin/index`](http://localhost/symfony-boilerplate/web/app_dev.php/admin/index)

---

Assets are handled by [Assetic](1) - for production environment, dump them as reported in Symfony docs:

	$ php app/console assetic:dump --env=prod --no-debug
	
## Project Structure

I usually prepare 4 main bundles:

* `FrontendBundle` holds all the routes for the frontend website;
* `RestBundle` provides RESTful api to the application - just `UserController` is provided as an example;
* `UserBundle` overrides [`FOSUserBundle`](9) views in order to be customized quickly;
* `BackendBundle` is the administration core - a basic **CRUD** example is provided over the `User` entity, built over the RESTful api, **dataTables** and **simple.js**.

**Caveats:**

* Entities can be defined either in `RestBundle` or in `FrontendBundle`, Doctrine takes care of it all;
* RESTful `api/` route is not secured by default - be sure to provide your security logic over different routes and HTTP methods [e.g. not everybody should make `DELETE` requests!];
* I do use **simple.js** as a frontend solution because I tailored it on my needs - if you need to handle a more complex website go with a more structured and scalable tool!


## Client side stuff included

* A bit of [Bootstrap](2) for prototype's sake;
* [simple.js](3) minimal MVC framework;
* [underscore.js](4) library for client-side templating;
* [spin.js](5) pure CSS spinner;
* [xdate](6) js date library;
* [dataTables](7) table plugin for jQuery, bootstrap-themed, integrated via simple.js.

## Bundles Installed (beside Symfony Standard Edition)

* [**jms/metadata**](8) - Class/method/property metadata management;
* [**KnpLabs/KnpPaginatorBundle**](11) - SEO friendly paginator to sort and paginate;
* [**KnpLabs/ npMarkdownBundle**](12) - Symfony2 wrapper for PHP markdown;
* [**jms/serializer-bundle**](13) - Easily serialize, and deserialize object graphs of any complexity (supports XML, JSON, YAML);
* [**FriendsOfSymfony/FOSUserBundle**](9) - User management.
* [**FriendsOfSymfony/FOSRestBundle**](10) - Facilities for mantaining RESTful webservices.

[1]:  http://symfony.com/doc/current/cookbook/assetic/asset_management.html  
[2]:  http://twitter.github.com/bootstrap/
[3]:  http://moonwave99.github.com/simple.js/
[4]:  http://underscorejs.org/
[5]:  http://fgnass.github.com/spin.js/
[6]:  http://arshaw.com/xdate/
[7]:  http://datatables.net/
[8]:  https://github.com/schmittjoh/metadata
[9]:  https://github.com/FriendsOfSymfony/FOSUserBundle
[10]: https://github.com/FriendsOfSymfony/FOSRestBundle
[11]: https://github.com/KnpLabs/KnpPaginatorBundle
[12]: https://github.com/KnpLabs/KnpMarkdownBundle
[13]: https://github.com/schmittjoh/JMSSerializerBundle
[14]: http://getcomposer.org/