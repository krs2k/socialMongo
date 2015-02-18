<?php
class View
{
    private $file;
    private $layout = 'app';
    public $data = array();
    public $notifies = array();

    public function addNotify($msg, $class)
    {
        $notify = ['msg'=>$msg, 'class'=>$class];
        array_push($this->notifies, $notify);
    }
    public function addNotifies($msgs, $class)
    {
        foreach ($msgs as $msg)
        {
           $this->addNotify($msg, $class);
        }
    }
    public function setLayout($layout)
    {
    	$this->layout = $layout;
    }
    public function setFile($file)
    {
    	$this->file = $file;
    }
    public function linkTo($href, $text, $class = 'blackBlock')
    {
        if (strpos($href, "#"))
        {
            $slice = explode('#', $href);
            $href = "index.php?controller=".$slice[0]."&task=".$slice[1];
        }
        echo "<a href=".$href." class='".$class."' >".$text."</a>";
    }
    public function getContent()
    {
        foreach ($this->data as $name => $value) 
        {
            $$name = $value;
        }
        $dir =  __DIR__ . '/../app/views';
        $this->file = $dir . "/" . $this->file . ".php.html";
        $this->layout = $dir . "/layouts/" . $this->layout . ".php.html";
        $notifies = $this->notifies;
        $view = $this;
        $main = $this->file;
        require $this->layout;
    }
}