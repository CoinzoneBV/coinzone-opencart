<?php
class ModelPaymentCoinzone extends Model {
    public function getMethod($address) {
        $this->language->load('payment/coinzone');

        if ($this->config->get('coinzone_status')) {
            $status = true;
        } else {
            $status = false;
        }

        $method_data = array();

        if ($status) {
            $method_data = array(
                'code'       => 'coinzone',
                'title'      => $this->language->get('text_title'),
                'terms'      => '',
                'sort_order' => $this->config->get('coinzone_sort_order')
            );
        }

        return $method_data;
    }
}
