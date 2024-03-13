$("#ie_cliente").mask("0000000000.00-00")
$(".input_ie_father").parent().css("display","none")

$('#yes').click(function () {
  console.log("a")
  $(".input_endereco_cliente").css('visibility', ' unset')
})
$('#no').click(function () {
  $(".input_endereco_cliente").css('visibility', ' hidden')

})
let timeoutId;
let input_codigo_focado = false;

let condicao_favoravel = true;
$("#tel_cliente").mask("(00) 00000-0000")

$(".modal_clientes").submit(async (e) => {
  e.preventDefault()
  let data = {
    nome: $("#nome_cliente").val(),
    tel: $("#tel_cliente").val(),
    CEP: $("#cep_cliente").val(),
    UF: $("#uf_cliente").val(),
    municipio: $("#municipio_cliente").val(),
    bairro: $("#bairro_cliente").val(),
    rua: $("#rua_cliente").val(),
    numero: $("#numero_cliente").val(),
    CPF: $("#cnpj_cliente").cleanVal().trim(),
    IE: $("#ie_cliente").cleanVal(),
    email: $("#email_cliente").val(),
  }
  if ($("#cnpj_cliente").cleanVal().length == 14) {

    await $.get("https://brasilapi.com.br/api/cnpj/v1/" + $("#cnpj_cliente").cleanVal(), (ret) => {
      $("#tel_cliente").val($('#tel_cliente').masked(ret.ddd_telefone_1))
      $("#email_cliente").val(ret.email)
      data["nome_fantasia"] = ret.nome_fantasia
      console.log(data)
    })
  }
  $.post("Models/post_receivers/insert_cliente.php", data, (ret) => {
    $.alert({
      title: 'Sucesso',
      content: "Cliente cadastrado!",
      boxWidth: '500px',
      useBootstrap: false,
    });
    $(".modal_clientes input").val("")
    $(".modal_clientes").css("display", "none")
    $("fundo").css("display", "none")
    console.log(ret)
  })
})

let alcancou_14_cliente = false
$("#tel_cliente").keyup(function (e) {
  let numero_digitado = (String.fromCharCode(e.keyCode))
  console.log($(this).val().length)


  if ($(this).val().length == 14) {
    if (alcancou_14_cliente) {
      $("#tel_cliente").mask("(00) 0-0000-0000")

      $("#tel_cliente").val($("#tel_cliente").val() + numero_digitado)
    }
    alcancou_14_cliente = true
  } else if ($(this).val().length < 16) {
    alcancou_14_cliente = false
    $("#tel_cliente").mask("(00) 0000-0000")
  }



})
const include_path = $("#include_path").val();

document.addEventListener("keydown", function (e) {

  if (e.keyCode === 13) {

    e.preventDefault();

  }

});

function getCookie(name) {
  let cookie = {};

  document.cookie.split(";").forEach(function (el) {
    let [k, v] = el.split("=");
    cookie[k.trim()] = v;
  });

  return cookie[name];
}
$(".input_valor_pedido_produto").keyup(function () {

  let valor_produto = parseFloat(
    $(this).val().replace(".", "").replace(",", ".")
  );
  let produto = $(this).attr("produto");
  let novoValor =
    valor_produto * $(".produto_pedido" + produto).attr("quantidade");
  const options = {
    style: "currency",
    currency: "BRL",
    minimumFractionDigits: 2,
    maximumFractionDigits: 5,
  };
  $(".produto_pedido" + produto).attr(
    "preco_produto",
    novoValor.toFixed(2)
  );
  $("#valor_produto_total_" + produto).text(
    new Intl.NumberFormat("pt-BR", options).format(novoValor)
  );
});

function selectTr() {
  $("body .modal_produtos tbody tr").on("click", function () {
    $(".modal_produtos tbody tr").removeClass("marked_tr_tabela_produtos")
    $(this).addClass("marked_tr_tabela_produtos")

  })
}
$("#cnpj_cliente").mask("000.000.000-00")

