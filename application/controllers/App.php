<?php
class App extends MY_Controller{
    public function __construct(){
        parent::__construct();
        if(!user_id()){ redirect('auth'); }
    }
    public function view($view_folder = 'dashboard', $view_file = 'index'){
        $view_path = 'app/' . $view_folder . '/' . $view_file;
        if(
            !file_exists(APPPATH . 'views/' . $view_path . '.php') ||
            (!in_array(user()->type, [1, 2]) && $view_folder != 'dashboard') ||
            (!in_array(user()->type, [1]) && $view_folder == 'dashboard') ||
            (!in_array(user()->type, [1]) && in_array($view_folder, ['users', 'settings'])) ||
            (!in_array(user()->type, [1]) && in_array($view_file, ['page_setup'])) ||
            !page_access($view_folder) ||
            !page_sub_access($view_file)
        ){ show_404(); }
        $data = [];
        $data['view_folder'] = $view_folder;
        $data['view_file'] = $view_file;
        $data['user_type'] = $this->BaseModel->r_tbl('user_type', true, [], ['id' => 1]);
        if(user()->type == 1){ $data['user_type'] = $this->BaseModel->r_tbl('user_type', true); }
        // 
        $data['bor_count'] = $this->BaseModel->bor_count();
        // 
        $data['app_head'] = $this->load->view('temp/app_head', $data, true);
        $data['app_body'] = $this->load->view($view_path, $data, true);
        $data['app_foot'] = $this->load->view('temp/app_foot', $data, true);
        $data['content'] = $this->load->view('temp/app_wrapper', $data, true);
        $this->load->view('temp/wrapper', $data);
    }
    public function pdf_print($view_folder = '', $view_file = ''){
        $view_path = 'app/' . $view_folder . '/' . $view_file;
        if(!file_exists(APPPATH . 'views/' . $view_path . '.php')){ show_404(); }
        $this->load->view($view_path);
    }
}
