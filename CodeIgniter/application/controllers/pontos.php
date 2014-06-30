<?php 
    header("Content-type: text/html; charset=UTF-8"); 
    if(!defined('BASEPATH'))
        exit('No direct script access allowed');
    
    class Pontos extends CI_Controller{
        public function index(){
            
        }
             
        //public function getAllPontos($id, $situacao, $favoritos, $consumoMin, $consumoMax, $dataMin, $dataMax){
        public function getAllPontos($id_Pais, $id_Tipo, $nome="") {
           // echo "nome=".$nome."<br>";
           // echo "nomeentities=".htmlentities($nome)."<br>";
           // echo "nomeurldecode=".urldecode($nome)."<br>";
            
            
            
           
            $this->load->model('pedidosPontos');
            $email = $this->session->userdata('email');
            $query = $this->pedidosPontos->getAllPontos_($id_Pais, $id_Tipo, $nome);
            $linhas = $query->num_rows();
            if($linhas>0){
                $row = $query->first_row();
                $geoJSON = array('type' => 'FeatureCollection', 'features' => array());
                for($i=1 ; $i<=$linhas ; $i++){
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
                                    'id_ponto' => "".$row->id_ponto,
                                    'id_tipo' => "".$row->id_tipo,
                                    'id_dialeto' => "".$row->id_dialeto,
                                    'tipo' => "".$row->designacao_tipo,
                                    'pais' => "".$row->designacao_pais,
                                    'imagem' => "".$row->imagem_dialeto,
                                    'link' => "".$row->link_dialeto,
                                    'descricao' => "".$row->descricao_dialeto,
                                    'designacao' => "".$row->designacao_dialeto
                                )
                            )
                    );
                    array_push($geoJSON['features'], $linha['features']);
                    $row = $query->next_row();
                }
                echo json_encode($geoJSON);
            }
        }
        
        public function registaPonto(){
            $servico = $this->input->post('servico');
            $situacao = $this->input->post('situacao');
            $consumo = $this->input->post('consumo');
            $data = $this->input->post('data');
            $descricao = $this->input->post('descricao');
            $lon = $this->input->post('longitude');
            $lat = $this->input->post('latitude');
            
            $this->load->model('pedidosPontos');
            $contador = array(
                'id_servico' => $servico,
                'situacao' => $situacao,
                'consumo' => $consumo,
                'data_instalacao' => $data
            );
            $resultado = $this->pedidosPontos->regista_Ponto($contador, $descricao, $lat, $lon);
            $json = array(
                'estado' => $resultado
            );
            echo json_encode($json);
        }
        
        public function apagaPonto($id){
            $this->load->model('pedidosPontos');
            $resultado = $this->pedidosPontos->apaga_Ponto($id);
            $json = array(
                'estado' => $resultado
            );
            echo json_encode($json);
        }
        
        public function editaPonto(){
            $id = $this->input->post('id');
            $situacao = $this->input->post('situacao');
            $descricao = $this->input->post('descricao');
            $consumo = $this->input->post('consumo');
            
            $this->load->model('pedidosPontos');
            $contador = array(
                'situacao' => $situacao,
                'consumo' => $consumo,
            );
            $this->pedidosPontos->edita_Ponto($contador, $descricao, $id);
        }
        
        public function adicionaFavorito($id){
            $this->load->model('pedidosPontos');
            $email = $this->session->userdata('email');
            $this->pedidosPontos->adiciona_Favorito($id, $email);
        }
        
        public function verificaFavorito($id){
            $this->load->model('pedidosPontos');
            $email = $this->session->userdata('email');
            $resultado = $this->pedidosPontos->verifica_Favorito($id, $email);
            $json = array('estado' => $resultado);
            echo json_encode($json);
        }
        
        public function removeFavorito($id){
            $this->load->model('pedidosPontos');
            $email = $this->session->userdata('email');
            $resultado = $this->pedidosPontos->remove_Favorito($id, $email);
            $json = array('estado' => $resultado);
            echo json_encode($json);
        }
        
        public function descricao($descricao){
            $this->load->model('pedidosPontos');
            $query = $this->pedidosPontos->descricao_($descricao);
            $linhas = $query->num_rows();
            if($linhas>0){
                $row = $query->first_row();
                $geoJSON = array('type' => 'FeatureCollection', 'features' => array());
                for($i=1 ; $i<=$linhas ; $i++){
                    $linha = array(
                            'type' => 'Feature',
                            'features' => array(
                                'type' => 'Feature',
                                "geometry" => array(
                                    'type' => 'Point',
                                    'coordinates' => array(
                                                    floatval($row->latitude),
                                                    floatval($row->longitude)
                                    )
                                ),
                                'properties' => array(
                                    'id_ponto' => "".$row->id_ponto,
                                    'id_contador' => "".$row->id_contador,
                                    'id_servico' => "".$row->id_servico,
                                    'servico' => "".$row->nome,
                                    'situacao' => "".$row->situacao,
                                    'consumo' => "".$row->consumo,
                                    'data_instalacao' => "".$row->data_instalacao,
                                    'descricao' => "".$row->descricao
                                )
                            )
                    );
                    array_push($geoJSON['features'], $linha['features']);
                    $row = $query->next_row();
                }
                echo json_encode($geoJSON);
            }
        }
        
    }