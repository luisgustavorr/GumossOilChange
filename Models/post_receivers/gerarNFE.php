<?php

include('../../MySql.php');
include("./sendNFE.php");
require __DIR__ . '/../../vendor/autoload.php';
date_default_timezone_set('America/Sao_Paulo');

use NFePHP\NFe\Make;
use NFePHP\DA\NFe\Danfe;
use NFePHP\NFe\Complements;
// $_COOKIE['last_codigo_colaborador']

$id_venda = 66;

$arrayRetorno = [
    'retorno' => [],

];

function findCEST($ncm)
{
    $arrayNCMNumbers = str_split($ncm);
    $cestJSON = file_get_contents("./CEST.json");
    $cestARRAY = json_decode($cestJSON, true);
    foreach ($arrayNCMNumbers as $key => $value) {
        $encontrou = false;
        $novoNCM = implode($arrayNCMNumbers);
        foreach ($cestARRAY as $arrayValue) {
            if (str_replace(".", "", $arrayValue['NCM/SH']) ===  $novoNCM) {
                // Se encontrar, imprimir o valor de "CEST" desse objeto
                return $arrayValue['CEST'];
                $encontrou = true;
                break; // Parar o loop após encontrar o objeto
            }
        }
        if ($encontrou) {
            break;
        } else {
            array_pop($arrayNCMNumbers);
        }
    }
}

// Percorrer o array para encontrar o objeto com "NCM/SH" igual a "3917"

