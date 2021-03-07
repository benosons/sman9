<?php
class Model_sys extends CI_Model {

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


    public function save($params = NULL)
    {
        $valid = false;

        $this->db->set("name", $params->name);
        $this->db->set("username", $params->username);
        $this->db->set("password", md5($params->password));
        $this->db->set("kotaKab", $params->kotaKab);
        $this->db->set("kategori", 'admin');
        $this->db->set("created_by", $this->session->userdata('username'));
        $this->db->set("created_at", date("Y-m-d H:i:s"));
        $this->db->set("role", $params->role);
        $this->db->set("islogin", 0);
        $this->db->set("status", $params->status);
        $this->db->set("foto", $params->foto);
        $valid = $this->db->insert('muser');

        return $valid;

    }

    public function saveRegis($params = NULL)
    {
        $valid = true;
        $this->db->set("username", $params->username_regis);
        $this->db->set("password", md5($params->password_regis));
        $this->db->set("kotaKab", $params->kota_kab_regis);
        $this->db->set("kategori", 'user');
        $this->db->set("created_by", '');
        $this->db->set("created_at", date("Y-m-d H:i:s"));
        $this->db->set("role", '20');
        $this->db->set("islogin", 0);
        $this->db->set("status", '1');
        $this->db->set("name", $params->name_regis);
        $this->db->set("no_telp", $params->telp_regis);
        $this->db->set("email", $params->email_regis);
        $valid = $this->db->insert('muser');

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

    public function delete($id)
    {
        // $idx = $this->db->escape_str($id);
        $this->db->where('id', $id->id);
        $this->db->delete('muser');
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

}
