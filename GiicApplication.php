<?php
/**
 * CConsoleApplication class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CConsoleApplication represents a console application.
 *
 * CConsoleApplication extends {@link CApplication} by providing functionalities
 * specific to console requests. In particular, it deals with console requests
 * through a command-based approach:
 * <ul>
 * <li>A console application consists of one or several possible user commands;</li>
 * <li>Each user command is implemented as a class extending {@link CConsoleCommand};</li>
 * <li>User specifies which command to run on the command line;</li>
 * <li>The command processes the user request with the specified parameters.</li>
 * </ul>
 *
 * The command classes reside in the directory {@link getCommandPath commandPath}.
 * The name of the class follows the pattern: &lt;command-name&gt;Command, and its
 * file name is the same as the class name. For example, the 'ShellCommand' class defines
 * a 'shell' command and the class file name is 'ShellCommand.php'.
 *
 * To run the console application, enter the following on the command line:
 * <pre>
 * php path/to/entry_script.php <command name> [param 1] [param 2] ...
 * </pre>
 *
 * You may use the following to see help instructions about a command:
 * <pre>
 * php path/to/entry_script.php help <command name>
 * </pre>
 *
 * @property string $commandPath The directory that contains the command classes. Defaults to 'protected/commands'.
 * @property CConsoleCommandRunner $commandRunner The command runner.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.console
 * @since 1.0
 */
class GiiConsoleApplication extends CApplication
{
	/**
	 * @var array mapping from command name to command configurations.
	 * Each command configuration can be either a string or an array.
	 * If the former, the string should be the file path of the command class.
	 * If the latter, the array must contain a 'class' element which specifies
	 * the command's class name or {@link YiiBase::getPathOfAlias class path alias}.
	 * The rest name-value pairs in the array are used to initialize
	 * the corresponding command properties. For example,
	 * <pre>
	 * array(
	 *   'email'=>array(
	 *      'class'=>'path.to.Mailer',
	 *      'interval'=>3600,
	 *   ),
	 *   'log'=>'path/to/LoggerCommand.php',
	 * )
	 * </pre>
	 */
	public $commandMap=array();

	private $_commandPath;
	private $_runner;

	/**
	 * Initializes the application by creating the command runner.
	 */
	protected function init()
	{
		parent::init();
		if(!isset($_SERVER['argv'])) // || strncasecmp(php_sapi_name(),'cli',3))
			die('This script must be run from the command line.');
		$this->_runner=$this->createCommandRunner();
		$this->_runner->commands=$this->commandMap;
		$this->_runner->addCommands($this->getCommandPath());
	}

	/**
	 * Processes the user request.
	 * This method uses a console command runner to handle the particular user command.
	 * Since version 1.1.11 this method will exit application with an exit code if one is returned by the user command.
	 */
	public function processRequest()
	{
		$exitCode=$this->_runner->run($_SERVER['argv']);
		if(is_int($exitCode))
			$this->end($exitCode);
	}

	/**
	 * Creates the command runner instance.
	 * @return CConsoleCommandRunner the command runner
	 */
	protected function createCommandRunner()
	{
		return new CConsoleCommandRunner;
	}

	/**
	 * Displays the captured PHP error.
	 * This method displays the error in console mode when there is
	 * no active error handler.
	 * @param integer $code error code
	 * @param string $message error message
	 * @param string $file error file
	 * @param string $line error line
	 */
	public function displayError($code,$message,$file,$line)
	{
		echo "PHP Error[$code]: $message\n";
		echo "    in file $file at line $line\n";
		$trace=debug_backtrace();
		// skip the first 4 stacks as they do not tell the error position
		if(count($trace)>4)
			$trace=array_slice($trace,4);
		foreach($trace as $i=>$t)
		{
			if(!isset($t['file']))
				$t['file']='unknown';
			if(!isset($t['line']))
				$t['line']=0;
			if(!isset($t['function']))
				$t['function']='unknown';
			echo "#$i {$t['file']}({$t['line']}): ";
			if(isset($t['object']) && is_object($t['object']))
				echo get_class($t['object']).'->';
			echo "{$t['function']}()\n";
		}
	}

