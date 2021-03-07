<?php
class Model_auth extends CI_Model {

    function __construct(){
        parent::__construct();
    }

     public function loginAuth($username, $password)
    {
        $valid = false;
        $password = md5($password);

        $check = $this->db->get_where("muser", array("username" => $username,"password" => $password));

            if ($check->num_rows() > 0) {
            $data = $check->row();
                $session = array(
                    'id'          => $data->id,
                    'username'    => $data->username,
                    'kategori'    => $data->kategori,
                    'password'    => $data->password,
                    'kotaKab'     => $data->kotaKab,
                    'role'        => $data->role,
                    'name'        => $data->name,
                    'foto'        => ($data->foto ? $data->foto : 'assets/dokumen/gambar/user/default.jpg'),
                    'notelp'      => $data->no_telp,
                    'email'       => $data->email,
                    'userLogged'  => TRUE
                );

                $valid = TRUE;
                $this->db->set("islogin", '1');
                $this->db->where('id', $data->id);
                $this->db->update('muser');

                $this->session->set_userdata($session);

                $response = (object) array(
                  'valid' => true,
                  'role'  => $data->role,
                );
        }else{
          $response = (object) array(
            'valid' => 0,
            'role'  => 0
          );
        }
        return $response;
    }

    public function regisAuth($username, $password)
   {
       $valid = false;
       $password = md5($password);

       $check = $this->db->get_where("muser", array("username" => $username));
       print_r($check->num_rows());die;
           if ($check->num_rows() > 0) {
           $data = $check->row();
               $session = array(
                   'id' => $data->id,
                   'username' => $data->username,
                   'kategori' => $data->kategori,
                   'password' => $data->password,
                   'kotaKab'   => $data->kotaKab,
                   'role'   => $data->role,
                   'userLogged' => TRUE
               );

               $valid = TRUE;
               $this->db->set("islogin", '1');
               $this->db->where('id', $data->id);
               $this->db->update('muser');

               $this->session->set_userdata($session);

               $response = (object) array(
                 'valid' => true,
                 'role'  => $data->role,
               );
       }else{
         $response = (object) array(
           'valid' => false,
           'role'  => 0
         );
       }
       return $response;
   }

    public function updateislogin($id){

      $valid = TRUE;
      $this->db->set("islogin", '0');
      $this->db->where('id', $id);
      $this->db->update('muser');
      return $valid;
    }

    public function cekUname($username){

      $check = $this->db->get_where("muser", array("username" => $username));
      return $check->num_rows();
    }



}
