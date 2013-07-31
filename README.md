giic
====

This package provides a toolset for running Gii on the command line.

Giic wraps the Gii module in a `GiicApplication`, which is *hacky-mixture* of `CConsoleApplication` and `CWebApplication`.

You can use a custom config file to specify your input parameters. Every `action` corresponds to a click on the **Generate** button in the web-frontend.

> Note! This code is experimental, please **make a backup** before using it in a project.


Installation
------------

### via composer

`composer.phar require schmunk42/giic`


Setup
-----

Add your Gii generator aliases to `console.php`

    'gii-template-collection' => 'vendor.phundament.gii-template-collection',

Usage
-----

Because Yii can only create `CConsoleApplication`s we've use the supplied CLI entry-script. 

    php vendor/schmunk42/giic/giic.php giic generate config.folder.alias

Glitches
--------

> Note: All output files are overwritten by default with

    define('GIIC_ALL_CONFIRMED', true);

> Note: Watch out for XSLT bugs, eg.  Entity: line 134: parser error : EntityRef: expecting ';' / Entity nbsp not defined / ...


Examples
--------

For a test-drive, install [schmunk42/yii-sakila-crud](https://github.com/schmunk42/yii-sakila-crud) which provides migrations and configurations for the MySQL demo database "Sakila".

## giic-config.sample.php

```
<?php

// select tables
$tables = array(
    'actor',
    'address',
    'category',
    'film_text',
    'inventory',
    'language',
    'payment',
    'rental',
    'staff',
    'store',
    'film',
    'city',
    'customer',
    'country'
);

// select cruds
$cruds  = $tables;

$actions = array();

// build actions
foreach ($tables AS $table) {
    $actions[] = array(
        "template" => "FullModel",
        "generator"=> 'vendor.phundament.gii-template-collection.fullModel.FullModelGenerator',
        "templates"=> array(
            'default' => dirname(__FILE__) . '/../../../vendor/phundament/gii-template-collection/fullModel/templates/default',
        ),
        "model"    => array(
            "tableName"  => $table,
            "modelClass" => ucFirst($table),
            "modelPath"  => "sakila.models",
            "template"   => "default"
        )
    );
}

// build actions
foreach ($cruds AS $crud) {
    $actions[] = array(
        "template" => "FullCrud",
        "generator"=> 'vendor.phundament.gii-template-collection.fullCrud.FullCrudGenerator',
        "templates"=> array(
            'slim' => dirname(__FILE__) . '/../../../vendor/phundament/gii-template-collection/fullCrud/templates/slim',
        ),
        "model"    => array(
            "model"      => "sakila.models." . ucfirst($crud),
            "controller" => $crud,
            "template"   => "slim"
        )
    );
}

return array(
    "actions" => $actions
);
```

    
## giic development setup

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

