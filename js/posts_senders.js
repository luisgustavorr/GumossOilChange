let editando_troca_oleo = false

function alterarValorTotal() {
  $(".quantidade_produto_input,.input_valor_produto").keyup(function () {
    let produto = $(this).parent().parent().find(".remove_item_pedido ").attr("produto")
    let row = $(this).parent().parent()
    $(this).parent().parent().attr("quantidade", $(row).find(".quantidade_produto_input").val())
    $(this).parent().parent().attr("preco_produto", $(row).find(".input_valor_produto").val())
    let valor_total = $(row).find(".quantidade_produto_input").val() * $(row).find(".input_valor_produto").val()
    $("#valor_produto_total_" + produto).text(parseFloat(valor_total).toFixed(2))
  })
}
function selecionarInfoProdutosEditados() {
  produtos = []
  $(".modal_anotar_pedido tbody tr").each(function (e) {
    let quantidade = $(this).find(".quantidade_produto_input").val()
    let valor_unit = $(this).find(".input_valor_produto").val()
    let valor_total = quantidade * valor_unit
    console.log(valor_total)
    let produto = {
      "quantidade": quantidade,
      "id": $(this).attr("produto"),
      "valor_unit": valor_unit,
      "valor_total": valor_total
    }
    produtos.unshift(produto)
  })
  return produtos
}
async function pedirSenha(text) {
  let aprovado = false
  const resultado = new Promise((resolve, reject) => {
    $.confirm({
      title: 'Código Necessário!',
      theme: 'supervan',
      content: '' +
        '<form id="verificar_senha" action="" class="formName">' +
        '<div class="form-group">' +
        '<label>Seu Código</label><br>' +
        '<input type="text" placeholder="Seu Código" class="name form-control" required />' +
        '</div>' +
        '</form>',
      buttons: {
        formSubmit: {
          text: text,
          btnClass: 'btn-orange',
          action: function () {
            var name = this.$content.find('.name').val();

            $.post("Models/post_receivers/select_by_senha.php", { codigo: name }, (ret) => {
              console.log(ret)
              aprovado = ret
              resolve()

            })

          }
        },
        Cancelar: function () {
          resolve()
          console.log("cancelar")

          //close
        },
      },
      onContentReady: function () {
        // bind to events
        var jc = this;
        this.$content.find('form').on('submit', function (e) {
          // if the user submits the form by pressing enter in the field.
          e.preventDefault();
          jc.$$formSubmit.trigger('click'); // reference the button and click it
        });
      }
    });
  })
  await resultado.then()
  console.log(aprovado)
  return aprovado
}

async function editarPreVenda(elemento) {
  const aprovado = await pedirSenha("Editar")
  console.log(aprovado === "true")
  $("#venda_id").val($(elemento).attr("id_venda"))
  if (aprovado.trim() === "true") {
    $.post("Models/post_receivers/select_troca_oleo.php", { id_venda: $(elemento).attr("id_venda") }, (ret) => {
      resetVenda()
      let JSONret = JSON.parse(ret)
      if (JSONret.pre_venda == 0) {
        let valor_mao_obra = parseFloat(JSONret.valor) - parseFloat(JSONret.valor_produtos)
        
        $("#troca_oleo").trigger("click")
        $("#quilometragem").val(JSONret.quilometragem)
        $("#numero_cliente_input").val(JSONret.tel)
        $("#valor_mao_obra").val(valor_mao_obra)
        $("#placa_veiculo").val(JSONret.placa_carro)
        $("#marca_veiculo").val(JSONret.marca)
        $("#modelo_veiculo").val(JSONret.modelo)
      } else {
        $("#pre_venda_opener").trigger("click")

      }
      editando_troca_oleo = true

      $("#codigo_colaborador_input").val(JSONret.colaborador)
      $("#prazo_cliente_input").val(JSONret.prazo)
      $("#cliente_id").val(JSONret.id_cliente)
      $("#nome_cliente_input").val(JSONret.id_cliente + "-" + JSONret.nome_cliente)
      $("#metodo_pagamento").val(JSONret.forma_pagamento)
      JSONret.produtos_vendidos.forEach((e) => {
        let produto = e.nome
        console.log(e.valor_venda)
        $(".modal_anotar_pedido tbody").append(
          '<tr preco_produto="' + e.valor_venda + '" produto="' +
          e.id +
          '" quantidade="' +
          $("#quantidade_produto_pedido").val() +
          '" class="produto_pedido' +
          produto.replace(/ /g, "_") +
          '"><td> <input class = "quantidade_produto_input" value="' +
          $("#quantidade_produto_pedido").val() +
          '"></td><td>' +
          produto +
          "</td><td> <input class = 'input_valor_produto' value='" + e.valor_venda + "' > </td><td id='valor_produto_total_" +
          produto.replace(/ /g, "_") +
          "' >" +
          parseFloat(e.valor_venda * e.quantidade).toFixed(2) +
          '</td> <td produto="' +
          produto.replace(/ /g, "_") +
          '" class="remove_item_pedido ">-</td>'
        );
      })
      alterarValorTotal()
      console.log(selecionarInfoProdutosEditados())

      $(".input_valor_produto").mask("0000000.00", { reverse: true })

      $(".remove_item_pedido").click(function () {
        $(".produto_pedido" + $(this).attr("produto")).remove();
      });
    })

  } else {
    $.alert({
      title: 'Código Inválido',
      content: "",
      boxWidth: '500px',
      useBootstrap: false,
    });
  }
}
async function fecharVenda(elemento) {
  const aprovado = await pedirSenha("Fechar Venda")
  console.log(aprovado)
  if (aprovado.trim() === "true") {
    $.post("Models/post_receivers/fechar_venda.php", { id_venda: $(elemento).attr("id_venda") }, (ret) => {
      console.log(ret)
    })
    alterarTabela();
  } else {
    $.alert({
      title: 'Código Inválido',
      content: "",
      boxWidth: '500px',
      useBootstrap: false,
    });
  }
}

