<?php

/**
 * Class View
 */
class View
{
    /**
     * @var
     */
    private $file;
    /**
     * @var string
     */
    private $layout = 'app';
    /**
     * @var array
     */
    public $data = array();
    /**
     * @var array
     */
    public $notifies = array();

    /**
     * @param $msg
     * @param $class
     */
    public function addNotify($msg, $class)
    {
        $notify = ['msg' => $msg, 'class' => $class];
        array_push($this->notifies, $notify);
    }

    /**
     * @param $msgs
     * @param $class
     */
    public function addNotifies($msgs, $class)
    {
        foreach ($msgs as $msg) {
            $this->addNotify($msg, $class);
        }
    }

    /**
     * @param $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * @param $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @param $href
     * @param $text
     * @param string $class
     */
    public function linkTo($href, $text, $class = 'blackBlock')
    {
        if (strpos($href, "#")) {
            $slice = explode('#', $href);
            $href = "index.php?controller=" . $slice[0] . "&task=" . $slice[1];
        }
        echo "<a href=" . $href . " class='" . $class . "' >" . $text . "</a>";
    }

    /**
     *
     */
    public function getContent()
    {
        foreach ($this->data as $name => $value) {
            $$name = $value;
        }
        $dir = __DIR__ . '/../app/views';
        $this->file = $dir . "/" . $this->file . ".php.html";
        $this->layout = $dir . "/layouts/" . $this->layout . ".php.html";
        $notifies = $this->notifies;
        $view = $this;
        $main = $this->file;
        require $this->layout;
    }
}