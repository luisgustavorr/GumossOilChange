function editarFechamento(esse_elemento) {
  $(".body_valores .first_column")
    .find(".valores_informados")
    .filter(function () {
      return $(this).attr("name") && $(this).attr("name").includes("informadas");
    })
    .each(function () {
      if (
        $(this).attr("disabled") == "disabled" &&
        $(this).attr("id") != "moedas_apuradas" 
      ) {
        $(this).removeAttr("disabled");
        $(".edit_fechamento_button").text("Editando Fechamento");

        setTimeout(function () {
          $(".dot1").css("animation", "bounce 1s infinite");
        }, 200);

        setTimeout(function () {
          $(".dot2").css("animation", "bounce 1s infinite");
        }, 400);

        setTimeout(function () {
          $(".dot3").css("animation", "bounce 1s infinite");
        }, 600);
      } else if ($(this).attr("id") != "moedas_apuradas") {
        $(this).attr("disabled", true);
        $(".edit_fechamento_button").text("Editar Fechamento");
      }
    });
}
