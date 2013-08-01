giic
====

This package provides a toolset for running Gii on the command line. It runs an unlimited number of pre-configured Gii Generator templates.

Introduction
------------

> I have configured several templates and with Gii I create the code for modules by selecting the templates 
> that is associated with the DB-table. It is working great and saves me lots of time. If I can bash-script or 
> php-script it, it would be awesome.

> "Currently it's not possible and, I'm afraid, will not be possible during all your prototyping stage. "

> [unixjunky and Samdark](http://www.yiiframework.com/forum/index.php/topic/11146-gii-functionality-from-command-line/page__view__findpost__p__54687), 2010

**But we made it work!**

How does it work?
-----------------

Giic wraps the Generator and Gii-module in a `GiicApplication`, which is *funky mixture* of `CConsoleApplication` 
and `CWebApplication`.

You can use a custom [config file](https://github.com/schmunk42/yii-sakila-crud/blob/master/giic-config.php) to specify 
your input parameters. Every `action` corresponds to a click on the **Generate** button in the web-frontend. Just specify the model attributes as you'd have done in the web application. For more details follow the link in the 'Confiugration' section.

> Note! This code is experimental, please **make a backup** before using it in a project. If you find an issue, please report it [here](https://github.com/schmunk42/giic/issues).

Resources
---------

* [Yii Framework Extension](http://www.yiiframework.com/extension/giic)
* [GitHub Project](https://github.com/schmunk42/giic)
* [Phundament](http://phundament.com) Package

Installation
------------

### via composer

    composer.phar require schmunk42/giic

Usage
-----

    php vendor/schmunk42/giic/giic.php giic generate alias.to.giic-config

Setup
-----

Giic can be installed in any application, but to get a better impression how it works, we'll guide you through a sample
setup with an Yii extension which features CRUDs for the MySQL demo database Sakila.

For the test-drive, we'll install [Phundament](http://phundament.com) together with the Sakila Demo module  [schmunk42/yii-sakila-crud](https://github.com/schmunk42/yii-sakila-crud) 
This module provides migrations and configurations for the MySQL demo database "Sakila" to use with giic.
It also includes the generated CRUDs to play around with.

Install a development(!) version Phundament and the demo extension:

    composer.phar create-project -sdev phundament/app app-crud-test
    cd app-crud-test
    composer.phar require schmunk42/yii-sakila-crud

> Note: Standard gii-template-collection usage is preconfigured in Phundament.

Add sakila migrations to `app/config/console-local.php`:

    'import' => array(
        'vendor.phundament.gii-template-collection.components.*'
    ),
    'aliases' => array(
        'sakila' => 'vendor.schmunk42.yii-sakila-crud.*'
    ),    
    'commandMap' => array(
        'migrate' => array(
            // enable eg. data migrations for your local machine
            'modulePaths' => array(
                'sakila  => 'vendor.schmunk42.yii-sakila-crud.migrations',
            ),
        ),
    ),
    

Add sakila module and MySQL database to `app/config/main-local.php`:

    'modules' => array(
        'sakila' => array(
            'class' => 'vendor.schmunk42.yii-sakila-crud.SakilaModule'
        )
    ),
    'components' => array(
        'db'            => array(
            'tablePrefix'      => '',
            'connectionString' => 'mysql:host=localhost;dbname=giic',
            'emulatePrepare'   => true,
            'username' => 'test',
            'password' => 'test',
            'charset'  => 'utf8',
        ),
    )

Run the migrations to setup the database:

    app/yiic migrate

Because Yii can only create `CConsoleApplication`s we have to use the supplied CLI entry-script to create our hybrid application.
Run thw following command to invoke the set configured actions:

    php vendor/schmunk42/giic/giic.php giic sakila

Your console output should look similar to [this](https://gist.github.com/schmunk42/6124928).

Open http://phundament.local/index.php?r=sakila to checkout your CRUDs.

#### Bonus: giix

The config file also looks for giix generators in `application.extensions.giix`, you may download giix and place
it into extensions.



### Configuration

"The big one" - actions for generating two types of models (gtc & giix) and four types of CRUDs into five different locations.

[See the Sakila Configuration](https://github.com/schmunk42/yii-sakila-crud/blob/master/giic-config.php) checkout the comments 
for an explanation.



### Troubleshooting

* Watch out for XSLT bugs, eg.  Entity: line 134: parser error : EntityRef: expecting ';' / Entity nbsp not defined / ...
* If you don't get any errors or output, check your generator templates in your browser in gii
* Set file permission to `777` in `/app/runtime/gii-1.1.13`
* run `composer.phar update` to get the latest packages

### Glitches

* All output files are overwritten by default with

    define('GIIC_ALL_CONFIRMED', true);

Patch your code model (`GiixModelCode`, `GiixCrudCode`), override this method:

    public function confirmed($file)
    {
        if (defined('GIIC_ALL_CONFIRMED') && GIIC_ALL_CONFIRMED === true) {
            return true;
        } else {
            return parent::confirmed($file);
        }
    }

>Note: You'll have patch exisiting extensions like eg. `giix`

### Tested Generators

* [gii-template-collection](https://github.com/schmunk42/gii-template-collection) (models and cruds)
* giix (models and cruds)*

\* patch from above needed

