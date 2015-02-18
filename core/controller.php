<?php
abstract class Controller
{
    protected $view;
    
    function __construct()
    {
        $this->view = new View();
    }
    protected function isLogged()
    {
        if (!isset($_SESSION['userId']))
        {
            self::redirect('login', 'index');
        }
    }
    protected static function redirect($controller = "", $task = "")
    {
        if (!$controller)
            $controller =  str_replace('Controller', '', get_class($this));
        if (!$task)
            $task = 'index';

        $location = 'index.php?controller='.$controller.'&task='.$task;
        header('Location: '.$location);
        exit;
    }
    protected  function renderJson($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        die;
    }
    public function renderView($viewName = "")
    {
        if (strpos($viewName, "/"))
        {
            $slice = explode('/', $viewName);
            $folderName = $slice[0];
            $viewName = $slice[1];
        }
        else
        {
            $folderName = str_replace('Controller', '', get_class($this));
            $folderName = strtolower($folderName);
        }
        $this->view->setFile($folderName . '/' .$viewName);
        $this->view->getContent();
        exit();
    }
}
?>