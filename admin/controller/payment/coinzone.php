<?php
class ControllerPaymentCoinzone extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('payment/coinzone');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('coinzone', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['entry_clientCode'] = $this->language->get('entry_clientCode');
        $data['entry_apiKey'] = $this->language->get('entry_apiKey');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_order_status'] = $this->language->get('entry_order_status');

        $data['desc_configure'] = $this->language->get('desc_configure');
        $data['desc_details'] = $this->language->get('desc_details');
        $data['desc_questions'] = $this->language->get('desc_questions');
        $data['desc_account'] = $this->language->get('desc_account');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['clientCode'])) {
            $data['error_clientCode'] = $this->error['clientCode'];
        } else {
            $data['error_clientCode'] = '';
        }

        if (isset($this->error['apiKey'])) {
            $data['error_apiKey'] = $this->error['apiKey'];
        } else {
            $data['error_apiKey'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/coinzone', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['action'] = $this->url->link('payment/coinzone', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['coinzone_clientCode'])) {
            $data['coinzone_clientCode'] = $this->request->post['coinzone_clientCode'];
        } else {
            $data['coinzone_clientCode'] = $this->config->get('coinzone_clientCode');
        }

        if (isset($this->request->post['coinzone_apiKey'])) {
            $data['coinzone_apiKey'] = $this->request->post['coinzone_apiKey'];
        } else {
            $data['coinzone_apiKey'] = $this->config->get('coinzone_apiKey');
        }

        if (isset($this->request->post['coinzone_status'])) {
            $data['coinzone_status'] = $this->request->post['coinzone_status'];
        } else {
            $data['coinzone_status'] = $this->config->get('coinzone_status');
        }

        if (isset($this->request->post['coinzone_paid_status_id'])) {
            $data['coinzone_paid_status_id'] = $this->request->post['coinzone_paid_status_id'];
        } else {
            $data['coinzone_paid_status_id'] = $this->config->get('coinzone_paid_status_id');
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/coinzone.tpl', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'payment/coinzone')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['coinzone_clientCode']) {
            $this->error['clientCode'] = $this->language->get('error_clientCode');
        }

        if (!$this->request->post['coinzone_apiKey']) {
            $this->error['apiKey'] = $this->language->get('error_apiKey');
        }

        return !$this->error;
    }

    public function install()
    {
        $this->load->model('setting/setting');
        $this->load->model('localisation/order_status');
        $order_statuses = $this->model_localisation_order_status->getOrderStatuses();
        $statuses = array();
        foreach ($order_statuses as $order_status) {
            $statuses[$order_status['name']] = $order_status['order_status_id'];
        }
        $order_status_default = $this->config->get('config_order_status_id');
        $this->model_setting_setting->editSetting('coinzone', array(
            'coinzone_paid_status_id'  => (isset($statuses['Complete'])) ? $statuses['Complete'] : $order_status_default
        ));
    }
}