async function deletaPreVenda(elemento) {
  const aprovado = await pedirSenha("Excluir")
  console.log(aprovado === "true")
  if (aprovado.trim() === "true") {
    data = {

      pesquisa: "",
      data_min: $("#data_minima").val(),
      data_max: $("#data_maxima").val(),

    }
    $.post("Models/post_receivers/delete_venda.php", { id: $(elemento).attr("id_venda") }, (ret) => {
      gerarGráficos()
      $(elemento).parent().parent().remove()
    })

  } else {
    $.alert({
      title: 'Código Inválido',
      content: "",
      boxWidth: '500px',
      useBootstrap: false,
    });
  }
}
$(".datas").change(function () {
  alterarTabela();
});
function procurarVeiculos(inputID) {
  console.log(inputID)

  data = {

    pesquisa: $("#" + inputID).val().trim(),
    data_min: $("#data_minima").val(),
    data_max: $("#data_maxima").val(),

  }
  console.log(data)
  if (inputID == "pesquisar_venda_carro") {
    data["placa"] = true
  }
  $.post("Models/post_receivers/pesquisar_vendas.php", data, (ret) => {
    console.log(ret)
    $("#table_tabela tbody").html(ret)
    if ($("#" + inputID).val() == "") {
      $(".tabela_header span").html("Todos os Atendimentos")

    } else {
      if (inputID == "pesquisar_venda_carro") {
        $("#pesquisar_venda_cliente").val("")
        $(".tabela_header span").html("Atendimentos do Veículo: <yellow>" + $("#pesquisar_venda_carro").val().toUpperCase() + "</yellow>")

      } else {
        $("#pesquisar_venda_carro").val("")

        $(".tabela_header span").html("Atendimentos do(s) Cliente(s): <yellow>" + $("#pesquisar_venda_cliente").val() + "</yellow>")

      }
    }

  })
}
$(".pesquisar_venda input").keyup(function (e) {
  if (e.keyCode == 13) {
    procurarVeiculos($(this).attr("id"))
  } else {
    $(this).val($(this).val().toUpperCase())
  }
})
function printTable() {
  $(".chart_father").css("display", "none")
  $("thead th:contains('NFC-e')").css("display", "none")
  $("tbody td").find(".fa-print").parent().css("display", "none")
  $(".gerar_pdf ").css("visibility", "hidden")
  $("#voltar_semana ").css("visibility", "hidden")
  $("#adiantar_semana ").css("visibility", "hidden")

  window.print();
  $("#voltar_semana ").css("visibility", "inherit")
  $("#adiantar_semana ").css("visibility", "inherit")
  $(".gerar_pdf ").css("visibility", "inherit")
  $("thead th:contains('NFC-e')").css("display", "flex")
  $("tbody td").find(".fa-print").parent().css("display", "block")
  $(".chart_father").css("display", "flex")
}
let editando_produto = false

let id_produto_editando = 0;
function selecionarAvaiableIDProximo(id) {
  return new Promise((resolve, reject) => {
    $.post("Models/post_receivers/select_avaiable_id.php", { codigo: id }, (ret) => {
      resolve(ret); // Retorna o resultado da solicitação AJAX quando estiver disponível
    }).fail((jqXHR, textStatus, errorThrown) => {
      reject(errorThrown); // Rejeita a Promise se houver algum erro na solicitação AJAX
    });
  });
}
let codigo_produto_editando = 0
function editarProduto() {
  $(".editar_produto").click(function () {
    editando_produto = true
    $("#input_file_label").css("display", "none")
    console.log('foi')
    const id_produto = $(this).attr('produto')
    id_produto_editando = id_produto
    const linha_produto = $('.produto_' + id_produto)
    console.log(linha_produto)
    const nome_produto = linha_produto.find(".nome").text()
    const codigo_id_produto = linha_produto.find(".codigo_id").text()
    const codigo_produto = linha_produto.find(".codigo").text()
    const preco_produto = linha_produto.find(".preco").text().replace("R$", "")
    const produto_pesado = linha_produto.find(".pesado").text()
    codigo_produto_editando = id_produto
    let data = {
      editando_pedido: true,
      codigo: id_produto
    }
    $.post("Models/post_receivers/select_produto_codigo.php", data, function (ret) {
      console.log(ret)
      let json_ret = JSON.parse(ret)
      console.log(linha_produto.find(".nome"))
      console.log(nome_produto)
      if (produto_pesado == 'Não') {
        $("#nao").prop("checked", true)
      } else {
        $("#sim").prop("checked", true)
      }
      $("#nome_produto_add").val(nome_produto)
      $('#preco_produto_add').val(preco_produto)
      $('#ncm_produto_add').val(json_ret.ncm)
      $('#cst_icms_produto_add').val(json_ret.CST_ICMS)
      $('#icms_produto_add').val(json_ret.pICMS)
      $('#cst_pis_produto_add').val(json_ret.CST_PIS)
      $('#cst_cofins_produto_add').val(json_ret.CST_COFINS)
      $('#pCOFINS_produto_add').val(json_ret.pCOFINS)
      $('#unid_tributavel').val(json_ret.unid_tributavel)
      $('#quantidade_produto_add').val(json_ret.quantidade)
      $('#unid_vendas_produto_add').val(json_ret.unid_comercial)
      $('#preco_atacado_produto_add').val(json_ret.valor_atacado)
      $('#custo_produto_add').val(json_ret.valor_compra)
      $("#codigo_produto_add").val(json_ret.codigo)
      $('#pPIS_produto_add').val(json_ret.pPIS)
      $('#cfop_produto_add').val(json_ret.CFOP)

      $(".modal_adicionar_produto").css("display", "flex")
      $(".modal_produtos").css("display", "none")
    })


  })
}
editarProduto()
$('#select_caixa').change(function () {
  alterarTabela();
});
$('#pesquisar_produto_button').click(function (e) {
  e.preventDefault()
  $.post('Models/post_receivers/select_produtos_modal_produtos.php', { produto: $("#pesquisar_produto").val() }, (ret) => {
    console.log(ret)
    $(".modal_produtos tbody").html(ret)
    selectTr()
    editarProduto()
    deletarProdutos()
  })
})
function resetVenda() {
  editando_troca_oleo = false
  formatoDataHora = function (data) {
    var dia = ('0' + data.getDate()).slice(-2);
    var mes = ('0' + (data.getMonth() + 1)).slice(-2);
    var ano = data.getFullYear();
    var horas = ('0' + data.getHours()).slice(-2);
    var minutos = ('0' + data.getMinutes()).slice(-2);
    return ano + '-' + mes + '-' + dia + 'T' + horas + ':' + minutos;
  };
  $(".modal_anotar_pedido input[type='text']").each(function () {
    if ($(this).attr("id") == "quantidade_produto_pedido") {
      $(this).val(1)
    } else {
      $(this).val("")

    }
  })
  $(".modal_anotar_pedido tbody").children().remove()
  var dataAtual = new Date();
  var dataFutura = new Date();
  dataFutura.setMinutes(dataFutura.getMinutes() + 30);
  $('#data_pedido').val(formatoDataHora(dataAtual))
  $('#data_entrega').val(formatoDataHora(dataFutura))
  $("#finaliza_sangria_button").text("Finalizar Operação")
  $("#finaliza_sangria_button").removeAttr("disabled")
  $(".modal").css("display", 'none')
  $("fundo").css("display", 'none')
}

