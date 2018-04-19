<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller 
{
	protected $popular_list_count = 100;

	public function __construct()
	{
		parent::__construct();
		$this->load->library('tinyurl');
		$this->load->helper('url');
		$this->load->model('url_model');
		// Define Asset Folder Path
		define('ASSETS', base_url() . 'assets/');
	}

	public function index()
	{
		$this->load->view('home');
	}

	public function fetchTinyUrl()
	{
		$this->tinyurl->urlToShortCode();
	}

	public function codeToUrl($code)
	{
		$this->tinyurl->shortCodeToUrl($code);
	}

	public function fetchPopularUrls()
	{
		$list = $this->url_model->fetchPopular($this->popular_list_count);
		$link_arr = array();
		if($list) {
			foreach ($list as $key => $value) {
				$link_arr[] = array(
									'link' => base_url() . $value->unique_code, 
									'counter' => $value->counter
								);
			}
		}
		echo json_encode($link_arr);
	}
}
