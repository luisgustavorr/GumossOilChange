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
function selectTr(){
  $("body .modal_produtos tbody tr").on("click",function(){
    $(".modal_produtos tbody tr").removeClass("marked_tr_tabela_produtos")
  $(this).addClass("marked_tr_tabela_produtos")
  
  })
}

selectTr()
shortcut.add("F1",()=>{
  $(".modal").each(function () {
    $(this).css("display", "none");
    $("fundo").css("display", "none");
  });
  $("#pre_venda_opener").trigger("click")
})
shortcut.add("F2",()=>{
  $(".modal").each(function () {
    $(this).css("display", "none");
    $("fundo").css("display", "none");
  });
  $("#troca_oleo").trigger("click")
})
shortcut.add("F3",()=>{
  $(".modal").each(function () {
    $(this).css("display", "none");
    $("fundo").css("display", "none");
  });
  $("#add_produto_opener").trigger("click")
})
shortcut.add("F4",()=>{
  $(".modal").each(function () {
    $(this).css("display", "none");
    $("fundo").css("display", "none");
  });
  $("#clientes_opener").trigger("click")
})


$(".tags_produto_name").keyup(function (e) {
  data = {
    nome: $("#nome_produto_pedido").val()
  }
  var availableTags = [];
  $.post("Models/post_receivers/select_produto.php", data, (ret) => {
    ret_inJSON = JSON.parse(ret)

    ret_inJSON.forEach(e => {
      produto = { "label": e.nome, "value": { "id": e.id, "preco": e.valor_venda,"estoque":e.quantidade } }
      availableTags.unshift(produto)
    })
  })
  console.log(availableTags)
  $("#nome_produto_pedido").autocomplete({
    source: availableTags,
    select: function (event, ui) {
      event.preventDefault()
      let quantidade = parseFloat($("#quantidade_produto_pedido").val())
      if(quantidade > ui.item.value.estoque ){
        alert(`Estoque do produto insuficiente, estoque atual : ${ui.item.value.estoque}. Caso isso seja um erro contate o suporte e altere o estoque editando o produto.`)
      }
      let produto = ui.item.label
      $(".modal_anotar_pedido tbody").append(
        '<tr preco_produto="' + ui.item.value.preco + '" produto="' +
        ui.item.value.id +
        '" quantidade="' +
        $("#quantidade_produto_pedido").val() +
        '" class="produto_pedido' +
        produto.replace(/ /g, "_") +
        '"><td>' +
        $("#quantidade_produto_pedido").val() +
        "</td><td>" +
        produto +
        "</td><td> " + ui.item.value.preco + "</td><td id='valor_produto_total_" +
        produto.replace(/ /g, "_") +
        "' >" +
        parseFloat(ui.item.value.preco * quantidade).toFixed(2) +
        '</td> <td produto="' +
        produto.replace(/ /g, "_") +
        '" class="remove_item_pedido ">-</td>'
      );
      $(".tags_produto_name").val("");
      $("#quantidade_produto_pedido").val("1");
      $(".remove_item_pedido").click(function () {
        $(".produto_pedido" + $(this).attr("produto")).remove();
      });
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
