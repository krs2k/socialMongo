<?php
abstract class Model
{
    static $logFile;
    static $mongoDriver;
    private $collection;
    
    function __construct()
    {
        $this->collection = $this->getCollection();
    }
    private function getCollection()
    {
        $collectionName = str_replace('Model', '', get_class($this)) . "s";
        $collection =  new MongoCollection(self::$mongoDriver->db, $collectionName);
        return $collection;
    }
    protected function schemaValidate(&$data, $option = array())
    {
        $err = array();
        foreach ($data as $key => $val) 
        {
            if (!isset($this->schema[$key]))
                unset($data[$key]);
        }
        foreach ($this->schema as $key => $schemaParmas) 
        {
            if(isset($option['setDefault'])  && isset($schemaParmas["default"]) && !isset($data[$key]))
            {
                $data[$key] = $schemaParmas["default"];
            }
            if(isset($option['checkRequired']) && $option['checkRequired'] && isset($schemaParmas["required"]) && $data[$key] == "")
            {
                array_push($err, $key ." is required");
            }
            if(isset($option['checkUnique']) && $option['checkUnique'] && isset($schemaParmas["unique"]))
            {
                $doc = $this->findOne([$key => $data[$key]], [$key => true]);
                if ($doc)
                    array_push($err, $key ." must be unique");
            }
            if( isset($schemaParmas["type"]) && isset($data[$key]))
            {
                switch ($schemaParmas["type"]) {
                    case "string":
                        if(!is_string($data[$key]) || is_numeric($data[$key]))
                        {
                            array_push($err, $key ." must be string");
                        }
                        break;
                    case "number":
                        if(!is_numeric($data[$key]))
                        {
                           array_push($err,  $key ." must be number");
                        }
                        break;
                    case "email":
                        if (!filter_var($data[$key], FILTER_VALIDATE_EMAIL))
                        {
                            array_push($err,  $key ." must be email");
                        }
                        break;
                }
            }
        }
        $ok = true;
        if($err)
            $ok = false;
        return ['error'=>$err,'ok'=>$ok];
    }
    public function __call($method, $arguments)
    {
        $query = array();
        $fields = array();
        if ( method_exists($this->collection, $method))
        {
            if (isset($arguments[0]))
                $query = $arguments[0];
            else
            {
                $response = $this->collection->$method();
                return $response;
            }
            if (isset($arguments[1]))
                $fields = $arguments[1];
            $response = $this->collection->$method($query, $fields);
            return $response;
        }else
            trigger_error('Call to undefined method '.get_class($this).'::'.$method.'()');
    }
    public function insert($data)
    {
        $res = $this->schemaValidate($data, ['setDefault'=> true, 'checkRequired' =>true, 'checkUnique' => true]);
        if ($res['ok']){
            $res =  $this->collection->insert($data);
            if ($res["ok"])
                $res = $data;
        }
        return $res;
    }
    public function update($query, $update)
    {
        if(isset($update['$set']))
            $res = $this->schemaValidate($update['$set']);
        else
            $res['ok'] = true;

        if ($res['ok']){
            $res =  $this->collection->update($query, $update);
        }
        return $res;
    }
}
?>