// API key for http://openlayers.org. Please get your own at http://bingmapsportal.com/ and use that instead.
var apiKey = "Aj5xvmpos4E-Zb3hMrW6xHZckz2Q3_-babJESX3DtfZmuI07q9el6KO5iY53G09O";
var map, layer;
var lon, lat;
var clickfeito = false;
var acessos = new Array();
var tipoFinal = null;
var estadoFavoritos = 0;
var temAcessos = false;
var texto;
var vector, vectore, utilizador, estado;
var desenhaPonto;
var criarPonto = false;
var descricaoFinal, consumoFinal, situacaoFinal, idFinal, idPontoFinal;
var featureglobal;
var style = {
    fillColor: '#000',
    fillOpacity: 0.1,
    strokeWidth: 0
};

/*$('.navbar li').click(function(e) {
 alert("novo");
 $('.navbar li.active').removeClass('active');
 var $this = $(this);
 if (!$this.hasClass('active')) {
 $this.addClass('active');
 }
 e.preventDefault();
 });*/


function atualizaMapa() {
    vectore.protocol.options.url = "../CodeIgniter/pontos/getAllPontos/" + $("#selecao_pais").val() + "/" + $("#selecao_tipo").val() + "/" + $("#nomeProcurar").val();
    vectore.refresh();
}

function limpaFiltros(limpaNome) {
    $("#selecao_pais").text("Todos");
    $("#selecao_pais").val("0");
    $('#menu_paises > li').removeClass('active');
    $('#menu_paises > li').last().addClass('active');

    $("#selecao_tipo").text("Todos");
    $("#selecao_tipo").val("0");
    $('#menu_tipos > li').removeClass('active');
    $('#menu_tipos > li').last().addClass('active');

    if (limpaNome)
        $("#nomeProcurar").val("");
}

