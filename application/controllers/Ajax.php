<?php
class Ajax extends MY_Controller{
    public function __construct(){
        parent::__construct();
        if(!user_id()){ show_404(); }
        if($this->input->method() !== 'post'){ show_404(); }
    }
    // purchase
    public function base_purchase($data){
        $result = [];
        if(isset($data['purchase_product'])){
            $result['purchase_product'] = $data['purchase_product'];
            unset($data['purchase_product']);
        }
        $result['purchase'] = $data;
        return $result;
    }
    public function c_purchase(){
        $data = $this->input->post('data') ?? [];
        $data['created_at'] = date('Y-m-d H:i:s');
        $data = $this->base_purchase($data);
        $result = $this->BaseModel->c_tbl('purchase', $data['purchase']);
        if($result->status == 200 && !empty($data['purchase_product'])){
            foreach($data['purchase_product'] as &$p){
                $p['purchase_id'] = $result->new_id;
            }
            $this->BaseModel->c_tbl_batch('purchase_product', $data['purchase_product']);
        }
        echo json_encode($result->status);
    }
    public function r_purchase(){
        $all = filter_var($this->input->post('all'), FILTER_VALIDATE_BOOLEAN);
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->r_tbl('purchase', $all, $toFilter));
    }
    public function u_purchase(){
        $data = $this->input->post('data') ?? [];
        $toFilter = $this->input->post('toFilter') ?? [];
        $data = $this->base_purchase($data);
        $this->db->trans_start();
        $result = $this->BaseModel->u_tbl('purchase', $data['purchase'], $toFilter);
        if($result == 200){
            if(!empty($toFilter['id'])){
                $this->BaseModel->d_tbl('purchase_product', [
                    'purchase_id' => $toFilter['id']
                ]);
            }
            if(!empty($data['purchase_product'])){
                foreach($data['purchase_product'] as &$p){
                    $p['purchase_id'] = $toFilter['id'];
                }
                unset($p);
                $this->BaseModel->c_tbl_batch('purchase_product', $data['purchase_product']);
            }
        }
        $this->db->trans_complete();
        echo json_encode($result);
    }
    public function d_purchase(){
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->d_tbl('purchase', $toFilter));
    }
    // purchase others
    public function purchase_paid(){
        $d1 = $this->input->post('d1');
        $d2 = $this->input->post('d2');
        echo json_encode($this->BaseModel->purchase_paid($d1, $d2));
    }
    public function purchase_filtered(){
        echo json_encode($this->BaseModel->purchase_filtered(user()->type, user()->role));
    }
    // purchase_product
    public function base_purchase_product($data){
        return $data;
    }
    public function c_purchase_product(){
        $data = $this->input->post('data') ?? [];
        $data = $this->base_purchase_product($data);
        $result = $this->BaseModel->c_tbl('purchase_product', $data);
        echo json_encode($result->status);
    }
    public function r_purchase_product(){
        $all = filter_var($this->input->post('all'), FILTER_VALIDATE_BOOLEAN);
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->r_tbl('purchase_product', $all, $toFilter));
    }
    public function u_purchase_product(){
        $data = $this->input->post('data') ?? [];
        $toFilter = $this->input->post('toFilter') ?? [];
        $data = $this->base_purchase_product($data);
        echo json_encode($this->BaseModel->u_tbl('purchase_product', $data, $toFilter));
    }
    public function d_purchase_product(){
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->d_tbl('purchase_product', $toFilter));
    }
    public function po_count(){
        $filter = $this->input->post('filter');
        echo json_encode($this->BaseModel->po_count($filter));
    }
    public function add_po_product(){
        $purchase_id = $this->input->post('purchase_id');
        $name = $this->input->post('name');
        echo json_encode($this->BaseModel->add_po_product($purchase_id, $name));
    }
    public function getpoproducts_purchaseid(){
        $purchase_id = $this->input->post('purchase_id');
        echo json_encode($this->BaseModel->getpoproducts_purchaseid($purchase_id));
    }
    // purchase_payment
    public function base_purchase_payment($data){
        return $data;
    }
    public function c_purchase_payment(){
        $data = $this->input->post('data') ?? [];
        $data['created_at'] = date('Y-m-d H:i:s');
        $data = $this->base_purchase_payment($data);
        $result = $this->BaseModel->c_tbl('purchase_payment', $data);
        echo json_encode($result->status);
    }
    public function r_purchase_payment(){
        $all = filter_var($this->input->post('all'), FILTER_VALIDATE_BOOLEAN);
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->r_tbl('purchase_payment', $all, $toFilter));
    }
    public function u_purchase_payment(){
        $data = $this->input->post('data') ?? [];
        $toFilter = $this->input->post('toFilter') ?? [];
        $data = $this->base_purchase_payment($data);
        echo json_encode($this->BaseModel->u_tbl('purchase_payment', $data, $toFilter));
    }
    public function d_purchase_payment(){
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->d_tbl('purchase_payment', $toFilter));
    }
    // bo
    public function base_bo($data){
        $result = [];
        if(isset($data['bo_product'])){
            $result['bo_product'] = $data['bo_product'];
            unset($data['bo_product']);
        }
        $result['bo'] = $data;
        return $result;
    }
    public function c_bo(){
        $data = $this->input->post('data') ?? [];
        $data['created_at'] = date('Y-m-d H:i:s');
        $data = $this->base_bo($data);
        $result = $this->BaseModel->c_tbl('bo', $data['bo']);
        if($result->status == 200 && !empty($data['bo_product'])){
            foreach($data['bo_product'] as &$p){
                $p['bo_id'] = $result->new_id;
            }
            $this->BaseModel->c_tbl_batch('bo_product', $data['bo_product']);
        }
        echo json_encode($result->status);
    }
    public function r_bo(){
        $all = filter_var($this->input->post('all'), FILTER_VALIDATE_BOOLEAN);
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->r_tbl('bo', $all, $toFilter));
    }
    public function u_bo(){
        $data = $this->input->post('data') ?? [];
        $toFilter = $this->input->post('toFilter') ?? [];
        $data = $this->base_bo($data);
        $this->db->trans_start();
        $result = $this->BaseModel->u_tbl('bo', $data['bo'], $toFilter);
        if($result == 200){
            if(!empty($toFilter['id'])){
                $this->BaseModel->d_tbl('bo_product', [
                    'bo_id' => $toFilter['id']
                ]);
            }
            if(!empty($data['bo_product'])){
                foreach($data['bo_product'] as &$p){
                    $p['bo_id'] = $toFilter['id'];
                }
                unset($p);
                $this->BaseModel->c_tbl_batch('bo_product', $data['bo_product']);
            }
        }
        $this->db->trans_complete();
        echo json_encode($result);
    }
    public function d_bo(){
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->d_tbl('bo', $toFilter));
    }
    public function bo_create(){
        $bor_id = $this->input->post('bor_id');
        $pickedup_by = $this->input->post('pickedup_by');
        $pickedup_at = $this->input->post('pickedup_at');
        $bor_product = $this->input->post('bor_product');
        $bor = $this->BaseModel->r_tbl('bor', false, ['id' => $bor_id]);
        $bo_data = [
            'bor_id' => $bor_id,
            'vendor_id' => $bor->vendor_id,
            'pickedup_by' => $pickedup_by,
            'pickedup_at' => $pickedup_at,
            'created_at' => date('Y-m-d')
        ];
        echo json_encode($this->BaseModel->bo_create($bo_data, $bor_product));
    }
    public function bo(){
        echo json_encode($this->BaseModel->bo());
    }
    public function bo_encode(){
        $bo_id = $this->input->post('bo_id');
        $encoded_at = $this->input->post('encoded_at');
        $bo_product = $this->input->post('bo_product');
        $data = [
            'encoded_at' => $encoded_at
        ];
        echo json_encode($this->BaseModel->bo_encode($bo_id, $data, $bo_product));
    }
    // bo_product
    public function c_bo_product(){
        $data = $this->input->post('data') ?? [];
        $result = $this->BaseModel->c_tbl('bo_product', $data);
        echo json_encode($result->status);
    }
    public function r_bo_product(){
        $all = filter_var($this->input->post('all'), FILTER_VALIDATE_BOOLEAN);
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->r_tbl('bo_product', $all, $toFilter));
    }
    public function u_bo_product(){
        $data = $this->input->post('data') ?? [];
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->u_tbl('bo_product', $data, $toFilter));
    }
    public function d_bo_product(){
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->d_tbl('bo_product', $toFilter));
    }
    // bor
    public function base_bor($data){
        $result = [];
        if(isset($data['bor_product'])){
            $result['bor_product'] = $data['bor_product'];
            unset($data['bor_product']);
        }
        $result['bor'] = $data;
        return $result;
    }
    public function c_bor(){
        $data = $this->input->post('data') ?? [];
        $data['created_at'] = date('Y-m-d H:i:s');
        $data = $this->base_bor($data);
        $result = $this->BaseModel->c_tbl('bor', $data['bor']);
        if($result->status == 200 && !empty($data['bor_product'])){
            foreach($data['bor_product'] as &$p){
                $p['bor_id'] = $result->new_id;
            }
            $this->BaseModel->c_tbl_batch('bor_product', $data['bor_product']);
        }
        echo json_encode($result->status);
    }
    public function r_bor(){
        $all = filter_var($this->input->post('all'), FILTER_VALIDATE_BOOLEAN);
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->r_tbl('bor', $all, $toFilter));
    }
    public function u_bor(){
        $data = $this->input->post('data') ?? [];
        $toFilter = $this->input->post('toFilter') ?? [];
        $data = $this->base_bor($data);
        $this->db->trans_start();
        $result = $this->BaseModel->u_tbl('bor', $data['bor'], $toFilter);
        if($result == 200){
            if(!empty($toFilter['id'])){
                $this->BaseModel->d_tbl('bor_product', [
                    'bor_id' => $toFilter['id']
                ]);
            }
            if(!empty($data['bor_product'])){
                foreach($data['bor_product'] as &$p){
                    $p['bor_id'] = $toFilter['id'];
                }
                unset($p);
                $this->BaseModel->c_tbl_batch('bor_product', $data['bor_product']);
            }
        }
        $this->db->trans_complete();
        echo json_encode($result);
    }
    public function d_bor(){
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->d_tbl('bor', $toFilter));
    }
    public function bor(){
        echo json_encode($this->BaseModel->bor());
    }
    public function bor_count(){
        echo json_encode($this->BaseModel->bor_count());
    }
    // bor_product
    public function c_bor_product(){
        $data = $this->input->post('data') ?? [];
        $result = $this->BaseModel->c_tbl('bor_product', $data);
        echo json_encode($result->status);
    }
    public function r_bor_product(){
        $all = filter_var($this->input->post('all'), FILTER_VALIDATE_BOOLEAN);
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->r_tbl('bor_product', $all, $toFilter));
    }
    public function u_bor_product(){
        $data = $this->input->post('data') ?? [];
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->u_tbl('bor_product', $data, $toFilter));
    }
    public function d_bor_product(){
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->d_tbl('bor_product', $toFilter));
    }
    // vendor
    public function base_vendor($data){
        $result = [];
        if(isset($data['vendor_product'])){
            $result['vendor_product'] = $data['vendor_product'];
            unset($data['vendor_product']);
        }
        $result['vendor'] = $data;
        return $result;
    }
    public function c_vendor(){
        $data = $this->input->post('data') ?? [];
        $data = $this->base_vendor($data);
        $result = $this->BaseModel->c_tbl('vendor', $data['vendor']);
        if($result->status == 200 && !empty($data['vendor_product'])){
            foreach($data['vendor_product'] as &$p){ $p['vendor_id'] = $result->new_id; }
            $this->BaseModel->c_tbl_batch('vendor_product', $data['vendor_product']);
        }
        echo json_encode($result->status);
    }
    public function r_vendor(){
        $all = filter_var($this->input->post('all'), FILTER_VALIDATE_BOOLEAN);
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->r_tbl('vendor', $all, $toFilter));
    }
    public function u_vendor(){
        $data = $this->input->post('data') ?? [];
        $toFilter = $this->input->post('toFilter') ?? [];
        $data = $this->base_vendor($data);
        $this->db->trans_start();
        $result = $this->BaseModel->u_tbl('vendor', $data['vendor'], $toFilter);
        if($result == 200){
            if(!empty($toFilter['id'])){
                $this->BaseModel->d_tbl('vendor_product', [
                    'vendor_id' => $toFilter['id']
                ]);
            }
            if(!empty($data['vendor_product'])){
                foreach($data['vendor_product'] as &$p){
                    $p['vendor_id'] = $toFilter['id'];
                }
                unset($p);
                $this->BaseModel->c_tbl_batch('vendor_product', $data['vendor_product']);
            }
        }
        $this->db->trans_complete();
        echo json_encode($result);
    }
    public function d_vendor(){
        $toFilter = $this->input->post('toFilter') ?? [];
        $result = $this->BaseModel->d_tbl('vendor', $toFilter);
        if($result == 200){
            if(!empty($toFilter['id'])){
                $this->BaseModel->d_tbl('vendor_product', [
                    'vendor_id' => $toFilter['id']
                ]);
            }
        }
        echo json_encode($result);
    }
    // vendor_product
    public function base_vendor_product($data){
        return $data;
    }
    public function c_vendor_product(){
        $data = $this->input->post('data') ?? [];
        $data = $this->base_vendor_product($data);
        $result = $this->BaseModel->c_tbl('vendor_product', $data);
        echo json_encode($result->status);
    }
    public function r_vendor_product(){
        $all = filter_var($this->input->post('all'), FILTER_VALIDATE_BOOLEAN);
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->r_tbl('vendor_product', $all, $toFilter));
    }
    public function u_vendor_product(){
        $data = $this->input->post('data') ?? [];
        $toFilter = $this->input->post('toFilter') ?? [];
        $data = $this->base_vendor_product($data);
        echo json_encode($this->BaseModel->u_tbl('vendor_product', $data, $toFilter));
    }
    public function d_vendor_product(){
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->d_tbl('vendor_product', $toFilter));
    }
    public function vendor_product_add(){
        $vendor_id = $this->input->post('vendor_id');
        $name = $this->input->post('name');
        $data = [
            'vendor_id' => $vendor_id,
            'name' => $name
        ];
        echo json_encode($this->BaseModel->vendor_product_add($data));
    }
    // vp others
    public function vp_cost(){
        $purchase_id = $this->input->post('purchase_id');
        $vendor_id = $this->input->post('vendor_id');
        $name = $this->input->post('name');
        $cost = $this->input->post('cost');
        $data = [ 'cost' => $cost ];
        $this->BaseModel->vp_cost($purchase_id, $vendor_id, $name, $data);
    }
    // user
    public function base_user($data){
        if(isset($data['fname'])){ $data['fname'] = trim(ucwords($data['fname'])); }
        if(isset($data['lname'])){ $data['lname'] = trim(ucwords($data['lname'])); }
        if(isset($data['uname'])){ $data['uname'] = no_space(strtolower($data['uname'])); }
        if(isset($data['upass'])){ $data['upass'] = password_hash($data['upass'], PASSWORD_DEFAULT); }
        return $data;
    }
    public function c_user(){
        $data = $this->input->post('data') ?? [];
        $data['upass'] = sys()->default_upass;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data = $this->base_user($data);
        $result = $this->BaseModel->c_tbl('user', $data);
        echo json_encode($result->status);
    }
    public function u_user(){
        $data = $this->input->post('data') ?? [];
        $toFilter = $this->input->post('toFilter') ?? [];
        $data = $this->base_user($data);
        echo json_encode($this->BaseModel->u_tbl('user', $data, $toFilter));
    }
    public function r_user(){
        $all = filter_var($this->input->post('all'), FILTER_VALIDATE_BOOLEAN);
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->r_tbl('user', $all, $toFilter));
    }
    public function d_user(){
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->d_tbl('user', $toFilter));
    }
    // user_page_access
    public function base_user_page_access($data){
        $data['page'] = json_encode($data['page'] ?? []);
        $data['page_sub'] = json_encode($data['page_sub'] ?? []);
        return $data;
    }
    public function c_user_page_access(){
        $data = $this->input->post('data') ?? [];
        $data = $this->base_user_page_access($data);
        $result = $this->BaseModel->c_tbl('user_page_access', $data);
        echo json_encode($result->status);
    }
    public function r_user_page_access(){
        $all = filter_var($this->input->post('all'), FILTER_VALIDATE_BOOLEAN);
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->r_tbl('user_page_access', $all, $toFilter));
    }
    public function u_user_page_access(){
        $data = $this->input->post('data') ?? [];
        $toFilter = $this->input->post('toFilter') ?? [];
        $data = $this->base_user_page_access($data);
        echo json_encode($this->BaseModel->u_tbl('user_page_access', $data, $toFilter));
    }
    public function d_user_page_access(){
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->d_tbl('user_page_access', $toFilter));
    }
    // user others
    public function check_upass(){
        $user_id = $this->input->post('user_id');
        $upass = $this->input->post('upass');
        echo json_encode($this->BaseModel->check_upass($user_id, $upass));
    }
    public function change_upass(){
        $user_id = $this->input->post('user_id');
        $upass = $this->input->post('upass');
        echo json_encode($this->BaseModel->change_upass($user_id, $upass));
    }
    // page
    public function c_page(){
        $data = $this->input->post('data') ?? [];
        $result = $this->BaseModel->c_tbl('page', $data);
        echo json_encode($result->status);
    }
    public function r_page(){
        $all = filter_var($this->input->post('all'), FILTER_VALIDATE_BOOLEAN);
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->r_tbl('page', $all, $toFilter));
    }
    public function u_page(){
        $data = $this->input->post('data') ?? [];
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->u_tbl('page', $data, $toFilter));
    }
    public function d_page(){
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->d_tbl('page', $toFilter));
    }
    // page_sub
    public function c_page_sub(){
        $data = $this->input->post('data') ?? [];
        $result = $this->BaseModel->c_tbl('page_sub', $data);
        echo json_encode($result->status);
    }
    public function r_page_sub(){
        $all = filter_var($this->input->post('all'), FILTER_VALIDATE_BOOLEAN);
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->r_tbl('page_sub', $all, $toFilter));
    }
    public function u_page_sub(){
        $data = $this->input->post('data') ?? [];
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->u_tbl('page_sub', $data, $toFilter));
    }
    public function d_page_sub(){
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->d_tbl('page_sub', $toFilter));
    }
    // sys
    public function c_sys(){
        $data = $this->input->post('data') ?? [];
        $result = $this->BaseModel->c_tbl('sys', $data);
        echo json_encode($result->status);
    }
    public function r_sys(){
        $all = filter_var($this->input->post('all'), FILTER_VALIDATE_BOOLEAN);
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->r_tbl('sys', $all, $toFilter));
    }
    public function u_sys(){
        $data = $this->input->post('data') ?? [];
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->u_tbl('sys', $data, $toFilter));
    }
    public function d_sys(){
        $toFilter = $this->input->post('toFilter') ?? [];
        echo json_encode($this->BaseModel->d_tbl('sys', $toFilter));
    }
}