function numberFormat(numero, casasDecimais = 2, separadorMilhar = ',', separadorDecimal = '.') {
  let verdadeiro_numero = numero;
  if (isNaN(numero)) {
    verdadeiro_numero = 0;
  }
  return parseFloat(verdadeiro_numero).toFixed(casasDecimais).replace(/\d(?=(\d{3})+\.)/g, '$&' + separadorMilhar).replace('.', separadorDecimal);
}
$(".modal_fechar_caixa").submit(function (e) {
  e.preventDefault()
  data = {
    data_fechamento: $('#data_fechamento_funcionario').val(),
    moedas: $("#moedas_apuradas").val(),
    caixa: $("#caixa_ser_fechado").val(),
    dinheiro_informadas: $("#dinheiro_informadas").val(),
    moedas_informadas: $("#moedas_informadas").val(),
    pix_informadas: $("#pix_informadas").val(),
    cartao_informadas: $("#cartao_informadas").val(),
    sangria_informadas: $("#sangria_informadas").val(),
    funcionario: $("#codigo_funcionario_fechamento_caixa").val()
  }
  $.post(include_path + "Models/post_receivers/update_fechamento.php",
    data,
    function (ret) {
      console.log(ret)
      if (ret == ' ') {
        alert('Fechamento realizado com sucesso!');
      }
    })
})
$("#data_fechamento_funcionario").change(function () {
  updateFechamento($(this))
});
$("#caixa_ser_fechado").change(function () {
  updateFechamento($("#data_fechamento_funcionario"))
});
function updateFechamento(esse) {
  $(".fa-solid.fa-spinner.fa-spin-pulse").css("visibility", 'initial')
  const fechamentoInfo = $("#caixa_ser_fechado").val().split("||")
  let data = {
    data_fechamento: esse.val(),
    caixa: fechamentoInfo[0],
    userId: fechamentoInfo[1],

  };
  console.log('data:', data)
  $.post(
    include_path + "Models/post_receivers/select_fechamento.php",
    data,
    function (ret) {
      console.log(ret);
      if (ret != "false") {
        let valores_informados_in_json = JSON.parse(ret);
        let sangriaApurado = parseFloat(valores_informados_in_json.sangriaApurado) || 0;
        let dinheiroApurado = parseFloat(valores_informados_in_json.dinheiroApurado) - sangriaApurado || 0;
        let cartaoApurado = parseFloat(valores_informados_in_json.cartaoApurado) || 0;
        let moedaApurado = parseFloat(valores_informados_in_json.moedaApurado) || 0;
        let pixApurado = parseFloat(valores_informados_in_json.pixApurado) || 0;

        let dinheiro = parseFloat(valores_informados_in_json.dinheiro) || 0;
        let cartao = parseFloat(valores_informados_in_json.cartao) || 0;
        let moeda = parseFloat(valores_informados_in_json.moeda) || 0;
        let pix = parseFloat(valores_informados_in_json.pix) || 0;
        let sangria = parseFloat(valores_informados_in_json.sangria) || 0;
        console.log(dinheiroApurado, parseFloat(valores_informados_in_json.sangriaApurado), parseFloat(valores_informados_in_json.dinheiroApurado))
        $("#dinheiro_informadas").val(
          numberFormat(dinheiro + moeda, 2, '', '.')
        );
        $("#cartao_informadas").val(
          numberFormat(cartao, 2, '', '.')
        );

        $("#pix_informadas").val(numberFormat(pix, 2, '', '.'));
        $("#sangria_informadas").val(
          numberFormat(sangria, 2, '', '.')
        );
        $("#dinheiro_apuradas").val(
          numberFormat(dinheiroApurado, 2, '', '.')
        );
        $("#cartao_apuradas").val(
          numberFormat(cartaoApurado, 2, '', '.')
        );
        // $("#moedas_apuradas").val(
        //   numberFormat(parseFloat(valores_informados_in_json.moedaApurado),2,'','.')
        // );
        $("#pix_apuradas").val(numberFormat(pixApurado, 2, '', '.'));
        $("#sangria_apuradas").val(
          numberFormat(sangriaApurado, 2, '', '.')
        );
        $("#codigo_funcionario_fechamento_caixa").val(valores_informados_in_json.colaborador
        );


        let valor_total_informado = parseFloat(
          (dinheiro + cartao + moeda + pix).toFixed(2)
        );

        let valor_total_apurado = parseFloat(
          numberFormat(
            dinheiroApurado + cartaoApurado + moedaApurado + pixApurado,
            2,
            '',
            '.'
          )
        ).toFixed(2);

        console.log(valor_total_apurado)
        if (parseFloat(valor_total_informado)) {
          $(".valores_informados_footer red").text(
            "R$" + valor_total_informado

          );
        } else {
          $(".valores_informados_footer red").text(
            "R$00.00"

          );
        }
        if (parseFloat(valor_total_apurado)) {
          $(".valores_apurados_footer red").text(
            "R$" + valor_total_apurado

          );
        } else {
          $(".valores_apurados_footer red").text(
            "R$00.00"

          );
        }

      }

      verificarValoresFechamentoCaixa()
      $(".fa-solid.fa-spinner.fa-spin-pulse").css("visibility", 'hidden')

    }
  );
}
function setCaixa(code) {
  data = {
    colaborador: code,
    blue_sky: true,
  };
  let caixa_retornado = "";
  $.post(
    include_path + "Models/post_receivers/select_colaborador.php",
    data,
    function (ret) {
      console.log(ret);
      caixa_retornado = ret;
    }
  );
  return caixa_retornado;
}
$("#pesquisar_produto").keyup((e) => {
  if (e.keyCode == 13) {
    e.preventDefault()
    $.post('Models/post_receivers/select_produtos_modal_produtos.php', { produto: $("#pesquisar_produto").val() }, (ret) => {
      console.log(ret)
      $(".modal_produtos tbody").html(ret)
      selectTr()
      editarProduto()
      deletarProdutos()
    })
  }
})
function openProdModal() {
  $("#input_file_label").css("display", "flex")
  reiniciarAddProduto()
  $(".modal_produtos").css("display", "none");

  editando_produto = false
  $(".modal_adicionar_produto input[type='text']").val("")

}
$("#add_produto_opener").on("click", function () {
  $("#input_file_label").css("display", "flex")
  reiniciarAddProduto()
  $(".modal_produtos").css("display", "none");

  editando_produto = false
  $(".modal_adicionar_produto input[type='text']").val("")

})
$("#ncm_produto_add").mask("0000.00.00")
$("#pesquisar_venda_carro").mask("AAA-0A00")
$("#placa_veiculo").mask("AAA-0A00")
$("#quilometragem").mask("000.000.000.000", { reverse: true })
$(".porcentagem").mask("00.00%", { reverse: true })
$(".porcentagem").keyup(function (e) {

  if (e.code == "Backspace") {
    str = $(this).val()
    $(this).val(str.slice(0, -2) + str.slice(-1))
  }

})