if (isset($id_venda )) {


    $data_emissao = date('Y-m-d-H-i-s');
    $arrayRetorno['data'] = $data_emissao;

    $venda = \MySql::conectar()->prepare("SELECT *  FROM `tb_vendas` WHERE `tb_vendas`.`id` = ? ");
    $venda->execute(array($id_venda));
    $venda = $venda->fetch();

    $cookieteste = $venda["colaborador"];

    $colab = \MySql::conectar()->prepare("SELECT * FROM `tb_colaboradores` WHERE codigo = ?");
    $colab->execute(array($cookieteste));
    $colab = $colab->fetch();

    $caixa = \MySql::conectar()->prepare("SELECT * FROM `tb_lojas` WHERE  `caixa` = ?");
    $caixa->execute(array($colab['caixa']));
    $caixa = $caixa->fetch();





    $cliente = \MySql::conectar()->prepare("SELECT *  FROM `tb_clientes` WHERE `tb_clientes`.`id` = ? ");
    $cliente->execute(array($venda["id_cliente"]));
    $cliente = $cliente->fetch();

    $produtos_vendidos = \MySql::conectar()->prepare("SELECT  `tb_produtos_vendidos`.quantidade_produto,`tb_produtos_vendidos`.desconto, `tb_produtos_vendidos`.acrescimo,`tb_produtos`.*, ROUND((`tb_produtos`.`valor_venda` - `tb_produtos_vendidos`.`desconto` +`tb_produtos_vendidos`.`acrescimo`) * `tb_produtos_vendidos`.`quantidade_produto`,2) as valor_produtos  FROM `tb_produtos_vendidos` INNER JOIN  `tb_produtos` ON `tb_produtos`.id = `tb_produtos_vendidos`.id_produto WHERE `tb_produtos_vendidos`.`id_venda` = ?");
    $produtos_vendidos->execute(array($id_venda));
    $produtos_vendidos = $produtos_vendidos->fetchAll();
    $n_nfe = rand(0, 999) + rand(0, 999);

    $select_nfe = \MySql::conectar()->prepare("SELECT * FROM `tb_nfe` WHERE data_venda =  ?");
    $select_nfe->execute(array($venda['data']));
    $select_nfe = $select_nfe->fetchAll();

    // if (count($select_nfe) != 0) {
    //     $data_formatada = date("Y-m-d-H-i-s", strtotime($select_nfe[0]['data']));
    //     $arrayRetorno['data'] = $data_formatada;
    //     echo "aqui";
    //     print_r(json_encode($arrayRetorno));
    //     exit;
    // }
    $insert_nfe = \MySql::conectar()->prepare("INSERT INTO `tb_nfe` (`id`, `data`, `data_venda`, `numero_nfe`) VALUES (NULL, ?, ?, ?);");
    $insert_nfe->execute(array($data_emissao, $venda['data'], $n_nfe));
    $insert_nfe = $insert_nfe->fetchAll();



    $nome_empresa = $caixa['caixa'];
    $IE = $caixa['IE'] . "";
    $cUF =  $caixa['cUF'] . "";
    $CNPJ = $caixa['CNPJ'] . ""; //transformar em str

    $nfe = new Make();
    $std = new \stdClass();
    // '2023-12-28-09-20-30'
    function criarArquivoNFe($data_atual, $tipo, $arquivo)
    {

        [$ano, $mes, $dia, $hora, $minuto, $segundos] = explode('-', $data_atual);
        if (file_exists('../../NotasFiscais/NFe/' . $tipo . '/' . $ano)) {

            if (file_exists('../../NotasFiscais/NFe/' . $tipo . '/' . $ano . '/' . $mes)) {
                if (file_exists('../../NotasFiscais/NFe/' . $tipo . '/' . $ano . '/' . $mes . '/' . $dia)) {
                    file_put_contents('../../NotasFiscais/NFe/' . $tipo . '/' . $ano . '/' . $mes . '/' . $dia . '/' . $data_atual . '.' . $tipo, $arquivo);
                } else {
                    mkdir('../../NotasFiscais/NFe/' . $tipo . '/' . $ano . '/' . $mes . '/' . $dia, 0777, true);
                    file_put_contents('../../NotasFiscais/NFe/' . $tipo . '/' . $ano . '/' . $mes . '/' . $dia . '/' . $data_atual . '.' . $tipo, $arquivo);
                }
            } else {
                mkdir('../../NotasFiscais/NFe/' . $tipo . '/' . $ano . '/' . $mes . '/' . $dia, 0777, true);
                file_put_contents('../../NotasFiscais/NFe/' . $tipo . '/' . $ano . '/' . $mes . '/' . $dia . '/' . $data_atual . '.' . $tipo, $arquivo);
            }
        } else {
            mkdir('../../NotasFiscais/NFe/' . $tipo . '/' . $ano . '/' . $mes . '/' . $dia, 0777, true);
            file_put_contents('../../NotasFiscais/NFe/' . $tipo . '/' . $ano . '/' . $mes . '/' . $dia . '/' . $data_atual . '.' . $tipo, $arquivo);
        }
    };
    $std->versao = '4.00';
    $std->Id = null;
    $std->pk_nItem = '';
    $nfe->taginfNFe($std);

    $std = new \stdClass();
    $std->cUF = 31; //coloque um código real e válido
    $std->cNF = '97626321';
    $std->natOp = 'VENDA';
    $std->mod = 55;
    $std->serie = 1;
    $std->nNF = $n_nfe;
    $std->dhEmi = date('Y-m-d\TH:i:sP');
    $std->dhSaiEnt = date('Y-m-d\TH:i:sP');
    $std->tpNF = 1;
    $std->idDest = 1;
    $std->cMunFG = 3133808; //Código de município precisa ser válido
    $std->tpImp = 1;
    $std->tpEmis = 1;
    $std->cDV = 2;
    $std->tpAmb = 2; // Se deixar o tpAmb como 2 você emitirá a nota em ambiente de homologação(teste) e as notas fiscais aqui não tem valor fiscal
    $std->finNFe = 1;
    $std->indFinal = 1;
    $std->indPres = 0;
    $std->procEmi = '0';
    $std->verProc = 1;
    $nfe->tagide($std);

    $std = new \stdClass();
    $std->xNome = $nome_empresa;
    $std->IE = $IE;
    $std->CRT = 3;
    $std->CNPJ = $CNPJ;
    $nfe->tagemit($std);
    $infoEnd = json_decode(file_get_contents("https://viacep.com.br/ws/".$caixa['CEP']."/json/"), true);

    $std = new \stdClass();
    $std->xLgr = strtoupper($cliente["rua"]);
    $std->nro = $cliente['numero'];
    $std->xBairro = strtoupper($cliente["bairro"]);
    $std->cMun = "3133808 "; //Código de município precisa ser válido e igual o  cMunFG
    $std->xMun = $cliente["municipio"];
    $std->UF = $cliente["estado"];
    $std->CEP = str_replace("-", "", $cliente['CEP']) . "";
    $std->cPais = '1058';
    $std->xPais = 'BRASIL';

    $nfe->tagenderEmit($std);
    // $_POST["cpf_nfe"]; $_POST["nome_cliente"]


    $std = new \stdClass();
    $nome_cliente =  $cliente["nome"];

    $std->xNome = $nome_cliente;

    $std->indIEDest = 9;
    $std->IE = $cliente["ie"] . "";
    $cpf = $cliente["CPF"];

    $cpf_cliente = str_replace("/", "", str_replace(".", "", str_replace("-", "", $cpf)));
    if (strlen($cpf)  == 14) {
        $std->CNPJ = intval($cpf_cliente);
    } else {
        $std->CPF = intval($cpf_cliente);
    }

    $nfe->tagdest($std);

    $std = new \stdClass();
    $std->xLgr = strtoupper($cliente["rua"]);
    $std->nro = $cliente['numero'];
    $std->xBairro = strtoupper($cliente["bairro"]);
    $std->cMun = $caixa["cMunicipio"]; //Código de município precisa ser válido e igual o  cMunFG
    $std->xMun = $cliente["municipio"];
    $std->UF = $cliente["estado"];
    $std->CEP = str_replace("-", "", $cliente['CEP']) . "";
    $std->cPais = '1058';
    $std->xPais = 'BRASIL';
    $nfe->tagenderDest($std);

    $valor_total_produtos = 0;
    $valor_total_icms = 0;
    $totalICMS = 0;
    $valor_total_tributos = 0;
    $valor_servico = $venda["valor_servico"];

    $quantidade_produtos =  count($produtos_vendidos);
    $valor_servico_dividido =  $valor_servico / $quantidade_produtos;
    // echo $valor_servico_dividido;
    foreach ($produtos_vendidos as $key => $value) {
        $valor_produto = ($value['valor_venda'] - $value["desconto"] + $value["acrescimo"]) * $value['quantidade_produto'] + $valor_servico_dividido;
        $quantidade = $value['quantidade_produto'];
        $valor_total_produtos = $valor_produto + $valor_total_produtos;
        // print_r($venda);

        $arrayRetorno['retorno']['quantidade'] = $quantidade;


        $UN = $value["unid_comercial"];

        $pICMS = $value['pICMS'];
        $prodICMS =  $pICMS * $valor_produto;
        $totalICMS = $prodICMS + $totalICMS;
        $PIS = $value["pPIS"];
        $COFINS =  $value["pCOFINS"];

        $prodPIS = ($PIS / 100) * $valor_produto;
        $prodCOFINS = ($COFINS / 100) * $valor_produto;


        $IPI = 0;
        $valor_total_tributos = ($pICMS / 100 * $valor_produto) + ($IPI / 100 * $valor_produto) + ($PIS / 100 * $valor_produto) + ($COFINS / 100 * $valor_produto) +  $valor_total_tributos;
        $item = $key + 1;

        $valorICMS = number_format($valor_produto * ($pICMS / 100), 2, '.', '');
        $valor_total_icms = $valorICMS + $valor_total_icms;
        // $arrayRetorno['retorno'][$value['nome']] = number_format($valor_total_icms, 2, '.', '');
        $std = new \stdClass();
        $std->item = $item;
        $std->cEAN = 'SEM GTIN';
        $std->cEANTrib = 'SEM GTIN';
        $std->cProd = $value['codigo'];
        $std->xProd = $value['nome'];
        $std->NCM = str_replace('.', '', $value['ncm']);
        $std->CFOP = '5102';
        $std->uCom = $UN;
        $std->qCom = $quantidade;
        $std->vUnCom = str_replace(',', '.', $valor_produto);
        $std->vProd =   $valor_produto * $quantidade;
        $std->uTrib = $UN;
        $std->qTrib = $quantidade;
        $std->vUnTrib = str_replace(',', '.', $valor_produto);
        $std->indTot = 1;
        $nfe->tagprod($std);
        // echo $quantidade;
        // echo $valor_produto;
        // echo  str_replace(',', '.', $value["preco"]);

        $std = new \stdClass();
        $std->item = $item;
        $std->vTotTrib = 0.00;
        $nfe->tagimposto($std);

        $std = new \stdClass();
        $std->item = $item;
        $std->orig = 0;
        $std->CST =  $value["CST_ICMS"]; //codigo do icms (CST_ICMS na tabela) funciona com 00
        $std->modBC = 1;
        $std->vBC = $valor_produto; // valor do produto
        $std->pICMS = $pICMS; //porcentagem do icms (ICMS na tabela)
        $std->vICMS = $valorICMS; // valor icms porcentagem X valor do produto
        $nfe->tagICMS($std);
        if ($value["CST_ICMS"] == 60 or $value["CST_ICMS"] == 90 or $value["CST_ICMS"] == 10 or $value["CST_ICMS"] == 30 or $value["CST_ICMS"] == 70) {

            $std = new stdClass();
            $std->item =  $item; //item da NFe
            $std->CEST =  findCEST($value["ncm"]);
            $std->indEscala = 'S'; //incluido no layout 4.00
            $nfe->tagCEST($std);
        }

        $std = new \stdClass();
        $std->item = $item;
        $std->cEnq = '999';
        $std->CST = '50';
        $std->vIPI =  ($IPI / 100) * $valor_produto;
        $std->vBC = $valor_produto;
        $std->pIPI = $IPI;
        $nfe->tagIPI($std);

        $std = new \stdClass();
        $std->item = $item;
        $std->CST = '99'; //codigo do PIS (CST_PIS_COFINS na tabela)
        $std->vBC = $valor_produto;
        $std->pPIS = $PIS; //PIS DA MIX
        $std->vPIS = ($PIS / 100) * $valor_produto; // valor PIS porcentagem X valor do produto
        $nfe->tagPIS($std);

        $std = new \stdClass();
        $std->item = $item;
        $std->CST = '99';
        $std->vBC =  $valor_produto;
        $std->pCOFINS = $COFINS;
        $std->vCOFINS = $prodCOFINS;
        $std->qBCProd = $quantidade;
        $std->vAliqProd = $COFINS / 100;
        $nfe->tagCOFINS($std);

        $std = new \stdClass();
        $std->item = $item;
        $std->vCOFINS =   $prodCOFINS;
        $std->vBC = $valor_produto;
        $std->pCOFINS = $COFINS / 100;

        $nfe->tagCOFINSST($std);
    }


    $std = new \stdClass();

    $std->vBC = $prodICMS;
    $std->vICMS = $valor_total_icms;
    $std->vICMSDeson = null;
    $std->vBCST = 0;
    $std->vST = null;
    $std->vProd = $valor_total_produtos;
    $std->vFrete = null;
    $std->vSeg = null;
    $std->vDesc = null;
    $std->vII = null;
    $std->vIPI = null;
    $std->vPIS = null;
    $std->vCOFINS = null;
    $std->vOutro = null;
    $std->vNF = $valor_total_produtos;
    $std->vTotTrib = null;
    $nfe->tagICMSTot($std);

    //USAR EM CASO DE ENTREGA 
    //9 se nao for entrega e 3 se for
    $std = new \stdClass();
    $std->modFrete = 9;
    $nfe->tagtransp($std);

    // $std = new \stdClass();
    // $std->item = 1;
    // $std->qVol = 2;
    // $std->esp = 'caixa';
    // $std->marca = 'OLX';
    // $std->nVol = '11111';
    // $std->pesoL = 10.00;
    // $std->pesoB = 11.00;
    // $nfe->tagvol($std);

    $std = new \stdClass();
    $std->nFat = '002';
    $std->vOrig = 100;
    $std->vLiq = 100;
    $nfe->tagfat($std);

    //USAR QUANDO FOR PARCELA
    // $std = new \stdClass();
    // $std->nDup = '001';
    // $std->dVenc = date('Y-m-d');
    // $std->vDup = 11.03;
    // $nfe->tagdup($std);

    $std = new \stdClass();
    $std->vTroco = 0;
    $nfe->tagpag($std);

    $std = new \stdClass();
    $std->indPag = 0;
    $std->tPag = "01";
    $std->vPag = $valor_total_produtos;
    $nfe->tagdetPag($std);

    $xml = $nfe->getXML();
    $caminhoArquivo = './NFE/xml/Nfe_' . $data_emissao . '.xml';
    // Tenta salvar o XML no arquivo

    // if (file_put_contents($caminhoArquivo, $xml) !== false) {
    //     $arrayRetorno['retorno']['XML'] = 'XML da NFe salvo com sucesso!';
    // } else {
    //     $arrayRetorno['retorno']['errorXML'] =  'Erro ao salvar o XML da NFe.';
    // }

    $config  =  [
        "atualizacao" => date('Y-m-d h:i:s'),
        "tpAmb" => 2,
        "razaosocial" => $caixa["caixa"],
        "cnpj" => $caixa["CNPJ"] . "", // PRECISA SER VÁLIDO
        "ie" => $caixa["IE"] . "", // PRECISA SER VÁLIDO
        "siglaUF" => $infoEnd["uf"],
        "schemes" => "PL_009_V4",
        "versao" => '4.00',
        "tokenIBPT" => "AAAAAAA",
        "CSC" => $caixa["CSC"],
        "CSCid" => $caixa["CSCid"],
        "aProxyConf" => [
            "proxyIp" => "",
            "proxyPort" => "",
            "proxyUser" => "",
            "proxyPass" => ""
        ]
    ];
  

    $configJson = json_encode($config);
    // $certificadoDigital = file_get_contents('certificado.pfx');}
    $path = "../../certificados/" . strtoupper($infoEnd["logradouro"]) . "/";
    $diretorio = scandir($path);
    $arquivo = $diretorio[2];

    $caminhoCertificado = $path . $arquivo;

    $certificadoDigital = file_get_contents($caminhoCertificado);

    $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, '123456'));

    $xmlAssinado = $tools->signNFe($xml); // O conteúdo do XML assinado fica armazenado na variável $xmlAssinado


    try {
        $idLote = str_pad(100, 15, '0', STR_PAD_LEFT); // Identificador do lote
        $resp = $tools->sefazEnviaLote([$xmlAssinado], $idLote);
        $st = new NFePHP\NFe\Common\Standardize();
        $std = $st->toStd($resp);
        $xmlResposta;
        if ($std->cStat != 103) {
            //erro registrar e voltar
            // exit("[$std->cStat] $std->xMotivo");

        }
        // print_r($xmlAssinado);

        $recibo = $std->infRec->nRec; // Vamos usar a variável $recibo para consultar o status da nota
    } catch (\Exception $e) {
        //aqui você trata possiveis exceptions do envio
        exit($e->getMessage());
    }
    try {
        $protocolo = $tools->sefazConsultaRecibo($recibo);
        //print_r($protocolo);

    } catch (\Exception $e) {
        //aqui você trata possíveis exceptions da consulta

        exit($e->getMessage());
    };
    $request = $xmlAssinado;
    $response = $protocolo;
    try {
        $xml = Complements::toAuthorize($request, $response);
        try {
            criarArquivoNFe($data_emissao, 'xml', $xml);
            
            try {
                $logo = file_get_contents(realpath(__DIR__ . '/../../img/Logo mix.png'));
                $danfe = new Danfe($xml);
                $danfe->exibirTextoFatura = false;
                $danfe->exibirPIS = false;
                $danfe->exibirIcmsInterestadual = false;
                $danfe->exibirValorTributos = false;
                $danfe->descProdInfoComplemento = false;
                $danfe->exibirNumeroItemPedido = false;
                $danfe->setOcultarUnidadeTributavel(true);
                $danfe->obsContShow(false);
                $danfe->printParameters(
                    $orientacao = 'P',
                    $papel = 'A7',
                    $margSup = 2,
                    $margEsq = 2
                );
                $danfe->logoParameters($logo, $logoAlign = 'C', $mode_bw = false);
                $danfe->setDefaultFont($font = 'times');
                $danfe->setDefaultDecimalPlaces(4);
                $danfe->debugMode(false);
                $danfe->creditsIntegratorFooter('WEBNFe Sistemas - http://www.webenf.com.br');
                //$danfe->epec('891180004131899', '14/08/2018 11:24:45'); //marca como autorizada por EPEC
        
                // Caso queira mudar a configuracao padrao de impressao
                /*  $this->printParameters( $orientacao = '', $papel = 'A4', $margSup = 2, $margEsq = 2 ); */
                // Caso queira sempre ocultar a unidade tributável
                /*  $this->setOcultarUnidadeTributavel(true); */
                //Informe o numero DPEC
                /*  $danfe->depecNumber('123456789'); */
                //Configura a posicao da logo
                $danfe->logoParameters($logo, 'C', false);
                //Gera o PDF
                $pdf = $danfe->render($logo);
        
                criarArquivoNFe($data_emissao, 'pdf', $pdf);
        
                // if (file_put_contents('../../NFE/pdf/Nfe_' . $data_emissao . '.pdf', $pdf) !== false) {
                //     $arrayRetorno['retorno']['PDF'] =  'Sucesso Pdf';
                // } else {
                //     $arrayRetorno['retorno']['errorPDF'] = 'Erro ao salvar o XML da NFe.';
                // }
        
            } catch (InvalidArgumentException $e) {
                $arrayRetorno['retorno']['error'] = "Ocorreu um erro durante o processamento :" . $e->getMessage();
            }
            $arrayRetorno['data'] = $data_formatada;
            \sendNFEMail::enviar($cliente["email"],$cliente["nome"],$data_emissao);
            print_r(json_encode($arrayRetorno));
        } catch (\Exception $e) {
            //aqui você trata possíveis exceptions da assinatura
            echo "asdf";
        }

        // header('Content-type: text/xml; charset=UTF-8');

    } catch (\Exception $e) {
        echo "Err: " . $e->getMessage();
    }
}
