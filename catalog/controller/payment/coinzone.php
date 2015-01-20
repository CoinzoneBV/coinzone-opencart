<?php
class ControllerPaymentCoinzone extends Controller {

    public function index() {
        $this->language->load('payment/coinzone');

        $data['text_wait'] = $this->language->get('text_wait');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['button_confirm'] = $this->language->get('button_confirm');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/coinzone.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/coinzone.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/coinzone.tpl', $data);
        }
    }

    public function send() {
        $this->load->library('coinzone');
        $this->load->model('checkout/order');
        $this->language->load('payment/coinzone');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        $coinzone = new Coinzone($this->config->get('coinzone_clientCode'), $this->config->get('coinzone_apiKey'));

        /* create payload array */
        $payload = array(
            'amount' => $order_info['total'],
            'currency' => $order_info['currency_code'],
            'merchantReference' => $order_info['order_id'],
            'email' => $order_info['email'],
            'notificationUrl' => $this->url->link('/payment/coinzone/callback'),
            'redirectUrl' => $this->url->link('checkout/success')
        );

        $response = $coinzone->callApi('transaction', $payload);

        if ($response->status->code !== 201) {
            $json['error'] = $this->language->get('text_error');
        } else {
            $json['success'] = $response->response->url;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function callback()
    {
        $this->load->model('checkout/order');
        $content = file_get_contents('php://input');

        /* request type : json | http_post */
        $input = json_decode($content, true);
        if (empty($content) || json_last_error() !== JSON_ERROR_NONE) {
            $input = array();
            foreach ($this->request->post as $key => $value) {
                $input[$key] = htmlspecialchars_decode($value);
            }
            $content = http_build_query($input);
        }
        $url = '';
        if ($this->request->get['route'] === '/payment/coinzone/callback') {
            $url = $this->url->link('/payment/coinzone/callback');
        }
        /** check signature */
        $apiKey = $this->config->get('coinzone_apiKey');
        $stringToSign = $content . $url . $this->request->server['HTTP_TIMESTAMP'];
        $signature = hash_hmac('sha256', $stringToSign, $apiKey);
        if ($signature !== $this->request->server['HTTP_SIGNATURE']) {
            $this->response->addHeader('HTTP/1.1 400 Bad Request');
            $this->response->setOutput('Invalid Signature ' . $signature . '//' . $stringToSign . '//' . $this->request->server['HTTP_SIGNATURE']);
        }

        $order = $this->model_checkout_order->getOrder($input['merchantReference']);
        switch ($input['status']) {
            case 'PAID':
            case 'COMPLETE':
                $this->model_checkout_order->addOrderHistory(
                    $input['merchantReference'],
                    $this->config->get('coinzone_paid_status_id'),
                    'Coinzone Gateway: Order Paid',
                    true
                    );
                $this->response->setOutput('OK_PAID');
                break;
        }
    }
}
