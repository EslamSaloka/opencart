<?php

class ControllerExtensionModuleCustomerApi extends Controller
{
    private $error = array();

    public function add_action(&$route, &$args, &$output)
    {
        $this->load->model('extension/module/customer_api');
        $this->model_extension_module_customer_api->add_action($args);
    }
}
