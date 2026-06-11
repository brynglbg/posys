<?php
require_once APPPATH . 'libraries/fpdf-v1.86/fpdf.php';
class Pdf extends FPDF{
    public function __construct(){
        parent::__construct();
    }
}
