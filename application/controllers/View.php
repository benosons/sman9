<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View extends CI_Controller {

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

	public function listdata()
	{
		if ( $this->logged && $this->role == '10' || $this->role == '20')
		{
			$this->twig->display('admin/data/listdata.html', $this->content);
			$this->layout->js('js/index.js');
		}else{
			redirect("dashboard");
		}
	}

	public function tambahdata()
	{
		if ( $this->logged && $this->role == '10' || $this->role == '20')
		{

			$this->twig->display('admin/data/tambahdata.html', $this->content);
		}else{
			redirect("dashboard");
		}
	}

	public function reportdata()
	{
		if ( $this->logged && $this->role == '10' || $this->role == '20')
		{

			$this->twig->display('admin/data/reportdata.html', $this->content);
		}else{
			redirect("dashboard");
		}
	}

	public function kegiatan()
	{
		if ( $this->logged && $this->role == '10' || $this->role == '20')
		{
			$this->content['js'] = '/assets/js/action/data/listkegiatan.js';
			$this->twig->display('admin/data/kegiatan.html', $this->content);
		}else{
			redirect("dashboard");
		}
	}

	public function tambahkegiatan()
	{
		if ( $this->logged && $this->role == '10' || $this->role == '20')
		{
			$this->content['js'] = '/assets/js/action/data/tambahkegiatan.js';
			$this->twig->display('admin/data/tambahkegiatan.html', $this->content);
		}else{
			redirect("dashboard");
		}
	}

	public function so()
	{
			$this->content['js'] = '/assets/js/action/user/so.js';
			$this->twig->display('users/so/so.html', $this->content);
	}

	public function inputso()
	{
			$this->content['js'] = '/assets/js/action/data/so.js';
			$this->twig->display('admin/data/so.html', $this->content);
	}

	public function formPangan()
	{
		if ( $this->logged && $this->role == '10' || $this->role == '20')
		{

			$data = NULL;
			$id = $this->input->get('id');
			$idx = $this->db->escape_str($id);
            $KodeEdit = $idx;
            if (!empty($KodeEdit)) {
                $q = $this->db->get_where("pangan", array("id" => $KodeEdit));
                $data = $q->row();
            }

            $this->content['data'] = $data;
			$this->twig->display('admin/pangan.html', $this->content);
		}else{
			redirect("dashboard");
		}
	}

	public function savepangan()
	{
		if ( $this->logged && $this->role == '10' || $this->role == '20')
		{
			$params = (object)$this->input->post();
			$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $params->img));
			$im = imagecreatefromstring($data);
			$source_width = imagesx($im);
			$source_height = imagesy($im);
			$ratio =  $source_height / $source_width;
			$new_width = 660; // assign new width to new resized image
			$new_height = 660;
			$thumb = imagecreatetruecolor($new_width, $new_height);
			$transparency = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
			imagefilledrectangle($thumb, 0, 0, $new_width, $new_height, $transparency);
			imagecopyresampled($thumb, $im, 0, 0, 0, 0, $new_width, $new_height, $source_width, $source_height);

			$filepath = "assets/dokumen/gambar/pangan/".$params->nama_file; // or image.jpg
			imagepng($thumb, $filepath, 9);
			chmod($filepath,0777);
			file_put_contents($filepath,$thumb);
			$params->foto = $filepath;
			imagedestroy($im);
			// print_r($ee);die;

 	        $data = $this->Model_pangan->savepangan($params);
 	        echo json_encode(array("status" => TRUE));
		}
		else
		{
			redirect("dashboard");
		}
	}


	public function updatePangan()
	{
		if ( $this->logged && $this->role == '10' || $this->role == '20')
		{
			$params = (object)$this->input->post();
		 	$data = $this->Model_pangan->update($params);
		 	echo json_encode(array("status" => TRUE));
		}
		else
		{
			redirect("dashboard");
		}
	}

	public function deletePangan($id = NULL)
	{
		if(!$id){

			$id = (object)$this->input->post();
		}
			$path = (object)$this->input->post();
		if(file_exists($path->path)){
			unlink($path->path);
		}

		$this->Model_pangan->delete($id);
		echo json_encode(array("status" => TRUE));
	}

	public function listDataPangan()
	{
		if ($this->logged && $this->role == '10' || $this->role == '20')
		{
			$params = $columns = $totalRecords = $data = array();
			$params = $_REQUEST;
			$query = $this->Model_pangan->listDataPangan();
			$x = 0;
			$i=0;
			foreach ($query as $proses) {
				$x++;
				$row = array();
				$row[] = (!empty($proses->nama) ? $proses->nama : "NULL");
				$row[] = (!empty($proses->tgl) ? $proses->tgl : "NULL");
				$row[] = (!empty($proses->jenisPangan) ? $proses->jenisPangan : "NULL");
				$row[] = (!empty($proses->created_by) ? $proses->created_by : "NULL");

				// if ($this->kategori == 'superAdmin') {
					$row[] = '<a href="'.base_url().'formPangan/?id='.$proses->id.'" class="btn btn-sm btn-info" title="Edit" id="Edit"><i class="fa fa-edit"></i> Edit </a> <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="deleteData('."'".$proses->id."'".')"><i class="fa fa-trash"></i> Delete</a> ';
				// }else{
				// 	$row[] = '<a href="javascript:void(0)" class="btn btn-sm btn-success" title="Hasil" onclick="view('."'".$proses->id."'".')" id="view"><i class="fa fa-eye"></i> View </a> <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="deleteData('."'".$proses->id."'".')"><i class="fa fa-trash"></i> Delete</a> ';
				// }



				//add html for action
				$data[] = $row;
			}

                $output = array(
    			                "draw" => $_POST['draw'],
                                "recordsTotal" => $this->Model_pangan->count_all(),
                                "recordsFiltered" => $this->Model_pangan->count_filtered(),
    	                         "data" => $data
    	                         );
			//output to json format
			echo json_encode($output);
		}else{
			redirect("dashboard");
		}


	}

	public function dataDetailPangan()
	{
		if ($this->logged && $this->role == '10' || $this->role == '20')
		{
			$no = $_POST['no'];
			$data = $this->Model_pangan->dataDetail($no);
			echo json_encode($data);
		}
		else
		{
			redirect("dashboard");
		}
	}

	public function loadparam()	{

			// $params = $columns = $totalRecords = $data = array();
			// $params = $_REQUEST;
			$param = $this->input->post('param');
			$id = $this->input->post('id');

			$query = $this->Model_pangan->loadparam($param, $id);

	header('Content-Type: application/json');
	echo json_encode($query);
}

	public function loadpangan()	{

			// $params = $columns = $totalRecords = $data = array();
			// $params = $_REQUEST;

			$query = $this->Model_pangan->loadpangan();

	header('Content-Type: application/json');
	echo json_encode($query);
}

public function verifikasi()
{
	if ( $this->logged && $this->role == '10' || $this->role == '20')
	{
				$params = (object)$this->input->post();

				$data = $this->Model_pangan->verifikasi($params);
				echo json_encode(array("status" => TRUE));
	}
	else
	{
		redirect("dashboard");
	}
}

public function kalibrasi()
{
		$this->content['js'] = '/assets/js/action/data/kalibrasi.js';
		$this->twig->display('admin/data/kalibrasi.html', $this->content);
}


}
