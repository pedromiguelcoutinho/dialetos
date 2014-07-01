<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main extends CI_Controller {

    public function index() {
        $this->mapa();
    }

    public function mapa() {
        $this->load->view('mapa');
    }

    public function getPaises() {
        $this->load->model('PedidosAplicacao');
        $query = $this->PedidosAplicacao->getAllPaises();
        $linhas = $query->num_rows();
        $paisesJSON = array();
        if ($linhas > 0) {
            foreach ($query->result() as $row) {
                $pais = array(
                    'id' => $row->id_pais,
                    'designacao' => "" . $row->designacao_pais
                );
                array_push($paisesJSON, $pais);
            }
        }
        echo json_encode($paisesJSON);
    }
    
    public function getTipos() {
        $this->load->model('PedidosAplicacao');
        $query = $this->PedidosAplicacao->getAllTipos();
        $linhas = $query->num_rows();
        $tiposJSON = array();
        if ($linhas > 0) {
            foreach ($query->result() as $row) {
                $tipo = array(
                    'id' => $row->id_tipo,
                    'designacao' => "" . $row->designacao_tipo
                );
                array_push($tiposJSON, $tipo);
            }
        }
        echo json_encode($tiposJSON);
    }
    
    public function verificaLogin() {
        $this->load->model('PedidosUtilizadores');
        $json = $this->PedidosUtilizadores->verificaLogin();
        echo $json;
    }
    
    public function fazLogin($email, $password) {
        $this->load->model('PedidosUtilizadores');
        $verifica = $this->PedidosUtilizadores->fazLogin($email, $password);
        if($verifica['estado']) {
            $data = array('email' => $email,'is_logged_in' => 1, 'tipo' => $verifica['tipo']);
            $this->session->set_userdata($data);
            $json = array('estado' => true, 'tipo' => $verifica['tipo']);
            echo json_encode($json);
        } else {
            $json = array('estado' => false);
            echo json_encode($json);
        }
    }
    
    public function fazLogout() {
        $this->session->sess_destroy();
        $json = array('estado' => true);
        echo json_encode($json);
    }

    public function fazRegisto($email, $password) {
        $this->load->model('PedidosUtilizadores');
        if ($this->PedidosUtilizadores->emailExiste($email)) {
            $json = array('estado' => false);
            echo json_encode($json);
        } else {
            if ($this->PedidosUtilizadores->fazRegisto($email, $password)) {
                $json = array('estado' => true);
                echo json_encode($json);
            } else {
                $json = array('estado' => false);
                echo json_encode($json);
            }
        }
    }
    
    public function recuperaPassword($email) {
        $this->load->model('PedidosUtilizadores');
        if ($this->PedidosUtilizadores->enviaPassword($email)) {
            $json = array('estado' => true);
            echo json_encode($json);
        } else {
            $json = array('estado' => false);
            echo json_encode($json);
        }
    }
}
