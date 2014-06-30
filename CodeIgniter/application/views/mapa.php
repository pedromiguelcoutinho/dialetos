<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dialetos da Lusofonia</title>
        <link href="<?php echo (CSS . 'bootstrap.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo (CSS . 'styles.css'); ?>" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo (OP . 'theme/default/style.css'); ?>" type="text/css">

    </head>

    <body style="padding-top: 50px;" onload="init()">
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menuCima">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <div class="collapse navbar-collapse" id="menuCima">
                    <ul class="nav navbar-nav">
                        <li><button class="navbar-btn btn btn-success" id="ondeEstou">Onde estou?</button></li>
                        <li>&nbsp;&nbsp;</li>
                        <li class="dropdown">
                            <button class="navbar-btn btn btn-primary dropdown-toggle" data-toggle="dropdown">País: <span id="selecao_pais"></span>&nbsp;&nbsp;<b class="caret"></b></button>
                            <ul class="dropdown-menu" role="menu" id="menu_paises"></ul>
                        </li>
                        <li>&nbsp;&nbsp;</li>
                        <li class="dropdown">
                            <button class="navbar-btn btn btn-primary dropdown-toggle" data-toggle="dropdown">Tipo de Dialeto: <span id="selecao_tipo"></span>&nbsp;&nbsp;<b class="caret"></b></button>
                            <ul class="dropdown-menu" role="menu" id="menu_tipos"></ul>
                        </li>
                        <li>&nbsp;&nbsp;</li>
                        <li><input type="text" placeholder="Nome do Dialeto" id="nomeProcurar" style="margin-top: 4.5%;" class="form-control"></li>
                        <li>&nbsp;<button id="procuraNome" class="navbar-btn btn btn-primary"><span class="glyphicon glyphicon-search"></span></button></li>
                        <li>&nbsp;&nbsp;</li>
                        <li><button class="navbar-btn btn btn-success" id="limpaFiltros">Limpa Filtros</button></li>
                        <li>&nbsp;&nbsp;</li>
                        <li><button class="navbar-btn btn btn-success" id="criarDialeto" style="display: none">Criar Diateto</button></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><button id="instrucoes" class="pull-left navbar-btn btn btn-info" data-toggle="modal" data-target="#modalAjuda">Ajuda</button></li>
                        <li>&nbsp;&nbsp;&nbsp;&nbsp;</li>
                        <li><button style="margin-right:15%; display: none;" id='login' class='pull-right navbar-btn btn btn-info' data-toggle='modal' data-target='#modalLogin'>Login</button></li>
                        <li><button style="margin-right:15%; display: none;" id="logout" class="pull-right navbar-btn btn btn-info">Logout</button></li>
                    </ul>
                </div>
            </div>
        </nav>       

        <div class="container" id="mapa" style="width: 100%; height: 100%; "></div>

        <div class="modal fade" id="modalAjuda" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Ajuda</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <h4>Pontos de Dialetos no mapa</h4>
                            <p>Para um determinado dialeto é possível:</p>
                            <ul>
                                <li><p>Criar Pontos no mapa</p></li>
                                <li><p>Editar Pontos no mapa</p></li>
                                <li><p>Remover Pontos do mapa</p></li>
                                <li><p>Visualizar Informação detalhada sobre o ponto/dialeto</p></li>
                            </ul>
                            <h4>Filtros de Dialetos</h4>
                            <p>Existem vários filtros para mostrar apenas alguns dialetos, nomeadamente:</p>
                            <ul>
                                <li><p>Filtro por país lusófono: mostra apenas os dialetos dos países selecionados.</p></li>
                                <li><p>Filtro por tipo de dialeto: falado, escrito ou ambos.</p></li>
                                <li><p>Filtro por nome de dialeto, permitindo mostrar os dialetos que tenham a palavra de procura.</p></li>
                                <li><p>Visualizar Informação detalhada sobre o ponto/dialeto</p></li>
                            </ul>
                            <p></p>
                            <h4>Utilizador</h4>
                            <p>O Utilizador visualizar os pontos sobre os dialetos e pode aplicar os filtros disponíveis.</p>
                            <h4>Administrador</h4>
                            <p>O Administrador pode gerir os pontos e a informação sobre os dialetos a eles associado.</p>
                        </div> <!-- /container -->
                    </div>
                    <div class="modal-footer">
                        <b class="pull-left">GSI-UM HC</b>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalLogin" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Efetuar Login</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container" id="formContainer">
                            <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-horizontal" role="form" id="myForm">
                                <div class="form-group text-center">
                                    <label for="inputEmail" class="col-sm-2 control-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="inputEmail" name="email" size="75" placeholder="Email" required autofocus style="width: 300px;">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword" class="col-sm-2 control-label">Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" name="password" size="50" id="inputPassword" placeholder="Password" required autofocus style="width: 300px;">
                                    </div>
                                </div>
                                <a href="index.php#modalPW" data-toggle="modal" data-target="#modalPW">Esqueceu a sua password?</a>
                                <p id="erroLogin" style="width: 40%; margin-top: 10px;"></p>
                            </form>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-info" id="fazlogin">Login</button>
                        <button class="btn btn-info" id="regista">Registar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalPW" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Esqueceu a sua Password?</h4>
                    </div>

                    <div class="modal-body">
                        <div class="container" id="formContainer">
                            <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-horizontal" role="form">
                                <div class="form-group text-center">
                                    <label for="inputEmailRecupera" class="col-sm-2 control-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="inputEmailRecupera" name="email" size="75" placeholder="Email" required autofocus style="width: 300px;">
                                    </div>
                                </div>
                            </form>
                            <p id="erroPW" style="width: 40%; margin-top: 10px;"></p>
                        </div> <!-- /container -->
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" id="recupera">Recuperar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="registaPonto" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Registe o Dialeto</h4>
                    </div>

                    <div class="modal-body">
                        <div class="container" id="formContainer">
                            <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-horizontal" role="form">
                                <div class="col-md-6">
                                    <label class="col-sm-2 control-label">Latitude: </label><span id="latitude"></span><br/>
                                    <label class="col-sm-2 control-label">Longitude: </label><span id="longitude"></span>
                                </div>    

                                <div class="col-md-2">
                                    <select class="form-control paisesPonto" data-width="100%"></select>
                                    <select class="form-control tiposPonto"></select>

                                </div>

                                <div class="form-group text-ccenter">
                                    <label class="col-sm-2 control-label">Dialeto</label>
                                    <div class="col-sm-10">
                                        <input type="radio" name="servico" id="inputAgua" value="inputAgua" checked>&nbsp;Água&nbsp;
                                        <input type="radio" name="servico" id="inputLuz" value="inputLuz">&nbsp;Luz&nbsp;
                                        <input type="radio" name="servico" id="inputGas" value="inputGas">&nbsp;Gás
                                    </div>
                                </div>
                                <div class="form-group text-ccenter">
                                    <label for="situacao" class="col-sm-2 control-label">Situação</label>
                                    <div class="col-sm-10">
                                        <input type="radio" name="situacao" id="inputLigado" value="inputLigado" checked>&nbsp;Ligado&nbsp;
                                        <input type="radio" name="situacao" id="inputDesligado" value="inputDesligado">&nbsp;Desligado&nbsp;
                                        <input type="radio" name="situacao" id="inputAvariado" value="inputAvariado">&nbsp;Avariado&nbsp;
                                        <input type="radio" name="situacao" id="inputContagem" value="inputContagem">&nbsp;Em contagem
                                    </div>
                                </div>
                                <div class="form-group text-ccenter">
                                    <label for="inputConsumo" class="col-sm-2 control-label">Consumo</label>
                                    <div class="col-sm-10">
                                        <input type="number" name="" id="inputConsumo" required>
                                    </div>
                                </div>
                                <div class="form-group text-ccenter">
                                    <label for="inputData" class="col-sm-2 control-label">Data Instalação</label>
                                    <div class="col-sm-10">
                                        <input type="date" id="inputData" required>
                                    </div>
                                </div>
                                <div class="form-group text-ccenter">
                                    <label for="inputDescricao" class="col-sm-2 control-label">Descrição</label>
                                    <div class="col-sm-10">
                                        <textarea rows="2" cols="50" id="inputDescricao" required></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" id="registarPonto">Registar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editaPonto" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Edite o Contador</h4>
                        <p id="lonlat"></p>
                    </div>

                    <div class="modal-body">
                        <div class="container" id="formContainer">
                            <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-horizontal" role="form">
                                <div class="form-group text-ccenter">
                                    <label for="situacao" class="col-sm-2 control-label">Situação</label>
                                    <div class="col-sm-10">
                                        <input type="radio" name="situacao" id="inputLigadoEdit" value="inputLigado" checked>&nbsp;Ligado&nbsp;
                                        <input type="radio" name="situacao" id="inputDesligadoEdit" value="inputDesligado">&nbsp;Desligado&nbsp;
                                        <input type="radio" name="situacao" id="inputAvariadoEdit" value="inputAvariado">&nbsp;Avariado&nbsp;
                                        <input type="radio" name="situacao" id="inputContagemEdit" value="inputContagem">&nbsp;Em contagem
                                    </div>
                                </div>
                                <div class="form-group text-ccenter">
                                    <label for="inputConsumo" class="col-sm-2 control-label">Consumo</label>
                                    <div class="col-sm-10">
                                        <input type="number" id="inputConsumoEdit" required>
                                    </div>
                                </div>
                                <div class="form-group text-ccenter">
                                    <label for="inputDescricaoEdit" class="col-sm-2 control-label">Descrição</label>
                                    <div class="col-sm-10">
                                        <textarea rows="2" cols="50" id="inputDescricaoEdit" required></textarea>
                                    </div>
                                </div>
                                <p id="erroEdit" style="width: 40%; margin-top: 10px;"></p>
                            </form>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" id="editarPonto">Editar</button>
                    </div>
                </div>
            </div>
        </div>

    </body>
    <script src="<?php echo (JS . 'jQuery-2.1.0.min.js'); ?>"></script>
    <script src="<?php echo (JS . 'bootstrap.js'); ?>"></script>
    <script src="http://maps.google.com/maps/api/js?v=3&amp;sensor=false"></script>
    <script src="<?php echo (OP . 'OpenLayers.js'); ?>"></script>
    <script src="<?php echo (JS . 'dialetos.js'); ?>" type="text/javascript"></script>
</html>