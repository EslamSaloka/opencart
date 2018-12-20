<?php

class ModelExtensionModuleDeleteCustomerApi extends Model
{
    public function add_action($args){
        if(!$this->config->get('module_customer_api_status')) {
            return ;
        }
        $this->load->model('customer/customer');
        $user = $this->model_customer_customer->getCustomerByEmail($args[0]['email']);
        
        $opts = array('http' =>
            array(
                'method'  => 'DELETE',
                'header'  => "Content-type: application/x-www-form-urlencoded",
            )
        );

        $context  = stream_context_create($opts);
        $api_url  = $this->config->get('module_customer_api_ENVENTORY_URL');
        $token    = urlencode($this->config->get('module_customer_api_ENVENTORY_TOKRN'));
        $url      = $api_url.'/crm/customers/'.$user['customer_id'].'?api_token='.$token;
        $result   = file_get_contents($url, false, $context);
    }
}
