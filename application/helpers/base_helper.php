<?php
function _ci(){
    static $ci;
    return $ci ?: $ci =& get_instance();
}
// base
function user_id(){
    $ci = _ci();
    return $ci->session->userdata('user_id');
}
function sys(){
    $ci = _ci();
    return $ci->BaseModel->r_tbl('sys', false, ['id' => 1]);
}
function user(){
    $ci = _ci();
    return $ci->BaseModel->r_tbl('user', false, ['id' => user_id()]);
}
function page(){
    $ci = _ci();
    return $ci->BaseModel->r_tbl('page', true);
}
function page_sub(){
    $ci = _ci();
    return $ci->BaseModel->r_tbl('page_sub', true);
}
function page_access($view_folder){
    if($view_folder != 'dashboard'){
        if(!in_array(user()->type, [1])){
            if(user()->upage){
                $list = (array) json_decode(user()->upage);
                if(empty($list)) return false;
                if(empty(user()->upage)){
                    return false;
                }
                foreach(page() as $p){
                    if($p->slug == $view_folder && !in_array($p->id, $list)){
                        return false;
                    }
                }
            }else{
                return false;
            }
        }
    }
    return true;
}
function page_sub_access($view_file){
    if($view_file != 'index'){
        if(!in_array(user()->type, [1])){
            $list = (array) json_decode(user()->upage_sub);
            foreach(page_sub() as $ps){
                if($ps->slug == $view_file && !in_array($ps->id, $list)){
                    return false;
                }
            }
        }
    }
    return true;
}
function payment_method(){
    $ci = _ci();
    return $ci->BaseModel->r_tbl('payment_method');
}
function vendor(){
    $ci = _ci();
    return $ci->BaseModel->r_tbl('vendor', true);
}
function po_count($filter = 0){
    $ci = _ci();
    return $ci->BaseModel->po_count($filter);
}
// others
function count_array($array){
    return count((array) json_decode($array));
}
function no_space($str){
    return preg_replace('/\s+/', '', $str);
}
function timezones(){
    return DateTimeZone::listIdentifiers();
}
function base_foot(){
    return '<i class="bi bi-c-circle me-2"></i>2026' . (date('Y') != '2026' ? ' - ' . date('Y') : '') . (sys() ? ' ' . sys()->name : '');
}
function str0($str){
    return ucwords(str_replace('_', ' ', $str));
}
