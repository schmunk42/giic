giic
====

This package provides a toolset for running Gii on the command line. It runs an unlimited number of pre-configured Gii Generator templates.

How does it work?
-----------------

> "Currently it's not possible and, I'm afraid, will not be possible during all your prototyping stage. "
[Samdark](http://www.yiiframework.com/forum/index.php/topic/11146-gii-functionality-from-command-line/page__view__findpost__p__54687), 2010

**But we made it work!**

Giic wraps the Generator and Gii-module in a `GiicApplication`, which is *funky mixture* of `CConsoleApplication` and `CWebApplication`.

You can use a custom config file to specify your input parameters. Every `action` corresponds to a click on the **Generate** button in the web-frontend. Just specify the model attributes as you'd have done in the web application. For more details follow the link in the 'Confiugration' section.

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


Setup
-----

Add your Gii generator aliases to `console.php`

    'gii-template-collection' => 'vendor.phundament.gii-template-collection',


Usage
-----

For a test-drive, install Phundament and [schmunk42/yii-sakila-crud](https://github.com/schmunk42/yii-sakila-crud) which provides migrations and configurations for the MySQL demo database "Sakila".

    composer.phar create-project phundament/app app-crud-test
    composer.phar require schmunk42/yii-sakila-crud

Update you application db component (MySQL)

    app/yiic migrate

Because Yii can only create `CConsoleApplication`s we have to use the supplied CLI entry-script. 

    php vendor/schmunk42/giic/giic.php giic generate config.folder.alias

### Configuration

#### "The big one" - 2 types of models (gtc & giix) and 4 types of CRUDs into 5 locations

[See the Sakila Configuration](https://github.com/schmunk42/yii-sakila-crud/blob/master/giic-config.php)


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

---
    
Development Setup
-----------------

> Add sakila migrations to `console-local.php`

    'sakila' => 'vendor.schmunk42.yii-sakila-crud.migrations',

> Add sakila module to `main-local.php`

    'modules' => array(
        'sakila' => array(
            'class' => 'vendor.schmunk42.yii-sakila-crud.SakilaModule'
        )
    )

Your logs should look similar to [this](https://gist.github.com/schmunk42/6124928).
    
### console-local.php

```
return array(
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
                'sakila'               => 'vendor.schmunk42.yii-sakila-crud.migrations',
            ),
        ),
    ),
);
```

### main-local.php

```
    'components' => array(
        'db' => array(
            'tablePrefix'      => '',
            // MySQL
            'connectionString' => 'mysql:host=localhost;dbname=p3-crud',
            'emulatePrepare' => true,
            'username' => 'test',
            'password' => 'test',
            'charset' => 'utf8',
        ),
    ),
    'modules' => array(
        'sakila' => array(
            'class' => 'vendor.schmunk42.yii-sakila-crud.SakilaModule'
        )
    )
```
