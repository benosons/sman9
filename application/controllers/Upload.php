<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends CI_Controller {
	private $filename = "import_data"; // Kita tentukan nama filenya

	public function __construct(){
		parent::__construct();

		$this->load->model('model_bantuan');
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

	public function bantuan()
	{
		if ( $this->logged && $this->role == '10' || $this->role == '20')
		{
			$this->twig->display('admin/upload/bantuan.html', $this->content);
		}else{
			redirect("dashboard");
		}
	}

	public function laporan()
	{
		if ( $this->logged && $this->role == '10' || $this->role == '20')
		{
			$this->twig->display('admin/upload/laporan-kegiatan.html', $this->content);
		}else{
			redirect("dashboard");
		}
	}

	public function rekap()
	{
		if ( $this->logged && $this->role == '10' || $this->role == '20')
		{
			$this->twig->display('admin/upload/rekap.html', $this->content);
		}else{
			redirect("dashboard");
		}
	}

	public function upload(){

		// $data['siswa'] = $this->SiswaModel->view();
		// $this->load->view('upload', $data);
		$this->twig->display("upload.html", $this->content);
	}

	public function siswa(){

		$data['siswa'] = $this->SiswaModel->view();

		$this->load->view('upload', $data);
		// $this->twig->display("upload.html", $this->content);
	}

	public function getData()
	{
		$postData = $this->input->post('param');
		$bantuan = $this->model_bantuan->getBantuan($postData);

		echo json_encode($bantuan);
	}

	public function form(){

		$data = array(); // Buat variabel $data sebagai array
		$nama_file_baru = 'data.xlsx';
		$name = strtolower(str_replace(' ', '_', $_FILES['file_data']['name']));
		$nama_file = $_POST['nama_file'];
		$bulan = $_POST['bulan'];
		$tahun = $_POST['tahun'];

		if(is_file('assets/dokumen/excel/'.$name)) // Jika file tersebut ada
			unlink('assets/dokumen/excel/'.$name);
			$ext = pathinfo($_FILES['file_data']['name'], PATHINFO_EXTENSION); // Ambil ekstensi filenya apa
			$tmp_file = $_FILES['file_data']['tmp_name'];

		if($ext == "xlsx"){
			move_uploaded_file($tmp_file, 'assets/dokumen/excel/'.$name);
		}

			// lakukan upload file dengan memanggil function upload yang ada di SiswaModel.php

			// $upload = $this->SiswaModel->upload_file($this->filename);
			// print_r($upload);die;
			// if($upload['result'] == "success"){ // Jika proses upload sukses
				// Load plugin PHPExcel nya
				include APPPATH.'third_party/PHPExcel/PHPExcel.php';

				$excelreader = new PHPExcel_Reader_Excel2007();

				// $loadexcel = $excelreader->load('assets/dokumen/excel/'.$name); // Load file yang tadi diupload ke folder excel
				$loadexcel = $excelreader->load('assets/dokumen/excel/'.$name); // Load file yang tadi diupload ke folder excel
				if($name == 'rekap_per_kab_bantuan_pemerintah_akabi.xlsx'){
					foreach ($loadexcel->getSheetNames() as $key1 => $value1) {
						$data[$value1] = $loadexcel->getSheet($key1)->toArray(null, true, true ,true);
						foreach ($data[$value1] as $key2 => $value2) {
							if($key2 >= 6){
								if(is_float($value2['A'])){
									$datakab = [
												'id' => $value2['A'],
												'kabupaten' => $value2['B'],
												'kedelai_full_paket' => $value2['C'],
												'kedelai_non_phc' => $value2['D'],
												'kedelai_jumlah' => $value2['E'],
												'kacang_tanah_full_paket' => $value2['F'],
												'kacang_tanah_non_phc' => $value2['G'],
												'kacang_tanah_jumlah' => $value2['H'],
												'kacang_hijau_full_paket' => $value2['I'],
												'kacang_hijau_non_phc' => $value2['J'],
												'kacang_hijau_jumlah' => $value2['K'],
												'ubi_jalar' => $value2['L'],
												'jumlah_akabi' => $value2['M'],
												'bulan' => $bulan,
												'tahun' => $tahun,
											];
									$inst = $this->model_bantuan->insert_data('rekap_perkab',$datakab);
								}


							}
						}
					}

				}else if($name == 'laporan_bulanan_kegiatan_akabi.xlsx'){

					foreach ($loadexcel->getSheetNames() as $key1 => $value1) {

						if($value1 != 'Sheet5'){
							$data[$value1] = $loadexcel->getSheet($key1)->toArray(null, true, true ,true);

							foreach ($data[$value1] as $key2 => $value2) {

								if($key2 >= 9){

									if($value2['A']){
										$datakab = [
													'no' => $value2['A'],
													'jenis' => explode('. ', $value1)[1],
													'kabupaten' => $value2['B'],
													'jumlah_kec' => $value2['C'],
													'jumlah_desa' => $value2['D'],
													'jumlah_poktan' => $value2['E'],
													'sasaran_areal' => $value2['F'],
													'sk_penetapan' => $value2['G'],
													'realisasi_kontrak' => $value2['H'],
													'realisasi_distribusi' => $value2['I'],
													'apr' => $value2['J'],
													'mei' => $value2['K'],
													'juni' => $value2['L'],
													'juli' => $value2['M'],
													'ags' => $value2['N'],
													'sep' => $value2['O'],
													'okt' => $value2['P'],
													'nop' => $value2['Q'],
													'des' => $value2['R'],
													'jumlah' => $value2['S'],
													'realisasi_panen_luas' => $value2['T'],
													'realisasi_panen_produktivitas' => $value2['U'],
													'realisasi_panen_produksi' => $value2['V'],
													'tidak_dilaksanakan' => $value2['W'],
													'provitas_sebelum' => $value2['X'],
													'ket' => $value2['Y'],
													'bulan' => $bulan,
													'tahun' => $tahun,
												];
										$inst = $this->model_bantuan->insert_data('laporan_bulanan_kegiatan',$datakab);
										// print_r($inst);die;
									}


								}
							}
						}
					}
				}else if($name == 'penerima_bantuan_pemerintah_akabi.xlsx'){
						// $sheet = $loadexcel->getSheetNames(); //ambil nama sheet
						// $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
						// $sheet_1 = $loadexcel->getSheet(0)->toArray(null, true, true ,true); //ambil dengan index
						foreach ($loadexcel->getSheetNames() as $key1 => $value1) {
								$data[$value1] = $loadexcel->getSheet($key1)->toArray(null, true, true ,true);
								foreach ($data[$value1] as $key2 => $value2) {
									// code...

									if($key2 >= 7){
										$names;
										if($value2['A']){
											if(!is_numeric($value2['A'])){
												$explode = explode(". ",$value2['A']);

												$id = $explode[0];
												$name = $explode[1];
												$datakab = [
															'jenis_bantuan' => $value1,
															'id_kabupaten' => $id,
															'nama_kabupaten' => $name
														];
												$names = $name;
												// $insert = $this->SiswaModel->insert_data('bantuan_kabupaten', $datakab);
												// if($insert){
												// 	$ids = $id;
												// }
											}else{

												$data = [
																'jenis_bantuan' => $value1,
																'nama_kabupaten' => $names,
																'no' => $value2['A'],
																'kelompok_tani' => $value['B'],
																'kecamatan' => $value2['C'],
																'desa' => $value2['D'],
																'nama' => $value2['E'],
																'nik' => $value2['F'],
																'no_hp' => $value2['G'],
																'jml_anggota' => $value2['H'],
																'luas' => $value2['I'],
																'jenis_lahan' => $value2['J'],
																'benih' => $value2['K'],
																'varietas' => $value2['L'],
																'pupuk' => $value2['M'],
																'rhizobium' => $value2['N'],
																'herbisida' => $value2['O'],
																'jadwal' => $value2['P'],
																'provitas_existing' => $value2['Q'],
																'provitas_target' => $value2['R'],
																'create_date' => date("Y-m-d H:i:s"),
																'update_date' => date("Y-m-d H:i:s"),
																'create_by' => '',
																'bulan' => $bulan,
																'tahun' => $tahun,
												];
												
												$insert = $this->model_bantuan->insert_data('bantuan', $data);
											}
										}
									}
								}
						}
					}

				// Masukan variabel $sheet ke dalam array data yang nantinya akan di kirim ke file form.php
				// Variabel $sheet tersebut berisi data-data yang sudah diinput di dalam excel yang sudha di upload sebelumnya


			// }else{ // Jika proses upload gagal
			// 	$data['upload_error'] = $upload['error']; // Ambil pesan error uploadnya untuk dikirim ke file form dan ditampilkan
			// }

		// redirect("upload");
		echo json_encode(array("status" => TRUE));
	}

	public function import(){
		// Load plugin PHPExcel nya
		include APPPATH.'third_party/PHPExcel/PHPExcel.php';

		$excelreader = new PHPExcel_Reader_Excel2007();
		$loadexcel = $excelreader->load('excel/'.$this->filename.'.xlsx'); // Load file yang telah diupload ke folder excel
		$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

		// Buat sebuah variabel array untuk menampung array data yg akan kita insert ke database
		$data = array();

		$numrow = 1;
		foreach($sheet as $row){
			// Cek $numrow apakah lebih dari 1
			// Artinya karena baris pertama adalah nama-nama kolom
			// Jadi dilewat saja, tidak usah diimport
			if($numrow > 1){
				// Kita push (add) array data ke variabel data
				array_push($data, array(
					'nis'=>$row['A'], // Insert data nis dari kolom A di excel
					'nama'=>$row['B'], // Insert data nama dari kolom B di excel
					'jenis_kelamin'=>$row['C'], // Insert data jenis kelamin dari kolom C di excel
					'alamat'=>$row['D'], // Insert data alamat dari kolom D di excel
				));
			}

			$numrow++; // Tambah 1 setiap kali looping
		}

		// Panggil fungsi insert_multiple yg telah kita buat sebelumnya di model
		$this->SiswaModel->insert_multiple($data);

		redirect("Siswa"); // Redirect ke halaman awal (ke controller siswa fungsi index)
	}

	public function deletedata()
	{

		$params = (object)$this->input->post();
		$this->model_bantuan->deletedata($params);
		header('Content-Type: application/json');
		echo json_encode(array("status" => TRUE));
	}

}
