<?php

class ModelExtensionModuleEditCustomerApi extends Model
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
                        'phone'             =>  $user['telephone'],
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
        $url      = $api_url.'/crm/customers/'.$user['customer_id'].'?api_token='.$token;
        $result   = file_get_contents($url, false, $context);
    }
}