let index = 0
let arrayRetorno = ""
let last_mudanca = "none"
function reiniciarAddProduto(fecharModal) {
  index = 0
  arrayRetorno = ""
  last_mudanca = "none"
  produtos_add_array = []
  $("#before_button_add").css("display", "none")
  $("#after_button_add").html(`Finalizar`)
  $(".modal_adicionar_produto input").val("")
  $("#input_file_label").text("Importar XML")
  $(".warning_add_produto").css("display", "none")
  if (fecharModal) {
    $(".modal_adicionar_produto").css("display", "none")
    $(".modal_produtos").css("display", "flex")
  }

  $.post('Models/post_receivers/select_produtos_modal_produtos.php', { produto: $("#pesquisar_produto").val() }, (ret) => {
    $(".modal_produtos tbody").html(ret)
    editarProduto()
    deletarProdutos()
  })
}
$("#input_file_xml").change(function () {

  var fileInput = $("#input_file_xml")[0];
  index = 0
  arrayRetorno = ""
  if (fileInput.files.length > 0) {
    var file = fileInput.files[0];
    console.log(file)
    $("#input_file_label").text(file.name)
    var formData = new FormData();
    formData.append("xmlFile", file);
    let item_novo = true
    $.ajax({
      url: "Models/post_receivers/ler_xml.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        retorno = response.split("__CODE__")[1]
        console.log(retorno)

        retorno = JSON.parse(retorno)
        arrayRetorno = retorno

        $("#before_button_add").css("display", "block")
        $("#after_button_add").html(`Próximo (${index + 1}/${arrayRetorno.length}) <i class="fa-solid fa-arrow-right"></i>`)
        changeValues(retorno[index], true)

      },
      error: function (error) {
        console.error("Error during upload:", error);
      }
    });
  } else {
    console.error("No file selected.");
  }

  $("#finalziar_button_add").click(() => {
    console.log(arrayRetorno.length)
    console.log(index)


  })
})
function salvarProdutos(array) {
  if (editando_produto) {
    array[0]["id_antigo"] = codigo_produto_editando
    console.log(array[0])
    $.post("Models/post_receivers/update_produto.php", array[0], (ret) => {
      console.log(ret)
      reiniciarAddProduto(true)
      deletarProdutos()

    })
  } else {
    array.forEach(e => {
      $.post("Models/post_receivers/insert_produto.php", e, (ret) => {
        console.log(ret)
        reiniciarAddProduto(true)
        deletarProdutos()

      })
    })
  }


}
$("#nome_produto_add").blur((e) => {
  let textoInput = $("#nome_produto_add").val()
  if (textoInput.includes("=")) {
    let produtoRelacionado = textoInput.split("=")[1]
    $.post("Models/post_receivers/select_produtos_relacionados.php", {
      produto: produtoRelacionado.trim()
    }, (ret) => {
      let retInJson = JSON.parse(ret)
      if (retInJson != false) {

        $(".warning_add_produto").css("display", "block")
        $("#id_prod_relacionado").val(retInJson.id)
        $(".warning_add_produto span").text(`Produto identificado como semelhante ao ${retInJson.nome}, os produtos serão colocados próximos uns aos outros na tabela com marcações de cores semelhantes quando o modo "Assistente de Estoque" estiver ativo. Caso esteja incorreto retire o "=" no Nome do produto`)
      } else {
        console.log("a")
        $("#id_prod_relacionado").val("")

        $(".warning_add_produto").css("display", "none")
      }

    })
  }
})
$("#codigo_produto_add").keyup(() => {
  $.post("Models/post_receivers/select_produto_codigo.php", { codigo: $("#codigo_produto_add").val() }, async (ret) => {
    if (ret != " ") {
      console.log(ret)
      let NovoId = await selecionarAvaiableIDProximo($("#codigo_produto_add").val())
      console.log(NovoId)
      let retornoJSON = JSON.parse(ret)
      let novoEstoque = parseFloat(retornoJSON.quantidade) + parseFloat($("#quantidade_produto_add").val())
      $(".warning_add_produto").css("display", "block")
      $(".warning_add_produto span").text(`Código Repetido, caso não seja alterado o produto será identificado como : "${retornoJSON.nome}" e aumentará o estoque do mesmo para ${novoEstoque}. Caso queira cadastrar como um novo produto altere o campo "Código do produto". (Código recomendado -> ${NovoId})`)
    } else {
      $(".warning_add_produto").css("display", "none")

    }
  })
})
function changeValues(e, lerXML) {


  if (!lerXML) {

    CFOP = e.cfop
    PISCST = e.cst_pis
    pCOFINS = e.pCOFINS
    cstCOFINS = e.cst_cofins
    cstICMS = e.cst_icms
    pICMS = e.icms_prod
    pPIS = e.pPIS
    xProd = e.nome
    cProd = e.cod
    uCom = e.unid_venda
    NCM = e.ncm
    uTrib = e.cst_unid_tributavel
    vProd = e.custo_prod
    vUnCom = e.preco_prod
    qCom = e.quantidade_prod

  } else {
    prod = e.prod
    CFOP = prod.CFOP
    imposto = e.imposto
    PIS = imposto.PIS
    PIS = PIS[Object.keys(PIS)]
    PISCST = PIS.CST
    COFINS = imposto.COFINS
    COFINS = COFINS[Object.keys(COFINS)]
    pCOFINS = COFINS.pCOFINS
    cstCOFINS = COFINS.CST
    ICMS = imposto.ICMS
    ICMS = ICMS[Object.keys(ICMS)]
    cstICMS = ICMS.CST
    pICMS = ICMS.pICMS
    pPIS = PIS["pPIS"]
    xProd = prod.xProd
    cProd = prod.cProd
    uCom = prod.uCom
    NCM = prod.NCM
    uTrib = prod.uTrib
    vProd = prod.vProd
    vUnCom = prod.vUnCom
    qCom = prod.qCom
  }
  console.log(e)


  $.post("Models/post_receivers/select_produto_codigo.php", { codigo: prod.cProd }, async (ret) => {
    if (ret != " ") {
      let NovoId = await selecionarAvaiableIDProximo(prod.cProd)
      console.log(NovoId)
      let retornoJSON = JSON.parse(ret)
      let novoEstoque = parseFloat(retornoJSON.quantidade) + parseFloat(prod.qCom)
      $(".warning_add_produto").css("display", "block")
      $(".warning_add_produto span").text(`Código Repetido, caso não seja alterado o produto será identificado como : "${retornoJSON.nome}" e aumentará o estoque do mesmo para ${novoEstoque}. Caso queira cadastrar como um novo produto altere o campo "Código do produto". (Código recomendado -> ${NovoId})`)
    }
  })
  if (cstICMS == "60") {
    pICMS = "00"
  }

  if (PISCST == "07" || PISCST == "04") {
    pPIS = "00"
    pCOFINS = "00"
    $("#pPIS_produto_add").val($("#icms_produto_add").masked(parseFloat(pPIS).toFixed(2)))
    $("#pCOFINS_produto_add").val($("#icms_produto_add").masked(parseFloat(pCOFINS).toFixed(2)))

  } else {
    console.log(pPIS)
    $("#pPIS_produto_add").val($("#icms_produto_add").masked(parseFloat(pPIS).toFixed(2)))
    $("#pCOFINS_produto_add").val($("#icms_produto_add").masked(parseFloat(pCOFINS).toFixed(2)))
  }

  $("#cfop_produto_add").val(CFOP)
  $("#nome_produto_add").val(xProd)
  $("#codigo_produto_add").val(cProd)
  $("#unid_vendas_produto_add").val(uCom)
  $("#ncm_produto_add").val(NCM)
  $("#cst_pis_produto_add").val(PISCST)
  $("#cst_cofins_produto_add").val(cstCOFINS)
  $("#cst_icms_produto_add").val(cstICMS)
  $("#unid_tributavel").val(uTrib)
  $("#custo_produto_add").val(vProd)
  $("#preco_produto_add").val(vUnCom)
  $("#quantidade_produto_add").val(qCom)
  $("#preco_atacado_produto_add").val(vUnCom)
  $("#icms_produto_add").val($("#icms_produto_add").masked(parseFloat(pICMS).toFixed(2)))



}
let produtos_add_array = []
$("#father_add_produto_button button").click(function (e) {
  e.preventDefault();
  let id_button = $(this).attr("id")

  if (id_button == "after_button_add") {

    let data_produto = {
      cfop: $("#cfop_produto_add").val(),
      nome: $("#nome_produto_add").val(),
      cod: $("#codigo_produto_add").val(),
      unid_venda: $("#unid_vendas_produto_add").val(),
      ncm: $("#ncm_produto_add").val(),
      cst_pis: $("#cst_pis_produto_add").val(),
      cst_cofins: $("#cst_cofins_produto_add").val(),
      cst_icms: $("#cst_icms_produto_add").val(),
      cst_unid_tributavel: $("#unid_tributavel").val(),
      custo_prod: $("#custo_produto_add").val(),
      preco_prod: $("#preco_produto_add").val(),
      pPIS: $("#pPIS_produto_add").val(),
      pCOFINS: $("#pCOFINS_produto_add").val(),
      quantidade_prod: $("#quantidade_produto_add").val(),
      preco_atacado_prod: $("#preco_atacado_produto_add").val(),
      icms_prod: $("#icms_produto_add").val(),
      produto_relacionado: $("#id_prod_relacionado").val()

    };


    produtos_add_array[index] = data_produto

    index = index + 1
    console.log(produtos_add_array[index] === undefined)
    console.log(produtos_add_array)
    last_mudanca = "plus"
    console.log(arrayRetorno.length)
    if (arrayRetorno.length <= index) {
      console.log("aqui")
      console.log(produtos_add_array)
      salvarProdutos(produtos_add_array)
      return
    }

    console.log(produtos_add_array)
    console.log(produtos_add_array[index] != "")
    if ($("#input_file_xml").val() != "") {
      if (produtos_add_array[index] === undefined) {
        changeValues(arrayRetorno[index], true)

      } else {
        changeValues(produtos_add_array[index], false)

      }

    }
    console.log(index)
    if (index + 1 == arrayRetorno.length) {
      $("#after_button_add").html(`Finalizar`)
    } else {
      $("#after_button_add").html(`Próximo (${index + 1}/${arrayRetorno.length}) <i class="fa-solid fa-arrow-right"></i>`)
    }
  } else {
    let data_produto = {
      cfop: $("#cfop_produto_add").val(),
      nome: $("#nome_produto_add").val(),
      cod: $("#codigo_produto_add").val(),
      unid_venda: $("#unid_vendas_produto_add").val(),
      ncm: $("#ncm_produto_add").val(),
      cst_pis: $("#cst_pis_produto_add").val(),
      cst_cofins: $("#cst_cofins_produto_add").val(),
      cst_icms: $("#cst_icms_produto_add").val(),
      cst_unid_tributavel: $("#unid_tributavel").val(),
      custo_prod: $("#custo_produto_add").val(),
      preco_prod: $("#preco_produto_add").val(),
      pPIS: $("#pPIS_produto_add").val(),
      pCOFINS: $("#pCOFINS_produto_add").val(),
      quantidade_prod: $("#quantidade_produto_add").val(),
      preco_atacado_prod: $("#preco_atacado_produto_add").val(),
      icms_prod: $("#icms_produto_add").val()
    };


    produtos_add_array[index] = data_produto

    index = index - 1
    last_mudanca = "minus"
    console.log(index)
    $("#after_button_add").html(`Próximo (${index + 1}/${arrayRetorno.length}) <i class="fa-solid fa-arrow-right"></i>`)

    changeValues(produtos_add_array[index], false)
  }
});
$(".pedido_feito").change(function () {
  let pedido = $(this).attr("pedido");
  data = {
    pedido: pedido,
  };
  $.post(
    include_path + "Models/post_receivers/update_pedido_feito.php",
    data,
    function (ret) {
      location.reload();
    }
  );
});

