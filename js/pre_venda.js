
function abrirPreVenda() {
    $("#pre_venda").val(true)
    $(".modal_anotar_pedido .first_row").css("flex_direction", "row")
    $(".endereco_cliente_father").append($(".middle_row > div"))
    $("#data_revisao").removeAttr("required").parent().css("display", "none")
    $(".endereco_cliente_father").prepend($(".dates_father > .subdivision"))
    $("#numero_cliente_input").removeAttr("required").parent().css("display", "none")
    $("#quilometragem").removeAttr("required").parent().css("display", "none")
    $("#valor_mao_obra").removeAttr("required").parent().css("display", "none")
    $("#placa_veiculo").removeAttr("required").parent().css("display", "none")
    $("#marca_veiculo").removeAttr("required").parent().css("display", "none")
    $("#modelo_veiculo").removeAttr("required").parent().css("display", "none")

}

function fecharPreVenda() {
    $("#pre_venda").val(false)
    $(".modal_anotar_pedido .first_row").css("flex_direction", "column")
    $(".middle_row").append($(".endereco_cliente_father > .valor_caixa_father "))
    $("#data_revisao").attr("required", true).parent().css("display", "flex")
    $(".dates_father").append($(".endereco_cliente_father > .subdivision"))
    $("#numero_cliente_input").attr("required", true).parent().css("display", "flex")
    $("#quilometragem").attr("required", true).parent().css("display", "flex")
    $("#valor_mao_obra").attr("required", true).parent().css("display", "flex")
    $("#placa_veiculo").attr("required", true).parent().css("display", "flex")
    $("#marca_veiculo").removeAttr("required").parent().css("display", "flex")
    $("#modelo_veiculo").removeAttr("required").parent().css("display", "flex")
}
