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
        $_SERVER['REQUEST_URI'] = "console://index.php";
        $_SERVER['SCRIPT_FILENAME'] = $_SERVER['SCRIPT_NAME'] = "index.php";
        $_POST['generate'] = true;
        $_POST['answers'] = true;
        Yii::import('system.gii.*');
        $module = Yii::createComponent('system.gii.GiiModule', 'gii', null);
        $module->password = false;

        define('GIIC_ALL_CONFIRMED', true);
        $config = require(Yii::getPathOfAlias($args[0])."/giic-config.php");        
        #var_dump($config);exit;
        
        foreach($config['actions'] AS $action) {
             $_POST[$action['template']."Code"] = $action['model'];
             
             if ($action['template'] == "FullCrud")  {
                 $controller = Yii::createComponent('vendor.phundament.gii-template-collection.fullCrud.FullCrudGenerator', 'fullCrud', $module);
                 $controller->templates = array('slim'=>realpath(dirname(__FILE__).'/../../phundament/gii-template-collection/fullCrud/templates/slim'));
                 }
             else {
                 $controller = Yii::createComponent('vendor.phundament.gii-template-collection.fullModel.FullModelGenerator', 'fullModel', $module);
             $controller->templates = array('default'=>realpath(dirname(__FILE__).'/../../phundament/gii-template-collection/fullModel/templates/default'));
        }

             $module->layout = null;
             Yii::app()->controller = $controller;
             $controller->run('index');
             //exit;
        }
    }
}