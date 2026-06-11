<?php
class BaseModel extends CI_Model{
    // ===== SINGLE ROW CRUD =====
    public function c_tbl($tbl, $data){
        $result = new stdClass();
        $result->status = 500;
        if(empty($data)) return $result;
        if($this->db->insert($tbl, $data)){
            $result->new_id = $this->db->insert_id();
            $result->status = 200;
        }
        return $result;
    }
    public function r_tbl($tbl, $all = true, $toFilter = [], $toFilterOut = [], $toExcludeCol = []){
        $this->{'base_' . $tbl}($toExcludeCol);
        foreach($toFilter as $key => $value){ $this->db->where($tbl . '.' . $key, $value); }
        foreach($toFilterOut as $key => $value){ $this->db->where($tbl . '.' . $key . ' !=', $value); }
        $query = $this->db->get();
        return $all ? $query->result() : $query->row();
    }
    public function u_tbl($tbl, $data, $toFilter){
        if(empty($toFilter) || empty($data)) return 500;
        foreach($toFilter as $key => $value){
            $this->db->where($tbl . '.' . $key, $value);
        }
        if($this->db->update($tbl, $data)) return 200;
        return 500;
    }
    public function d_tbl($tbl, $toFilter){
        if(empty($toFilter)) return 500;
        foreach($toFilter as $key => $value){
            $this->db->where($tbl . '.' . $key, $value);
        }
        if($this->db->delete($tbl)) return 200;
        return 500;
    }
    // ===== BATCH CRUD =====
    public function c_tbl_batch($tbl, $data){
        $result = new stdClass();
        $result->status = 500;
        if(empty($data)) return $result;
        if($this->db->insert_batch($tbl, $data)) $result->status = 200;
        return $result;
    }
    // ===== BASE QUERIES =====
    // purchase
    public function base_purchase($toExcludeCol = []){
        $tbl = 'purchase';
        $fields = $this->db->list_fields($tbl);
        $excludedCol = [];
        if($toExcludeCol){
            foreach($toExcludeCol as $key => $value){
                if($value === true){
                    $excludedCol[] = $key;
                }
            }
        }
        $fields = array_diff($fields, $excludedCol);
        $prefixedFields = array_map(function($f) use ($tbl){
            return $tbl . '.' . $f;
        }, $fields);
        $this->db->select($prefixedFields);
        $this->db->select('
            v.name AS vendor_name,
            v.agent_name AS vendor_agent_name,
            v.contact_no AS vendor_contact_no,
            ROUND(COALESCE(pp.total_amount, 0), 2) AS total_amount,
            ROUND(COALESCE(SUM(COALESCE(py.total_paid, 0) + COALESCE(purchase.discount, 0)), 0), 2) AS total_paid,
            ROUND((COALESCE(pp.total_amount, 0) - COALESCE(SUM(COALESCE(py.total_paid, 0) + COALESCE(purchase.discount, 0)), 0)), 2) AS total_balance,
            pp_names.pp_names
        ');
        $this->db->from($tbl);
        $this->db->join('vendor v', 'v.id = ' . $tbl . '.vendor_id', 'LEFT');
        $this->db->join('
            (
                SELECT purchase_id, SUM(cost * qty) AS total_amount
                FROM purchase_product
                GROUP BY purchase_id
            ) pp',
            'pp.purchase_id = ' . $tbl . '.id',
            'LEFT'
        );
        $this->db->join('
            (
                SELECT purchase_id, SUM(amount) AS total_paid
                FROM purchase_payment
                GROUP BY purchase_id
            ) py',
            'py.purchase_id = ' . $tbl . '.id',
            'LEFT'
        );
        $this->db->join('
            (
                SELECT 
                    purchase_id,
                    GROUP_CONCAT(name SEPARATOR ", ") AS pp_names
                FROM purchase_product
                GROUP BY purchase_id
            ) pp_names',
            'pp_names.purchase_id = ' . $tbl . '.id',
            'LEFT'
        );
        $this->db->group_by($tbl . '.id');
    }
    public function base_purchase_product($toExcludeCol){
        $tbl = 'purchase_product';
        $fields = $this->db->list_fields($tbl);
        $excludedCol = [];
        if($toExcludeCol){
            foreach($toExcludeCol as $key => $value){
                if($value === true){
                    $excludedCol[] = $key;
                }
            }
        }
        $fields = array_diff($fields, $excludedCol);
        $prefixedFields = array_map(function($f) use ($tbl){
            return $tbl . '.' . $f;
        }, $fields);
        $this->db->select($prefixedFields);
        $this->db->from($tbl);
    }
    public function base_purchase_payment($toExcludeCol){
        $tbl = 'purchase_payment';
        $fields = $this->db->list_fields($tbl);
        $excludedCol = [];
        if($toExcludeCol){
            foreach($toExcludeCol as $key => $value){
                if($value === true){
                    $excludedCol[] = $key;
                }
            }
        }
        $fields = array_diff($fields, $excludedCol);
        $prefixedFields = array_map(function($f) use ($tbl){
            return $tbl . '.' . $f;
        }, $fields);
        $this->db->select($prefixedFields);
        $this->db->select('
            payment_method.name AS payment_method_name,
        ');
        $this->db->from($tbl);
        $this->db->join('payment_method', 'payment_method.id = ' . $tbl . '.method', 'LEFT');
        $this->db->group_by($tbl . '.id');
    }
    public function purchase_paid($d1, $d2){
        $cols = $this->db->query("
            SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'purchase_payment'
        ")->result();
        $fields = [];
        foreach($cols as $col){
            $name = $col->COLUMN_NAME;
            $fields[] = "'" . $name . "', pp." . $name;
        }
        // add payment method name manually
        $fields[] = "'payment_method_name', pm.name";
        $jsonObject = "JSON_OBJECT(" . implode(',', $fields) . ")";
        $this->db->select('
            purchase.*,
            v.name AS vendor_name,
            v.agent_name AS vendor_agent_name,
            v.contact_no AS vendor_contact_no,
            ROUND(COALESCE(pp.total_amount, 0), 2) AS total_amount,
            ROUND(COALESCE((COALESCE(py.total_paid, 0) + COALESCE(purchase.discount, 0)), 0), 2) AS total_paid,
            ROUND((COALESCE(pp.total_amount, 0) - COALESCE((COALESCE(py.total_paid, 0) + COALESCE(purchase.discount, 0)), 0)), 2) AS total_balance,
            py_list.payments AS payments
        ');
        $this->db->from('purchase');
        $this->db->join('vendor v', 'v.id = purchase.vendor_id', 'LEFT');
        $this->db->join('
            (
                SELECT purchase_id, SUM(cost * qty) AS total_amount
                FROM purchase_product
                GROUP BY purchase_id
            ) pp',
            'pp.purchase_id = purchase.id',
            'LEFT'
        );
        $this->db->join('
            (
                SELECT purchase_id, SUM(amount) AS total_paid
                FROM purchase_payment
                GROUP BY purchase_id
            ) py',
            'py.purchase_id = purchase.id',
            'LEFT'
        );
        $this->db->join('
            (
                SELECT pp.purchase_id,
                    JSON_ARRAYAGG(' . $jsonObject . ') AS payments
                FROM purchase_payment pp
                LEFT JOIN payment_method pm ON pm.id = pp.method
                GROUP BY pp.purchase_id
            ) py_list',
            'py_list.purchase_id = purchase.id',
            'LEFT'
        );
        $this->db->where('purchase.delivered_at >=', $d1);
        $this->db->where('purchase.delivered_at <=', $d2);
        $this->db->having('total_balance <=', 0);
        return $this->db->get()->result();
    }
    public function purchase_filtered($user_type, $user_role = 0){
        $this->base_purchase();
        if(!in_array($user_type, [1])){
            $this->db->where('encoded_at', '');
        }
        if($user_role == 1){
            $this->db->having('total_paid < total_amount');
        }
        return $this->db->get()->result();
    }
    public function po_count($filter = 0){
        if($filter == 1){
            $this->db->where('delivered_at', '');
            $this->db->where('encoded_at', '');
            $this->db->select('COUNT(id) AS id_count');
            return $this->db->get('purchase')->row()->id_count;
        }elseif($filter == 2){
            $this->db->where('delivered_at !=', '');
            $this->db->where('encoded_at', '');
            $this->db->select('COUNT(id) AS id_count');
            return $this->db->get('purchase')->row()->id_count;
        }else{
            return 0;
        }
    }
    public function add_po_product($purchase_id, $name){
        $purchase = $this->db->where('id', $purchase_id)->from('purchase')->get()->row();
        if(!$purchase){ return 400; }
        $a = $this->db->insert('purchase_product', [
            'purchase_id' => $purchase->id,
            'name' => $name
        ]);
        $b = $this->db->insert('vendor_product', [
            'vendor_id' => $purchase->vendor_id,
            'name' => $name
        ]);
        if($a && $b){ return 200; }
        return 500;
    }
    public function getpoproducts_purchaseid($purchase_id){
        $this->db->where('purchase_id', $purchase_id);
        $this->db->from('purchase_product');
        return $this->db->get()->result();
    }
    // bo
    public function base_bo($toExcludeCol = []){
        $tbl = 'bo';
        $fields = $this->db->list_fields($tbl);
        $excludedCol = [];
        if($toExcludeCol){
            foreach($toExcludeCol as $key => $value){
                if($value === true){
                    $excludedCol[] = $key;
                }
            }
        }
        $fields = array_diff($fields, $excludedCol);
        $prefixedFields = array_map(function($f) use ($tbl){
            return $tbl . '.' . $f;
        }, $fields);
        $this->db->select($prefixedFields);
        $this->db->select('
            v.name AS vendor_name,
            v.agent_name AS vendor_agent_name,
            v.contact_no AS vendor_contact_no,
        ');
        $this->db->from($tbl);
        $this->db->join('vendor v', 'v.id = ' . $tbl . '.vendor_id', 'LEFT');
        $this->db->group_by($tbl . '.id');
    }
    public function bo(){
        $this->base_bo();
        return $this->db->get()->result();
    }
    public function bo_create($bo_data, $bor_product){
        $this->db->insert('bo', $bo_data);
        $bo_id = $this->db->insert_id();
        if($bo_id){
            $bo_product = [];
            foreach($bor_product as $borp){
                $this->u_tbl('bor_product', ['pickup_status' => 1], ['id' => $borp['id']]);
                $bo_product[] = [
                    'bo_id' => $bo_id,
                    'name' => $borp['name'],
                    'barcode' => $borp['barcode'],
                    'expired_at' => $borp['expired_at'],
                    'qty' => $borp['qty']
                ];
            }
            if(!empty($bo_product)){
                $this->db->insert_batch('bo_product', $bo_product);
            }
            return 200;
        }
        return 500;
    }
    public function base_bo_product($toExcludeCol = []){
        $tbl = 'bo_product';
        $fields = $this->db->list_fields($tbl);
        $excludedCol = [];
        if($toExcludeCol){
            foreach($toExcludeCol as $key => $value){
                if($value === true){
                    $excludedCol[] = $key;
                }
            }
        }
        $fields = array_diff($fields, $excludedCol);
        $prefixedFields = array_map(function($f) use ($tbl){
            return $tbl . '.' . $f;
        }, $fields);
        $this->db->select($prefixedFields);
        $this->db->from($tbl);
    }
    public function bo_encode($bo_id, $data, $bo_product){
        if($this->db->where('id', $bo_id)->update('bo', $data)){
            foreach($bo_product as $bop){
                $this->u_tbl('bo_product', ['encode_status' => 1], ['id' => $bop['id']]);
            }
            return 200;
        }
        return 500;
    }
    // bor
    public function base_bor($toExcludeCol = []){
        $tbl = 'bor';
        $fields = $this->db->list_fields($tbl);
        $excludedCol = [];
        if($toExcludeCol){
            foreach($toExcludeCol as $key => $value){
                if($value === true){
                    $excludedCol[] = $key;
                }
            }
        }
        $fields = array_diff($fields, $excludedCol);
        $prefixedFields = array_map(function($f) use ($tbl){
            return $tbl . '.' . $f;
        }, $fields);
        $this->db->select($prefixedFields);
        $this->db->select('
            v.name AS vendor_name,
            v.agent_name AS vendor_agent_name,
            v.contact_no AS vendor_contact_no,
            COUNT(borp.id) AS borp_count
        ');
        $this->db->from($tbl);
        $this->db->join('vendor v', 'v.id = ' . $tbl . '.vendor_id', 'LEFT');
        $this->db->join('bor_product borp', 'borp.bor_id = ' . $tbl . '.id AND borp.pickup_status = 0', 'LEFT');
        $this->db->group_by($tbl . '.id');
    }
    public function bor(){
        $this->base_bor();
        $this->db->having('borp_count >', 0);
        return $this->db->get()->result();
    }
    public function bor_count(){
        $this->base_bor();
        $this->db->having('borp_count >', 0);
        return count($this->db->get()->result());
    }
    public function base_bor_product($toExcludeCol = []){
        $tbl = 'bor_product';
        $fields = $this->db->list_fields($tbl);
        $excludedCol = [];
        if($toExcludeCol){
            foreach($toExcludeCol as $key => $value){
                if($value === true){
                    $excludedCol[] = $key;
                }
            }
        }
        $fields = array_diff($fields, $excludedCol);
        $prefixedFields = array_map(function($f) use ($tbl){
            return $tbl . '.' . $f;
        }, $fields);
        $this->db->select($prefixedFields);
        $this->db->from($tbl);
        $this->db->where('pickup_status', 0);
    }
    // vendor
    public function base_vendor($toExcludeCol){
        $tbl = 'vendor';
        $fields = $this->db->list_fields($tbl);
        $excludedCol = [];
        if($toExcludeCol){
            foreach($toExcludeCol as $key => $value){
                if($value === true){
                    $excludedCol[] = $key;
                }
            }
        }
        $fields = array_diff($fields, $excludedCol);
        $prefixedFields = array_map(function($f) use ($tbl){
            return $tbl . '.' . $f;
        }, $fields);
        $this->db->select($prefixedFields);
        $this->db->from($tbl);
        $this->db->group_by($tbl . '.id');
    }
    public function base_vendor_product($toExcludeCol){
        $tbl = 'vendor_product';
        $fields = $this->db->list_fields($tbl);
        $excludedCol = [];
        if($toExcludeCol){
            foreach($toExcludeCol as $key => $value){
                if($value === true){
                    $excludedCol[] = $key;
                }
            }
        }
        $fields = array_diff($fields, $excludedCol);
        $prefixedFields = array_map(function($f) use ($tbl){
            return $tbl . '.' . $f;
        }, $fields);
        $this->db->select($prefixedFields);
        $this->db->from($tbl);
    }
    public function vp_cost($purchase_id, $vendor_id, $name, $data){
        $this->db->where('vendor_id', $vendor_id);
        $this->db->where('name', $name);
        if($this->db->update('vendor_product', $data)){
            $this->db->where('purchase_id', $purchase_id);
            $this->db->where('name', $name);
            $this->db->update('purchase_product', $data);
        }
    }
    public function vendor_product_add($data){
        if($this->db->insert('vendor_product', $data)) return 200;
        return 500;
    }
    // user
    public function base_user($toExcludeCol){
        if(!$toExcludeCol){ $toExcludeCol = ['upass' => true]; }
        $tbl = 'user';
        $fields = $this->db->list_fields($tbl);
        $excludedCol = [];
        if($toExcludeCol){
            foreach($toExcludeCol as $key => $value){
                if($value === true){
                    $excludedCol[] = $key;
                }
            }
        }
        $fields = array_diff($fields, $excludedCol);
        $prefixedFields = array_map(function($f) use ($tbl){
            return $tbl . '.' . $f;
        }, $fields);
        $this->db->select($prefixedFields);
        $this->db->select('
            user_type.name AS type_name,
            user_page_access.page AS upage,
            JSON_LENGTH(user_page_access.page) AS upage_count,
            user_page_access.page_sub AS upage_sub,
            JSON_LENGTH(user_page_access.page_sub) AS upage_sub_count
        ');
        $this->db->from($tbl);
        $this->db->join('user_type', 'user_type.id = ' . $tbl . '.type', 'LEFT');
        $this->db->join('user_page_access', 'user_page_access.user_id = ' . $tbl . '.id', 'LEFT');
        $this->db->group_by($tbl . '.id');
    }
    public function base_user_type($toExcludeCol){
        $tbl = 'user_type';
        $fields = $this->db->list_fields($tbl);
        $excludedCol = [];
        if($toExcludeCol){
            foreach($toExcludeCol as $key => $value){
                if($value === true){
                    $excludedCol[] = $key;
                }
            }
        }
        $fields = array_diff($fields, $excludedCol);
        $prefixedFields = array_map(function($f) use ($tbl){
            return $tbl . '.' . $f;
        }, $fields);
        $this->db->select($prefixedFields);
        $this->db->from($tbl);
        $this->db->group_by($tbl . '.id');
    }
    public function base_user_page_access($toExcludeCol){
        $tbl = 'user_page_access';
        $fields = $this->db->list_fields($tbl);
        $excludedCol = [];
        if($toExcludeCol){
            foreach($toExcludeCol as $key => $value){
                if($value === true){
                    $excludedCol[] = $key;
                }
            }
        }
        $fields = array_diff($fields, $excludedCol);
        $prefixedFields = array_map(function($f) use ($tbl){
            return $tbl . '.' . $f;
        }, $fields);
        $this->db->select($prefixedFields);
        $this->db->from($tbl);
        $this->db->group_by($tbl . '.user_id');
    }
    public function check_upass($user_id, $upass){
        $user = $this->r_tbl('user', false, ['id' => $user_id], [], ['upass' => false]);
        if(!password_verify($upass, $user->upass)) return false;
        return true;
    }
    public function change_upass($user_id, $upass){
        $this->db->where('id', $user_id);
        if($this->db->update('user', ['upass' => password_hash($upass, PASSWORD_DEFAULT)])) return 200;
        return 500;
    }
    // page
    public function base_page($toExcludeCol){
        $tbl = 'page';
        $fields = $this->db->list_fields($tbl);
        $excludedCol = [];
        if($toExcludeCol){
            foreach($toExcludeCol as $key => $value){
                if($value === true){
                    $excludedCol[] = $key;
                }
            }
        }
        $fields = array_diff($fields, $excludedCol);
        $prefixedFields = array_map(function($f) use ($tbl){
            return $tbl . '.' . $f;
        }, $fields);
        $this->db->select($prefixedFields);
        $this->db->select('COUNT(page_sub.id) AS page_sub_count');
        $this->db->from($tbl);
        $this->db->join('page_sub', 'page_sub.page_id = ' . $tbl . '.id', 'LEFT');
        $this->db->group_by($tbl . '.id');
        $this->db->order_by($tbl . '.sort', 'ASC');
    }
    public function base_page_sub($toExcludeCol){
        $tbl = 'page_sub';
        $fields = $this->db->list_fields($tbl);
        $excludedCol = [];
        if($toExcludeCol){
            foreach($toExcludeCol as $key => $value){
                if($value === true){
                    $excludedCol[] = $key;
                }
            }
        }
        $fields = array_diff($fields, $excludedCol);
        $prefixedFields = array_map(function($f) use ($tbl){
            return $tbl . '.' . $f;
        }, $fields);
        $this->db->select($prefixedFields);
        $this->db->from($tbl);
        $this->db->group_by($tbl . '.id');
        $this->db->order_by($tbl . '.sort', 'ASC');
    }
    // sys
    public function base_sys($toExcludeCol){
        $tbl = 'sys';
        $fields = $this->db->list_fields($tbl);
        $excludedCol = [];
        if($toExcludeCol){
            foreach($toExcludeCol as $key => $value){
                if($value === true){
                    $excludedCol[] = $key;
                }
            }
        }
        $fields = array_diff($fields, $excludedCol);
        $prefixedFields = array_map(function($f) use ($tbl){
            return $tbl . '.' . $f;
        }, $fields);
        $this->db->select($prefixedFields);
        $this->db->from($tbl);
        $this->db->group_by($tbl . '.id');
    }
    // payment
    public function base_payment_method($toExcludeCol){
        $tbl = 'payment_method';
        $fields = $this->db->list_fields($tbl);
        $excludedCol = [];
        if($toExcludeCol){
            foreach($toExcludeCol as $key => $value){
                if($value === true){
                    $excludedCol[] = $key;
                }
            }
        }
        $fields = array_diff($fields, $excludedCol);
        $prefixedFields = array_map(function($f) use ($tbl){
            return $tbl . '.' . $f;
        }, $fields);
        $this->db->select($prefixedFields);
        $this->db->from($tbl);
        $this->db->group_by($tbl . '.id');
    }
}
