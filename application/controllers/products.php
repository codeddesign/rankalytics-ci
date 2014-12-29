<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		// load language file:
        //$this->lang->load('productshome');
		$this->load->view('products/index.php');
	}
	
	public function seo_api()
	{
		// load language file:
        //$this->lang->load('productsapi');
		$this->load->view('products/seo-api');
	}
	
	//public function rank_tracker()
	//{
	//	$this->load->view('products/rank-tracker');
	//}
	
	//public function seo_crawl()
	//{
	//	$this->load->view('products/seo-crawl');
	//}
	
	public function roadmap()
	{
		$this->load->view('products/roadmap');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */