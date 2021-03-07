<?php
class Model_json extends CI_Model {

    var $table = 'muser';
    var $column = array('username','kategori','kotaKab'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $column_search = array('username','kategori','kotaKab');
    var $order = array('id' => 'desc'); // default order

    function __construct(){
        parent::__construct();
    }


    // server side

    private function _get_datatables_query()
    {
        $id = $this->db->escape_str('admin');
        $this->db->from('muser');
        $this->db->where('kategori',$id);



        $i = 0;

        foreach ($this->column as $item) // loop column
    {
         if($_POST['search']['value']) // if datatable send POST for search
         {

            if($i===0) // first loop
            {
               $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
               $this->db->like($item, $_POST['search']['value']);
            }
            else
            {
               $this->db->or_like($item, $_POST['search']['value']);
            }

            if(count($this->column) - 1 == $i) //last loop
               $this->db->group_end(); //close bracket
         }
         $column[$i] = $item; // set column array variable to order processing
         $i++;
    }

        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function kotaKab()
    {
        $query = $this->db->query("select * from kabupaten_kota order by nama asc")->result();
        return $query;
    }


    public function savekegiatan($params = NULL)
    {
        // $valid = true;
        $convertDate = date("Y-m-d", strtotime($params->tanggal));

        $this->db->set($params);
        $this->db->set("tanggal", $convertDate);
        $this->db->set("create_date", date("Y-m-d H:i:s"));
        $this->db->set("create_by", $this->session->userdata('username'));
        $this->db->insert('kegiatan');
        $insert_id = $this->db->insert_id();

        return $insert_id;

    }

    public function saveso($params = NULL)
    {
        $valid = true;
        $convertDate = date("Y-m-d", strtotime($params->tanggal));

        $this->db->set($params);
        $this->db->set("create_date", date("Y-m-d H:i:s"));
        $this->db->set("create_by", $this->session->userdata('username'));
        if($params->foto){
          $this->db->set("foto", $params->foto);
        }else{
          unset($params->foto);
        }
        $this->db->where('id', $params->id);
        $valid = $this->db->update('so');

        return $valid;

    }

    public function loadindikator($param)
    {
        $nama = $this->session->userdata('id');
        $kategori = $this->session->userdata('kategori');
        $id = $this->db->escape_str($nama);
        $param = $param['param'];

        $query = $this->db->query("select * from indikator where indikator_type = '$param' order by id desc")->result();

        return $query;
    }

    public function loaddokumen($param)
    {

        $nama = $this->session->userdata('id');
        $kategori = $this->session->userdata('kategori');
        $id = $this->db->escape_str($nama);
        $param = $param['param'];

        $query = $this->db->query("select * from dokumen order by id desc")->result();

        return $query;
    }

    public function loadrka($param)
    {

        $nama = $this->session->userdata('id');
        $kategori = $this->session->userdata('kategori');
        $id = $this->db->escape_str($nama);
        $param = $param['param'];

        $query = $this->db->query("select * from rka order by id desc")->result();

        return $query;
    }


    public function saveindikator($params = NULL, $id)
    {
        $valid = true;

        $params->indikator_2 == 'undefined' ?   $params->indikator_2 = null :   $params->indikator_2 = $params->indikator_2;
        $params->create_by = $id;
        $params->create_date = date("Y-m-d H:i:s");

        if($params->id){

          $this->db->set($params);
          $this->db->where('id', $params->id);
          $valid = $this->db->update('indikator');
        }else{

          $this->db->set($params);
          $valid = $this->db->insert('indikator');
        }

        return $valid;

    }

    public function savedokumen($params = NULL, $id)
    {
        $valid = true;

        $table = $params->table;
        unset($params->table);
        $params->create_by = $id;
        $params->create_date = date("Y-m-d H:i:s");

        if($params->id){
          if($params->file_data == 'undefined'){
            unset($params->file_data);
            unset($params->dokumen);
          }

          $this->db->set($params);
          $this->db->where('id', $params->id);
          $valid = $this->db->update($table);
        }else{
          $this->db->set($params);
          $valid = $this->db->insert($table);
        }




        return $valid;

    }

    public function saverka($params = NULL, $id)
    {
        $valid = true;
        $table = $params->table;
        unset($params->table);
        $params->create_by = $id;
        $params->create_date = date("Y-m-d H:i:s");

        if($params->id){

          $this->db->set($params);
          $this->db->where('id', $params->id);
          $valid = $this->db->update($table);
        }else{

          $this->db->set($params);
          $valid = $this->db->insert($table);
        }

        return $valid;

    }

    public function update($params = NULL)
    {
        $valid = false;

        // $pass = $params->password;
        // $query = $this->db->query("select password, id from muser where id = '".$params->id."' ")->row();
        //
        // if ($pass != $query->password) {
        //     $this->db->set("password", md5($params->password));
        // }
        $this->db->set("username", $params->username);
        $this->db->set("name", $params->name);
        $this->db->set("kotaKab", $params->kotaKab);
        $this->db->set("updated_by", $this->session->userdata('username'));
        $this->db->set("updated_at", date("Y-m-d H:i:s"));
        $this->db->set("role", $params->role);
        $this->db->set("status", $params->status);
        if($params->foto){
          $this->db->set("foto", $params->foto);
        }
        $this->db->where('id', $params->id);
        $valid = $this->db->update('muser');

        return $valid;

    }

    public function deletekegiatan($param)
    {
        $this->db->where('id', $param->id);
        $valid = $this->db->delete('kegiatan');
        return $valid;
    }

    public function deleteindikator($param)
    {
        $this->db->where('id', $param->id);
        $valid = $this->db->delete($param->table);
        return $valid;
    }

    public function listDataUsers($param)
    {
        $nama = $this->session->userdata('id');
        $kategori = $this->session->userdata('kategori');
        $role = $this->session->userdata('role');
        $id = $this->db->escape_str($nama);
        if ($role == '10') {
            $query = $this->db->query(" select
                                        m.*,
                                        k.nama as nama_kotakab,
                                        r.role_desc as role_desc
                                        from muser m
                                        INNER JOIN kabupaten_kota k on k.id = m.kotaKab
                                        INNER JOIN role r on r.id_role = m.role where m.id != '".$id."' order by m.id desc")->result();
        }else{
            $query = $this->db->query("select * from pangan where created_by = '".$id."' order by id desc")->result();
        }
        return $query;
    }

    public function loadkota($param)
    {
        $nama = $this->session->userdata('id');
        $kategori = $this->session->userdata('kategori');
        $id = $this->db->escape_str($nama);
        $query = $this->db->query("select * from kabupaten_kota order by id desc")->result();

        return $query;
    }

    public function loadkegiatan($param)
    {
        $nama = $this->session->userdata('id');
        $kategori = $this->session->userdata('kategori');
        $id = $this->db->escape_str($nama);
        $query = $this->db->query("select *, (select param_name from param_indikator where param_id = kegiatan.indikator_ssd and param_type = 'ssd') as indikator_ssd_name, (select param_name from param_indikator where param_id = kegiatan.indikator_manager and param_type = 'manager') as indikator_manager_name from kegiatan order by id desc")->result();

        return $query;
    }

    public function loadso($param)
    {
        $nama = $this->session->userdata('id');
        $kategori = $this->session->userdata('kategori');
        $id = $this->db->escape_str($nama);
        $query = $this->db->query("select * from so order by id asc")->result();

        return $query;
    }

    public function hitungAll()
    {
        $query = array();
        $query['tv']    = $this->db->query("select count(*) as total from mperizinan where jenisLP like '%Televisi%'")->result();
        $query['radio'] = $this->db->query("select count(*) as total from mperizinan where jenisLP like '%Radio%'")->result();
        $query['aduan'] = $this->db->query("select count(*) as total from aduan")->result();
        $query['video'] = $this->db->query("select count(*) as total from videotutorial")->result();

        return $query;
    }

    public function loaduser($id)
    {
        $query    = $this->db->query("select * from muser where id = $id order by id desc")->result();

        return $query;
    }

    public function listbanner($id)
    {
        if($id){
          $query    = $this->db->query("select * from banner where status = $id order by id asc")->result();

        }else{
          $query    = $this->db->query("select * from banner order by id desc")->result();

        }

        return $query;
    }

    public function loadsetting($id)
    {
        $query    = $this->db->query("select * from setting order by id desc")->result();

        return $query;
    }

    public function updatesetting($params = NULL)
    {
        $valid = true;

        // $this->db->set("updated_by", $this->session->userdata('username'));
        // $this->db->set("updated_at", date("Y-m-d H:i:s"));
        $this->db->set("nama", $params->nama);
        $this->db->set("deskripsi", $params->deskripsi);
        $this->db->set("alamat", $params->alamat);
        $this->db->set("email", $params->email);
        $this->db->set("notlp", $params->notlp);
        $this->db->set("instagram", $params->ig);
        $this->db->set("twitter", $params->twit);
        $this->db->set("facebook", $params->fb);
        // if($params->foto){
        //   $this->db->set("foto", $params->foto);
        // }
        $this->db->where('id', $params->id);
        $valid = $this->db->update('setting');

        return $valid;

    }

    public function savebanner($params = NULL)
    {
        $valid = true;

        $this->db->set("judul", $params->judul);
        $this->db->set("deskripsi", $params->deskripsi);
        $this->db->set("created_by", $this->session->userdata('username'));
        $this->db->set("created_date", date("Y-m-d H:i:s"));
        $this->db->set("status", $params->status);
        $this->db->set("foto", $params->foto);
        $valid = $this->db->insert('banner');

        return $valid;

    }

    public function updatebanner($params = NULL)
    {
        $valid = true;

        $this->db->set("judul", $params->judul);
        $this->db->set("deskripsi", $params->deskripsi);
        $this->db->set("created_by", $this->session->userdata('username'));
        $this->db->set("created_date", date("Y-m-d H:i:s"));
        $this->db->set("status", $params->status);
        if($params->foto){

          $this->db->set("foto", $params->foto);
        }
        $this->db->where('id', $params->id);
        $valid = $this->db->update('banner');

        return $valid;

    }

    public function deletebanner($id)
    {
        // $idx = $this->db->escape_str($id);
        $this->db->where('id', $id->id);
        $this->db->delete('banner');
    }

    public function updateprofile($params = NULL)
    {
        $valid = true;

        $this->db->set("username", $params->username);
        $this->db->set("name", $params->name);
        $this->db->set("kotaKab", $params->kotaKab);
        $this->db->set("updated_by", $this->session->userdata('username'));
        $this->db->set("updated_at", date("Y-m-d H:i:s"));
        $this->db->set("no_telp", $params->no_telp);
        $this->db->set("email", $params->email);
        if($params->password){
          $this->db->set("password", md5($params->password));
        }

        if($params->foto){
          $this->db->set("foto", $params->foto);
        }
        $this->db->where('id', $params->id);
        $valid = $this->db->update('muser');

        return $valid;

    }

    public function getDash($params = null)
    {
        $query    = $this->db->query("select * from dash_$params->param order by id asc")->result();

        return $query;
    }

    public function updatekalibrasi($params = NULL)
    {
        $valid = true;
        $id = $params['id'];
        $this->db->set("update_by", $this->session->userdata('username'));
        $this->db->set("update_date", date("Y-m-d H:i:s"));
        $this->db->set($params);

        unset($params['id']);
        // print_r($id);die;
        $this->db->where('id', $id);
        $valid = $this->db->update('dash_kalibrasi');

        return $valid;

    }

    public function insertUpload($params = NULL)
    {
        // $valid = true;

        $this->db->set($params);
        $this->db->set("create_date", date("Y-m-d H:i:s"));
        $this->db->insert('file_attachment');
        $insert_id = $this->db->insert_id();

        return $insert_id;

    }

    public function loadfile($id)
    {
        $query    = $this->db->query("select * from file_attachment where id_master = '$id'")->result();

        return $query;
    }

    public function savekalibrasi($params = NULL)
    {
        $valid = true;

        $this->db->set("update_by", $this->session->userdata('username'));
        $this->db->set("update_date", date("Y-m-d H:i:s"));
        $this->db->set("nama_pemilik_alat_ukur", $params['pemilik']);
        $this->db->set("trackgauge", 0);
        $this->db->set("back_to_back", 0);
        $this->db->set("vernier_calipper", 0);
        $this->db->set("diterima", 0);
        $this->db->set("ditolak", 0);
        $this->db->set("total", 0);

        $valid = $this->db->insert('dash_kalibrasi');

        return $valid;

    }

    public function deletekalibrasi($param = null)
    {
        $this->db->where('id', $param['id']);
        $valid = $this->db->delete('dash_kalibrasi');
        return $valid;
    }

}
