<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sys extends CI_Controller {

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
		$this->load->model('Model_sys');
		$this->logs = $this->session->all_userdata();
		$this->logged = $this->session->userdata('userLogged');
		$this->kategori = $this->session->userdata('kategori');
		$this->role = $this->session->userdata('role');
		$this->username = $this->session->userdata('username');
		$this->kotaKab = $this->session->userdata('kotaKab');
		$this->name = $this->session->userdata('name');
		$this->foto = $this->session->userdata('foto');
		$this->id 	= $this->session->userdata('id');
		$this->notelp 	= $this->session->userdata('notelp');
		$this->email 	= $this->session->userdata('email');
		$this->content = array(
			"base_url" => base_url(),
			"logs" => $this->session->all_userdata(),
			"username" => $this->username,
			"role" => $this->role,
			"name" => $this->name,
			"foto" => $this->foto,
			"kategori" => $this->kategori,
			"notelp" => $this->notelp,
			"email" => $this->email,
			"id" => $this->id
		);

	}


	public function dashboard()
	{
		if ( $this->logged)
		{
			if( $this->role == '10' || $this->role == '20' || $this->role == '30'){
				$this->twig->display('admin/dashboard/index.html', $this->content);
			}else{
				redirect("/");
			}
		}else{
			redirect("logout");
		}
	}

	public function profile()
	{
		if ( $this->logged)
		{
			if( $this->role == '10' || $this->role == '20' || $this->role == '30'){
				$this->twig->display('admin/userprofile.html', $this->content);
			}else{
				redirect("/");
			}
		}else{
			redirect("logout");
		}
	}

	public function infodata()
	{
		if ( $this->logged)
		{
			if( $this->role == '10' || $this->role == '20'){
				$this->twig->display('admin/infodata.html', $this->content);
			}else{
				redirect("/");
			}
		}else{
			redirect("logout");
		}
	}

	public function loaduser(){

			$params = $columns = $totalRecords = $data = array();
			$params = $_REQUEST;
			$postData = $this->input->post('param');

			$query = $this->Model_sys->loaduser($this->id);

			$x = 0;
			$i=0;
			foreach ($query as $proses) {
				$x++;
				$row = array();
				$row['id'] = (!empty($proses->id) ? $proses->id : "NULL");
				$row['username'] = (!empty($proses->username) ? $proses->username : "NULL");
				$row['kotaKab'] = (!empty($proses->kotaKab) ? $proses->kotaKab : "NULL");
				$row['kategori'] = (!empty($proses->kategori) ? $proses->kategori : "NULL");
				$row['created_at'] = (!empty($proses->created_at) ? $proses->created_at : "NULL");
				$row['updated_at'] = (!empty($proses->updated_at) ? $proses->updated_at : "NULL");
				$row['role'] = (!empty($proses->role) ? $proses->role : "NULL");
				$row['status'] = (!empty($proses->status) ? $proses->status : "NULL");
				$row['name'] = (!empty($proses->name) ? $proses->name : "NULL");
				$row['no_telp'] = (!empty($proses->no_telp) ? $proses->no_telp : "NULL");
				$row['email'] = (!empty($proses->email) ? $proses->email : "NULL");
				$row['foto'] = (!empty($proses->foto) ? $proses->foto : "assets/dokumen/gambar/user/default.jpg");

				$data[] = $row;
			}
			header('Content-Type: application/json');
			echo json_encode($data);
	}

	public function loadkota(){

			$params = $columns = $totalRecords = $data = array();
			$params = $_REQUEST;
			$postData = $this->input->post('param');

			$query = $this->Model_sys->loadkota($postData);
			$x = 0;
			$i=0;
			foreach ($query as $proses) {
				$x++;
				$row = array();
				$row['id'] = (!empty($proses->id) ? $proses->id : "NULL");
				$row['id_provinsi'] = (!empty($proses->id_provinsi) ? $proses->id_provinsi : "NULL");
				$row['nama'] = (!empty($proses->nama) ? $proses->nama : "NULL");

				$data[] = $row;
			}
			header('Content-Type: application/json');
			echo json_encode($data);
	}

	public function listUser()
	{
		if ($this->logged) {
			if($this->role == '10'){
				$this->twig->display('admin/listUser.html', $this->content);
			}else{
				redirect("dashboard");
			}
		}else{
			redirect("logout");
		}
	}

	public function listDataUser()
	{
		if ($this->logged && $this->role == '10')
		{
			$params = $columns = $totalRecords = $data = array();
			$params = $_REQUEST;
			$postData = $this->input->post('param');

			$query = $this->Model_sys->listDataUsers($postData);
			$x = 0;
			$i=0;
			foreach ($query as $proses) {
				$x++;
				$row = array();
				$row['id'] = (!empty($proses->id) ? $proses->id : "NULL");
				$row['name'] = (!empty($proses->name) ? $proses->name : "NULL");
				$row['username'] = (!empty($proses->username) ? $proses->username : "NULL");
				$row['kategori'] = (!empty($proses->kategori) ? $proses->kategori : "NULL");
				$row['kotaKab'] = (!empty($proses->kotaKab) ? $proses->kotaKab : "NULL");
				$row['nama_kotakab'] = (!empty($proses->nama_kotakab) ? $proses->nama_kotakab : "NULL");
				$row['status'] = (!empty($proses->status) ? $proses->status : "NULL");
				$row['islogin'] = (!empty($proses->islogin) ? $proses->islogin : "NULL");
				$row['role'] = (!empty($proses->role) ? $proses->role : "NULL");
				$row['role_desc'] = (!empty($proses->role_desc) ? $proses->role_desc : "NULL");
				$row['foto'] = (!empty($proses->foto) ? $proses->foto : "assets/dokumen/gambar/user/default.jpg");

				// if ($this->kategori == 'superAdmin') {
					// $row[] = '<a href="'.base_url().'formPangan/?id='.$proses->id.'" class="btn btn-sm btn-info" title="Edit" id="Edit"><i class="fa fa-edit"></i> Edit </a> <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="deleteData('."'".$proses->id."'".')"><i class="fa fa-trash"></i> Delete</a> ';
				// }else{
				// 	$row[] = '<a href="javascript:void(0)" class="btn btn-sm btn-success" title="Hasil" onclick="view('."'".$proses->id."'".')" id="view"><i class="fa fa-eye"></i> View </a> <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="deleteData('."'".$proses->id."'".')"><i class="fa fa-trash"></i> Delete</a> ';
				// }



				//add html for action
				$data[] = $row;
			}

      //           $output = array(
    	// 		                "draw" => $_POST['draw'],
      //                           "recordsTotal" => $this->Model_siaran->count_all(),
      //                           "recordsFiltered" => $this->Model_siaran->count_filtered(),
    	//                          "data" => $data
    	//                          );
			// //output to json format
			header('Content-Type: application/json');
			echo json_encode($data);
		}else{
			redirect("dashboard");
		}


	}

	public function saveUser()
	{
		$params = (object)$this->input->post();
		// remove the part that we don't need from the provided image and decode it
		$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $params->img));

		$filepath = "assets/dokumen/gambar/user/".$params->username.".png"; // or image.jpg
		chmod($filepath,0777);
		file_put_contents($filepath,$data);
		$params->foto = $filepath;

		$data = $this->Model_sys->save($params);
		header('Content-Type: application/json');
		echo json_encode(array("status" => TRUE));

	}

	public function updateUser()
	{
		$params = (object)$this->input->post();
		// remove the part that we don't need from the provided image and decode it
		if($params->img){
			$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $params->img));
			$filepath = "assets/dokumen/gambar/user/".$params->username.".png"; // or image.jpg
			chmod($filepath,0777);
			file_put_contents($filepath,$data);
			$params->foto = $filepath;
		}

		$data = $this->Model_sys->update($params);
		header('Content-Type: application/json');
		echo json_encode(array("status" => TRUE));

	}

	public function deleteUser()
	{

		$params = (object)$this->input->post();
		$this->Model_sys->delete($params);
		header('Content-Type: application/json');
		echo json_encode(array("status" => TRUE));
	}

	public function hitungAll(){

		$update = $this->Model_sys->hitungAll();
		header('Content-Type: application/json');
		echo json_encode($update);
	}

	public function listdatabanner()
	{
		if ($this->logged && $this->role == '10')
		{
			$params = $columns = $totalRecords = $data = array();
			$params = $_REQUEST;
			$postData = $this->input->post('param');

			$query = $this->Model_sys->listbanner($postData);

			$x = 0;
			$i=0;
			foreach ($query as $proses) {
				$x++;
				$row = array();
				$row['id'] = (!empty($proses->id) ? $proses->id : "NULL");
				$row['judul'] = (!empty($proses->judul) ? $proses->judul : "NULL");
				$row['deskripsi'] = (!empty($proses->deskripsi) ? $proses->deskripsi : "NULL");
				$row['foto'] = (!empty($proses->foto) ? $proses->foto : "assets/dokumen/gambar/user/default.jpg");
				$row['status'] = (!empty($proses->status) ? $proses->status : "NULL");
				$row['created_by'] = (!empty($proses->created_by) ? $proses->created_by : "NULL");
				$row['created_date'] = (!empty($proses->created_date) ? $proses->created_date : "NULL");
				$row['updated_date'] = (!empty($proses->updated_date) ? $proses->updated_date : "NULL");

				$data[] = $row;
			}
			header('Content-Type: application/json');
			echo json_encode($data);
		}else{
			redirect("dashboard");
		}

	}

	public function listdatabanneruser()
	{

			$params = $columns = $totalRecords = $data = array();
			$params = $_REQUEST;
			$postData = $this->input->post('param');

			$query = $this->Model_sys->listbanner(1);

			$x = 0;
			$i=0;
			foreach ($query as $proses) {
				$x++;
				$row = array();
				$row['id'] = (!empty($proses->id) ? $proses->id : "NULL");
				$row['judul'] = (!empty($proses->judul) ? $proses->judul : "NULL");
				$row['deskripsi'] = (!empty($proses->deskripsi) ? $proses->deskripsi : "NULL");
				$row['foto'] = (!empty($proses->foto) ? $proses->foto : "assets/dokumen/gambar/user/default.jpg");
				$row['status'] = (!empty($proses->status) ? $proses->status : "NULL");
				$row['created_by'] = (!empty($proses->created_by) ? $proses->created_by : "NULL");
				$row['created_date'] = (!empty($proses->created_date) ? $proses->created_date : "NULL");
				$row['updated_date'] = (!empty($proses->updated_date) ? $proses->updated_date : "NULL");

				$data[] = $row;
			}
			header('Content-Type: application/json');
			echo json_encode($data);

	}

	public function loadsetting()
	{

			$params = $columns = $totalRecords = $data = array();
			$params = $_REQUEST;
			$postData = $this->input->post('param');

			$query = $this->Model_sys->loadsetting(1);

			$x = 0;
			$i=0;
			foreach ($query as $proses) {
				$x++;
				$row = array();

				$row['id'] = (!empty($proses->id) ? $proses->id : "NULL");
				$row['logo'] = (!empty($proses->logo) ? $proses->logo : "assets/dokumen/gambar/user/default.jpg");
				$row['nama'] = (!empty($proses->nama) ? $proses->nama : "NULL");
				$row['deskripsi'] = (!empty($proses->deskripsi) ? $proses->deskripsi : "NULL");
				$row['alamat'] = (!empty($proses->alamat) ? $proses->alamat : "NULL");
				$row['email'] = (!empty($proses->email) ? $proses->email : "NULL");
				$row['notlp'] = (!empty($proses->notlp) ? $proses->notlp : "NULL");
				$row['instagram'] = (!empty($proses->instagram) ? $proses->instagram : "NULL");
				$row['twitter'] = (!empty($proses->twitter) ? $proses->twitter : "NULL");
				$row['facebook'] = (!empty($proses->facebook) ? $proses->facebook : "NULL");
				$row['copyright'] = (!empty($proses->copyright) ? $proses->copyright : "NULL");

				$data[] = $row;
			}
			header('Content-Type: application/json');
			echo json_encode($data);

	}

	public function simpansetting()
	{

		$params = (object)$this->input->post();
		// remove the part that we don't need from the provided image and decode it
		// if($params->img){
		// 	$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $params->img));
		// 	$filepath = "assets/dokumen/gambar/user/".$params->username.".png"; // or image.jpg
		// 	chmod($filepath,0777);
		// 	file_put_contents($filepath,$data);
		// 	$params->foto = $filepath;
		// }

		$data = $this->Model_sys->updatesetting($params);
		header('Content-Type: application/json');
		echo json_encode(array("status" => TRUE));

	}

	public function savebanner()
	{
		$params = (object)$this->input->post();
		// remove the part that we don't need from the provided image and decode it
		$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $params->img));

		$filepath = "assets/dokumen/gambar/banner/".str_replace(" ","_",$params->judul).".jpg"; // or image.jpg
		chmod($filepath,0777);
		file_put_contents($filepath,$data);
		$params->foto = $filepath;

		$data = $this->Model_sys->savebanner($params);
		header('Content-Type: application/json');
		echo json_encode(array("status" => TRUE));

	}

	public function updatebanner()
	{
		$params = (object)$this->input->post();
		// remove the part that we don't need from the provided image and decode it
		if($params->img){
			$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $params->img));
			$filepath = "assets/dokumen/gambar/banner/".$params->username.".jpg"; // or image.jpg
			chmod($filepath,0777);
			file_put_contents($filepath,$data);
			$params->foto = $filepath;
		}

		$data = $this->Model_sys->updatebanner($params);
		header('Content-Type: application/json');
		echo json_encode(array("status" => TRUE));

	}

	public function deletebanner()
	{

		$params = (object)$this->input->post();
		$this->Model_sys->deletebanner($params);
		header('Content-Type: application/json');
		echo json_encode(array("status" => TRUE));
	}

	public function updateprofile()
	{

		$params = (object)$this->input->post();
		$check = $this->db->get_where("muser", array("username" => $params->username,"password" => md5($params->validasi)));
		if($check->num_rows() > 0){
			if($params->img){
				$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $params->img));
				$filepath = "assets/dokumen/gambar/user/".$params->username.".png"; // or image.jpg
				chmod($filepath,0777);
				file_put_contents($filepath,$data);
				$params->foto = $filepath;
			}

			$data = $this->Model_sys->updateprofile($params);
			header('Content-Type: application/json');
			echo json_encode(array("status" => TRUE));
		}else{
			header('Content-Type: application/json');
			echo json_encode(array("status" => FALSE));
		}

	}

}
