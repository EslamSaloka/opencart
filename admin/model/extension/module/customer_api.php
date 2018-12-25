<?php

class ModelExtensionModuleCustomerApi extends Model
{
    public function add_action($args){
        if(!$this->config->get('module_customer_api_status')) {
            return ;
        }
        $this->load->model('customer/customer');
        $user = $this->model_customer_customer->getCustomerByEmail($args[0]['email']);

        $postdata = http_build_query(
            array(
                'name'              => $user['firstname'].' '.$user['lastname'],
                'email'             => $user['email'],
                'source'            =>  'opencart',
                'source_refrence'   =>  $user['customer_id'],
                'crm'   =>  [
                    'default'   =>  [
                        'phone'     =>  $user['telephone'],
                    ]
                ]
            )
        );
        
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => "Content-type: application/x-www-form-urlencoded",
                'content' => $postdata
            )
        );

        $context  = stream_context_create($opts);
        $api_url  = $this->config->get('module_customer_api_ENVENTORY_URL');
        $token    = urlencode($this->config->get('module_customer_api_ENVENTORY_TOKRN'));
        $url      = $api_url.'/crm/customers?api_token='.$token;
        $result   = file_get_contents($url, false, $context);
    }

    public function edit_action($args) {
        if(!$this->config->get('module_customer_api_status')) {
            return ;
        }
        $postdata = http_build_query(
            array(
                'name'              => $args[1]['firstname'].' '.$args[1]['lastname'],
                'email'             => $args[1]['email'],
                'source'            =>  'opencart',
                'source_refrence'   =>  $args[0],
                'crm'   =>  [
                    'default'   =>  [
                        'phone'             =>  $args[1]['telephone'],
                    ]
                ]
            )
        );
        $opts = array('http' =>
            array(
                'method'  => 'PUT',
                'header'  => "Content-type: application/x-www-form-urlencoded",
                'content' => $postdata
            )
        );

        $context  = stream_context_create($opts);
        $api_url  = $this->config->get('module_customer_api_ENVENTORY_URL');
        $token    = urlencode($this->config->get('module_customer_api_ENVENTORY_TOKRN'));
        $url      = $api_url.'/crm/customers/'.$args[0].'?source=opencart&api_token='.$token;
        $result   = file_get_contents($url, false, $context);
    }

    public function delete_action($args) {
        if(!$this->config->get('module_customer_api_status')) {
            return ;
        }
        $postdata = http_build_query(
            array()
        );
        $opts = array('http' =>
            array(
                'method'  => 'DELETE',
                'header'  => "Content-type: application/x-www-form-urlencoded",
                'content' => $postdata
            )
        );

        $context  = stream_context_create($opts);
        $api_url  = $this->config->get('module_customer_api_ENVENTORY_URL');
        $token    = urlencode($this->config->get('module_customer_api_ENVENTORY_TOKRN'));
        $url      = $api_url.'/crm/customers/'.$args[0].'?source=opencart&api_token='.$token;
        $result   = file_get_contents($url, false, $context);
    }

    /*
        ============================================================================
        ============================================================================
        -------------------------------- Items Area --------------------------------
        ============================================================================
        ============================================================================
    */
    public function add_items($args,$output) {
        // if(!$this->config->get('module_customer_api_status')) {
        //     return ;
        // }
        if($args[0]['price'] == null) {
            $args[0]['price'] = 0;
        }
        $postdata = http_build_query(
            array(
                'title'             => $args[0]['product_description'][1]['name'],
                'sale_price'        => $args[0]['price'],
                'quantity'          => $args[0]['quantity'],
                'image'             => HTTPS_CATALOG.$args[0]['image'],
                'source'            => 'opencart',
                'source_refrence'   => $output,
                'extra_data'        => $this->fillterApiArray($args[0]),
            )
        );
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => "Content-type: application/x-www-form-urlencoded",
                'content' => $postdata
            )
        );

        $context  = stream_context_create($opts);
        $api_url  = $this->config->get('module_customer_api_ENVENTORY_URL');
        $token    = urlencode($this->config->get('module_customer_api_ENVENTORY_TOKRN'));
        $url      = $api_url.'/inventory/items/?api_token='.$token;
        $result   = file_get_contents($url, false, $context);
    }
    
    public function edit_items($args) {
        if(!$this->config->get('module_customer_api_status')) {
            return ;
        }
        $postdata = http_build_query(
            array(
                'title'             => $args[1]['product_description'][1]['name'],
                'price'             => $args[1]['price'],
                'quantity'          => $args[1]['quantity'],
                'image'             => HTTPS_CATALOG.$args[0]['image'],
                'source'            => 'opencart',
                'source_refrence'   => $args[0],
                'extra_data'        => $this->fillterApiArray($args[1]),
            )
        );
        $opts = array('http' =>
            array(
                'method'  => 'PUT',
                'header'  => "Content-type: application/x-www-form-urlencoded",
                'content' => $postdata
            )
        );

        $context  = stream_context_create($opts);
        $api_url  = $this->config->get('module_customer_api_ENVENTORY_URL');
        $token    = urlencode($this->config->get('module_customer_api_ENVENTORY_TOKRN'));
        $url      = $api_url.'/inventory/items/'.$args[0].'?source=opencart&api_token='.$token;
        $result   = file_get_contents($url, false, $context);
    }
    
    public function delete_items($args) {
        if(!$this->config->get('module_customer_api_status')) {
            return ;
        }
        $postdata = http_build_query(
            array()
        );
        $opts = array('http' =>
            array(
                'method'  => 'DELETE',
                'header'  => "Content-type: application/x-www-form-urlencoded",
                'content' => $postdata
            )
        );

        $context  = stream_context_create($opts);
        $api_url  = $this->config->get('module_customer_api_ENVENTORY_URL');
        $token    = urlencode($this->config->get('module_customer_api_ENVENTORY_TOKRN'));
        $url      = $api_url.'/inventory/items/'.$args[0].'?source=opencart&api_token='.$token;
        $result   = file_get_contents($url, false, $context);
    }

    public function fillterApiArray($array = []){
        unset($array['product_description']);
        unset($array['price']);
        unset($array['quantity']);
        unset($array['sku']);
        unset($array['upc']);
        unset($array['ean']);
        unset($array['jan']);
        unset($array['isbn']);
        unset($array['mpn']);
        unset($array['location']);
        unset($array['sort_order']);
        unset($array['category']);
        unset($array['filter']);
        unset($array['product_store']);
        unset($array['download']);
        unset($array['related']);
        unset($array['option']);
        unset($array['product_layout']);
        unset($array['product_seo_url']);
        unset($array['product_reward']);
        unset($array['points']);
        return $array;
    }
}