let pf = true
$("#cep_cliente").keyup(function () {
  pesquisarCEP($(this))


})
function pesquisarCEP(elemento) {
  if ($(elemento).val().length == 9) {
    $.get(`https://viacep.com.br/ws/${$("#cep_cliente").cleanVal()}/json/`, (ret) => {
      console.log(ret.cep)
      $("#uf_cliente").val(ret.uf)
      $("#municipio_cliente").val(ret.localidade)
      $("#rua_cliente").val(ret.logradouro)
      $("#bairro_cliente").val(ret.bairro)

    })
  }
}
$("#cnpj_cliente").keyup(function () {
  if ($(this).cleanVal().length == 14 && pf != true) {
    $.get("https://brasilapi.com.br/api/cnpj/v1/" + $(this).cleanVal(), (ret) => {
      $(".input_ie_father a").css("background", "red")
      let cnpj_unmasked = $('#cnpj_cliente').cleanVal();
      $(".input_ie_father a").attr("href", "https://cnpj.biz/" + cnpj_unmasked)
      $(".input_ie_father a").attr("target", "_blank")

      $("#email_cliente").val(ret.email)
      if($("#cep_cliente").val() == "") {
        $("#cep_cliente").val($("#cep_cliente").masked(ret.cep))
        pesquisarCEP($("#cep_cliente"))
      }
      if($("#numero_cliente").val() == "") {
        $("#numero_cliente").val(ret.numero)
      }
      if($("#nome_cliente").val() == "") {
        $("#nome_cliente").val(ret.razao_social)
      }
    })
  }

})

$("#cep_cliente").mask("00000-000")
$(".cnpj_selector").click(function () {
  $("#cnpj_cliente").val("")
  console.log("a")
  if (pf == true) {
    $("#nome_cliente").parent().find("label").text("Razão Social")
    $("#cnpj_cliente").parent().find("label").text("CNPJ")
    $("#cnpj_cliente").mask("00.000.000/0000-00")
    $("#ie_cliente").attr("required",true)

    $(".input_ie_father").parent().css("display","flex")
    $(".selector").css("right", "50px")
    $(".selector").text("PJ")
  } else {
    $("#nome_cliente").parent().find("label").text("Nome")
    $("#cnpj_cliente").parent().find("label").text("CPF")
    $("#cnpj_cliente").mask("000.000.000-00")
    $("#ie_cliente").removeAttr("required")
    $("#ie_cliente").val("")

    $(".input_ie_father").parent().css("display","none")
    $(".selector").css("right", "0")
    $(".selector").text("PF")
  }
  pf = !pf
})
selectTr()
shortcut.add("F1", () => {
  $(".modal").each(function () {
    $(this).css("display", "none");
    $("fundo").css("display", "none");
  });
  resetVenda()

  $("#pre_venda_opener").trigger("click")
})
shortcut.add("F2", () => {
  $(".modal").each(function () {
    $(this).css("display", "none");
    $("fundo").css("display", "none");
  });
  resetVenda()

  $("#troca_oleo").trigger("click")
})
shortcut.add("F3", () => {
  $(".modal").each(function () {
    $(this).css("display", "none");
    $("fundo").css("display", "none");
  });
  $("#add_produto_opener").trigger("click")
})
shortcut.add("F4", () => {
  $(".modal").each(function () {
    $(this).css("display", "none");
    $("fundo").css("display", "none");
  });
  $("#clientes_opener").trigger("click")
})



$("#nome_cliente_input").keyup(function (e) {
  let availableTags_client = [];

  data = {
    nome: $("#nome_cliente_input").val()
  }
  $.post("Models/post_receivers/select_cliente.php", data, (ret) => {
    ret_inJSON = JSON.parse(ret)


    ret_inJSON.forEach(e => {
      produto = { "label": e.id + "-" + e.nome, "value": { "id": e.id, "nome": e.nome } }
      availableTags_client.unshift(produto)
    })


  })
  console.log(availableTags_client)
  $("#nome_cliente_input").autocomplete({
    source: availableTags_client,
    select: function (event, ui) {
      event.preventDefault()
      console.log(ui.item.value)
      $("#cliente_id").val(ui.item.value.id)
      $("#nome_cliente_input").val(ui.item.label)
    }
  });



});

