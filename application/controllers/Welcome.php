<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller 
{
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
		echo $result = $this->url_model->fetchShortCode('http:www.google.com');
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
}
