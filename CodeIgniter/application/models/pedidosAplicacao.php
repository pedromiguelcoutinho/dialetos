<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PedidosAplicacao extends CI_Model {

    public function getAllPaises() {
        $this->db->select('*');
        $this->db->from('paises');
        $query = $this->db->get();
        //echo $this->db->last_query()."<br><br><br>";
        return $query;
    }

    public function getAllTipos() {
        $this->db->select('*');
        $this->db->from('tipos');
        $query = $this->db->get();
        //echo $this->db->last_query()."<br><br><br>";
        return $query;
    }
}