function init() {
    var idPais = 0, idTipo = "todos", numFavoritos = 0, consumoMin = 0, consumoMax = 0, dataMin = 0, dataMax = 0;

    $("#ondeEstou").click(function() {
        vector.removeAllFeatures();
        geolocate.deactivate();
        geolocate.watch = false;
        firstGeolocation = true;
        geolocate.activate();
    });

    $(document).ready(function preenchePaises() {
        $.getJSON("../CodeIgniter/main/getPaises", function(paises) {
            var paisesHtml = "";
            var paisesOption = "";
            for (var i = 0; i < paises.length; i++) {
                paisesHtml += '<li class="dropdown-menu-item" role="presentation"><a id="' + paises[i].id + '" href="#">' + paises[i].designacao + '</a></li>';
                paisesOption += '<option value="'+paises[i].id+'">'+paises[i].designacao+'</option>';
            }
            paisesHtml += '<li class="dropdown-menu-item active" role="presentation"><a id="0" href="#">Todos</a></li>';
            $("#selecao_pais").text("Todos");
            $("#selecao_pais").val("0");
            $("#menu_paises").html(paisesHtml);
            $("#menu_paises li a").click(function() {
                $("#selecao_pais").text($(this).text());
                $("#selecao_pais").val($(this).attr("id"));
                $('#menu_paises > li').removeClass('active');
                $(this).parent().addClass('active');
                $("#nomeProcurar").val("");
                atualizaMapa();
            });
            $(".paisesPonto").html(paisesOption);
        });
    });

    $(document).ready(function preencheTipos() {
        $.getJSON("../CodeIgniter/main/getTipos", function(tipos) {
            var tiposHtml = "";
            var tiposOption = "";
            for (var i = 0; i < tipos.length; i++) {
                tiposHtml += '<li class="dropdown-menu-item" role="presentation"><a role="menuitem" id="' + tipos[i].id + '" tabindex="-1" href="#">' + tipos[i].designacao + '</a></li>';
                tiposOption += '<option value="'+tipos[i].id+'">'+tipos[i].designacao+'</option>';
            }
            tiposHtml += '<li class="dropdown-menu-item active" role="presentation"><a role="menuitem" id="0" tabindex="-1" href="#">Todos</a></li>';
            $("#selecao_tipo").text("Todos");
            $("#selecao_tipo").val("0");
            $("#menu_tipos").html(tiposHtml);
            $("#menu_tipos li a").click(function() {
                $("#selecao_tipo").text($(this).text());
                $("#selecao_tipo").val($(this).attr("id"));
                $('#menu_tipos > li').removeClass('active');
                $(this).parent().addClass('active');
                $("#nomeProcurar").val("");
                atualizaMapa();
            });
            $(".tiposPonto").html(tiposOption);
        });
    });

    $("#procuraNome").click(function() {
        limpaFiltros(false);
        atualizaMapa();
    });

    $("#nomeProcurar").keyup(function(e) {
        if (e.keyCode === 13) {
            limpaFiltros(false);
            atualizaMapa();
        }
    });

    $("#limpaFiltros").click(function() {
        limpaFiltros(true);
        atualizaMapa();
    });

    $("#criarDialeto").click(function() {
        if (criarPonto){  
          criarPonto = false;
          desenhaPonto.deactivate();
          $("#criarDialeto").html("Criar Diateto");
          $("#criarDialeto").removeClass("btn-danger");
          $("#criarDialeto").addClass("btn-primary");
        } else {
          criarPonto = true;
          desenhaPonto.activate();
          $("#criarDialeto").html("Cancelar Criar Diateto");
          $("#criarDialeto").removeClass("btn-primary");
          $("#criarDialeto").addClass("btn-danger");
        }
    });

    $(document).ready(function verificaLogin() {
        $.getJSON("../CodeIgniter/main/verificaLogin", function(utilizador) {
            estado = utilizador[0].estado;
            tipoFinal = utilizador[0].tipo;
            if (estado == 0) {
                $("#login").show();
            } else {
                $("#logout").show();
                $("#criarDialeto").show();
            }
        });
    });

    $('#modalLogin').on('hidden.bs.modal', function() {
        $("#inputEmail").val('');
        $("#inputPassword").val('');
        $("#erroLogin").removeClass("alert alert-danger");
        $("#erroLogin").html('');
    });

    $("#fazlogin").click(function() {
        var email, password;
        email = $("#inputEmail").val();
        password = $("#inputPassword").val();
        if (email.length == 0 || password.length == 0) {
            alert("Deve preencher todos os campos!");
        } else {
            $.getJSON("../CodeIgniter/main/fazLogin/" + email + "/" + password + "", function(dados) {
                if (dados.estado) {
                    estado = 1;
                    $("#modalLogin").modal('hide');
                    $("#login").hide();
                    $("#logout").show();
                    $("#criarDialeto").show();
                }
                else {
                    $("#modalLogin").modal('show');
                    $("#erroLogin").addClass("alert alert-danger");
                    $("#erroLogin").html("Email e/ou password errados.");
                }
            });
        }
    });

    $("#logout").click(function() {
        $.getJSON("../CodeIgniter/main/fazLogout", function(info) {
            $("#logout").hide();
            $("#login").show();
            $("#criarDialeto").hide();
            //apaga popup do mapa caso esteja aberta
            map.removePopup(featureglobal.popup);
            featureglobal.popup.destroy();
            featureglobal.popup = null;
        });
    });

    /* EXEMPLO DE TODOS OS MAPAS GOOGLE
     var options = {
     singleTile: true,
     ratio: 1,
     isBaseLayer: true,
     wrapDateLine: true,
     getURL: function() {
     var center = this.map.getCenter().transform("EPSG:3857", "EPSG:4326"),
     size = this.map.getSize();
     return [
     this.url, "&center=", center.lat, ",", center.lon,
     "&zoom=", this.map.getZoom(), "&size=", size.w, "x", size.h
     ].join("");
     }
     };
     
     var map = new OpenLayers.Map({
     div: "mapa",
     projection: "EPSG:3857",
     numZoomLevels: 22,
     layers: [
     new OpenLayers.Layer.Grid(
     "Google Physical",
     "http://maps.googleapis.com/maps/api/staticmap?sensor=false&maptype=terrain",
     null, options
     ),
     new OpenLayers.Layer.Grid(
     "Google Streets",
     "http://maps.googleapis.com/maps/api/staticmap?sensor=false&maptype=roadmap",
     null, options
     ),
     new OpenLayers.Layer.Grid(
     "Google Hybrid",
     "http://maps.googleapis.com/maps/api/staticmap?sensor=false&maptype=hybrid",
     null, options
     ),
     new OpenLayers.Layer.Grid(
     "Google Satellite",
     "http://maps.googleapis.com/maps/api/staticmap?sensor=false&maptype=satellite",
     null, options
     ),
     // the same layer again, but scaled to allow map sizes up to 1280x1280 pixels
     new OpenLayers.Layer.Grid(
     "Google Satellite (scale=2)",
     "http://maps.googleapis.com/maps/api/staticmap?sensor=false&maptype=satellite&scale=2",
     null, OpenLayers.Util.applyDefaults({
     getURL: function() {
     var center = this.map.getCenter().transform("EPSG:3857", "EPSG:4326"),
     size = this.map.getSize();
     return [
     this.url, "&center=", center.lat, ",", center.lon,
     "&zoom=", (this.map.getZoom() - 1),
     "&size=", Math.floor(size.w / 2), "x", Math.floor(size.h / 2)
     ].join("");
     }
     }, options)
     )
     ],
     center: new OpenLayers.LonLat(10.2, 48.9).transform("EPSG:4326", "EPSG:3857"),
     zoom: 5
     });
     */

    map = new OpenLayers.Map('mapa', {displayProjection: 'EPSG:4326'});
    map.addControl(new OpenLayers.Control.LayerSwitcher());


    // Mostrar coordenadas
    var coords = $('#coordenadas');
    map.addControl(new OpenLayers.Control.MousePosition({element: coords}));

    // Google
    layer = new OpenLayers.Layer.Google("Google Satellite", {
        type: google.maps.MapTypeId.SATELLITE,
        numZoomLevels: 22
    });
    map.addLayer(layer);

    // Bing
    layer = new OpenLayers.Layer.Bing({
        name: "Bing Aerial",
        key: apiKey,
        type: "Aerial"
    });
    map.addLayer(layer);
    //map.zoomToMaxExtent();
    vector = new OpenLayers.Layer.Vector('Localização Atual');
    map.addLayer(vector);
    var center = new OpenLayers.LonLat(-8.289775, 41.4530731).transform("EPSG:4326", "EPSG:900913");
    map.setCenter(center, 17);

    map.events.register("click", map, function(e) {
        var lonlat = map.getLonLatFromPixel(e.xy);
        lonlat.transform("EPSG:900913", "EPSG:4326");
        //alert("Longitude: " + lonlat.lon + "\nLatitude: " + lonlat.lat)
        lon = lonlat.lon;
        lat = lonlat.lat;
        if (clickfeito === true) {
            $("#latitude").html(lat);
            $("#longitude").html(lon);
            $("#registaPonto").modal('show');
            clickfeito = false;
        }
    });

    // Para colocar simbolos
    var styleMap = new OpenLayers.StyleMap({
        fillOpacity: 1,
        pointRadius: 12
    });

    var lookup = {
        1: {externalGraphic: "../OpenLayers-2.13.1/img/falada.png"},
        2: {externalGraphic: "../OpenLayers-2.13.1/img/escrita.png"},
        3: {externalGraphic: "../OpenLayers-2.13.1/img/faladaescrita.png"}
    };

    // adicionar lookup com base na propriedade tipo
    styleMap.addUniqueValueRules("default", "id_tipo", lookup);

    // Localizar-me
    var geolocate = new OpenLayers.Control.Geolocate({
        bind: false,
        geolocationOptions: {
            enableHighAccuracy: false,
            maximumAge: 0,
            timeout: 7000
        }
    });
    map.addControl(geolocate);
    var firstGeolocation = true;
    geolocate.events.register("locationupdated", geolocate, function(e) {
        vector.removeAllFeatures();
        var circle = new OpenLayers.Feature.Vector(
                OpenLayers.Geometry.Polygon.createRegularPolygon(
                        new OpenLayers.Geometry.Point(e.point.x, e.point.y),
                        200,
                        40,
                        0
                        ),
                {},
                style
                );
        vector.addFeatures([
            new OpenLayers.Feature.Vector(
                    e.point,
                    {},
                    {
                        graphicName: 'cross',
                        strokeColor: '#f00',
                        strokeWidth: 2,
                        fillOpacity: 0,
                        pointRadius: 10
                    }
            ),
            circle
        ]);
        if (firstGeolocation) {
            map.zoomToExtent(vector.getDataExtent());
            //pulsate(circle);
            firstGeolocation = false;
            this.bind = true;
        }
    });
    geolocate.events.register("locationfailed", this, function() {
        OpenLayers.Console.log('Location detection failed');
    });

    // Começar na própria localização
    vector.removeAllFeatures();
    geolocate.deactivate();
    geolocate.watch = false;
    firstGeolocation = true;
    geolocate.activate();

    vectore = new OpenLayers.Layer.Vector("Dialetos", {
        styleMap: styleMap, // simbologia
        projection: "EPSG:4326",
        strategies: [new OpenLayers.Strategy.Fixed()], //, new OpenLayers.Strategy.BBOX()
        protocol: new OpenLayers.Protocol.HTTP({
            url: "../CodeIgniter/pontos/getAllPontos/" + idPais + "/" + idTipo + "",
            format: new OpenLayers.Format.GeoJSON()
        })
    });
    map.addLayer(vectore);

    // Seleção de pontos
    var select = new OpenLayers.Control.SelectFeature(vectore, {hover: false});
    map.addControl(select);
    select.activate();

    vectore.events.on({
        "featureselected": function(e) {
            onFeatureSelect(e.feature);
        },
        "featureunselected": function(e) {
            onFeatureUnselect(e.feature);
        }
    });

    var selectedFeaure, popup;
    function onPopupClose(evt) {
        select.unselect(selectedFeature);
    }
    function onFeatureSelect(feature) {
        selectedFeature = feature;
        var attr = feature.attributes;

        tipoDialeto = attr.tipo;
        paisDialeto = attr.pais;
        imagemDialeto = attr.imagem;
        linkDialeto = attr.link;
        descricaoDialeto = attr.descricao;
        designacaoDialeto = attr.designacao;
        idDialeto = attr.id_dialeto;
        idPontoFinal = attr.id_ponto;
        var auxestado;
        $.getJSON("../CodeIgniter/main/verificaLogin", function(utilizador) {
            auxestado = utilizador[0].estado;
            texto = "<div><h3>" + designacaoDialeto + " </h3>Tipo: " + tipoDialeto +
                    "<br> País: " + paisDialeto + "<br>Descrição: " + descricaoDialeto +
                    "<br>Imagem: <b>" + imagemDialeto + "</b><br>Link: " + linkDialeto + "<br><br>";
            if (auxestado == 0) {
                $("#login").show();
            }
            else {
                $("#logout").show();
                $("#criarDialeto").show();
                texto += "<button class='btn btn-primary' id='alterarPonto'>Editar</button>&nbsp<button class='btn btn-primary' id='apagarPonto'>Apagar</button>";
            }
            texto += "</div>";
            popup = new OpenLayers.Popup.FramedCloud("popup",
                    feature.geometry.getBounds().getCenterLonLat(),
                    null, texto,
                    null, true, onPopupClose);
            feature.popup = popup;
            map.addPopup(popup);

            $("#alterarPonto").click(function() {

                if (situacaoFinal === "ligado")
                    document.getElementById('inputLigadoEdit').checked = true;
                else if (situacaoFinal === "desligado")
                    document.getElementById('inputDesligadoEdit').checked = true;
                else if (situacaoFinal === "avariado")
                    document.getElementById('inputAvariadoEdit').checked = true;
                else
                    document.getElementById('inputContagemEdit').checked = true;
                $("#inputDescricaoEdit").val(descricaoFinal);
                $("#inputConsumoEdit").val(consumoFinal);
                $('#editaPonto').modal('show');
            });
            $("#apagarPonto").click(function() {
                $.getJSON("../CodeIgniter/pontos/apagaPonto/" + attr.id_contador + "", function(info) {
                    if (info.estado)
                        alert("Dialeto apagado com sucesso!");
                    else
                        alert("Não foi possível apagar este dialeto!");
                });
            });
        });

    }
    function onFeatureUnselect(feature) {
        featureglobal = feature;
        map.removePopup(feature.popup);
        feature.popup.destroy();
        feature.popup = null;
        vectore.protocol.options.url = "../CodeIgniter/pontos/getAllPontos/" + numServico + "/" + numSituacao + "/" + numFavoritos + "/" + consumoMin + "/" + consumoMax + "/" + dataMin + "/" + dataMax + "";
        vectore.refresh();
    }

    desenhaPonto = new OpenLayers.Control.DrawFeature(vector, OpenLayers.Handler.Point);
    map.addControl(desenhaPonto);
    vector.events.on({"featureadded": function(e) {
            if (criarPonto) {
                var feature = e.feature;
                criarPonto = false;
                desenhaPonto.deactivate();
                vector.drawFeature(feature);
                clickfeito = true;
            }
        }});


    /*    $("#resetFiltros").click(function() {
     numServico = 0;
     numSituacao = "todos";
     numFavoritos = 0;
     consumoMin = 0;
     consumoMax = 0;
     dataMin = 0;
     dataMax = 0;
     vectore.protocol.options.url = "../CodeIgniter/pontos/getAllPontos/" + numServico + "/" + numSituacao + "/" + numFavoritos + "/" + consumoMin + "/" + consumoMax + "/" + dataMin + "/" + dataMax + "";
     vectore.refresh();
     });*/

    /*   $("#atualizaPontos").click(function() {
     vectore.protocol.options.url = "../CodeIgniter/pontos/getAllPontos/" + numServico + "/" + numSituacao + "/" + numFavoritos + "/" + consumoMin + "/" + consumoMax + "/" + dataMin + "/" + dataMax + "";
     vectore.refresh();
     });*/

    /*    $("#editarPonto").click(function() {
     var situacao, consumo, descricao, verifica = true;
     if (document.getElementById('inputLigadoEdit').checked)
     situacao = "ligado";
     else if (document.getElementById('inputDesligadoEdit').checked)
     situacao = "desligado";
     else if (document.getElementById('inputAvariadoEdit').checked)
     situacao = "avariado";
     else
     situacao = "contagem";
     consumo = $("#inputConsumoEdit").val();
     descricao = $("#inputDescricaoEdit").val();
     if (consumo.length == 0 || descricao.length == 0) {
     alert("Tem de preencher todos os campos corretamente!");
     verifica = false;
     }
     if (verifica == true) {
     $.ajax({
     type: "POST",
     url: "../CodeIgniter/pontos/editaPonto",
     data: {id: idFinal, situacao: situacao, descricao: descricao, consumo: consumo}
     })
     .done(function(msg) {
     
     });
     $('#editaPonto').modal('hide');
     }
     });*/



    /*   $("#atribuiAcessos").click(function() {
     var email, agua = false, luz = false, gas = false, verifica = true;
     email = $("#inputAcessoEmail").val();
     if (document.getElementById('inputAcessoAgua').checked)
     agua = true;
     if (document.getElementById('inputAcessoLuz').checked)
     luz = true;
     if (document.getElementById('inputAcessoGas').checked)
     gas = true;
     if (email.length == 0) {
     alert("Tem de preencher todos os campos corretamente!");
     verifica = false;
     }
     if (verifica == true) {
     $.getJSON("../CodeIgniter/main/atribuiAcessos/" + email + "/" + agua + "/" + luz + "/" + gas + "", function(info) {
     if (info.estado) {
     $("#atribuirAcessos").modal('hide');
     }
     else {
     $("#erroAcessoEdit").addClass("alert alert-danger");
     $("#erroAcessoEdit").html("Email inexistente! Tente outra vez!");
     }
     });
     }
     });*/

    /*    $("#registarPonto").click(function() {
     var servico, situacao, consumo, data, descricao, verifica = true;
     if (document.getElementById('inputAgua').checked)
     servico = 1;
     else if (document.getElementById('inputLuz').checked)
     servico = 2;
     else //if(document.getElementById('inputGas').checked)
     servico = 3;
     if (temAcesso(servico) === false) {
     alert("Não pode registar contadores deste serviço!");
     verifica = false;
     $("#registaPonto").modal('hide');
     }
     if (document.getElementById('inputLigado').checked)
     situacao = "ligado";
     else if (document.getElementById('inputDesligado').checked)
     situacao = "desligado";
     else if (document.getElementById('inputAvariado').checked)
     situacao = "avariado";
     else  //if(document.getElementById('inputContagem').checked)
     situacao = "contagem";
     consumo = $("#inputConsumo").val();
     data = $("#inputData").val();
     descricao = $("#inputDescricao").val();
     if (consumo.length == 0 || data.length == 0 || descricao.length == 0) {
     alert("Tem de preencher todos os campos corretamente!");
     verifica = false;
     }
     if (verifica == true) {
     $.ajax({
     type: "POST",
     url: "../CodeIgniter/pontos/registaPonto",
     data: {servico: servico, situacao: situacao, consumo: consumo, data: data, descricao: descricao, latitude: lat, longitude: lon}
     })
     .done(function(msg) {
     });
     $("#registaPonto").modal('hide');
     alert("Contador Registado com Sucesso!"); // Vector só faz refresh com este alert
     vectore.protocol.options.url = "../CodeIgniter/pontos/getAllPontos/" + numServico + "/" + numSituacao + "/" + numFavoritos + "/" + consumoMin + "/" + consumoMax + "/" + dataMin + "/" + dataMax + "";
     vectore.refresh();
     }
     });*/





    /*  $("#recupera").click(function() {
     email = $("#inputEmailRecupera").val();
     $.getJSON("../CodeIgniter/main/recuperaPassword/" + email + "", function(info) {
     if (info.estado) {
     $("#modalPW").modal('hide');
     $("#erro").addClass("alert alert-success");
     $("#erro").html("Password enviada para o seu email.");
     }
     else {
     $("#modalPW").modal('show');
     $("#erroPW").addClass("alert alert-danger");
     $("#erroPW").html("Email não registado.");
     }
     });
     });*/

    /*   $("#regista").click(function() {
     var verifica = true;
     email = $("#inputEmail").val();
     password = $("#inputPassword").val();
     if (email.length == 0 || password.length == 0) {
     alert("Tem de preencher todos os campos!");
     verifica = false;
     }
     if (verifica == true) {
     $.getJSON("../CodeIgniter/main/fazRegisto/" + email + "/" + password + "", function(info) {
     if (info.estado) {
     $("#erro").addClass("alert alert-success");
     $("#erro").html("Registado com sucesso. Prossiga para o login.");
     }
     else {
     $("#erro").addClass("alert alert-danger");
     $("#erro").html("Email já existente.");
     }
     });
     }
     });*/

    /*   $("#procuraDescricao").keydown(function(tecla) {
     if (tecla.keyCode === 13) {
     desc = $(this).val();
     if (desc.length === 0) {
     alert("Tem de preencher o campo!");
     }
     else {
     vectore.protocol.options.url = "../CodeIgniter/pontos/descricao/" + desc + "";
     vectore.refresh();
     }
     //var center = new OpenLayers.LonLat(-8.84674, 41.69413).transform("EPSG:4326", "EPSG:900913");
     //map.setCenter(center, 17);
     }
     });*/

    /*    $("#removeDescricao").click(function() {
     $("#procuraDescricao").val('');
     vectore.protocol.options.url = "../CodeIgniter/pontos/getAllPontos/" + numServico + "/" + numSituacao + "/" + numFavoritos + "/" + consumoMin + "/" + consumoMax + "/" + dataMin + "/" + dataMax + "";
     vectore.refresh();
     });*/


}



function atribuiAcessos() {
    $.getJSON("../CodeIgniter/main/getAcessos", function(dados) {
        tipoFinal = dados.tipo;
        if (dados.quantidade > 0)
            temAcessos = true;
        if (temAcessos) {
            for (var i = 0; i < dados.quantidade; i++)
            {
                if (i == 0)
                    acessos[i] = dados.primeiroAcesso;
                if (i == 1)
                    acessos[i] = dados.segundoAcesso;
                if (i == 2)
                    acessos[i] = dados.terceiroAcesso;
            }
        }
    });
}

function temAcesso(id_servico) {
    for (var i = 0; i < acessos.length; i++) {
        if (acessos[i] == id_servico)
            return true;
    }
    return false;
}