<?php

header("Content-type: text/html; charset=UTF-8");
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pontos extends CI_Controller {

    public function index() {    
    }
    
    public function getAllPontos($id_Pais, $id_Tipo, $nome = "") {
        $this->load->model('pedidosPontos');
        $email = $this->session->userdata('email');
        $query = $this->pedidosPontos->getAllPontos_($id_Pais, $id_Tipo, $nome);
        $linhas = $query->num_rows();
        if ($linhas > 0) {
            $row = $query->first_row();
            $geoJSON = array('type' => 'FeatureCollection', 'features' => array());
            for ($i = 1; $i <= $linhas; $i++) {
                $linha = array(
                    'type' => 'Feature',
                    'features' => array(
                        'type' => 'Feature',
                        "geometry" => array(
                            'type' => 'Point',
                            'coordinates' => array(
                                floatval($row->longitude),
                                floatval($row->latitude)
                            )
                        ),
                        'properties' => array(
                            'id_ponto' => "" . $row->id_ponto,
                            'id_dialeto' => "" . $row->id_dialeto,
                            'id_tipo' => "" . $row->id_tipo,
                            'tipo' => "" . $row->designacao_tipo,
                            'id_pais' => "" . $row->id_pais,
                            'pais' => "" . $row->designacao_pais,
                            'designacao' => "" . $row->designacao_dialeto,
                            'descricao' => "" . $row->descricao_dialeto,
                            'link' => "" . $row->link_dialeto,
                            'imagem' => "" . $row->imagem_dialeto
                        )
                    )
                );
                array_push($geoJSON['features'], $linha['features']);
                $row = $query->next_row();
            }
            echo json_encode($geoJSON);
        }
    }

    public function registaPonto() {
        $dadosDialeto = array(
            'id_tipo' => $this->input->post('inputTipo'),
            'id_pais' => $this->input->post('inputPais'),
            'designacao_dialeto' => $this->input->post('inputDesignacao'),
            'descricao_dialeto' => $this->input->post('inputDescricao'),
            'link_dialeto' => $this->input->post('inputLink'),
            'imagem_dialeto' => $this->input->post('inputImagem')
        );
        $dadosPonto = array(
            'longitude' => $this->input->post('inputLongitude'),
            'latitude' => $this->input->post('inputLatitude')
        );
        $this->load->model('pedidosPontos');
        $resultado = $this->pedidosPontos->registaPonto($dadosDialeto, $dadosPonto);
        $json = array(
            'estado' => $resultado
        );
        echo json_encode($json);
    }

    public function apagaPonto($idDialeto) {
        $this->load->model('pedidosPontos');
        $resultado = $this->pedidosPontos->apagaPonto($idDialeto);
        $json = array(
            'estado' => $resultado
        );
        echo json_encode($json);
    }

    public function editaPonto() {
        $idDialeto = $this->input->post('inputIdDialeto');
        $dadosDialeto = array(
            'id_tipo' => $this->input->post('inputTipo'),
            'id_pais' => $this->input->post('inputPais'),
            'designacao_dialeto' => $this->input->post('inputDesignacao'),
            'descricao_dialeto' => $this->input->post('inputDescricao'),
            'link_dialeto' => $this->input->post('inputLink'),
            'imagem_dialeto' => $this->input->post('inputImagem')
        );
        $this->load->model('pedidosPontos');
        $resultado = $this->pedidosPontos->editaPonto($idDialeto, $dadosDialeto);
        $json = array(
            'estado' => $resultado
        );
        echo json_encode($json);
    }
}
