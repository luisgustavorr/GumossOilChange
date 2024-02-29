<?php
require("./tfpdf/tfpdf.php");

// Cria um objeto FPDF
$pdf = new tFPDF("L", "mm", "A4");
$CNPJ = "51.481.096/0001-98";
$END = "Rua Levy da Cruz dos Santos";
$EMAIL = "contato@crvcontabil.com";
$BAIRRO = "SÃO BENTO";
$CIDADE = "Itaúna";
$TEL = "(37) 984103402";
$CEP = "35680-037";
$NUMERO = "0000067";
$VENDEDOR = "0001 - Marisa";
$CLIENTE = "00000033  - R.A. COMÉRCIO E EMPREENDIEMNTOS LTDA";
$NOME_FANTASIA = "";
$END_CLIENTE = "RUA CALAMBAU. 701";
$BAIRRO_END_CLIENTE = "DISTRITO INDUSTRIAL";
$CPF_CNPJ = "52.334.167/00001-92";
$TEL_CLIENTE = "(37)3241-8249";
$CIDADE_CLIENTE = "ITAÚNA/MG";
$CEP_CLIENTE = "35681773";
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
$pdf->Cell(50, 40, '', 1, 0, "L");
$pdf->Image('../../Favicon/android-chrome-512x512.png', 22, 10, -500);
$pdf->AddFont('Roboto', '', 'Roboto-Regular.ttf', true);
$pdf->AddFont('Roboto_Bold', '', 'Roboto-Bold.ttf', true);


// Adiciona a segunda célula na direita
$x = $xInicial + 50; // Define a nova posição x
$pdf->SetXY($x, $y);
$pdf->SetFont("Roboto_Bold", "", 20);
$pdf->Cell(227, 40, '', 1, 0, "R");
$pdf->Text($x + 90, $y + 10, "AUTOLUB");



$pdf->SetFont("Roboto", "", 10);
//primeira coluna

$pdf->Text($x + 10, $y + 20, "CNPJ : $CNPJ");
$pdf->Text($x + 10, $y + 30, "END : $END");

//segunda coluna
$distance_cnpj = $x + $pdf->GetStringWidth("CNPJ : $CNPJ") + 25;
$distance_end = $x + $pdf->GetStringWidth("END : $END") + 25;

$pdf->Text($distance_cnpj, $y + 20, "EMAIL : $EMAIL");
$pdf->Text($distance_end, $y + 30, "BAIRRO : $END");

$distance_tel = $distance_cnpj + $pdf->GetStringWidth("EMAIL : $EMAIL") + 15;
$distance_cidade = $distance_end + $pdf->GetStringWidth("BAIRRO : $END") + 15;

$pdf->Text($distance_tel, $y + 20, "TEL : $TEL");
$pdf->Text($distance_cidade, $y + 30, "CIDADE : $CIDADE ");

// terceira coluna
$distance_cep = $distance_tel + $pdf->GetStringWidth("TEL : $TEL") + 15;
$pdf->Text($distance_cep, $y + 20, "CEP : $CEP ");

//Segundo retangulo
$y = $y + 40;
$x = $xInicial;
$pdf->SetFont("Roboto_Bold", "", 10);
$pdf->SetXY($x, $y);
$pdf->Cell(277, 10, '', 1, 1, "L");

$pdf->Text($x + 40, $y + 6, "Número : $NUMERO ");
$distance_numero = $x + 40 + $pdf->GetStringWidth("Numero : $NUMERO ") + 20;

$pdf->Text($distance_numero, $y + 6, "Data da Emisão : $CEP ");

$distance_data = $distance_numero + $pdf->GetStringWidth("Data da Emisão : $CEP ") + 20;

$pdf->Text($distance_data, $y + 6, "13:10 HORAS");

$distance_hora = $distance_data  + $pdf->GetStringWidth("13:10 HORAS") + 20;


$pdf->Text($distance_hora, $y + 6, "Vendedor : $VENDEDOR");

$y = $y + 10;
$x = $xInicial;
$pdf->SetXY($x, $y);
$pdf->SetFont("Roboto", "", 10);

$x = $x + 10;
$pdf->Cell(277, 40, '', 1, 1, "L");
$pdf->SetFont("Roboto_Bold", "", 10);
$pdf->Text($x, $y + 6, "CLIENTE : $CLIENTE ");
$pdf->Text($x, $y + 15, "NOME_FANTASIA : $NOME_FANTASIA ");
$pdf->SetFont("Roboto", "", 10);
$pdf->Text($x, $y + 25, "Endereço : $END_CLIENTE ");
$pdf->Text($x, $y + 35, "Bairro : $BAIRRO_END_CLIENTE ");

