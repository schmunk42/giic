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

require_once(realpath(dirname(__FILE__).'/../../yiisoft/yii/framework/yii.php'));

require_once(realpath(dirname(__FILE__).'/GiicApplication.php'));
$config=dirname(__FILE__).'/../../../app/config/console.php';

echo "Welcome to 
       _ _      
  __ _(_|_) ___ 
 / _` | | |/ __|
| (_| | | | (__ 
 \__, |_|_|\___|
 |___/    
The Gii command line toolset\n";

if(isset($config))
{
	$app=Yii::createApplication('GiicApplication',$config);
	$app->commandRunner->addCommands(dirname(__FILE__));
}
else {
    exit ('no config');
}


$env=@getenv('YII_CONSOLE_COMMANDS');
if(!empty($env))
	$app->commandRunner->addCommands($env);

$app->run();