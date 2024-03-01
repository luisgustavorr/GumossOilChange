<?php
require("./tfpdf/tfpdf.php");
require ('../../MySql.php');
date_default_timezone_set('America/Sao_Paulo');

$infos_VENDA_CLIENTE = \MySql::conectar()->prepare("SELECT `tb_vendas`.prazo,tb_colaboradores.nome as nome_vendedor,`tb_vendas`.colaborador,`tb_vendas`.forma_pagamento,tb_vendas.data,tb_vendas.valor - tb_vendas.valor_servico,`tb_clientes`.* FROM `tb_vendas` INNER JOIN `tb_clientes` ON tb_vendas.id_cliente = tb_clientes.id  INNER JOIN tb_colaboradores ON tb_vendas.colaborador = tb_colaboradores.codigo WHERE `tb_vendas`.id =?");
$infos_VENDA_CLIENTE->execute(array($_GET['venda']));
$infos_VENDA_CLIENTE = $infos_VENDA_CLIENTE->fetch();

$produtos = \MySql::conectar()->prepare("SELECT * FROM tb_produtos_vendidos WHERE id_venda = ? ");
$produtos->execute(array($_GET['venda']));
$produtos = $produtos->fetchAll();

$infos_LOJA = \MySql::conectar()->prepare("SELECT * FROM tb_lojas ");
$infos_LOJA->execute(array());
$infos_LOJA = $infos_LOJA->fetch();

// Cria um objeto FPDF
$pdf = new tFPDF("P", "mm", "A4");
$CNPJ = $infos_LOJA["CNPJ"];
$END = "Rua Levy da Cruz dos Santos";
$EMAIL = "afranio@autolub.com";
$BAIRRO = "SÃO BENTO";
$CIDADE = "Itaúna";
$TEL = "(37)9986-1532";
$CEP = "35681-476";
$NUMERO = $_GET['venda'];
$DATA_EMISSAO = date("d/m/Y");
$DATA_EMISSAO_S_FORMATACAO = date("Y-m-d");

$HORA_EMISSAO = date("H:m");
$VENDEDOR = $infos_VENDA_CLIENTE["colaborador"]." - ".$infos_VENDA_CLIENTE["nome_vendedor"];
$CLIENTE = $infos_VENDA_CLIENTE["id"]." - ".$infos_VENDA_CLIENTE["nome"];
$NOME_FANTASIA = $infos_VENDA_CLIENTE["nome_fantasia"];
$END_CLIENTE = $infos_VENDA_CLIENTE["nome_fantasia"].". ".$infos_VENDA_CLIENTE["numero"];
$BAIRRO_END_CLIENTE = $infos_VENDA_CLIENTE["bairro"];
$CPF_CNPJ = $infos_VENDA_CLIENTE["CPF"];
$TEL_CLIENTE = $infos_VENDA_CLIENTE["tel"];
$CIDADE_CLIENTE = $infos_VENDA_CLIENTE["municipio"]."/".$infos_VENDA_CLIENTE["estado"];

$CEP_CLIENTE =  $infos_VENDA_CLIENTE["CEP"];
$PRAZO_DIAS_MAX =  $infos_VENDA_CLIENTE["prazo"];

$PRAZO_DIAS_MIN = $infos_VENDA_CLIENTE["prazo"] - 3;
// Adiciona uma página ao documento
$pdf->AddPage();

// Define a fonte para o título

// Define a posição x inicial
$xInicial = 10;
//largura total = 297mm


// Define a posição y
$y = 1;

// Adiciona a primeira célula na esquerda
$pdf->SetXY($xInicial, $y);
$pdf->Cell(25, 25, '', 1, 0, "L");
$pdf->Image('../../Favicon/android-chrome-512x512.png', 14, 5, -700);
$pdf->AddFont('Roboto', '', 'Roboto-Regular.ttf', true);
$pdf->AddFont('Roboto_Bold', '', 'Roboto-Bold.ttf', true);


// Adiciona a segunda célula na direita
$x = $xInicial + 25; // Define a nova posição x
$pdf->SetXY($x, $y);
$pdf->SetFont("Roboto_Bold", "", 15);
$pdf->Cell(165,25, '', 1, 0, "R");
$pdf->Text($x + 60 , $y +5, "AUTOLUB");



$pdf->SetFont("Roboto", "", 8);
//primeira coluna

