// API key for http://openlayers.org. Please get your own at http://bingmapsportal.com/ and use that instead.
var apiKey = "Aj5xvmpos4E-Zb3hMrW6xHZckz2Q3_-babJESX3DtfZmuI07q9el6KO5iY53G09O";
var map, layer;
var lon, lat;
var clickfeito = false;
var tipoFinal = null;
var estadoFavoritos = 0;
var texto;
var vector, vectore, utilizador, estado;
var desenhaPonto;
var criarPonto = false;
var descricaoFinal, consumoFinal, situacaoFinal, idFinal, idPontoFinal;
var featureglobal, selectedFeature;
var style = {
    fillColor: '#000',
    fillOpacity: 0.1,
    strokeWidth: 0
};

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
                paisesOption += '<option value="' + paises[i].id + '">' + paises[i].designacao + '</option>';
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
                tiposOption += '<option value="' + tipos[i].id + '">' + tipos[i].designacao + '</option>';
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

    $("#atualizaPontos").click(function() {
        atualizaMapa();
    });

    $("#criarDialeto").click(function() {
        if (criarPonto) {
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
                if (tipoFinal == "admin")
                    $("#regista").show();
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
                    if (dados.tipo == "admin")
                        $("#regista").show();
                } else {
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
            $("#regista").hide();
            //apaga popup do mapa caso esteja aberta
            map.removePopup(featureglobal.popup);
            featureglobal.popup.destroy();
            featureglobal.popup = null;
        });
    });

    $("#fazRegisto").click(function() {
        var email = $("#inputEmailRegisto").val();
        var password = $("#inputPasswordRegisto").val();
        if (email.length == 0 || password.length == 0) {
            alert("Tem que preencher todos os campos!");
        } else {
            $.getJSON("../CodeIgniter/main/fazRegisto/" + email + "/" + password + "", function(info) {
                if (info.estado) {
                    $("#errorRegisto").removeClass("alert-danger");
                    $("#errorRegisto").addClass("alert alert-success");
                    $("#errorRegisto").html("Registado com sucesso. Efetue login.");
                }
                else {
                    $("#errorRegisto").removeClass("alert-success");
                    $("#errorRegisto").addClass("alert alert-danger");
                    $("#errorRegisto").html("Email já existente.");
                }
            });
        }
    });

    $("#recupera").click(function() {
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
    });

    $('#registaPonto').on('hidden.bs.modal', function() {
        $("#inputDesignacao").val('');
        $("#inputLatitude").val('');
        $("#inputLongitude").val('');
        $("#inputDescricao").val('');
        $("#inputLink").val('');
        $("#criarDialeto").html("Criar Diateto");
        $("#criarDialeto").removeClass("btn-danger");
        $("#criarDialeto").addClass("btn-primary");
    });

    $("#registarPonto").click(function() {
        var inputDesignacao, inputLatitude, inputLongitude, inputPais, inputTipo, inputDescricao, inputLink, erro = false, erroMsg = "Erro Registo de Dialeto:\n\n";

        inputDesignacao = $("#inputDesignacao").val();
        inputLatitude = $("#inputLatitude").val();
        inputLongitude = $("#inputLongitude").val();
        inputPais = $("#inputPais").val();
        inputTipo = $("#inputTipo").val();
        inputDescricao = $("#inputDescricao").val();
        inputLink = $("#inputLink").val();
        inputImagem = $("#inputImagem").val();
        if (inputDesignacao.length == 0) {
            erro = true;
            erroMsg += "Designação não pode ser vazia.\n";
        }
        if (inputDescricao.length == 0) {
            erro = true;
            erroMsg += "Descrição não pode ser vazia.\n";
        }
        if (erro) {
            alert(erroMsg);
        } else {
            $.ajax({
                type: "POST",
                url: "../CodeIgniter/pontos/registaPonto",
                data: {inputDesignacao: inputDesignacao, inputLatitude: inputLatitude, inputLongitude: inputLongitude, inputPais: inputPais, inputTipo: inputTipo, inputDescricao: inputDescricao, inputLink: inputLink, inputImagem: inputImagem},
                dataType: "json"
            }).done(function(msg) {
                $("#registaPonto").modal('hide');
                atualizaMapa();
            });
        }
    });

    $("#editarPonto").click(function() {
        var inputDesignacao, inputPais, inputTipo, inputDescricao, inputLink, erro = false, erroMsg = "Erro Edição de Dialeto:\n\n";

        inputDesignacao = $("#inputDesignacaoEdit").val();
        inputPais = $("#inputPaisEdit").val();
        inputTipo = $("#inputTipoEdit").val();
        inputDescricao = $("#inputDescricaoEdit").val();
        inputLink = $("#inputLinkEdit").val();
        inputImagem = $("#inputImagemEdit").val();
        inputIdDialeto = $("#inputIdDialetoEdit").val();
        if (inputDesignacao.length == 0) {
            erro = true;
            erroMsg += "Designação não pode ser vazia.\n";
        }
        if (inputDescricao.length == 0) {
            erro = true;
            erroMsg += "Descrição não pode ser vazia.\n";
        }
        if (erro) {
            alert(erroMsg);
        } else {
            $.ajax({
                type: "POST",
                url: "../CodeIgniter/pontos/editaPonto",
                data: {inputIdDialeto: inputIdDialeto, inputDesignacao: inputDesignacao, inputPais: inputPais, inputTipo: inputTipo, inputDescricao: inputDescricao, inputLink: inputLink, inputImagem: inputImagem},
                dataType: "json"
            }).done(function(info) {
                if (info.estado) {
                    alert("Dialeto alterado com sucesso!");
                    map.removePopup(selectedFeature.popup);
                    selectedFeature.popup.destroy();
                    selectedFeature.popup = null;
                    atualizaMapa();
                } else {
                    alert("Não foi possível alterar este dialeto!");
                }
                $('#editaPonto').modal('hide');
                atualizaMapa();
            });
        }
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
        lon = lonlat.lon;
        lat = lonlat.lat;
        if (clickfeito === true) {
            $("#inputLatitude").val(lat);
            $("#inputLongitude").val(lon);
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

    var popup;
    function onPopupClose(evt) {
        select.unselect(selectedFeature);
    }
    function onFeatureSelect(feature) {
        selectedFeature = feature;
        var attr = feature.attributes;

        var idTipoDialeto = attr.id_tipo;
        var tipoDialeto = attr.tipo;
        var idPaisDialeto = attr.id_pais;
        var paisDialeto = attr.pais;
        var imagemDialeto = attr.imagem;
        var linkDialeto = attr.link;
        var descricaoDialeto = attr.descricao;
        var designacaoDialeto = attr.designacao;
        var idDialeto = attr.id_dialeto;
        var idPontoFinal = attr.id_ponto;
        var auxestado;
        $.getJSON("../CodeIgniter/main/verificaLogin", function(utilizador) {
            auxestado = utilizador[0].estado;
            var textoImagem = "";
            if (imagemDialeto.length)
                textoImagem = "<img src='" + imagemDialeto + "'/>";
            var textoLink = "";
            if (linkDialeto.length)
                textoLink = "<a target='_blank' href='" + linkDialeto + "'>" + linkDialeto + "</a>";
            var texto = "<div><h3>" + designacaoDialeto + " </h3><br/><b>Tipo de Dialeto: </b>" + tipoDialeto +
                    "<br/><b>País: </b>" + paisDialeto + "<br/><b>Descrição: </b>" + descricaoDialeto +
                    "<br/><br/>" + textoLink + "<br/>" + textoImagem + "<br/><br/>";
            if (auxestado == 1) {
                texto += "<br/><button class='btn btn-primary' id='alterarPonto'>Editar</button>&nbsp;&nbsp;<button class='btn btn-primary' id='apagarPonto'>Apagar</button>";
            }
            texto += "</div>";
            popup = new OpenLayers.Popup.FramedCloud("popup",
                    feature.geometry.getBounds().getCenterLonLat(),
                    null, texto,
                    null, true, onPopupClose);
            feature.popup = popup;
            map.addPopup(popup);
            $("#alterarPonto").click(function() {
                $("#inputIdDialetoEdit").val(idDialeto);
                $("#inputDesignacaoEdit").val(designacaoDialeto);
                $("#inputPaisEdit").val(idPaisDialeto);
                $("#inputTipoEdit").val(idTipoDialeto);
                $("#inputDescricaoEdit").val(descricaoDialeto);
                $("#inputLinkEdit").val(linkDialeto);
                $("#inputImagemEdit").val(imagemDialeto);
                $('#editaPonto').modal('show');
            });
            $("#apagarPonto").click(function() {
                var resultado = confirm("Tem a certeza que pretende remover o dialeto \"" + designacaoDialeto + "\"?");
                if (resultado == true) {
                    $.getJSON("../CodeIgniter/pontos/apagaPonto/" + idDialeto + "", function(info) {
                        if (info.estado) {
                            alert("Dialeto apagado com sucesso!");
                            map.removePopup(selectedFeature.popup);
                            selectedFeature.popup.destroy();
                            selectedFeature.popup = null;
                            atualizaMapa();
                        } else {
                            alert("Não foi possível apagar este dialeto!");
                        }
                    });
                }
            });
        });
    }

    function onFeatureUnselect(feature) {
        selectedFeature = feature;
        map.removePopup(feature.popup);
        feature.popup.destroy();
        feature.popup = null;
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
}