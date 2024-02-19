gerarGráficos()
function gerarGráficos() {
  data = {
    data_min: $("#data_minima").val(),
    data_max: $("#data_maxima").val(),
    caixa:$("#select_caixa").val()
  };
  $.post(
    "Models/post_receivers/select_info_graficos.php",
    data,
    function (ret) {
        $(".chart_father").children().remove()
console.log(ret)
      let result_in_JSON = JSON.parse(ret);
   
          for (let key in result_in_JSON) {
            if (result_in_JSON.hasOwnProperty(key) && result_in_JSON[key]["array_valores"] !='') {
              console.log(key + ": " + result_in_JSON[key]["array_valores"]);
              console.log('a: '+result_in_JSON[key]["labels"])
              result_in_JSON[key]["array_valores"].unshift([
                "Veículos",
                result_in_JSON[key]['labels'],
                { role: "style" },
              ])
              iniciarAPIGoogle(result_in_JSON[key]["array_valores"], key.replace(/_/g, " "),'grafico_div_'+key);
            }
          }
         
      
    }
  );
}
function iniciarAPIGoogle(result_in_JSON, titulo_grafico,grafico_id) {
  $('.chart_father').css("display","block")
  $('.chart_father').css("visibility","hidden")

  google.charts.load("current", { packages: ["corechart", "bar"] });
  google.charts.setOnLoadCallback(drawDualY);
    $(".chart_father").prepend("<div class='chart_unic_father'id = '"+grafico_id+"' > </div>")
  function drawDualY() {
    console.log(result_in_JSON);
    // Criação da tabela de dados do Google Charts
    var data = google.visualization.arrayToDataTable(result_in_JSON);
    var options = {
      chart: {
        title: titulo_grafico,
        subtitle: "Baseado em uma escala de 1 para 1",
      },
      series: {
        0: { axis: "Vendas", color: 'rgb(36, 36, 36)' }, // Define a cor das barras da primeira série como vermelha
      },
      axes: {
        y: {
          Vendas: { label: "Atendimentos " },
        },
      },
      hAxis: {
        title: "Time of Day",
        format: "h:mm a",
        viewWindow: {
          min: [7, 30, 0],
          max: [17, 30, 0],
        },
      },
      vAxis: {
        title: "Em escala de 1 para 1",
      },
    };

    var materialChart = new google.charts.Bar(
      document.getElementById(grafico_id)
    );
    materialChart.draw(data, options);
  }
  setTimeout(function(){
    
    $('.chart_father').css("overflow-x","scroll")
    $('.chart_father').css("padding","15px 30px")

    $('.chart_father').css("display"," flex")
    $('.chart_father').css("visibility","inherit")

  },300)

}