$pdf->Text($x + 5, $y + 12, "CNPJ : $CNPJ");
$pdf->Text($x + 5, $y + 20, "END : $END");

//segunda coluna
$distance_cnpj = $x + $pdf->GetStringWidth("CNPJ : $CNPJ") + 10;
$distance_end = $x + $pdf->GetStringWidth("END : $END") + 10;

$pdf->Text($distance_cnpj, $y + 12, "EMAIL : $EMAIL");
$pdf->Text($distance_end, $y + 20, "BAIRRO : $END");

$distance_tel = $distance_cnpj + $pdf->GetStringWidth("EMAIL : $EMAIL") + 5;
$distance_cidade = $distance_end + $pdf->GetStringWidth("BAIRRO : $END") + 5;

$pdf->Text($distance_tel, $y + 12, "TEL : $TEL");
$pdf->Text($distance_cidade, $y + 20, "CIDADE : $CIDADE ");

// terceira coluna
$distance_cep = $distance_tel + $pdf->GetStringWidth("TEL : $TEL") + 5;
$pdf->Text($distance_cep, $y + 12, "CEP : $CEP ");

//Segundo retangulo
$y = $y +25;
$x = $xInicial;
$pdf->SetFont("Roboto_Bold", "", 8);
$pdf->SetXY($x, $y);
$pdf->Cell(190, 6, '', 1, 1, "L");

$pdf->Text($x + 10, $y + 4, "Número : $NUMERO ");
$distance_numero = $x + 10 + $pdf->GetStringWidth("Numero : $NUMERO ") + 20;

$pdf->Text($distance_numero, $y + 4, "Data da Emisão : $DATA_EMISSAO ");

$distance_data = $distance_numero + $pdf->GetStringWidth("Data da Emisão : $DATA_EMISSAO  ") + 20;

$pdf->Text($distance_data, $y + 4, "$HORA_EMISSAO HORAS");

$distance_hora = $distance_data  + $pdf->GetStringWidth("$HORA_EMISSAO  HORAS") + 20;


$pdf->Text($distance_hora, $y + 4, "Vendedor : $VENDEDOR");

$y = $y + 6;
$x = $xInicial;
$pdf->SetXY($x, $y);


$x = $x + 10;
$pdf->Cell(190, 40, '', 1, 1, "L");
$pdf->SetFont("Roboto_Bold", "", 8);
$pdf->Text($x, $y + 6, "CLIENTE : $CLIENTE ");
$pdf->Text($x, $y + 15, "NOME_FANTASIA : $NOME_FANTASIA ");
$pdf->SetFont("Roboto", "", 8);
$pdf->Text($x, $y + 25, "Endereço : $END_CLIENTE ");
$pdf->Text($x, $y + 35, "Bairro : $BAIRRO_END_CLIENTE ");

$espacamento = $x + $pdf->GetStringWidth("CLIENTE : $CLIENTE ") + 30;
$pdf->SetFont("Roboto_Bold", "", 8);

$pdf->Text($espacamento, $y + 6, "CPF/CNPJ : $CPF_CNPJ ");
$pdf->SetFont("Roboto", "", 8);

$pdf->Text($espacamento, $y + 15, "Telefone : $TEL_CLIENTE ");
$pdf->Text($espacamento, $y + 25, "Cidade : $CIDADE_CLIENTE ");
$pdf->Text($espacamento, $y + 35, "CEP : $CEP_CLIENTE ");

$y = $y + 40;
$x = $xInicial;

