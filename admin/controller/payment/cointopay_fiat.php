<?php
namespace Opencart\Admin\Controller\Extension\CointopayFiat\Payment;

class CointopayFiat extends \Opencart\System\Engine\Controller
{
	private $error = [];

	public function index(): void
	{

		$this->load->language('extension/cointopay_fiat/payment/cointopay_fiat');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$this->model_setting_setting->editSetting('payment_cointopay_fiat', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			if (isset($this->request->post['language_reload'])) {
				$this->response->redirect($this->url->link('payment/cointopay_fiat', 'user_token=' . $this->session->data['user_token'], true));
			} else {
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_payment'] = $this->language->get('text_payment');
		$data['text_success'] = $this->language->get('text_success');
		$data['text_bitcoin'] = $this->language->get('text_bitcoin');
		$data['text_litecoin'] = $this->language->get('text_litecoin');
		$data['text_darkcoin'] = $this->language->get('text_darkcoin');
		$data['text_freicoin'] = $this->language->get('text_freicoin');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_securitycode'] = $this->language->get('entry_securitycode');
		$data['entry_crypto_coin'] = $this->language->get('entry_crypto_coin');
		$data['entry_redirect_url'] = $this->language->get('entry_redirect_url');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_merchantID'] = $this->language->get('entry_merchantID');
		$data['entry_display_name'] = $this->language->get('entry_display_name');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_callback_success_order_status'] = $this->language->get('entry_callback_success_order_status');
		$data['entry_callback_failed_order_status'] = $this->language->get('entry_callback_failed_order_status');

		$data['error_permission'] = $this->language->get('error_permission');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['help_api_key_hint'] = $this->language->get('help_api_key_hint');
		$data['help_crypto_coin_hint'] = $this->language->get('help_crypto_coin_hint');
		$data['help_redirect_url_hint'] = $this->language->get('help_redirect_url_hint');
		$data['help_display_name_hint'] = $this->language->get('help_display_name_hint');
		$data['help_merchantID_hint'] = $this->language->get('help_merchantID_hint');

		$data['tab_general'] = 'General';

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['api_key'])) {
			$data['error_api_key'] = $this->error['api_key'];
		} else {
			$data['error_api_key'] = '';
		}

		if (isset($this->error['display_name'])) {
			$data['error_display_name'] = $this->error['display_name'];
		} else {
			$data['error_display_name'] = '';
		}

		if (isset($this->error['merchantID'])) {
			$data['error_merchantID'] = $this->error['merchantID'];
		} else {
			$data['error_merchantID'] = '';
		}

		if (isset($this->error['crypto_coin'])) {
			$data['error_crypto_coin'] = $this->error['crypto_coin'];
		} else {
			$data['error_crypto_coin'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], 'SSL'),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/cointopay_fiat', 'user_token=' . $this->session->data['user_token'], 'SSL'),
			'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/cointopay_fiat/payment/cointopay_fiat', 'user_token=' . $this->session->data['user_token'], 'SSL'),
			'separator' => ' :: '
		);

		$data['action'] = $this->url->link('extension/cointopay_fiat/payment/cointopay_fiat', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'user_token=' . $this->session->data['user_token'], 'SSL');

		if (isset($this->request->post['payment_cointopay_fiat_display_name'])) {
			$data['cointopay_fiat_display_name'] = $this->request->post['payment_cointopay_fiat_display_name'];
		} else {
			$data['cointopay_fiat_display_name'] = $this->config->get('payment_cointopay_fiat_display_name');
		}

		if (isset($this->request->post['payment_cointopay_fiat_securitycode'])) {
			$data['cointopay_fiat_securitycode'] = $this->request->post['payment_cointopay_fiat_securitycode'];
		} else {
			$data['cointopay_fiat_securitycode'] = $this->config->get('payment_cointopay_fiat_securitycode');
		}

		if (isset($this->request->post['payment_cointopay_fiat_status'])) {
			$data['cointopay_fiat_status'] = $this->request->post['payment_cointopay_fiat_status'];
		} else {
			$data['cointopay_fiat_status'] = $this->config->get('payment_cointopay_fiat_status');
		}

		if (isset($this->request->post['payment_cointopay_fiat_crypto_coin'])) {
			$data['cointopay_fiat_crypto_coin'] = $this->request->post['payment_cointopay_fiat_crypto_coin'];
		} else {
			$data['cointopay_fiat_crypto_coin'] = $this->config->get('payment_cointopay_fiat_crypto_coin');
		}

		if (isset($this->request->post['cointopay_fiat_order_status_id'])) {
			$data['cointopay_fiat_order_status_id'] = $this->request->post['payment_cointopay_fiat_order_status_id'];
		} else {
			$data['cointopay_fiat_order_status_id'] = $this->config->get('payment_cointopay_fiat_order_status_id');
		}

		if (isset($this->request->post['payment_cointopay_fiat_callback_success_order_status_id'])) {
			$data['cointopay_fiat_callback_success_order_status_id'] = $this->request->post['payment_cointopay_fiat_callback_success_order_status_id'];
		} else {
			$data['cointopay_fiat_callback_success_order_status_id'] = $this->config->get('payment_cointopay_fiat_callback_success_order_status_id');
		}

		if (isset($this->request->post['payment_cointopay_fiat_callback_failed_order_status_id'])) {
			$data['cointopay_fiat_callback_failed_order_status_id'] = $this->request->post['payment_cointopay_fiat_callback_failed_order_status_id'];
		} else {
			$data['cointopay_fiat_callback_failed_order_status_id'] = $this->config->get('payment_cointopay_fiat_callback_failed_order_status_id');
		}

		if (isset($this->request->post['payment_cointopay_fiat_callback_expired_order_status_id'])) {
			$data['cointopay_fiat_callback_expired_order_status_id'] = $this->request->post['payment_cointopay_fiat_callback_expired_order_status_id'];
		} else {
			$data['cointopay_fiat_callback_expired_order_status_id'] = $this->config->get('payment_cointopay_fiat_callback_expired_order_status_id');
		}

		if (isset($this->request->post['payment_cointopay_fiat_callback_notenough_order_status_id'])) {
			$data['cointopay_fiat_callback_notenough_order_status_id'] = $this->request->post['payment_cointopay_fiat_callback_notenough_order_status_id'];
		} else {
			$data['cointopay_fiat_callback_notenough_order_status_id'] = $this->config->get('payment_cointopay_fiat_callback_notenough_order_status_id');
		}

		if (isset($this->request->post['payment_cointopay_fiat_callback_cancel_order_status_id'])) {
			$data['cointopay_fiat_callback_cancel_order_status_id'] = $this->request->post['payment_cointopay_fiat_callback_cancel_order_status_id'];
		} else {
			$data['cointopay_fiat_callback_cancel_order_status_id'] = $this->config->get('payment_cointopay_fiat_callback_cancel_order_status_id');
		}

		if (isset($this->request->post['payment_cointopay_fiat_merchantID'])) {
			$data['cointopay_fiat_merchantID'] = $this->request->post['payment_cointopay_fiat_merchantID'];
		} else {
			$data['cointopay_fiat_merchantID'] = $this->config->get('payment_cointopay_fiat_merchantID');

			$data['crypto_coins'] = $this->getMerchantCoins($this->config->get('payment_cointopay_fiat_merchantID'));

		}

		if (isset($this->request->post['payment_cointopay_fiat_sort_order'])) {
			$data['cointopay_fiat_sort_order'] = $this->request->post['payment_cointopay_fiat_sort_order'];
		} else {
			$data['cointopay_fiat_sort_order'] = $this->config->get('payment_cointopay_fiat_sort_order');

		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		if (!empty($this->config->get('payment_cointopay_fiat_merchantID'))) {
			$currencyOutput = $this->getInputCurrencyList($this->config->get('payment_cointopay_fiat_merchantID'));
			if (in_array($this->config->get('config_currency'), $currencyOutput['currency'])) {
				$data['error_invalid_currency'] = '';
			} else {
				$data['error_invalid_currency'] = 'Your Store currency ' . $this->config->get('config_currency') . ' not supported. Please contact <a href="mailto:support@cointopay.com">support@cointopay.com</a> to resolve this issue.';
			}
		}
		$data['ctp_coints_url'] = $this->url->link('extension/cointopay_fiat/payment/cointopay_fiat|getMerchantCoinsByAjax', 'user_token=' . $this->session->data['user_token']);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/cointopay_fiat/payment/cointopay_fiat', $data));
	}

	private function validate()
	{
		if (!$this->user->hasPermission('modify', 'extension/cointopay_fiat/payment/cointopay_fiat')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_cointopay_fiat_securitycode']) {
			$this->error['api_key'] = $this->language->get('error_api_key');
		}

		if (!$this->request->post['payment_cointopay_fiat_display_name']) {
			$this->error['display_name'] = $this->language->get('error_display_name');
		}

		if (!$this->request->post['payment_cointopay_fiat_merchantID']) {
			$this->error['merchantID'] = $this->language->get('error_merchantID');
		}

		if (!$this->request->post['payment_cointopay_fiat_crypto_coin']) {
			$this->error['crypto_coin'] = $this->language->get('error_crypto_coin');
		}

		return !$this->error;
	}

	public function getMerchantCoinsByAjax(): void
	{
		/* if ($this->request->post['merchantId']) {
			$option = '<option value="">Select Default Coin</option>';
			$arr = $this->getMerchantCoins($this->request->post['merchantId']);
			foreach ($arr as $key => $value) {
				$option .= '<option value="' . $key . '">' . $value . '</option>';
			}
			echo $option;
		} */
		if (isset($this->request->post["merchantId"]) && !empty($this->request->post['merchantId'])) {
			$merchant = $this->request->post["merchantId"];
			$secretCode = $this->request->post["secret_code"];
			$url = 'https:///app.cointopay.com/MerchantAPI?MerchantID=' . $merchant . '&SecurityCode=' . $secretCode . '&output=json&Checkout=true&Amount=1&CustomerReferenceNr=test&testmerchant';
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => $url,
			));
			$response = curl_exec($curl);

			$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);

			if ($http_status === 200) {
				if ($response == '"testmerchant success"') {
					$option = '<option value="">Select Default Coin</option>';
					$resp = $this->getMerchantCoins($this->request->post['merchantId']);
					if (is_array($resp)) {
						foreach ($resp as $key => $value) {
							$option .= '<option value="' . $key . '">' . $value . '</option>';
						}
						echo $option;
					} else {
						$option = '<option value="">' . $resp . '</option>';
						echo $option;
					}
				} else {
					$option = '<option value="">' . $response . '</option>';
						echo $option;
				}
			} else {
				$option = '<option value="">' . $response . '</option>';
						echo $option;
			}
		}
	}

	function getMerchantCoins($merchantId)
	{
		$url = 'https://cointopay.com/CloneMasterTransaction?MerchantID=' . $merchantId . '&output=json';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$output = curl_exec($ch);
		curl_close($ch);

		$php_arr = json_decode($output);
		$new_php_arr = array();

		if (!empty($php_arr)) {
			for ($i = 0; $i < count($php_arr) - 1; $i++) {
				if (($i % 2) == 0) {
					$new_php_arr[$php_arr[$i + 1]] = $php_arr[$i];
				}
			}
			return $new_php_arr;
		} else {
		return $output;
		}
	}
	function getInputCurrencyList($merchantId)
	{
		$url = 'https://cointopay.com/v2REAPI?MerchantID=' . $merchantId . '&Call=inputCurrencyList&output=json&APIKey=_';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$output = curl_exec($ch);

		curl_close($ch);

		$php_arr = json_decode($output);
		$new_php_arr = array();

		if (!empty($php_arr)) {
			foreach ($php_arr as $c) {
				if (property_exists($c, 'ShortName')) {
					$new_php_arr['currency'][] = $c->ShortName;
				}
			}
		}

		return $new_php_arr;
	}
}