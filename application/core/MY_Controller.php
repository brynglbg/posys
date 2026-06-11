<?php
class MY_Controller extends CI_Controller{
    public function __construct(){
        parent::__construct();
        if(sys()){
            date_default_timezone_set(sys()->timezone);
        }else{
            date_default_timezone_set('Asia/Manila');
        }
    }
}
