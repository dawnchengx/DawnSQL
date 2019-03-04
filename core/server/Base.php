<?php
/**
 * Created by PhpStorm.
 * User: v_hrrchen
 * Date: 2019/3/4
 * Time: 12:59
 */
const SUCCESS = 0;
const NOTFOUND = -1000;
class DataStructure
{
    static private $instance = null;
    public $keys = array();
    public $kvs = array();
    private function __construct()
    {
    }
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }
    static public function getInstance() {
        if (NULL === self::$instance) {
            self::$instance = new __CLASS__();
        }
        return self::$instance;
    }
}
interface IDataOperate
{
    function set($key, $value);
    function get($key);
    function del($key);
}
class DataOperate
{
    protected $dataOperateInterface = null;
    protected $result = array(
        "code" => SUCCESS,
        "data" => null,
        "msg" => "success"
    );
}