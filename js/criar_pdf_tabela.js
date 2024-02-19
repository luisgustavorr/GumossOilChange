

 function gerarPDFFullFunction(esse) {
  const content = document.querySelector("#table_tabela")       
  const options = {
    margin:[10,10,10,10],
    filename:"arquivo.pdf",
    html2canvas:{scale:2},
    jsPDF:{unit:"mm",format:"A4",orientation:"portrait"}
  }       
  html2pdf().set(options).from(content).save()
}
