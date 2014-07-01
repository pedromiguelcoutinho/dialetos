<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PedidosPontos extends CI_Model {

    public function getAllPontos_($id_pais, $id_tipo, $nome) {
        $this->db->select('*');
        $this->db->from('pontos');
        if ($id_tipo != 0) {
            $this->db->where('dialetos.id_tipo', $id_tipo);
        }
        $this->db->join('dialetos', 'pontos.id_dialeto = dialetos.id_dialeto', 'inner');
        $this->db->join('tipos', 'dialetos.id_tipo = tipos.id_tipo', 'inner');
        $this->db->join('paises', 'dialetos.id_pais = paises.id_pais', 'inner');
        if ($id_pais != 0)
            $this->db->where('paises.id_pais', $id_pais);
        if ($nome != "")
            $this->db->like('dialetos.designacao_dialeto', urldecode($nome));
        $query = $this->db->get();
        //echo $this->db->last_query()."<br><br><br>";
        return $query;
    }

    public function registaPonto($dadosDialeto, $dadosPonto) {
        $this->db->insert('dialetos', $dadosDialeto);
        if ($this->db->affected_rows() != 1)
            return false;
        $dadosPonto['id_dialeto'] = $this->db->insert_id();
        $this->db->insert('pontos', $dadosPonto);
        return($this->db->affected_rows() != 1) ? false : true;
    }

    public function apagaPonto($idDialeto) {
        $this->db->where('id_dialeto', $idDialeto);
        $this->db->delete('dialetos');
        return($this->db->affected_rows() != 1) ? false : true;
    }

    public function editaPonto($idDialeto, $dadosDialeto) {
        $this->db->where('id_dialeto', $idDialeto);
        $this->db->update('dialetos', $dadosDialeto);
        return($this->db->affected_rows() != 1) ? false : true;
    }
}