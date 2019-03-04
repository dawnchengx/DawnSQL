<?php
/**
 * Created by PhpStorm.
 * User: v_hrrchen
 * Date: 2019/3/4
 * Time: 13:57
 */
require_once "../core/server/Base.php";

class StringOperate extends DataOperate implements IDataOperate
{
    private $instance = null;
    public function __construct() {
        $this->instance = DataStructure::getInstance();
    }
    function run($command) {
        $command = explode(" ", $command);
        switch ($command[0]) {
            case "set":
                return $this->set($command[1], $command[2]);
                break;
            case "get":
                return $this->get($command[1]);
                break;
            case "del":
                return $this->del($command[1]);
                break;
            case "append":
                return $this->append($command[1], $command[2]);
                break;
            default:
                $this->result['code'] =  NONSUPPORTCOMMOND;
                $this->result['msg'] =  "不支持该命令。";
                return $this->result;
                break;
        }
    }
    function set($key, $value){
        if(!in_array($key, $this->instance->keys)) {
            array_push($this->instance->keys, $key);
        }
        $this->instance->kvs[$key] = $value;
        return $this->result;
    }
    function get($key){
        if(!in_array($key, $this->instance->keys)) {
            $this->result['code'] =  NOTFOUND;
            $this->result['msg'] =  "该值不存在。";
        }else {
            $this->result['data'] = $this->instance->kvs[$key];
        }
        return $this->result;
    }
    function del($key){
        if(in_array($key, $this->instance->keys)) {
            $len = count($this->instance->keys);
            for($i = 0; $i < $len; $i++) {
                if($key === $this->instance->keys[$i]) {
                    array_splice($this->instance->keys, $i, 1);
                }
            }
            unset($this->instance->kvs[$key]);
        }
        return $this->result;
    }
    // 追加一个值到key上
    function append($key, $value){
        if(!in_array($key, $this->instance->keys)) {
            $this->instance->kvs[$key] = $value;
            array_push($this->instance->keys, $key);
        }else{
            $this->instance->kvs[$key] .= $value;
        }
        return SUCCESS;
    }
}
