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