$pdf->SetXY($x, $y);
$pdf->SetFont("Roboto_Bold", "", 8);
$pdf->Cell(15, 8, 'Cód.', 1, 0, "C");
$pdf->Cell(80, 8, 'Descrição', 1, 0, "C");
$pdf->Cell(31, 8, 'Grade', 1, 0, "C");
$pdf->Cell(10, 8, 'Unid.', 1, 0, "C");
$pdf->Cell(10, 8, 'Qtde.', 1, 0, "C");
$pdf->Cell(13, 8, 'Preço.', 1, 0, "C");
$pdf->Cell(10, 8, 'Desc.', 1, 0, "C");
$pdf->Cell(21, 8, 'Total.', 1, 0, "C");
$y = $y + 8;
$pdf->SetXY($x, $y);
$pdf->SetFont("Roboto", "", 7);
$linha= 0;
$total_calculado = 0;
$desconto = 0 ;
$acrescimo = 0;
foreach ($produtos as $key => $value) {
    $desconto = $desconto + number_format($value["desconto"],2,".") ;
$acrescimo = $acrescimo + number_format($value["acrescimo"],2,".") ;

    $produto = \MySql::conectar()->prepare("SELECT * FROM tb_produtos WHERE id = ? ");
$produto->execute(array($value['id_produto']));
$produto = $produto->fetch();
$total_produto =  number_format($produto["valor_venda"] *$value["quantidade_produto"] - $value["desconto"],2,".");
$total_calculado += $total_produto;
 $pdf->SetXY($x, $y);
$pdf->Cell(15, 8, $produto["id"], 1, 0, "C");
$pdf->Cell(80, 8, $produto["nome"], 1, 0, "C");
$pdf->Cell(31, 8, '0000 - GRADE PADRÃO', 1, 0, "C");
$pdf->Cell(10, 8, $produto["unid_comercial"], 1, 0, "C");
$pdf->Cell(10, 8,  $value["quantidade_produto"], 1, 0, "C");
$pdf->Cell(13, 8, $produto["valor_venda"] , 1, 0, "C");
$pdf->Cell(10, 8, '0.0', 1, 0, "C");
$pdf->Cell(21, 8, $total_produto , 1, 0, "C");
$y = $y + 8;

}


$pdf->SetXY($x, $y);
$pdf->Cell(190, 60, '', 1, 0, "C");
$x = $x + 10;
$pdf->SetFont("Roboto_Bold", "", 8);
$pdf->Text($x, $y + 6, "Forma de Pagamento :");
$y = $y + 6;
$pdf->SetFont("Roboto", "", 8);
$pdf->Text($x, $y + 6, "Receber  Prazo: $PRAZO_DIAS_MIN-$PRAZO_DIAS_MAX DIAS  Valor: R$".$total_calculado);
$pdf->SetFont("Roboto_Bold", "", 8);
$y = $y + 6;
$pdf->Text($x, $y + 6, "Vencimento : ".date('d/m/Y', strtotime($DATA_EMISSAO_S_FORMATACAO. ' + '.$PRAZO_DIAS_MAX.' days'))." - R$$total_calculado");
$y = $y + 6;
$pdf->Text($x, $y + 6, "Observações :");

$y = $y - 6 - 6;
$x = 150;
$pdf->SetFont("Roboto_Bold", "", 8);

$pdf->SetXY($x, $y);

$pdf->Text($x, $y, "Total Bruto :");
$pdf->SetFont("Roboto", "", 8);

$distancia = $x + $pdf->GetStringWidth("Total Bruto ") + 10;
$pdf->Text($distancia, $y, number_format($total_calculado,2,","));
$desconto = 0;

$acrescimo = 0;

$y = $y + 6;
$pdf->SetFont("Roboto_Bold", "", 8);

$pdf->Text($x, $y, "Desconto : ");

$pdf->SetFont("Roboto", "", 8);

$pdf->Text($distancia, $y, $desconto);

$y = $y + 6;
$pdf->SetFont("Roboto_Bold", "", 8);

$pdf->Text($x, $y, "Acréscimo :");
$pdf->SetFont("Roboto", "", 8);

$pdf->Text($distancia, $y, $acrescimo);


$y = $y + 6;
$pdf->SetFont("Roboto_Bold", "", 8);

$pdf->Text($x, $y, "Frete :");
$pdf->SetFont("Roboto", "", 8);

$pdf->Text($distancia, $y, "00,00");

$y = $y + 6;
$pdf->SetFont("Roboto_Bold", "", 8);
$pdf->Text($x, $y, "*Peso :");
$pdf->SetFont("Roboto", "", 8);

$pdf->Text($distancia, $y, "00.00");
$y = $y + 6;

$pdf->SetFont("Roboto_Bold", "", 9);
$pdf->Text($x, $y, "TOTAL : ");
$pdf->Text($distancia, $y, $total_calculado - $desconto + $acrescimo);

$pdf->SetFont("Roboto", "", 8);

$pdf->Text($x-35, $y+10, "_________________________________________________");
$pdf->Text($x-10, $y+14, "Assinatura");

$pdf->Text($x-110, $y+10, "_________/_________/_________");
$pdf->Text($x-100, $y+14, "Data de Entrega");

// Especifica o nome do arquivo de saída e exibe o PDF no navegador

$pdf->Output("./meu_pdf.pdf", "I");
