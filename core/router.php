<?php

/**
 * Class Router
 */
class Router
{
    /**
     * @var
     */
    private static $instance;
    /**
     * @var string
     */
    private $controllerDefault = "users";
    /**
     * @var string
     */
    private $taskDefault = "show";
    /**
     * @var
     */
    private $controller;
    /**
     * @var
     */
    private $task;

    /**
     * @return Router
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            $router = new Router();

            if (isset($_GET['controller']) && $_GET['controller']) {
                $router->controller = $_GET['controller'] . "Controller";
            } else {
                $router->controller = $router->controllerDefault . "Controller";
            }

            if (isset($_GET['task']) && $_GET['task']) {
                $router->task = $_GET['task'];
            } else {
                $router->task = $router->taskDefault;
            }

            self::$instance = $router;
        }

        return self::$instance;
    }

    /**
     *
     */
    public function run()
    {
        require_once __DIR__ . "/../app/controllers/" . $this->controller . ".php";
        $task = $this->task;
        $controller = new $this->controller();
        $controller->$task();
        $controller->renderView($task);
    }
}