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
class GiicApplication extends CConsoleApplication
{


    // DA HACKZ -- copied from CWebApplication


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
        $am->setBasePath($this->getBasePath().'/www/assets');
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
