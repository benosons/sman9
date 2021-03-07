<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Indikator extends CI_Controller {

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
		$this->load->model('Model_data');
		$this->logs = $this->session->all_userdata();
		$this->logged = $this->session->userdata('userLogged');
		$this->kategori = $this->session->userdata('kategori');
		$this->username = $this->session->userdata('username');
		$this->role = $this->session->userdata('role');
		$this->kotaKab = $this->session->userdata('kotaKab');
		$this->name = $this->session->userdata('name');
		$this->foto = $this->session->userdata('foto');
		$this->content = array(
			"base_url" => base_url(),
			"logs" => $this->session->all_userdata(),
			"username" => $this->username,
			"role" => $this->role,
			"name" => $this->name,
			"foto" => $this->foto,
			"kotakab" => $this->kotaKab
		);

	}

	public function ssd()
	{
		if ( $this->logged && $this->role == '10' || $this->role == '20')
		{
			$this->content['page'] = 'ssd';
			$this->content['js'] = '/assets/js/action/indikator/indikator.js';
			$this->twig->display('admin/indikator/ssd.html', $this->content);
		}else{
			redirect("dashboard");
		}
	}

	public function ssdi()
	{
		if ( $this->logged && $this->role == '10' || $this->role == '20')
		{
			$this->content['page'] = 'ssdi';
			$this->content['js'] = '/assets/js/action/indikator/indikator.js';
			$this->twig->display('admin/indikator/ssdi.html', $this->content);
		}else{
			redirect("dashboard");
		}
	}

	public function ssdr()
	{
		if ( $this->logged && $this->role == '10' || $this->role == '20')
		{
			$this->content['page'] = 'ssdr';
			$this->content['js'] = '/assets/js/action/indikator/indikator.js';
			$this->twig->display('admin/indikator/ssdr.html', $this->content);
		}else{
			redirect("dashboard");
		}
	}

	public function ssdt()
	{
		if ( $this->logged && $this->role == '10' || $this->role == '20')
		{
			$this->content['page'] = 'ssdt';
			$this->content['js'] = '/assets/js/action/indikator/indikator.js';
			$this->twig->display('admin/indikator/ssdt.html', $this->content);
		}else{
			redirect("dashboard");
		}
	}

	public function dokumen()
	{
		if ( $this->logged && $this->role == '10' || $this->role == '20')
		{
			$this->content['page'] = 'dokumen';
			$this->content['js'] = '/assets/js/action/indikator/indikator.js';
			$this->twig->display('admin/indikator/dokumen.html', $this->content);
		}else{
			redirect("dashboard");
		}
	}

	public function rka()
	{
		if ( $this->logged && $this->role == '10' || $this->role == '20')
		{
			$this->content['page'] = 'rka';
			$this->content['js'] = '/assets/js/action/indikator/indikator.js';
			$this->twig->display('admin/indikator/rka.html', $this->content);
		}else{
			redirect("dashboard");
		}
	}


}
