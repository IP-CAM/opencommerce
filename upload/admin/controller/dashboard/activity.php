<?php
class ControllerDashboardActivity extends Controller {
	public function index() {
		$this->load->language('dashboard/activity');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['token'] = $this->session->data['token'];

		$data['activities'] = array();

		$results = $this->model_report_dashboard->getActivities();

		foreach ($results as $result) {
			$comment = vsprintf($this->language->get('text_' . $result['key']), unserialize($result['data']));

			$find = array(
				'customer_id=',
				'order_id=',
				'affiliate_id='
			);

			$replace = array(
				$this->url->link('sale/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=', 'SSL'),
				$this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=', 'SSL'),
				$this->url->link('marketing/affiliate/edit', 'token=' . $this->session->data['token'] . '&affiliate_id=', 'SSL')
			);

			$data['activities'][] = array(
				'comment'    => str_replace($find, $replace, $comment),
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
			);
		}

		return $this->load->view('dashboard/activity.tpl', $data);
	}
}
