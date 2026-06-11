<?php
class Auth extends MY_Controller{
    public function index(){
        if(user_id()){
            if(!in_array(user()->type, [1])){
                $upa = $this->BaseModel->r_tbl('user_page_access', false, ['user_id' => user_id()]);
                $pageArray = (array) json_decode($upa->page);
                $page_subArray = (array) json_decode($upa->page_sub);
                $page_id = $pageArray[0] ?? null;
                if($page_id){
                    $page = $this->BaseModel->r_tbl('page', false, ['id' => $page_id]);
                    if($page){
                        $page_sub_id = $page_subArray[0] ?? null;
                        if($page_sub_id){
                            $page_subs = $this->BaseModel->r_tbl('page_sub', true, [
                                'id' => $page_sub_id,
                                'page_id' => $page_id
                            ]);
                            $page_sub = $page_subs[0] ?? null;
                            if($page_sub){
                                redirect($page->slug . '/' . $page_sub->slug);
                                return;
                            }
                        }
                        redirect($page->slug);
                        return;
                    }
                }
                redirect();
                return;
            }else{
                redirect();
                return;
            }
        }
        $data['content'] = $this->load->view('auth/index', [], true);
        $this->load->view('temp/wrapper', $data);
    }
    public function login(){
        if($this->input->method() !== 'post'){ show_404(); }
        $uname = strtolower(no_space($this->input->post('uname', true)));
        $upass = no_space($this->input->post('upass', true));
        $user = $this->BaseModel->r_tbl('user', false, ['uname' => $uname], [], ['upass' => false]);
        if(!$user){ echo json_encode(404); return; }
        if(!password_verify($upass, $user->upass)){ echo json_encode(401); return; }
        $this->session->set_userdata([
            'user_id' => $user->id,
            'toast0' => [
                'type' => 'success',
                'message' => 'Welcome <b>' . $user->fname . '</b>!'
            ],
        ]);
        echo json_encode(200);
    }
    public function logout(){
        $this->session->sess_destroy();
        redirect('auth');
    }
}
