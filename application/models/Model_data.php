<?php
class Model_data extends CI_Model {

    var $table = 'data';
    var $column = array('nama','tgl','jenisPangan'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $column_search = array('nama','tgl','jenisPangan');
    var $order = array('id' => 'desc'); // default order

    function __construct(){
        parent::__construct();
    }


    // server side

    private function _get_datatables_query()
    {
        $kat = $this->session->userdata('kategori');
        $nama = $this->session->userdata('username');
        $id = $this->db->escape_str($nama);
        if ($kat == 'superAdmin') {
            $this->db->from('pangan');
        }else{
            $this->db->from('pangan');
            $this->db->where('created_by',$nama);
        }


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


    public function listDataPangan()
    {
        $nama = $this->session->userdata('username');
        $kategori = $this->session->userdata('kategori');
        $id = $this->db->escape_str($nama);
        if ($kategori == 'superAdmin') {
            $query = $this->db->query("select * from pangan order by id desc")->result();
        }else{
            $query = $this->db->query("select * from pangan where created_by = '".$id."' order by id desc")->result();
        }


        return $query;
    }


    public function save($params = NULL)
    {
        $valid = false;

            $this->db->set("nama", $params->nama);
            $this->db->set("tgl", $params->tgl);
            $this->db->set("jenisPangan", $params->jenisPangan);
            $this->db->set("created_by", $this->session->userdata('username'));
            $this->db->set("created_at", date("Y-m-d H:i:s"));
            $valid = $this->db->insert('pangan');

        return $valid;

    }

    public function update($params = NULL)
    {
        $valid = false;

            $this->db->set("nama", $params->nama);
            $this->db->set("tgl", $params->tgl);
            $this->db->set("jenisPangan", $params->jenisPangan);
            $this->db->set("updated_by", $this->session->userdata('username'));
            $this->db->set("updated_at", date("Y-m-d H:i:s"));
            $this->db->where('id', $params->id);
            $valid = $this->db->update('pangan');

        return $valid;

    }

    public function delete($id)
    {
        $ids = $this->db->escape_str($id);
        if(!$ids){
          $ids = $id->id;
        }

        $this->db->where('id', $ids);
        $this->db->delete('pangan');
    }

    public function dataDetail($no = NULL)
    {
        $query = $this->db->query("select * from pangan where id = '".$this->db->escape_str($no)."' ")->row();

        return $query;
    }

    public function cek($kd)
    {
        $query = $this->db->query("select * FROM pangan WHERE id = '".$this->db->escape_like_str($kd)."' ");

        return $query;
    }

    public function loadparam($param, $id)
    {
        if($param == 'kab'){
          $query = $this->db->query("select * from kabupaten_kota order by id desc")->result();
        }else if ($param == 'kec'){
          $query = $this->db->query("select * from kecamatan where id_kabupaten = $id order by id desc")->result();
        }else{
          $query = $this->db->query("select * from kelurahan where id_kecamatan = $id order by id desc")->result();
        }

        return $query;
    }

    public function savepangan($params = NULL)
    {
            $valid = true;
            unset($params->img);
            unset($params->nama_file);
            $exp = explode("/",$params->tanggal_panen);
            $date = $exp[0];
            $month = $exp[1];
            $year = $exp[2];

            $dates =date_create($year."-".$month."-".$date);

            $params->tanggal_panen = date_format($dates,"Y-m-d");

            $this->db->set($params);
            // $this->db->set("tgl", $params->tgl);
            // $this->db->set("jenisPangan", $params->jenisPangan);
            $this->db->set("created_by", $this->session->userdata('username'));
            $this->db->set("updated_by", $this->session->userdata('username'));
            $this->db->set("create_date", date("Y-m-d H:i:s"));
            $this->db->set("update_date", date("Y-m-d H:i:s"));
            $valid = $this->db->insert('pangan');

        return $valid;

    }

    public function loadpangan(){

      $role = $this->session->userdata('role');
      $kotakab = $this->session->userdata('kotaKab');
      if($role == 10){
        $query = $this->db->query("select *,
        (select nama_penyuluh from penyuluh_pendamping where id= pangan.penyuluh) as nama_penyuluh,
        (SELECT nama from kabupaten_kota WHERE id= pangan.kabupaten_kota) as nama_kabupaten,
        (SELECT nama from kecamatan WHERE id=pangan.kecamatan) as nama_kecamatan,
        (SELECT nama from kelurahan WHERE id=pangan.kelurahan) as nama_kelurahan,
        (SELECT nama from varietas WHERE id= pangan.varietas) as nama_varietas

        from pangan order by id desc")->result();

      }else if($role == 20){
        $query = $this->db->query("select *,
        (select nama_penyuluh from penyuluh_pendamping where id= pangan.penyuluh) as nama_penyuluh,
        (SELECT nama from kabupaten_kota WHERE id= pangan.kabupaten_kota) as nama_kabupaten,
        (SELECT nama from kecamatan WHERE id=pangan.kecamatan) as nama_kecamatan,
        (SELECT nama from kelurahan WHERE id=pangan.kelurahan) as nama_kelurahan,
        (SELECT nama from varietas WHERE id= pangan.varietas) as nama_varietas
        from pangan where kabupaten_kota = '$kotakab' order by id desc")->result();

      }else{
        $query = $this->db->query("select *,
        (select nama_penyuluh from penyuluh_pendamping where id= pangan.penyuluh) as nama_penyuluh,
        (SELECT nama from kabupaten_kota WHERE id= pangan.kabupaten_kota) as nama_kabupaten,
        (SELECT nama from kecamatan WHERE id=pangan.kecamatan) as nama_kecamatan,
        (SELECT nama from kelurahan WHERE id=pangan.kelurahan) as nama_kelurahan,
        (SELECT nama from varietas WHERE id= pangan.varietas) as nama_varietas
        from pangan order by id desc")->result();

      }
      return $query;
    }

    public function verifikasi($params = NULL)
    {
        $valid = false;

            $this->db->set("status",'1');
            $this->db->set("updated_by", $this->session->userdata('id'));
            $this->db->set("update_date", date("Y-m-d H:i:s"));
            $this->db->where('id', $params->id);
            $valid = $this->db->update('pangan');

        return $valid;

    }

}