	/**
	 * Displays the uncaught PHP exception.
	 * This method displays the exception in console mode when there is
	 * no active error handler.
	 * @param Exception $exception the uncaught exception
	 */
	public function displayException($exception)
	{
		echo $exception;
	}

	/**
	 * @return string the directory that contains the command classes. Defaults to 'protected/commands'.
	 */
	public function getCommandPath()
	{
		$applicationCommandPath = $this->getBasePath().DIRECTORY_SEPARATOR.'commands';
		if($this->_commandPath===null && file_exists($applicationCommandPath))
			$this->setCommandPath($applicationCommandPath);
		return $this->_commandPath;
	}

	/**
	 * @param string $value the directory that contains the command classes.
	 * @throws CException if the directory is invalid
	 */
	public function setCommandPath($value)
	{
		if(($this->_commandPath=realpath($value))===false || !is_dir($this->_commandPath))
			throw new CException(Yii::t('yii','The command path "{path}" is not a valid directory.',
				array('{path}'=>$value)));
	}

	/**
	 * Returns the command runner.
	 * @return CConsoleCommandRunner the command runner.
	 */
	public function getCommandRunner()
	{
		return $this->_runner;
	}








    // DA HACKZ







    /**
     * The pre-filter for controller actions.
     * This method is invoked before the currently requested controller action and all its filters
     * are executed. You may override this method with logic that needs to be done
     * before all controller actions.
     * @param CController $controller the controller
     * @param CAction $action the action
     * @return boolean whether the action should be executed.
     */
    public function beforeControllerAction($controller,$action)
    {
        return true;
    }

    /**
     * The post-filter for controller actions.
     * This method is invoked after the currently requested controller action and all its filters
     * are executed. You may override this method with logic that needs to be done
     * after all controller actions.
     * @param CController $controller the controller
     * @param CAction $action the action
     */
    public function afterControllerAction($controller,$action)
    {
    }

    /**
     * @return CHttpSession the session component
     */
    public function getSession()
    {
        return new CHttpSession;//$this->getComponent('session');
    }

    /**
     * @return CThemeManager the theme manager.
     */
    public function getTheme()
    {
        return new CTheme(null,null,null);
    }

    /**
     * Returns the view renderer.
     * If this component is registered and enabled, the default
     * view rendering logic defined in {@link CBaseController} will
     * be replaced by this renderer.
     * @return IViewRenderer the view renderer.
     */
    public function getViewRenderer()
    {
        return $this->getComponent('viewRenderer');
    }

    /**
     * @return string the root directory of view files. Defaults to 'protected/views'.
     */
    private $_viewPath;
    public function getViewPath()
    {
        if($this->_viewPath!==null)
            return $this->_viewPath;
        else
            return $this->_viewPath=$this->getBasePath().DIRECTORY_SEPARATOR.'views';
    }

    /**
     * Returns the client script manager.
     * @return CClientScript the client script manager
     */
    public function getClientScript()
    {
        return new CClientScript();//$this->getComponent('clientScript');
    }

    /**
     * Returns the widget factory.
     * @return IWidgetFactory the widget factory
     * @since 1.1
     */
    public function getWidgetFactory()
    {
        return new CWidgetFactory();//$this->getComponent('widgetFactory');
    }

    public function getAssetManager()
    {
        $am = new CAssetManager();//$this->getComponent('widgetFactory');
        $am->setBasePath('www/assets');
        return $am;
    }

    public $controller;

    /**
     * @return string the root directory of layout files. Defaults to 'protected/views/layouts'.
     */
    private $_layoutPath;
    public function getLayoutPath()
    {
        if($this->_layoutPath!==null)
            return $this->_layoutPath;
        else
            return $this->_layoutPath=$this->getViewPath().DIRECTORY_SEPARATOR.'layouts';
    }

    /**
     * @return string the directory that contains the controller classes. Defaults to 'protected/controllers'.
     */
    private $_controllerPath;
    public function getControllerPath()
    {
        if($this->_controllerPath!==null)
            return $this->_controllerPath;
        else
            return $this->_controllerPath=$this->getBasePath().DIRECTORY_SEPARATOR.'controllers';
    }
}