$(".modal_funcionarios").submit(function (e) {
  e.preventDefault();
  data = {
    adm: $('input[name="add_funcionario"]:checked').val(),
    nome: $("#input_add_usuario_nome").val(),
    codigo: $("#input_add_usuario_codigo").val(),
    caixa: $("#select_caixa_add_usuario").val(),
  };
  $.post(
    include_path + "Models/post_receivers/insert_colaborador.php",
    data,
    function (ret) {
      console.log(ret);
      if (ret != "ERROR") {
        $(".modal_adicionar_produto input").each(function () {
          console.log($(this).val());
        });
        location.reload();
      } else {
        alert("Código já cadastrado");
      }
    }
  );
});
$("#adicionar_caixa").submit(function (e) {
  e.preventDefault();
  data = {
    nome_caixa: $("#nome_caixa").val(),
    troco_inicial: $("#troco_inicial").val(),
  };

  $.post(
    include_path + "Models/post_receivers/insert_caixa.php",
    data,
    function (ret) {
      location.reload();
    }
  );
});
$(".modal_criar_loja").submit(function (e) {
  e.preventDefault()
  let data = {
    nome_loja: $("#nome_loja").val(),
    cep_loja: $("#cep_loja").val(),
    numero_loja: $("#numero_loja").val(),
    cMun_loja: $("#cMun_loja").val(),
    cUF: $("#cUF_loja").val(),
    IE_loja: $("#ie_loja").val(),
    CNPJ_loja: $("#cnpj_loja").val()
  }
  $.post(include_path + "Models/post_receivers/insert_caixa.php", data, (ret) => {
    console.log(ret)
  })
})
$("#add_caixa_opener").click(function () {
  $(".modal_criar_loja").css("display", "flex")
  $("fundo").css("display", "flex")

});
$("#form_equip").submit(function (e) {
  e.preventDefault();
  data = {
    caixa: $("#select_caixa").val(),
    impressora: $("#nome_impressora").val(),
    porta_balanca: $("#porta_balanca").val(),
    freq_balanca: $("#freq_balanca").val(),
  };

  $.post(
    include_path + "Models/post_receivers/insert_equipamentos.php",
    data,
    function (ret) {
      location.reload();
    }
  );
});
let confirmou = false;
$("#caixa_remover").click(function () {
  if (confirmou) {
    data = {
      caixa: $("#select_caixa").val(),
    };

    $.post(
      include_path + "Models/post_receivers/delete_caixa.php",
      data,
      function (ret) {
        location.reload();
      }
    );
  } else {
    $(this).text("Tem certeza?");
  }
  confirmou = true;
});
$("#select_caixa").change(function () {
  $("#form_equip").css("display", "block");
  $("#caixa_remover").css("display", "block");

  $("#form_equip red").text($(this).val() + ":");
  if ($(this).val() == "todos") {
    $("#form_equip").css("display", "none");
    $("#caixa_remover").css("display", "none");
  }
  data = {
    caixa: $(this).val(),
  };

  $.post(
    include_path + "Models/post_receivers/select_equipamentos.php",
    data,
    function (ret) {
      let infos = JSON.parse(ret);
      console.log(infos);
      $("#nome_impressora").val(infos.impressora);
      $("#porta_balanca").val(infos.porta_balanca);
      $("#freq_balanca").val(infos.velocidade_balanca);
    }
  );
});
$(".modal_funcionarios .fa-trash-can").click(function () {
  data = {
    id: $(this).attr("pessoa"),
  };
  $.post(
    include_path + "Models/post_receivers/delete_colaborador.php",
    data,
    function (ret) {
      location.reload();
    }
  );
});
//TODO ARRUMAR O GERAR CODIGO PHP (QUANDO CLICAR EM ADICIONAR PRODUTO)
function deletarProdutos() {
  $(".apagar_produto").click(function () {

    let produto_id = $(this).attr("produto");
    data = {
      id: produto_id,
    };
    $.post(
      include_path + "Models/post_receivers/delete_produto.php",
      data,
      function (ret) {
        $(".produto_" + produto_id).remove();
      }
    );
  });

}

