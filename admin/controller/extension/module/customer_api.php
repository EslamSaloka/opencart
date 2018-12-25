<?php

class ControllerExtensionModuleCustomerApi extends Controller
{
    private $error = array();

    public function index() {
        $this->load->language('extension/module/customer_api');

        $this->load->model('setting/setting');

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('module_customer_api', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token='.$this->session->data['user_token'].'&type=module', true));
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token='.$this->session->data['user_token'], true),
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token='.$this->session->data['user_token'].'&type=module', true),
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/customer_api', 'user_token='.$this->session->data['user_token'], true),
        );

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['ENVENTORY_TOKRN'])) {
            $data['error_ENVENTORY_TOKRN'] = $this->error['ENVENTORY_TOKRN'];
        } else {
            $data['error_ENVENTORY_TOKRN'] = '';
        }

        if (isset($this->error['ENVENTORY_URL'])) {
            $data['error_ENVENTORY_URL'] = $this->error['ENVENTORY_URL'];
        } else {
            $data['error_ENVENTORY_URL'] = '';
        }

        if (isset($this->request->post['module_customer_api_status'])) {
			$data['module_customer_api_status'] = $this->request->post['module_customer_api_status'];
		} else {
			$data['module_customer_api_status'] = $this->config->get('module_customer_api_status');
        }
    
        if (isset($this->request->post['module_customer_api_ENVENTORY_TOKRN'])) {
			$data['module_customer_api_ENVENTORY_TOKRN'] = $this->request->post['module_customer_api_ENVENTORY_TOKRN'];
		} else {
			$data['module_customer_api_ENVENTORY_TOKRN'] = $this->config->get('module_customer_api_ENVENTORY_TOKRN');
        }

        if (isset($this->request->post['module_customer_api_ENVENTORY_URL'])) {
			$data['module_customer_api_ENVENTORY_URL'] = $this->request->post['module_customer_api_ENVENTORY_URL'];
		} else {
			$data['module_customer_api_ENVENTORY_URL'] = $this->config->get('module_customer_api_ENVENTORY_URL');
        }
// var_dump($data);
        $data['action']      = $this->url->link('extension/module/customer_api', 'user_token='.$this->session->data['user_token'], true);
        $data['cancel']      = $this->url->link('marketplace/extension', 'user_token='.$this->session->data['user_token'].'&type=module', true);

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/customer_api', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/customer_api')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if (!$this->request->post['module_customer_api_ENVENTORY_URL']) {
            $this->error['ENVENTORY_URL'] = $this->language->get('ENVENTORY_URL');
        }
        if (!$this->request->post['module_customer_api_ENVENTORY_TOKRN']) {
            $this->error['ENVENTORY_TOKRN'] = $this->language->get('ENVENTORY_TOKRN');
        }
        return !$this->error;
    }

    public function install() {
        $this->load->model('setting/event');
        /*
            ============================================================================
            ============================================================================
            -------------------------------- User Area --------------------------------
            ============================================================================
            ============================================================================
        */
        /*
            Add New User
        */
        $this->model_setting_event->addEvent('admin_new_customer_api', 'admin/model/customer/customer/addCustomer/after', 'extension/module/customer_api/add_action');
        $this->model_setting_event->addEvent('front_new_customer_api', 'catalog/model/account/customer/addCustomer/after', 'extension/module/customer_api/add_action');
        /*
            Update User Data
        */
        $this->model_setting_event->addEvent('admin_update_customer_api', 'admin/model/customer/customer/editCustomer/after', 'extension/module/customer_api/edit_action');
        $this->model_setting_event->addEvent('front_update_customer_api', 'catalog/model/account/customer/editCustomer/after', 'extension/module/customer_api/edit_action');
        /*
            Delete User Data
        */
        $this->model_setting_event->addEvent('admin_delete_customer_api', 'admin/model/customer/customer/deleteCustomer/after', 'extension/module/customer_api/delete_action');
        $this->model_setting_event->addEvent('front_delete_customer_api', 'catalog/model/account/customer/deleteCustomer/after', 'extension/module/customer_api/delete_action');
        /*
            ============================================================================
            ============================================================================
            -------------------------------- Items Area --------------------------------
            ============================================================================
            ============================================================================
        */
        /*
            Add New Items
        */
        $this->model_setting_event->addEvent('admin_add_items_api', 'admin/model/catalog/product/addProduct/after', 'extension/module/customer_api/add_items');
        /*
            Edit Item Data
        */
        $this->model_setting_event->addEvent('admin_edit_items_api', 'admin/model/catalog/product/editProduct/after', 'extension/module/customer_api/delete_items');
        /*
            Delete Item Data
        */   
        $this->model_setting_event->addEvent('admin_delete_items_api', 'admin/model/catalog/product/deleteProduct/after', 'extension/module/customer_api/delete_items');
    }

    public function uninstall() {
        $this->load->model('setting/event');
        /*
            ============================================================================
            ============================================================================
            -------------------------------- User Area --------------------------------
            ============================================================================
            ============================================================================
        */
        $this->model_setting_event->deleteEventByCode('admin_new_customer_api');
        $this->model_setting_event->deleteEventByCode('front_new_customer_api');
        $this->model_setting_event->deleteEventByCode('admin_update_customer_api');
        $this->model_setting_event->deleteEventByCode('front_update_customer_api');
        $this->model_setting_event->deleteEventByCode('admin_delete_customer_api');
        $this->model_setting_event->deleteEventByCode('front_delete_customer_api');
        /*
            ============================================================================
            ============================================================================
            -------------------------------- Items Area --------------------------------
            ============================================================================
            ============================================================================
        */
        $this->model_setting_event->deleteEventByCode('admin_add_items_api');
        $this->model_setting_event->deleteEventByCode('admin_edit_items_api');
        $this->model_setting_event->deleteEventByCode('admin_delete_items_api');
    }

    public function add_action(&$route, &$args, &$output) {
        $this->load->model('extension/module/customer_api');
        $this->model_extension_module_customer_api->add_action($args);
    }

    public function edit_action(&$route, &$args, &$output) {
        $this->load->model('extension/module/customer_api');
        $this->model_extension_module_customer_api->edit_action($args);
    }

    public function delete_action(&$route, &$args, &$output) {
        $this->load->model('extension/module/customer_api');
        $this->model_extension_module_customer_api->delete_action($args);
    }
    /*
        ============================================================================
        ============================================================================
        -------------------------------- Items Area --------------------------------
        ============================================================================
        ============================================================================
    */
    public function add_items(&$route, &$args, &$output) {
        $this->load->model('extension/module/customer_api');
        $this->model_extension_module_customer_api->add_items($args,$output);
    }
    
    public function edit_items(&$route, &$args, &$output) {
        $this->load->model('extension/module/customer_api');
        $this->model_extension_module_customer_api->edit_items($args,$output);
    }

    public function delete_items(&$route, &$args, &$output) {
        $this->load->model('extension/module/customer_api');
        $this->model_extension_module_customer_api->delete_items($args,$output);
    }
}
