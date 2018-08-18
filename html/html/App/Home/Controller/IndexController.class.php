<?php
namespace Home\Controller;
use Think\Controller;

/**
* 框架
*/
class IndexController extends CheckController {

    public function top(){
      $this->display();
    }

    public function index(){
    	$this -> display();
    }

    public function menu(){
    	$this -> display();
    }

    public function main(){
      $this->display();
    }
}
