<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model {

   function validar($login, $senha) {

   		$this->db->select('id, status, nome');
   		$this->db->from('usuarios');
   		$this->db->where('login', $login);
   		$this->db->where('senha', $senha);

		return $this->db->get()->result();
    }


}