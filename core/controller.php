<?php

/**
 * Class Controller
 */
abstract class Controller
{
    /**
     * @var View
     */
    protected $view;

    /**
     *
     */
    function __construct()
    {
        $this->view = new View();
    }

    /** redirect to to login page if user is not logged
     *
     */
    protected function isLogged()
    {
        if (!isset($_SESSION['userId'])) {
            self::redirect('login', 'index');
        }
    }

    /**
     * @param string $controller
     * @param string $task
     */
    protected static function redirect($controller = "", $task = "")
    {
        if (!$controller) {
            $controller = str_replace('Controller', '', get_called_class());
        }
        if (!$task) {
            $task = 'index';
        }

        $location = 'index.php?controller=' . $controller . '&task=' . $task;
        header('Location: ' . $location);
        exit;
    }

    /** Render Json
     * @param array $data
     */
    protected function renderJson($data = [])
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        die;
    }

    /**
     * @param string $viewName
     */
    public function renderView($viewName = "")
    {
        if (strpos($viewName, "/")) {
            $slice = explode('/', $viewName);
            $folderName = $slice[0];
            $viewName = $slice[1];
        } else {
            $folderName = str_replace('Controller', '', get_class($this));
            $folderName = strtolower($folderName);
        }
        $this->view->setFile($folderName . '/' . $viewName);
        $this->view->getContent();
        exit();
    }
}

?>
