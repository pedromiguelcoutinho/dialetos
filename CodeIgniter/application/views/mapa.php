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
                        <li><button class="navbar-btn btn btn-success" id="atualizaPontos"><span class="glyphicon glyphicon-refresh"></span></button></li>
                        <li>&nbsp;&nbsp;</li>
                        <li><button class="navbar-btn btn btn-success" id="criarDialeto" style="display: none">Criar Diateto</button></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><button id="instrucoes" class="pull-left navbar-btn btn btn-info" data-toggle="modal" data-target="#modalAjuda">Ajuda</button></li>
                        <li>&nbsp;&nbsp;</li>
                        <li><button style="display: none" id="regista" class='pull-right navbar-btn btn btn-info' data-toggle='modal' data-target='#modalRegisto'>Registar</button></li>
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
                        <h3 class="modal-title">Ajuda</h3>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <h4>Pontos de Dialetos no mapa</h4>
                            <p>Para um determinado dialeto é possível:</p>
                            <ul>
                                <li><p>Criar Pontos no mapa (após login de administração)</p></li>
                                <li><p>Editar Pontos no mapa (após login de administração)</p></li>
                                <li><p>Remover Pontos do mapa (após login de administração)</p></li>
                                <li><p>Visualizar Informação detalhada sobre o ponto/dialeto</p></li>
                            </ul>
                            <h4>Filtros de Dialetos</h4>
                            <p>Existem vários filtros para mostrar apenas alguns dialetos, nomeadamente:</p>
                            <ul>
                                <li><p>Filtro por país lusófono: mostra apenas os dialetos dos países selecionados.</p></li>
                                <li><p>Filtro por tipo de dialeto: ex. falado, escrito, etc.</p></li>
                                <li><p>Filtro por nome de dialeto, permitindo mostrar os dialetos que tenham <br/>a palavra de procura.</p></li>
                            </ul>
                            <p></p>
                            <h4>Utilizador</h4>
                            <p>O Utilizador visualizar os pontos sobre os dialetos e pode aplicar os filtros disponíveis.</p>
                            <h4>Administrador</h4>
                            <p>O Administrador pode gerir os pontos e a informação sobre os dialetos a eles associado.<br/>
                                O super-administrador pode criar outros administradores (normais) para gerirem os <br/>pontos no mapa e respetivos dialetos.</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <b class="pull-left">GSI-UM HC</b>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalRegisto" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Efetuar Registo</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container" id="formContainer">
                            <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-horizontal" role="form" id="myForm">
                                <div class="form-group text-center">
                                    <label for="inputEmailRegisto" class="col-sm-2 control-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="inputEmailRegisto" name="email" size="75" placeholder="Email" required autofocus style="width: 300px;">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPasswordRegisto" class="col-sm-2 control-label">Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" name="password" size="50" id="inputPasswordRegisto" placeholder="Password" required autofocus style="width: 300px;">
                                    </div>
                                </div>
                                <p id="errorRegisto" style="width: 40%; margin-top: 10px;"></p>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                        <button class="btn btn-primary" id="fazRegisto">Registar</button>
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
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                        <button class="btn btn-primary" id="fazlogin">Login</button>
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
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
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
                        <h4 class="modal-title" id="myModalLabel">Criar Novo Dialeto</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container" id="formContainer">
                            <form id="formRegistaPonto" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-horizontal" role="form">
                                <div class="form-group text-center">
                                    <label for="inputDesignacao" class="col-sm-1 control-label">Designação</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputDesignacao" name="inputDesignacao" size="75" placeholder="Designação do Dialeto" required autofocus style="width: 200px;">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label">Latitude</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputLatitude" name="inputLatitude" size="75" placeholder="Latitude" readonly autofocus style="width: 200px;">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label">Longitude</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputLongitude" name="inputLongitude" size="75" placeholder="Longitude" readonly autofocus style="width: 200px;">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputPais" class="col-sm-1 control-label">País</label>
                                    <div class="col-sm-10">
                                        <select id="inputPais" name="inputPais" class="form-control paisesPonto" required style="width: 200px;"></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputTipo" class="col-sm-1 control-label">Tipo</label>
                                    <div class="col-sm-10">
                                        <select id="inputTipo" name="inputTipo" class="form-control tiposPonto" required style="width: 200px;"></select>
                                    </div>
                                </div>
                                <div class="form-group text-ccenter">
                                    <label for="inputDescricao" class="col-sm-1 control-label">Descrição</label>
                                    <div class="col-sm-10">
                                        <textarea rows="5" cols="70" id="inputDescricao" name="inputDescricao" required placeholder="Descrição do dialeto"></textarea>
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <label for="inputLink" class="col-sm-1 control-label">Link(URL)</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputLink" name="inputLink" size="75" placeholder="URL sobre dialeto (http://...)" autofocus style="width: 444px;">
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <label for="inputImagem" class="col-sm-1 control-label">Imagem(URL)</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputImagem" name="inputImagem" size="75" placeholder="URL da imagem (http://...)" autofocus style="width: 444px;">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
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
                        <h4 class="modal-title" id="myModalLabel">Editar dialeto</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container" id="formContainer">
                            <form id="formEditaPonto" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-horizontal" role="form">
                                <input type="hidden" id="inputIdDialetoEdit" name="inputIdDialetoEdit"/>
                                <div class="form-group text-center">
                                    <label for="inputDesignacaoEdit" class="col-sm-1 control-label">Designação</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputDesignacaoEdit" name="inputDesignacaoEdit" size="75" placeholder="Designação do Dialeto" required autofocus style="width: 200px;">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPaisEdit" class="col-sm-1 control-label">País</label>
                                    <div class="col-sm-10">
                                        <select id="inputPaisEdit" name="inputPaisEdit" class="form-control paisesPonto" required style="width: 200px;"></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputTipoEdit" class="col-sm-1 control-label">Tipo</label>
                                    <div class="col-sm-10">
                                        <select id="inputTipoEdit" name="inputTipoEdit" class="form-control tiposPonto" required style="width: 200px;"></select>
                                    </div>
                                </div>
                                <div class="form-group text-ccenter">
                                    <label for="inputDescricaoEdit" class="col-sm-1 control-label">Descrição</label>
                                    <div class="col-sm-10">
                                        <textarea rows="5" cols="70" id="inputDescricaoEdit" name="inputDescricaoEdit" required placeholder="Descrição do dialeto"></textarea>
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <label for="inputLinkEdit" class="col-sm-1 control-label">Link(URL)</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputLinkEdit" name="inputLinkEdit" size="75" placeholder="URL sobre dialeto (http://...)" autofocus style="width: 444px;">
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <label for="inputImagemEdit" class="col-sm-1 control-label">Imagem(URL)</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputImagemEdit" name="inputImagemEdit" size="75" placeholder="URL da imagem (http://...)" autofocus style="width: 444px;">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
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