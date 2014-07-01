<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PedidosUtilizadores extends CI_Model {

    public function index() {
        
    }

    public function verificaLogin() {
        $estado = true;
        $email = null;
        $user = $this->session->userdata('is_logged_in');
        if (!$user)
            $estado = false;
        else
            $email = $this->session->userdata('email');
        $this->db->select('*');
        $this->db->from('utilizadores');
        $this->db->where('utilizadores.email', $email);
        $query = $this->db->get();
        $row = $query->first_row();
        $logJSON = array();
        if ($query->num_rows())
            array_push($logJSON, array('estado' => "1", 'email' => $email, 'tipo' => $row->tipo));
        else
            array_push($logJSON, array('estado' => "0", 'email' => $email, 'tipo' => ''));
        return json_encode($logJSON);
    }

    public function fazLogin($email, $password) {
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        $query = $this->db->get('utilizadores');
        $logJSON = array();
        if ($query->num_rows()) {
            $row = $query->first_row();
            $logJSON = array('estado' => "1", 'email' => $email, 'tipo' => $row->tipo);
        } else {
            $logJSON = array('estado' => "0", 'email' => $email, 'tipo' => '');
        }
        return $logJSON;
    }

    public function enviaPassword($email) {
        $this->db->select('password');
        $this->db->from('utilizadores');
        $this->db->where('email', $email);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return false;
        }
        $row = $query->first_row();
        $password = $row->password;
        $ai = $this->enviaEmail($email, $password);
        if ($this->enviaEmail($email, $password)) {
            return true;
        } else {
            return false;
        }
    }

    public function enviaEmail($email, $password) {
        error_reporting(E_PARSE);
        $this->load->library('phpmailer');
        $this->phpmailer->IsSMTP();
        $this->phpmailer->CharSet = "UTF-8";
        $this->phpmailer->Host = "smtp.live.com";
        $this->phpmailer->Port = 25;
        $this->phpmailer->SMTPSecure = 'tls';
        $this->phpmailer->SMTPAuth = true;
        $this->phpmailer->Username = 'dadadinhacorreia@hotmail.com';
        $this->phpmailer->Password = 'PASSWORD AQUI';
        $this->phpmailer->From = "dadadinhacorreia@hotmail.com";
        $this->phpmailer->FromName = "HC";
        $this->phpmailer->SMTPDebug = 1;
        $this->phpmailer->AddAddress($email);
        $this->phpmailer->IsHTML(true);
        $this->phpmailer->Subject = "Recuperação de passowrd";
        $this->phpmailer->Body = "A sua password é: <b>" . $password . "</b>";
        if ($this->phpmailer->Send()) {
            $this->phpmailer->ClearAllRecipients();
            return true;
        } else {
            $this->phpmailer->ClearAllRecipients();
            return false;
        }
    }

    public function emailExiste($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('utilizadores');
        if ($query->num_rows() == 1)
            return true;
        else
            return false;
    }

    public function fazRegisto($email, $password) {
        $data = array(
            'email' => $email,
            'password' => $password,
            'tipo' => 'normal'
        );
        $query = $this->db->insert('utilizadores', $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

}
