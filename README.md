giic
====

**Warning: This repos contains heavily WIP code!**


Install via `composer`


Add the alias to `console.php`

    'gii-template-collection'              => 'vendor.phundament.gii-template-collection', // TODO


Run gii via CLI

    php vendor/schmunk42/giic/giic.php giic generate


> Note: All output files are overwritten by default with

    define('GIIC_ALL_CONFIRMED', true);

> Note: Watch out for XSLT bugs, eg.  Entity: line 134: parser error : EntityRef: expecting ';' / Entity nbsp not defined / ...
    
    
## giic development setup

    git clone -bcrud git@github.com:phundament/app.git app-crud
    composer.phar create-project
    composer.phar update

> Update db component (MySQL)

> Add sakila migrations to `console-local.php`

    'sakila'               => 'vendor.schmunk42.yii-sakila-crud.migrations',

> Add sakila module to `main-local.php`

    'modules' => array(
        'sakila' => array(
            'class' => 'vendor.schmunk42.yii-sakila-crud.SakilaModule'
        )
    )

    app/yiic migrate
    
    php vendor/schmunk42/giic/giic.php giic generate
    
    
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