$(".tags_produto_name").keyup(function (e) {
  data = {
    nome: $("#nome_produto_pedido").val()
  }
  var availableTags = [];
  $.post("Models/post_receivers/select_produto.php", data, (ret) => {
    ret_inJSON = JSON.parse(ret)

    ret_inJSON.forEach(e => {
      produto = { "label": e.nome, "value": { "id": e.id, "preco": e.valor_venda, "estoque": e.quantidade } }
      availableTags.unshift(produto)
    })
  })
  console.log(availableTags)
  $("#nome_produto_pedido").autocomplete({
    source: availableTags,
    select: function (event, ui) {
      event.preventDefault()
      let quantidade = parseFloat($("#quantidade_produto_pedido").val())
      if (quantidade > ui.item.value.estoque) {
        alert(`Estoque do produto insuficiente, estoque atual : ${ui.item.value.estoque}. Caso isso seja um erro contate o suporte e altere o estoque editando o produto.`)
      }
      let produto = ui.item.label
      let disabled = editando_troca_oleo == true ? "" : "disabled"
      $(".modal_anotar_pedido tbody").append(
        '<tr preco_produto="' + ui.item.value.preco + '" produto="' +
        ui.item.value.id +
        '" quantidade="' +
        $("#quantidade_produto_pedido").val() +
        '" class="produto_pedido' +
        produto.replace(/ /g, "_") +
        '"><td><input class = "quantidade_produto_input"  value="' +
        $("#quantidade_produto_pedido").val() +
        '"</td><td>' +
        produto +
        "</td><td> <input class = 'input_valor_produto'  value='  " + ui.item.value.preco + "'></td><td id='valor_produto_total_" +
        produto.replace(/ /g, "_") +
        "' >" +
        parseFloat(ui.item.value.preco * quantidade).toFixed(2) +
        '</td> <td produto="' +
        produto.replace(/ /g, "_") +
        '" class="remove_item_pedido ">-</td>'
      );
      alterarValorTotal()

      $(".tags_produto_name").val("");
      $("#quantidade_produto_pedido").val("1");

      $(".remove_item_pedido").click(function () {
        $(".produto_pedido" + $(this).attr("produto")).remove();
      });
      $(".input_valor_pedido_produto").keyup(function () {
        let valor_produto = parseFloat(
          $(this).val().replace(".", "").replace(",", ".")
        );
        let produto = $(this).attr("produto");
        let novoValor =
          valor_produto * $(".produto_pedido" + produto).attr("quantidade");
        const options = {
          style: "currency",
          currency: "BRL",
          minimumFractionDigits: 2,
          maximumFractionDigits: 5,
        };
        $(".produto_pedido" + produto).attr(
          "preco_produto",
          novoValor.toFixed(2)
        );
        $("#valor_produto_total_" + produto).text(
          new Intl.NumberFormat("pt-BR", options).format(novoValor)
        );
      });
    }
  });



});
let caixa = getCookie("last_codigo_colaborador");
function setCaixa(code, callback) {
  console.log(code);
  var data = {
    colaborador: code,
    blue_sky: true,
  };

  $.post(
    include_path + "Models/post_receivers/select_colaborador.php",
    data,
    function (ret) {
      console.log(ret);
      // Chama a função de retorno de chamada e passa o valor retornado
      callback(ret);
    }
  );
}
setCaixa(caixa, function (caixa_retornado) {
  console.log(caixa_retornado);
  caixa = caixa_retornado;
  $("#blocked_fazer_sangria").attr("id", "fazer_sangria");
});

function verificarValoresFechamentoCaixa() {
  console.log("aqui");
  ultimo_valor = $("#dinheiro_apuradas").val();
  $(".input_modal_fechamento_column .input_princip_completo  ").each(function (
    index
  ) {
    result_subtracao = ultimo_valor - parseFloat($(this).val());
    if (index % 2 != 0) {
      if (result_subtracao > 0) {
        $(this).css("color", "red");
        $(this).prev().css("color", "black");

      } else if (result_subtracao < 0) {
        $(this).prev().css("color", "red");
        $(this).css("color", "black");

      } else {
        $(this).css("color", "black");
        $(this).prev().css("color", "black");

      }
    }
    ultimo_valor = parseFloat($(this).val());
  });
}
verificarValoresFechamentoCaixa();
let side_bar_aberta = false;
$(".open_sidebar_arrow").click(function () {
  if (side_bar_aberta) {
    $(".open_sidebar_arrow ").css("transform", "rotate(0deg)")

    $("#sidebar").animate({ width: "7%" });
    $("header img").animate({ width: "7%" });

    $("#sidebar span").css("display", "none");
    $("#adicionar_caixa").css("display", "none");
    $("#form_equip").css("display", "none");
    $("#sidebar .princip_span").css({ "display": "flex", "justify-content": "center" });
    $("#sidebar .princip_span i").css("margin-right", "0px");

    $("#salvar_caixa").css("display", "none");
  } else {
    $(".open_sidebar_arrow ").css("transform", "rotate(180deg)")
    $("header img").animate({ width: "300px" }, 200)

    $("#sidebar").animate({ width: "300px" }, 200, function () {
      $("#sidebar .princip_span").css({ "display": "flex", "justify-content": "start" });
      $("#sidebar .princip_span span").css("display", "block");

      $("#sidebar .princip_span i").css("margin-right", "20px");


      $("#salvar_caixa").css("display", "flex");
    });
  }
  side_bar_aberta = !side_bar_aberta;
});

function atualizarHorario() {
  moment.locale("pt-br");
  var dataAtual = moment().format("ddd: DD/MM/YYYY HH[h]mm");
  $(".horario_atual_finder").text(dataAtual);
}