$espacamento = $x + $pdf->GetStringWidth("CLIENTE : $CLIENTE ") + 30;
$pdf->Text($espacamento, $y + 6, "CPF/CNPJ : $CPF_CNPJ ");
$pdf->Text($espacamento, $y + 15, "Telefone : $TEL_CLIENTE ");
$pdf->Text($espacamento, $y + 25, "Cidade : $CIDADE_CLIENTE ");
$pdf->Text($espacamento, $y + 35, "CEP : $CEP_CLIENTE ");

$y = $y + 40;
$x = $xInicial;

$pdf->SetXY($x, $y);
$pdf->SetFont("Roboto_Bold", "", 10);
$pdf->Cell(20, 8, 'Cód.', 1, 0, "C");
$pdf->Cell(132, 8, 'Descrição', 1, 0, "C");
$pdf->Cell(40, 8, 'Grade', 1, 0, "C");
$pdf->Cell(15, 8, 'Unid.', 1, 0, "C");
$pdf->Cell(15, 8, 'Qtde.', 1, 0, "C");
$pdf->Cell(15, 8, 'Preço.', 1, 0, "C");
$pdf->Cell(15, 8, 'Desc.', 1, 0, "C");
$pdf->Cell(25, 8, 'Total.', 1, 0, "C");
$y = $y + 8;
$pdf->SetXY($x, $y);
$pdf->SetFont("Roboto", "", 10);

$pdf->Cell(20, 8, '000037', 1, 0, "C");
$pdf->Cell(132, 8, 'ÓLÉO RIMULA R3 EXTRA 15W40 Cl-4 20L', 1, 0, "C");
$pdf->Cell(40, 8, '0000 - GRADE PADRÃO', 1, 0, "C");
$pdf->Cell(15, 8, 'BD', 1, 0, "C");
$pdf->Cell(15, 8, '1.00', 1, 0, "C");
$pdf->Cell(15, 8, '300,00', 1, 0, "C");
$pdf->Cell(15, 8, '0.0', 1, 0, "C");
$pdf->Cell(25, 8, '400.00', 1, 0, "C");
$y = $y + 8;

$pdf->SetXY($x, $y);
$pdf->Cell(277, 60, '', 1, 0, "C");
$x = $x + 10;
$pdf->SetFont("Roboto_Bold", "", 10);
$pdf->Text($x, $y + 6, "Forma de Pagamento :");
$y = $y + 6;
$pdf->SetFont("Roboto", "", 10);
$pdf->Text($x, $y + 6, "Receber  Prazo: 12-15 DIAS  Valor: R$500");
$pdf->SetFont("Roboto_Bold", "", 10);
$y = $y + 6;
$pdf->Text($x, $y + 6, "Vencimento : 10/03/2024 - R$500");
$y = $y + 6;
$pdf->Text($x, $y + 6, "Observações :");

$y = $y - 6 - 6;
$x = 240;
$pdf->SetFont("Roboto_Bold", "", 10);

$pdf->SetXY($x, $y);

$pdf->Text($x, $y, "Total Bruto :");
$pdf->SetFont("Roboto", "", 10);

$distancia = $x + $pdf->GetStringWidth("Total Bruto ") + 10;
$pdf->Text($distancia, $y, "500,00");

$y = $y + 6;
$pdf->SetFont("Roboto_Bold", "", 10);

$pdf->Text($x, $y, "Desconto : ");

$pdf->SetFont("Roboto", "", 10);

$pdf->Text($distancia, $y, "00,00");

$y = $y + 6;
$pdf->SetFont("Roboto_Bold", "", 10);

$pdf->Text($x, $y, "Acréscimo :");
$pdf->SetFont("Roboto", "", 10);

$pdf->Text($distancia, $y, "00,00");


$y = $y + 6;
$pdf->SetFont("Roboto_Bold", "", 10);

$pdf->Text($x, $y, "Frete :");
$pdf->SetFont("Roboto", "", 10);

$pdf->Text($distancia, $y, "00,00");

$y = $y + 6;
$pdf->SetFont("Roboto_Bold", "", 10);
$pdf->Text($x, $y, "*Peso :");
$pdf->SetFont("Roboto", "", 10);

$pdf->Text($distancia, $y, "00.00");
$y = $y + 6;

$pdf->SetFont("Roboto_Bold", "", 15);
$pdf->Text($x, $y, "TOTAL : 500,00");
$pdf->SetFont("Roboto", "", 10);

$pdf->Text($x-40, $y+10, "_________________________________________________");
$pdf->Text($x-10, $y+14, "Assinatura");

$pdf->Text($x-140, $y+10, "_________/_________/_________");
$pdf->Text($x-130, $y+14, "Data de Entrega");

// Especifica o nome do arquivo de saída e exibe o PDF no navegador

$pdf->Output("./meu_pdf.pdf", "I");
