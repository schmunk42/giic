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
  yiic gii <path-to-giic-config>

DESCRIPTION
  This command runs gii on the command line

PARAMETERS
 * path-to-giic-config: path alias

EOD;
    }

    /**
     * Execute the action.
     *
     * @param array $args command line parameters specific for this command
     */
    public function actionGenerate($args)
    {
        if (!$this->confirm("\nAttention! The command may overwrite exisiting files wihtout further notice.\n\nEnable overwrite all existing files?")) {
            define('GIIC_ALL_CONFIRMED', false);
        } else {
            define('GIIC_ALL_CONFIRMED', true);
        }

        // fake input params
        $_SERVER['REQUEST_URI']     = "console://index.php";
        $_SERVER['SCRIPT_FILENAME'] = $_SERVER['SCRIPT_NAME'] = "index.php";
        $_POST['generate']          = true;
        $_POST['answers']           = true;

        // create gii module for controller
        Yii::import('system.gii.*');
        $module           = Yii::createComponent('system.gii.GiiModule', 'gii', null);
        $module->password = false;


        // load config
        $config = require(Yii::getPathOfAlias($args[0]) . "/giic-config.php");
        #var_dump($config);exit;

        // execute actions (run gii controller action multiple times)
        foreach ($config['actions'] AS $action) {

            // fake input param
            $_POST[$action['template'] . "Code"] = $action['model'];

            // create generator
            $controller            = Yii::createComponent(
                $action['generator'],
                lcfirst($action['template']),
                $module
            );
            // assign template
            $controller->templates = $action['templates'];

            // message
            echo $action['template'].' - '.substr(implode(', ',$action['model']),0,80);
            echo "\n\n";

            // assign controller to application
            Yii::app()->controller = $controller;

            // capture output from controller
            ob_start();
            $controller->run('index');
            $html = ob_get_clean();

            // sanitize
            $html = str_replace("&nbsp;","",$html); // XSLT hotfix

            // parse for console output
            $xslt = new XSLTProcessor();
            $xslt->importStylesheet(new SimpleXMLElement(file_get_contents(dirname(__FILE__).'/giic.xsl')));
            file_put_contents(dirname(__FILE__).'/giic.html', $html);
            echo $xslt->transformToXml(new SimpleXMLElement($html));
        }
    }
}