function verificarValorCaixa(codigoColab) {
  moment.locale("en");
  const dataAtual = moment();
  const dataFutura = dataAtual.add(30, "days");
  const GMTstring = dataFutura.utc().format("ddd, DD MMM YYYY HH:mm:ss [GMT]");

  document.cookie =
    "last_codigo_colaborador=" + codigoColab + ";SameSite=Strict";
  let dataMoment = moment();
  var dataNovaAdiantada = dataMoment.add(30, "days");

  data = {
    caixa: caixa,
  };

  $.post(
    include_path + "Models/post_receivers/select_valor_caixa.php",
    data,
    function (ret) {
      let valor = ret === "" ? 0 : parseFloat(ret);
      if (valor >= 30) {
        $("#fazer_sangria").css("animation", "hysterical_pulse 0.7s infinite");
      } else if (valor >= 20) {
        $("#fazer_sangria").css("animation", "pulse 3s infinite");
      }
    }
  );
}
verificarValorCaixa(1);
function valorCaixa() {
  data = {
    caixa: caixa,
  };
  console.log(caixa);
  $.post(
    include_path + "Models/post_receivers/select_valor_caixa.php",
    data,
    function (ret) {
      console.log(ret);
      let valor = ret == "" ? (valor = 0) : parseFloat(ret);
      $("#valor_sangria").val(valor.toFixed(2).replace(".", ","));
      $(".valor_caixa_father red").text(
        "R$" + valor.toFixed(2).replace(".", ",")
      );
    }
  );
}
atualizarHorario();
setInterval(function () {
  atualizarHorario();
}, 10000);

$("fundo").click(function () {
  $(".modal").each(function () {
    $(this).css("display", "none");
    $("fundo").css("display", "none");
  });
});
function abrirModal(modal) {
  resetVenda()
  $("." + modal).css("display", "flex");
  $("fundo").css("display", "flex");
  if (modal == "modal_sangria") {
    valorCaixa();
  }
}

let darker = false;

function pesquisarProduto(barcode) {
  if (barcode.length == 13 || barcode.length == 8) {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(function () {
      var data = {
        barcode: barcode,
      };
      var startTime = performance.now();
      $.post(
        include_path + "Models/post_receivers/select_produto.php",
        data,
        function (ret) {
          var endTime = performance.now();
          var row = JSON.parse(ret);
          $("#desc_produto").val(row.nome);
          var duration = endTime - startTime;
          console.log("Requisição concluída em " + duration + "ms");
        }
      );
      $("#codigo_produto").val("");
    }, 350);
  }
}
let alcancou_14 = false
$("#numero_cliente_input").keyup(function (e) {
  let numero_digitado = (String.fromCharCode(e.keyCode))
  console.log($(this).val().length)


  if ($(this).val().length == 14) {
    if (alcancou_14) {
      $("#numero_cliente_input").mask("(00) 0-0000-0000")

      $("#numero_cliente_input").val($("#numero_cliente_input").val() + numero_digitado)
    }
    alcancou_14 = true
  } else if ($(this).val().length < 16) {
    alcancou_14 = false
    $("#numero_cliente_input").mask("(00) 0000-0000")
  }



})
//Mascara de moeda
String.prototype.reverse = function () {
  return this.split("").reverse().join("");
};

function mascaraMoeda(campo, evento) {
  var tecla = !evento ? window.event.keyCode : evento.which;
  var valor = campo.value.replace(/[^\d]+/gi, "").reverse();
  var resultado = "";
  var mascara = "##.###.###,##".reverse();
  for (var x = 0, y = 0; x < mascara.length && y < valor.length;) {
    if (mascara.charAt(x) != "#") {
      resultado += mascara.charAt(x);
      x++;
    } else {
      resultado += valor.charAt(y);
      y++;
      x++;
    }
  }
  campo.value = resultado.reverse();
}
function mascaraMoedaSemSeparacaoMilhar(campo, evento) {

  var tecla = !evento ? window.event.keyCode : evento.which;
  var valor = campo.value.replace(/[^\d]+/gi, "").reverse();
  var resultado = "";
  var mascara = "########.##".reverse();
  for (var x = 0, y = 0; x < mascara.length && y < valor.length;) {
    if (mascara.charAt(x) != "#") {
      resultado += mascara.charAt(x);
      x++;
    } else {
      resultado += valor.charAt(y);
      y++;
      x++;
    }
  }
  campo.value = resultado.reverse();
  verificarValoresFechamentoCaixa()
}
function mascaraPeso(campo, evento) {
  var tecla = !evento ? window.event.keyCode : evento.which;
  var valor = campo.value.replace(/[^\d]+/gi, "").reverse();
  var resultado = "";
  var mascara = "########.##".reverse();
  for (var x = 0, y = 0; x < mascara.length && y < valor.length;) {
    if (mascara.charAt(x) != "#") {
      resultado += mascara.charAt(x);
      x++;
    } else {
      resultado += valor.charAt(y);
      y++;
      x++;
    }
  }
  campo.value = resultado.reverse();
}