deletarProdutos()
$("#salvar_caixa").click(function () {
  data = {
    caixa: $("#caixa_selecionado").val(),
    no_permission: true,
  };
  $.post(
    include_path + "Models/post_receivers/select_caixa.php",
    data,
    function (ret) {
      location.reload();
    }
  );
});
function gerarNotas(id_venda) {
  $.confirm({
    title: 'Gerar Notas',
    content: 'Qual nota deseja gerar ? ',
    boxWidth: '500px',
    useBootstrap: false,
    buttons: {
      nfe: {
        text: 'NF-e',
        btnClass: 'btn-orange',

        action: function () {
          $.alert('Something else?');
        }
      },
      nfce: {
        text: 'NFc-e',
        btnClass: 'btn-orange',

        action: function () {
          $.alert('Something else?');
        }
      },
      nnf: {
        text: 'Nota Não Fiscal',
        btnClass: 'btn-orange',

        action: function () {
          location.href = "https://localhost/SistemaAutolub/Models/post_receivers/gerarNota.php?venda=" + id_venda
        }
      },
      cancel: {
        text: 'Cancelar',

        action: function () {

        }
      }
    }
  });
}

$(".modal_anotar_pedido").submit(function (e) {
  e.preventDefault()
  let produtos = [];
  let valor_produtos = 0;
  //TODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTOD OTODOTODOTODO
  //ALTERAR OS ATTR DOS CHILDREN QUANDO MUDAR OS VALORES DE QUANTIDADE E PRECO UNITARIO
  //TODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODO

  $(".modal_anotar_pedido tbody")
    .children()
    .each(function (index) {
      valor_produtos = parseFloat(valor_produtos) + parseFloat($(this).attr("preco_produto") * parseFloat($(this).attr("quantidade")))
      let produto = {
        id: $(this).attr("produto"),
        quantidade: $(this).attr("quantidade"),
        preco: $(this).attr("preco_produto"),
      };
      produtos[index] = produto;
    });

  const data = {
    marca_veiculo: $("#marca_veiculo").val(),
    modelo_veiculo: $("#modelo_veiculo").val(),
    data_revisao: $("#data_revisao").val(),
    nome_cliente: $("#nome_cliente_input").val(),
    tel_cliente: $("#numero_cliente_input").val(),
    codigo_colaborador: $("#codigo_colaborador_input").val(),
    pre_venda: $("#pre_venda").val(),
    quilometragem: $("#quilometragem").val(),
    metodo_pagamento: $("#metodo_pagamento").val(),
    valor_mao_obra: $("#valor_mao_obra").val(),
    placa_veiculo: $("#placa_veiculo").val(),
    produtos: produtos,
    valor_produtos: valor_produtos,
    id_cliente: $("#cliente_id").val(),
    prazo: $("#prazo_cliente_input").val()
  }
  if (editando_troca_oleo) {
    data["id_venda"] = $("#venda_id").val()
    $.post("Models/post_receivers/update_troca_oleo.php", data, function (ret) {
      console.log(ret)
      alterarTabela();
      resetVenda()
    })
  }else{

  // if ($("#pre_venda").val() != true) {
  //   $.post("Models/post_receivers/insert_troca_oleo.php", data, function (ret) {
  //     console.log(ret)
  //     alterarTabela();
  //     resetVenda()
  //   })
  // } else {
  //   alert("GURI")
  // }
}


});




