<?php
    if(!defined('BASEPATH'))
        exit('No direct script access allowed');
    
    class PedidosPontos extends CI_Model{
        
        //public function getAllPontos_($id, $situacao, $favoritos, $email, $consumoMin, $consumoMax, $dataMin, $dataMax){
        public function getAllPontos_($id_pais, $id_tipo, $nome){
            $this->db->select('*');
            $this->db->from('pontos');
            
            if($id_tipo != 0){
                $this->db->where('dialetos.id_tipo', $id_tipo);
            }
            $this->db->join('dialetos', 'pontos.id_dialeto = dialetos.id_dialeto', 'inner');
            $this->db->join('tipos', 'dialetos.id_tipo = tipos.id_tipo', 'inner');
            $this->db->join('paises', 'dialetos.id_pais = paises.id_pais', 'inner');
            
            if($id_pais!=0)
                $this->db->where('paises.id_pais', $id_pais);
              
            if($nome != "")
                //$this->db->like('dialetos.designacao_dialeto', $nome);
                $this->db->like('dialetos.designacao_dialeto', urldecode($nome)); 
            
            $query = $this->db->get();
            //echo $this->db->last_query()."<br><br><br>";
            return $query;
        }
        
        public function regista_Ponto($contador, $descricao, $lon, $lat){
            $this->db->insert('contadores', $contador);
            if($this->db->affected_rows()!=1)
                return false;
            $dados = array(
                'id_contador' => $this->db->insert_id(),
                'descricao' => $descricao,
                'longitude' => $lon,
                'latitude' => $lat
            );
            $this->db->insert('pontos', $dados);
            
            return($this->db->affected_rows() != 1) ? false : true;
        }
        
        public function apaga_Ponto($id){
            $this->db->where('id_contador', $id);
            $this->db->delete('contadores');
            return($this->db->affected_rows() != 1) ? false : true;
        }
        
        public function edita_Ponto($contador, $descricao, $id){
            $this->db->where('id_contador', $id);
            $this->db->update('contadores', $contador);
            $aux = array('descricao' => $descricao);
            $this->db->where('id_contador', $id);
            $this->db->update('pontos', $aux);
        }
        
        public function adiciona_Favorito($id, $email){
            $query = $this->db->get_where('utilizadores', array('email' => $email));
            $row = $query->first_row();
            $data = array(
                'id_utilizador' => $row->id_utilizador,
                'id_ponto' => $id
            );
            $this->db->insert('favorito', $data);
        }
        
        public function verifica_Favorito($id, $email){
            $query = $this->db->get_where('utilizadores', array('email' => $email));
            $row = $query->first_row();
            $query2 = $this->db->get_where('favorito', array('id_utilizador' => $row->id_utilizador, 'id_ponto' => $id));
            if($query2->num_rows())
                return true;
            else
                return false;
        }
        
        public function remove_Favorito($id, $email){
            $query = $this->db->get_where('utilizadores', array('email' => $email));
            $row = $query->first_row();
            $query2 = $this->db->delete('favorito', array('id_utilizador' => $row->id_utilizador, 'id_ponto' => $id));
            if($query2->affected_rows())
                return true;
            else
                return false;
        }
        
        public function descricao_($descricao){
            $this->db->select('*');
            $this->db->from('pontos');
            $this->db->join('contadores', 'pontos.id_contador = contadores.id_contador', 'inner');
            $this->db->join('servicos', 'contadores.id_servico = servicos.id_servico', 'inner');
            $this->db->like('descricao', $descricao, 'both'); 
            $query = $this->db->get();
            
            //echo $this->db->last_query()."<br><br><br>";
            
            return $query;
        }
        
    }