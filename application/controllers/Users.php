<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

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
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Model_auth');
		$this->logs = $this->session->all_userdata();
		$this->logged = $this->session->userdata('userLogged');
		$this->kategori = $this->session->userdata('kategori');
		$this->username = $this->session->userdata('username');
		$this->name = $this->session->userdata('name');
		$this->role = $this->session->userdata('role');
		$this->foto = $this->session->userdata('foto');
		$this->id = $this->session->userdata('id');
		$this->content = array(
			"base_url" => base_url(),
			"logs" => $this->session->all_userdata(),
			"id" => $this->id,
			"username" => $this->username,
			"role" => $this->role,
			"name" => $this->name,
			"foto" => $this->foto
		);

	}


	public function index()
	{
		$this->content['js'] = '/assets/js/action/user/index.js';
		if ($this->logged)
		{

			if($this->role == '10' || $this->role == '20'){
				redirect("dashboard");
			}else{
				$this->twig->display("users/index.html", $this->content);
			}
		}else{
			$this->twig->display("users/index.html", $this->content);
		}

	}

	public function logout()
	{
		$this->session->sess_destroy();
		// session_destroy();
		redirect("/");
	}



}