function exibirModalAnotarPedido() {
  $("fundo").css("display", "flex");
  $(".modal_anotar_pedido").css("display", "flex");
}
function mudarTempo(esse) {
  var dataNaTabela = moment($("#data_minima").val(), "YYYY-MM-DD");
  var dataAtual = moment();
  dataNaTabela = dataNaTabela.add(1, "days");
  if (
    dataNaTabela.format("DD/MM/YYYY") == dataAtual.format("DD/MM/YYYY") &&
    $(esse).attr("id") == "adiantar_semana"
  ) {
    $("#adiantar_semana").css("visibility", "hidden");
  } else {
    $("#adiantar_semana").css("visibility", "unset");
  }

  let dataMoment = moment($("#data_minima").val(), "YYYY-MM-DD");
  if ($(esse).attr("id") == "voltar_semana") {
    var dataNovaAtrasada = dataMoment.subtract(1, "days");

    $("#data_minima").val(dataNovaAtrasada.format("YYYY-MM-DD"));
    $("#data_maxima").val(dataNovaAtrasada.format("YYYY-MM-DD"));
  } else {
    var dataNovaAdiantada = dataMoment.add(1, "days");

    $("#data_minima").val(dataNovaAdiantada.format("YYYY-MM-DD"));
    $("#data_maxima").val(dataNovaAdiantada.format("YYYY-MM-DD"));
  }

  alterarTabela();
}

