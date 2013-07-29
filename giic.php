<?php
/**
 * Yii command line script file.
 *
 * This script is meant to be run on command line to execute
 * one of the pre-defined console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

// fix for fcgi
defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));

defined('YII_DEBUG') or define('YII_DEBUG',true);

require_once(realpath(dirname(__FILE__).'/../../vendor/yiisoft/yii/framework/yii.php'));

require_once(realpath(dirname(__FILE__).'/GiiConsoleApplication.php'));
$config=dirname(__FILE__).'/../config/console.php';


if(isset($config))
{
	$app=Yii::createApplication('GiiConsoleApplication',$config);
	$app->commandRunner->addCommands(YII_PATH.'/cli/commands');
}
else {
    exit ('no config');
}


$env=@getenv('YII_CONSOLE_COMMANDS');
if(!empty($env))
	$app->commandRunner->addCommands($env);

$app->run();