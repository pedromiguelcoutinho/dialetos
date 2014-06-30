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
        if ($verifica) {
            $data = array('email' => $email,'is_logged_in' => 1);
            $this->session->set_userdata($data);
            $json = array('estado' => true);
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
            if ($this->PedidosUtilizadores->faz_Registo($email, $password)) {
                $json = array('estado' => true);
                echo json_encode($json);
            } else {
                $json = array('estado' => false);
                echo json_encode($json);
            }
        }
    }

    public function getAcessos() {
        $email = $this->session->userdata('email');
        $this->load->model('pedidosUtilizadores');
        $json = $this->pedidosUtilizadores->get_Acessos($email);
        echo $json;
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

    public function atribuiAcessos($email, $agua, $luz, $gas) {
        $this->load->model('pedidosUtilizadores');
        $existe = $this->pedidosUtilizadores->emailExiste($email);
        if ($existe) {
            $this->pedidosUtilizadores->atribui_Acessos($email, $agua, $luz, $gas);
            $json = array('estado' => true);
        } else {
            $json = array('estado' => false);
        }
        echo json_encode($json);
    }

}