function alterarTabela() {
  gerarGráficos()

  data = {
    data_min: $("#data_minima").val(),
    data_max: $("#data_maxima").val(),
    caixa: $("#select_caixa").val(),
  };
  $.post(
    include_path + "Models/post_receivers/select_metricas.php",
    data,
    function (ret) {
      console.log(ret)
      row = JSON.parse(ret);
      console.log(row.totalValor)
      let valorTotalMetricas =
        parseFloat(row.totalValor) ? parseFloat(row.totalValor).toFixed(2) : "00,00";
      $(".pagamento_recorrente").text(row.formaPagamentoMaisRepetida);
      $(".quant_vendas").text(row.quantidadeVendas);
      $(".top_produto").text(row.produtoMaisVendido);
      console.log(valorTotalMetricas)
      $(".right_subdivision .valor_total").text("R$" + valorTotalMetricas);
    }
  );

  data = {
    data_min: $("#data_minima").val(),
    data_max: $("#data_maxima").val(),
    caixa: $("#select_caixa").val(),
  };
  $.post(
    include_path + "Models/post_receivers/select_vendas_periodo.php",
    data,
    function (ret) {
      console.log(ret)
      $(".tabela_father tbody").html(ret);
      if ($("#data_minima").val() == $("#data_maxima").val()) {
        var novaData = moment($("#data_minima").val(), "YYYY-MM-DD");
        var novaDataFormatada = novaData.format("DD/MM/YYYY");
        $(".tabela_header span").html(
          " Vendas no dia: <yellow>" +
          novaDataFormatada +
          "</yellow> <i onclick='gerarPDFFullFunction(this)' class='gerar_pdf fa-regular fa-file-pdf'></i>"
        );
      } else {
        var dataMomentMAX = moment($("#data_maxima").val(), "YYYY-MM-DD");
        var dataMAXFormatada = dataMomentMAX.format("DD/MM/YYYY");
        var dataMomentMIN = moment($("#data_minima").val(), "YYYY-MM-DD");
        var dataMINFormatada = dataMomentMIN.format("DD/MM/YYYY");
        $(".tabela_header span").html(
          " Vendas no período de: <yellow>" +
          dataMINFormatada +
          "</yellow> até <yellow>" +
          dataMAXFormatada +
          "</yellow> <i onclick='gerarPDFFullFunction()'  class='gerar_pdf fa-regular fa-file-pdf'></i>"
        );
      }
    }
  );

}
$("switch").click(function () {
  if ($("dot").attr("style").includes("left")) {
    $("dot").css("float", "right");
    data = {
      data_min: $("#data_minima").val(),
      data_max: $("#data_maxima").val(),
      caixa: $("#select_caixa").val(),
      switch: true,
    };
    $.post(
      include_path + "Models/post_receivers/select_podium.php",
      data,
      function (ret) {
        $(".tabela_father").remove();
        $("body").append(ret);
        var dataMomentMAX = moment($("#data_maxima").val(), "YYYY-MM-DD");
        var dataMAXFormatada = dataMomentMAX.format("DD/MM/YYYY");
        var dataMomentMIN = moment($("#data_minima").val(), "YYYY-MM-DD");
        var dataMINFormatada = dataMomentMIN.format("DD/MM/YYYY");
        $(".tabela_header span").html(
          " Vendas no período de: <yellow>" +
          dataMINFormatada +
          "</yellow> até <yellow>" +
          dataMAXFormatada +
          "</yellow> <i onclick='gerarPDFFullFunction()'class='gerar_pdf fa-regular fa-file-pdf'></i>"
        );
      }
    );
  } else {
    $("dot").css("float", "left");
    data = {
      data_min: $("#data_minima").val(),
      data_max: $("#data_maxima").val(),
      caixa: $("#select_caixa").val(),
      switch: true,
    };
    $.post(
      include_path + "Models/post_receivers/select_vendas_periodo.php",
      data,
      function (ret) {
        $(".tabela_father").remove();
        $("body").append(ret);
        var dataMomentMAX = moment($("#data_maxima").val(), "YYYY-MM-DD");
        var dataMAXFormatada = dataMomentMAX.format("DD/MM/YYYY");
        var dataMomentMIN = moment($("#data_minima").val(), "YYYY-MM-DD");
        var dataMINFormatada = dataMomentMIN.format("DD/MM/YYYY");
        $(".tabela_header span").html(
          " Vendas no período de: <yellow>" +
          dataMINFormatada +
          "</yellow> até <yellow>" +
          dataMAXFormatada +
          "</yellow> <i onclick='gerarPDFFullFunction()' class='gerar_pdf fa-regular fa-file-pdf'></i>"
        );
      }
    );
  }
});
$("#input_codigo_user").on("input", function () {
  data = {
    colaborador: $(this).val(),
  };
  $.post(
    include_path + "Models/post_receivers/select_colaborador.php",
    data,
    function (ret) {
      $("#input_nome_user").val(ret);
    }
  );
});





