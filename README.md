giic
====

This package provides a toolset for running Gii on the command line. It runs an unlimited number of pre-configured Gii Generator templates.

How does it work?
-----------------

> "Currently it's not possible and, I'm afraid, will not be possible during all your prototyping stage. "
[Samdark](http://www.yiiframework.com/forum/index.php/topic/11146-gii-functionality-from-command-line/page__view__findpost__p__54687)

**But we made it work!**

Giic wraps the Generator and Gii-module in a `GiicApplication`, which is *funky mixture* of `CConsoleApplication` and `CWebApplication`.

You can use a custom config file to specify your input parameters. Every `action` corresponds to a click on the **Generate** button in the web-frontend. Just specify the model attributes as you'd have done in the web application. For more details follow the link in the 'Confiugration' section.

> Note! This code is experimental, please **make a backup** before using it in a project. If you find an issue, please report it [here](https://github.com/schmunk42/giic/issues).



Installation
------------

### via composer

`composer.phar require schmunk42/giic`


Setup
-----

Add your Gii generator aliases to `console.php`

    'gii-template-collection' => 'vendor.phundament.gii-template-collection',


Configuration
-------------

For a test-drive, install [schmunk42/yii-sakila-crud](https://github.com/schmunk42/yii-sakila-crud) which provides migrations and configurations for the MySQL demo database "Sakila".

### "The big one"

#### 2 types of models (gtc & giix) and 4 types of CRUDs into 5 locations

[See the Sakila Configuration](https://github.com/schmunk42/yii-sakila-crud/blob/master/giic-config.php)


Usage
-----

Because Yii can only create `CConsoleApplication`s we've use the supplied CLI entry-script. 

    php vendor/schmunk42/giic/giic.php giic generate config.folder.alias

Troubleshooting
---------------

* Watch out for XSLT bugs, eg.  Entity: line 134: parser error : EntityRef: expecting ';' / Entity nbsp not defined / ...
* If you don't get any errors or output, check your generator templates in your browser in gii
* Set file permission to `777` in `/app/runtime/gii-1.1.13`

Glitches
--------

* All output files are overwritten by default with

    define('GIIC_ALL_CONFIRMED', true);

Add this to your code model:

    public function confirmed($file)
    {
        if (defined('GIIC_ALL_CONFIRMED') && GIIC_ALL_CONFIRMED === true) {
            return true;
        } else {
            return parent::confirmed($file);
        }
    }

>Note: You'll have patch exisiting extensions like eg. `giix`

Tested Generators
---------------------

* gii-template-collection (models and cruds)
* giix (models and cruds)*

\* patch needed

---
    
## Development Setup

    git clone -bcrud git@github.com:phundament/app.git app-crud
    composer.phar create-project
    composer.phar update

> Update db component (MySQL)

> Add sakila migrations to `console-local.php`

    'sakila' => 'vendor.schmunk42.yii-sakila-crud.migrations',

> Add sakila module to `main-local.php`

    'modules' => array(
        'sakila' => array(
            'class' => 'vendor.schmunk42.yii-sakila-crud.SakilaModule'
        )
    )

Setup database

    app/yiic migrate

Generate all models and CRUDs
    
    php vendor/schmunk42/giic/giic.php giic generate vendor.schmunk42.yii-sakila-crud

Your logs should look similar to [this](https://gist.github.com/schmunk42/6124928).
    
### console-local.php

```
<?php

/**
 * Phundament 3 Console Config File
 * Containes predefined yiic console commands for Phundament.
 * Define composer hooks by the following name schema: <vendor>/<packageName>-<action>
 */

// for testing purposes
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
                'app-demo-data'        => 'vendor.waalzer.app-demo-data.migrations',
                'sakila'               => 'vendor.schmunk42.yii-sakila-crud.migrations',
            ),
        ),
    ),
);
```

### main-local.php

```
<?php

// Use this file as local.php to override settings only on your local machine
//
// DO NOT COMMIT THIS FILE !!!

// include 'development' or 'production'
$environmentConfigFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'main-development.php';


$localConfig = array(
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
);

// merge configs in the following order (most to least important) local, {env}, main
if (is_file($environmentConfigFile)) {
    return CMap::mergeArray(require($environmentConfigFile), $localConfig);
} else {
    return $localConfig;
}
```    

