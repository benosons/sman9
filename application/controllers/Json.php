<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Json extends CI_Controller {

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
		$this->load->model('Model_json');
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

	public function submitkegiatan()
	{
		$params = (object)$this->input->post();
		$img = $_FILES['img'];
		$doc = $_FILES['doc'];
		$countimg = count($img['name']);
		$countdoc = count($doc['name']);

		$id = $this->Model_json->savekegiatan($params);

		// $name = strtolower(str_replace(' ', '_', $_FILES['file_data']['name']));
		$path			= FCPATH;
		$bag			= 'assets/dokumen/kegiatan';
		$date 		= date('Y/m/d');
		$folder		= $path.'/'.$bag.'/'.$date.'/';

		if (!is_dir($folder)) {
		    mkdir($folder, 0777, TRUE);
		}

		//img
		for ($i=0; $i < $countimg ; $i++) {
			$name = $img['name'][$i];
			$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

			$filename = strtolower(str_replace(' ', '_', $name));
			$tmp_file = $img['tmp_name'][$i];
			move_uploaded_file($tmp_file, $folder.$filename);

			$file->name = $name;
			$file->type = $img['type'][$i];
			$file->tmp_name = $tmp_file;
			$file->size = $img['size'][$i];
			$file->path = '/'.$bag.'/'.$date.'/'.$filename;
			$file->id_master = $id;
			$file->param_val1 = 'images';

			$valid = $this->Model_json->insertUpload($file);
		}

		// Doc
		for ($i=0; $i < $countdoc ; $i++) {
			$name = $doc['name'][$i];
			$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

			$filename = strtolower(str_replace(' ', '_', $name));
			$tmp_file = $doc['tmp_name'][$i];
			move_uploaded_file($tmp_file, $folder.$filename);

			$file->name = $name;
			$file->type = $doc['type'][$i];
			$file->tmp_name = $tmp_file;
			$file->size = $doc['size'][$i];
			$file->path = '/'.$bag.'/'.$date.'/'.$filename;
			$file->id_master = $id;
			$file->param_val1 = 'document';

			$valid = $this->Model_json->insertUpload($file);
		}

		header('Content-Type: application/json');
		echo json_encode(array("status" => TRUE));

	}

	public function submitso()
	{
		$params = (object)$this->input->post();
		$name = strtolower(str_replace(' ', '_', $_FILES['file_data']['name']));
		$path			= FCPATH;
		$bag			= 'assets/dokumen/so';
		$date 		= date('Y/m/d');
		$folder		= $path.'/'.$bag.'/'.$date.'/';
		if (!is_dir($folder)) {
		    mkdir($folder, 0777, TRUE);
		}
		$tmp_file = $_FILES['file_data']['tmp_name'];
		move_uploaded_file($tmp_file, $folder.$name);
		$params->foto = '/'.$bag.'/'.$date.'/'.$name;
		unset($params->file_data);
		$data = $this->Model_json->saveso($params);
		header('Content-Type: application/json');
		echo json_encode(array("status" => TRUE));

	}

	public function loadkegiatan(){

			$params = $columns = $totalRecords = $data = array();
			$params = $_REQUEST;
			$postData = $this->input->post('param');
			$query = $this->Model_json->loadkegiatan($this->id);
			foreach ($query as $key => $value) {
				$attc = $this->Model_json->loadfile($value->id);
				$query[$key]->files = $attc;

			}
			$data = $query;

			header('Content-Type: application/json');
			echo json_encode($data);
	}

	public function loadso(){

			$params = $columns = $totalRecords = $data = array();
			$params = $_REQUEST;
			$postData = $this->input->post('param');
			$query = $this->Model_json->loadso($this->id);
			$data = $query;

			header('Content-Type: application/json');
			echo json_encode($data);
	}

	public function loadindikator(){

			$params = $this->input->post();
			$postData = $this->input->post('param');
			if($params['param'] == 'dokumen'){
				$query = $this->Model_json->loaddokumen($params);
			}else if($params['param'] == 'rka'){
				$query = $this->Model_json->loadrka($params);
			}else{
				$query = $this->Model_json->loadindikator($params);
			}

			$data = $query;

			header('Content-Type: application/json');
			echo json_encode($data);
	}

	public function saveindikator(){

			$params = (object)$this->input->post();
			if($params->table == 'dokumen'){
				$name = strtolower(str_replace(' ', '_', $_FILES['file_data']['name']));
				$path			= FCPATH;
				$bag			= 'assets/dokumen/dokumen';
				$date 		= date('Y/m/d');
				$folder		= $path.'/'.$bag.'/'.$date.'/';
				if (!is_dir($folder)) {
				    mkdir($folder, 0777, TRUE);
				}
				$tmp_file = $_FILES['file_data']['tmp_name'];
				move_uploaded_file($tmp_file, $folder.$name);
				$params->dokumen = '/'.$bag.'/'.$date.'/'.$name;

				$query = $this->Model_json->savedokumen($params, $this->id);
			}else if($params->table == 'rka'){
				$query = $this->Model_json->saverka($params, $this->id);
			}else{
				$query = $this->Model_json->saveindikator($params, $this->id);
			}
			$data = $query;

			header('Content-Type: application/json');
			echo json_encode($data);
	}

	public function actionkegiatan()
	{
		$params = (object)$this->input->post();

		if($params->param == 'hapus'){
			$data = $this->Model_json->deletekegiatan($params);
			// if($data){
			// 	$path			= FCPATH;
			// 	unlink($path.'/'.$params->dokumen);
			// }
		}

		header('Content-Type: application/json');
		echo json_encode(array("status" => TRUE));

	}

	public function actionindikator()
	{
		$params = (object)$this->input->post();
		$data = $this->Model_json->deleteindikator($params);
		if($data){
			$path			= FCPATH;
			unlink($path.'/'.$params->dokumen);
		}
		header('Content-Type: application/json');
		echo json_encode(array("status" => TRUE));

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

	public function getDash()
	{

		$params = (object)$this->input->post();

		$data = $this->Model_json->getDash($params);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function actionkalibrasi()
	{
		$params = (object)$this->input->post();

		if($params->param['pemilik']){
			$data = $this->Model_json->savekalibrasi($params->param);
		}else if($params->param['mode'] == 'delete'){
			
			$data = $this->Model_json->deletekalibrasi($params->param);
		}else{
			$data = $this->Model_json->updatekalibrasi($params->param);
		}


		header('Content-Type: application/json');
		echo json_encode(array("status" => TRUE));

	}

}
