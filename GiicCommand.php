<?php
/**
 * Class file.
 */

/**
 *
 */

Yii::import('system.cli.commands.*');
class GiicCommand extends CConsoleCommand
{
    public $message;

    public function getHelp()
    {
        return <<<EOD
USAGE
  yiic gii <message>

DESCRIPTION
  This command outputs a message

PARAMETERS
 * message: a string

EOD;
    }

    /**
     * Execute the action.
     *
     * @param array $args command line parameters specific for this command
     */
    public function actionGenerate($args)
    {
        Yii::import('vendor.phundament.p3extensions.behaviors.*');

        $_SERVER['REQUEST_URI'] = "console://index.php";
        $_SERVER['SCRIPT_FILENAME'] = $_SERVER['SCRIPT_NAME'] = "index.php";
        $_POST['generate'] = true;
        $_POST['answers'] = true;
        define('GIIC_ALL_CONFIRMED', true);

        $_POST['FullCrudCode'] = array(
            'model'=>'vendor.phundament.p3pages.models.P3Page',
            'controller' => 'test/p3Page'
        );

        Yii::import('system.gii.*');
        $module = Yii::createComponent('system.gii.GiiModule', 'gii', null);
        $module->password = false;
        $controller = Yii::createComponent('vendor.phundament.gii-template-collection.fullCrud.FullCrudGenerator', 'fullCrud', $module);

        Yii::app()->controller = $controller;
        $controller->templates = array('slim'=>realpath(dirname(__FILE__).'/../../phundament/gii-template-collection/fullCrud/templates/slim'));

        $controller->run('index');


    }
}