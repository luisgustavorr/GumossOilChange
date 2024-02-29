<script>
    $(function() {
        $(document).tooltip();
    });
</script>
<!-- <div class="saving_products">

    <h3 class="saving_header">
        <div class="loading_saving_products"></div> Salvando Produtos...
    </h3>
    <div class="progress_saving_bar">  
        <div class="progress_saving">
            
        </div>
    </div>
</div> -->
<form class="modal modal_adicionar_produto">
    <input type="text" name="id_prod_relacionado"hidden id="id_prod_relacionado">
    <input type="file" name="input_file_xml" id="input_file_xml">

    <div class="first_input_row">

        <div class="inputs input_nome_produto_add">
            <label for="">Nome do produto:</label><br />
            <input class="oders_inputs" type="text" placeholder="Digite o nome do Produto" name="nome_produto_add" id="nome_produto_add" required>
        </div>
        <div style="display: none;" class="warning_add_produto">
            <span>Código Repetido, caso não seja alterado o produto será identificado como : aumentará o estoque do mesmo para : . Caso queira cadastrar como um novo produto altere o campo "Código do produto"</span>
        </div>
    </div>
    <div class="second_input_row">
        <?php
        $ultimo_id = \MySql::conectar()->prepare("SELECT `id` FROM `tb_produtos` ORDER BY `id` DESC LIMIT 1;");
        $ultimo_id->execute();
        $ultimo_id = $ultimo_id->fetch() ?>
        <input type="hidden" id="next_code_id" value="<?php echo $ultimo_id["id"] ?>">
        <div class="inputs input_nome_produto_add">
            <label for="">Quantidade do produto:</label><br />
            <input class="oders_inputs" type="text" placeholder="Digite o nome do Produto" name="quantidade_produto_add" id="quantidade_produto_add" required>
        </div>

        <div class="inputs input_codigo_produto_add">
            <label for="">Código do produto:</label><br />
            <input class="oders_inputs" type="text" placeholder="Digite o Código de barras do Produto" name="codigo_produto_add" id="codigo_produto_add" required>
        </div>
        <div class="inputs input_codigo_produto_add">
            <label for="">CFOP :</label><br />
            <input class="oders_inputs" type="text" placeholder="Digite o CFOP" name="cfop_produto_add" id="cfop_produto_add" required>
        </div>
        <div class="inputs input_preco_produto_add">
            <label for="">Valor de Compra:</label><br />
            <input class="oders_inputs" type="text" placeholder="Digite o custo do Produto" name="custo_produto_add" id="custo_produto_add" required>
        </div>
        <div class="inputs input_preco_produto_add">
            <label for="">Valor de Venda:</label><br />
            <input class="oders_inputs" type="text" placeholder="Digite o valor de venda do Produto" name="preco_produto_add" id="preco_produto_add" required>
        </div>
        <div class="inputs input_preco_produto_add">
            <label for="">Valor no Atacado:</label><br />
            <input class="oders_inputs" type="text" placeholder="Digite o valor de venda em atacado do Produto" name="preco_atacado_produto_add" id="preco_atacado_produto_add" required>
        </div>
        <div class="inputs input_codigo_produto_add">
            <label for="">Unid Comercial:</label><br />
            <input class="oders_inputs" type="text" placeholder="Digite a unid de venda do produto" name="unid_vendas_produto_add" id="unid_vendas_produto_add" required>
        </div>
        <div class="inputs input_codigo_produto_add">
            <label for="">Unid Tributável:</label><br />
            <input class="oders_inputs" type="text" placeholder="Digite a unid de tributação do produto" name="unid_tributavel" id="unid_tributavel" required>
        </div>

        <div class="inputs input_codigo_produto_add">
            <label for="">NCM:</label><br />
            <input class="oders_inputs" type="text" placeholder="Digite o NCM do produto" name="ncm_produto_add" id="ncm_produto_add" required>
        </div>

        <div class="inputs input_codigo_produto_add">
            <label for="">CST ICMS:</label><br />
            <input class="oders_inputs" type="text" placeholder="Digite o CST do ICMS" name="cst_icms_produto_add" id="cst_icms_produto_add" required>
        </div>

        <div class="inputs input_codigo_produto_add">
            <label for="">CST PIS:</label><br />
            <input class="oders_inputs" type="text" placeholder="Digite o CST do PIS " name="cst_pis_produto_add" id="cst_pis_produto_add" required>
        </div>
        <div class="inputs input_codigo_produto_add">
            <label for="">CST COFINS:</label><br />
            <input class="oders_inputs" type="text" placeholder="Digite o CST do COFINS" name="cst_cofins_produto_add" id="cst_cofins_produto_add" required>
        </div>
        <div class="inputs input_codigo_produto_add">
            <label for="">% ICMS:</label><br />
            <input class="oders_inputs porcentagem" type="text" placeholder="Pocentagem ICMS. Ex: 18 para 18%" name="icms_produto_add" id="icms_produto_add" required>
        </div>
        <div class="inputs input_codigo_produto_add">
            <label for="">% COFINS:</label><br />
            <input class="oders_inputs porcentagem" type="text" placeholder="Digite a porcentagem do COFINS" name="pCOFINS_produto_add" id="pCOFINS_produto_add">
        </div>
        <div class="inputs input_codigo_produto_add">
            <label for="">% PIS:</label><br />
            <input class="oders_inputs porcentagem" value="%" type="text" placeholder="Digite a porcentagem do PIS" name="pPIS_produto_add" id="pPIS_produto_add">
        </div>


        <label id="input_file_label" for="input_file_xml"> Importar XML</label>

        <div id="father_add_produto_button">
            <button id="before_button_add"><i class="fa-solid fa-arrow-left"></i> Anterior</button>
            <!-- 
        <button id="finalziar_button_add">Finalizar</button> -->

            <button id="after_button_add">Finalizar</button>

        </div>
    </div>
</form>
<form class="modal modal_criar_loja">
    <h3>Adicionar <red>Loja</red>
    </h3>
    <section>
        <div class="left_side">
            <div class="input_father">
                <label for="">Nome</label>
                <input type="text" name="" id="nome_loja">
            </div>
            
            <span>Endereço</span>
            <div class="input_father">
                <label for="">CEP</label>
                <input type="text" name="" id="cep_loja">
            </div>

            <div class="input_father">
                <label for="">Número</label>
                <input type="text" name="" id="numero_loja">
            </div>

            <div class="input_father">
                <label for="">Código do Município</label>
                <input type="text" name="" id="cMun_loja">
            </div>

            <div class="input_father">
                <label for="">Código da UF</label>
                <input type="text" name="" id="cUF_loja">
            </div>
        </div>
        <div class="right_side">
            <span>Dados Fiscais</span>
            <div class="input_father">
                <label for="">IE</label>
                <input type="text" name="" id="ie_loja">
            </div>
            <div class="input_father">
                <label for="">CNPJ</label>
                <input type="text" name="" id="cnpj_loja">
            </div>
            <button>ADICIONAR</button>
        </div>
    </section>
</form>
<form class="modal modal_clientes">
    <h3>Adicionar <red>Cliente</red>
    </h3>
    <section>
        <div class="left_side">
        <div class="cnpj_selector">
        <div class="selector">PF</div>
    </div>
    <span>Identificação</span>

            <div class="input_father">
                <label for="">Nome</label>
                <input type="text" name="" id="nome_cliente" required>
            </div>

            <span>Endereço</span>
            <div class="input_father">
                <label for="">CEP</label>
                <input type="text" name="" id="cep_cliente" required>
            </div>


            <div class="input_father">
                <label for="">Estado</label>
                <input type="text" name="" id="uf_cliente" required>
            </div>

            <div class="input_father">
                <label for="">Município</label>
                <input type="text" name="" id="municipio_cliente" required>
            </div>
            <div class="input_father">
                <label for="">Bairro</label>
                <input type="text" name="" id="bairro_cliente" required>
            </div>
            <div class="input_father">
                <label for="">Rua</label>
                <input type="text" name="" id="rua_cliente" required>
            </div>
            
            <div class="input_father">
                <label for="">Número</label>
                <input type="text" name="" id="numero_cliente" required>
            </div>
        </div>
        <div class="right_side">
            <span>Dados Fiscais</span>
            <div class="input_father">
                <label for="">CPF</label>
                <input type="text" name="" id="cnpj_cliente" required>
            </div>
            <span>Contato</span>
            <div class="input_father">
                <label for="">Tel</label>
                <input type="text" name="" id="tel_cliente" required>
            </div>
            <div class="input_father">
                <label for="">Email</label>
                <input type="text" name="" id="email_cliente" required>
            </div>
            <button>ADICIONAR</button>
        </div>
    </section>
</form>

<form class="modal modal_produtos">

    <table>
        <thead>
            <tr>

                <th>Nome</th>
                <th>Código</th>
                <th>Custo</th>
                <th>V. Venda</th>
                <th>V. Atacado</th>
                <th>Estoque</th>
                <th>Editar</th>
                <th>Excluir</th>

            </tr>
        </thead>
        <tbody>

            <?php
            $caixas = \MySql::conectar()->prepare("SELECT * FROM `tb_produtos` ORDER BY relacionado DESC;");
            $caixas->execute();
            $caixas = $caixas->fetchAll();
            $relacionadosOrange = true;
            $lastRelacionado = 0;
            foreach ($caixas as $key => $value) {
                if($value["relacionado"] != ""){
                    if($relacionadosOrange){
                    
                        $icon = '<i class="fa-solid fa-link" style="color: #F53F00;"></i>';
                    }else{
                        $icon = '<i class="fa-solid fa-link" style="color: #242424;"></i>';

                    }
                    if($lastRelacionado == $value["relacionado"]){
                         $relacionadosOrange = !$relacionadosOrange;
                    }
                }else{
                    $icon = "";
                }
       
                $lastRelacionado = $value["relacionado"];
                $estoque = $value["quantidade"] != 0 ? $value["quantidade"] : '<i title="Produto sem estoque, caso isso seja um erro altere o estoque no campo Quantidade ao editar se esse produto." class="fa-solid fa-triangle-exclamation fa-beat" style="color: #ff0000;"></i>';
                echo '<tr class =" produto_' . $value['id'] . '" value="' . $value['id'] . '">
                                <td class="nome">'.$icon.' ' . ucfirst($value['nome']) . '</td>
                                <td class="codigo">' . ucfirst($value['codigo']) . '</td>
                                <td class="preco">R$' . $value["valor_compra"] . '</td>

                                <td class="preco">R$' . $value["valor_venda"] . '</td>
                                <td class="preco">R$' . $value["valor_atacado"] . '</td>

                                <td class="pesado">' . $estoque . '</td>
                                <td ><i produto="' . $value['id'] . '" class="fa-solid editar_produto fa-pen"></i></td>

                                <td><i title="Excluir Permanentemente" produto="' . $value['id'] . '" class="fa-solid apagar_produto fa-trash-can"></i></td>

                                </tr>';
            }
            ?>
        </tbody>
    </table>

    <div class="inputs_add_produto">

        <span id="add_produto_opener" onclick="abrirModal('modal_adicionar_produto')">Adicionar Produto</span>
        <div class="input_pesquisar_produto_father">
            <input type="text" placeholder="Pesquisar Produto" id="pesquisar_produto">
            <div id="pesquisar_produto_button"><i class="fa-solid fa-magnifying-glass"></i></div>
        </div>
    </div>

</form>

</div>
<form class="modal modal_anotar_pedido">
<input type="hidden" id="cliente_id" value="false" disabled>
    
    <input type="hidden" id="pre_venda" value="false" disabled>
    <input type="hidden" id="pedido_id" value="false" disabled>
    <div class="valor_caixa_father input_father">
        <span>Data da Revisão:</span>
        <input type="datetime-local" name="data_revisao" value="<?php echo (new DateTime())->format('Y-m-d\TH:i') ?>" id="data_revisao">

    </div>
    <div class="first_row">
        <div class="colaborador_father input_father">
            <div class="subdivision">

                <span>Nome do Cliente <red>(Opcional)</red></span>
                <input type="text" name="nome_cliente" class="oders_inputs" id="nome_cliente_input" placeholder="Insira o nome do cliente">
            </div>
            <div class="subdivision">

                <span>Tel. Cliente:</span>
                <input required type="text" name="numero_cliente" class="oders_inputs" id="numero_cliente_input" placeholder="Insira o numero do cliente">
            </div>
            <div class="subdivision">

                <span>Seu código:</span>
                <input required type="text" name="codigo_colaborador" value="<?php if (isset($_COOKIE['last_codigo_colaborador'])) echo $_COOKIE['last_codigo_colaborador'] ?>" class="oders_inputs colab_code" id="codigo_colaborador_input" placeholder="Insira o seu código">
            </div>

        </div>

        <div class="endereco_cliente_father">
 
            <div class="valor_sangria_father input_father">
                <span>KM:</span>
                <input type="text" value="" class="oders_inputs" name="quilometragem" id="quilometragem">
            </div>
            <div class="input_select">
                <span>Método de Pagamento:</span>
                <select name="metodo_pagamento" class="pagamento_input" id="metodo_pagamento">
                    <option value="Cartão Crédito">Cartão Crédito</option>
                    <option value="Dinheiro">Dinheiro</option>
                    <option value="Cartão Débito">Cartão Débito</option>
                    <option value="Pix">Pix</option>


                </select>
            </div>
            <div class="valor_sangria_father input_father">

                <span>Mão de Obra:</span>
                <input type="text" value="" onKeyUp="mascaraMoeda(this, event)" class="oders_inputs" name="valor_mao_obra" id="valor_mao_obra">
            </div>
        </div>

        <div class="entrega_retirada_father">


        </div>
    </div>
    <div class="middle_row">

        <div class="valor_caixa_father input_father">
            <span  medida="un" class="select_valor_clicker_father" button_identifier="pedido">Placa do Veículo:</span>
            <input required type="text" class="oders_inputs pedido_button" name="placa_veiculo" placeholder="AAA-0000" id="placa_veiculo">

        </div>
        <div class="valor_caixa_father input_father">
            <span  medida="un" class="select_valor_clicker_father" button_identifier="pedido">Marca do Veículo:</span>
            <input required type="text" class="oders_inputs pedido_button" name="marca_veiculo" placeholder="Marca" id="marca_veiculo">

        </div>
        <div class="valor_caixa_father input_father">
            <span  medida="un" class="select_valor_clicker_father" button_identifier="pedido">Modelo do Veículo:</span>
            <input required type="text" class="oders_inputs pedido_button" name="modelo_veiculo" placeholder="Modelo" id="modelo_veiculo">

        </div>

        <div class="valor_caixa_father input_father">
            <span  medida="un" class="select_valor_clicker_father" button_identifier="pedido">Quantidade:</span>
            <input type="text" class="oders_inputs pedido_button" name="quantidade_produto_pedido" value="1" id="quantidade_produto_pedido">

        </div>
        <div class="valor_caixa_father input_father">
            <span>Nome produto:</span>
            <input type="text" class="tags_produto_name oders_inputs" name="nome_produto_pedido" laceholder="Nome do produto" id="nome_produto_pedido">

        </div>

    </div>

    <div class="second_row">



    </div>
    <table>
        <thead>
            <tr>
                <th>Qt</th>
                <th>Produto</th>
                <th>Valor Unit.</th>
                <th>Valor Total</th>
                <th>Remover</th>

            </tr>
        </thead>
        <tbody>


        </tbody>
    </table>
    <div class="button_finalizar_father">
        <button id="finaliza_sangria_button" class="finalizar_button">Finalizar Operação</button>
    </div>
</form>
<aside id="sidebar">
    <i class="open_sidebar_arrow fa-solid fa-angles-right"></i>
    <div class="princip_span" id="pre_venda_opener" onclick="abrirModal('modal_anotar_pedido') ; abrirPreVenda()"> <i class="fa-solid fa-cash-register"></i> <span>Pré-Venda </span> </div>
    <div class="princip_span" id="troca_oleo" onclick="abrirModal('modal_anotar_pedido'); fecharPreVenda()"> <i class="fa-solid fa-oil-can"></i> <span>Cadastrar Troca de Óleo </span> </div>
    <div class="princip_span" onclick="abrirModal('modal_produtos');"  id="produtos_opener"><i  class="fa-solid fa-cart-shopping"></i> <span>Produtos </span> </div>
  
    <div class="princip_span" id="clientes_opener" onclick="abrirModal('modal_clientes')" > <i class="fa-solid fa-user-group"></i> <span>Adicionar Clientes </span> </div>
    <div class="princip_span" id="add_caixa_opener"><i class="fa-solid fa-house-medical"></i> <span>Adicionar Loja</span> </div>

</aside>
<fundo></fundo>
<form action="" class="modal modal_fechar_caixa">
    <!-- <i id="print_fechamento" class="fa-solid fa-print"></i> -->
    <div class="header_modal_fechar_caixa" style="display: flex;">
        <h3> Funcionário(a): <red><select id="caixa_ser_fechado">
                    <option disabled selected value="">Clique para selecionar</option>
                    <?php
                    $caixas = \MySql::conectar()->prepare("SELECT `tb_colaboradores`.* FROM  `tb_colaboradores`;");
                    $caixas->execute();
                    $caixas = $caixas->fetchAll();
                    foreach ($caixas as $key => $value) {
                        echo '<option value="' . $value['caixa'] . '||' . $value['codigo'] . '">' . ucfirst($value['nome']) . '</option>';
                    }
                    ?>
                </select></red>
        </h3>
        <div class="left_side">
            <label>Data do Fechamento:<i class="fa-solid fa-spinner fa-spin-pulse"></i></label>
            <input type="date" value="<?php echo date('Y-m-d') ?>" id="data_fechamento_funcionario">

        </div>
    </div>



    <div class="valores_informados_box">
        <span class="valores_informados_title">Valores Informados/Apurados:</span>
        <div class="body_valores">
            <div class="first_column">
                <span class="column_title">Informados/Apurados</span>
                <?php
                $valores_informados_de_hoje = \MySql::conectar()->prepare("SELECT * FROM `tb_fechamento` WHERE `data` = CURDATE() ORDER BY `id`");
                $valores_informados_de_hoje->execute(array());
                $valores_informados_de_hoje = $valores_informados_de_hoje->fetch();
                $valores_apurados_de_hoje = \MySql::conectar()->prepare("SELECT 
   (SELECT SUM(`valor`)
    FROM `tb_vendas`
    WHERE DATE(`data`) = CURDATE()
    AND (`forma_pagamento` = 'Cartão Crédito' OR `forma_pagamento` = 'Cartão Débito') GROUP BY DATE(`data`) 
    ) AS cartao,
    (SELECT SUM(`valor`)
    FROM `tb_vendas`
    WHERE DATE(`data`) = CURDATE()
    AND `forma_pagamento` = 'Dinheiro' GROUP BY DATE(`data`) 
    ) AS dinheiro,
      (SELECT SUM(`valor`)
    FROM `tb_vendas`
    WHERE DATE(`data`) = CURDATE()
    AND `forma_pagamento` = 'Pix' GROUP BY DATE(`data`) 
    ) AS pix,
    (SELECT SUM(`valor`)
    FROM `tb_sangrias`
    WHERE DATE(`data`) = CURDATE() GROUP BY DATE(`data`) 
    ) AS sangria,
    (SELECT `moeda_apurada` FROM `tb_fechamento` WHERE DATE(`data`) = CURDATE() GROUP BY DATE(`data`) ) AS moeda");
                $valores_apurados_de_hoje->execute();
                $valores_apurados_de_hoje = $valores_apurados_de_hoje->fetch();
                $total_valores_informados_de_hoje = $valores_informados_de_hoje['dinheiro'] + $valores_informados_de_hoje['moeda'] + $valores_informados_de_hoje['pix'] + $valores_informados_de_hoje['cartao'] - $valores_informados_de_hoje['sangria'];
                $total_valores_apurados_de_hoje = $valores_apurados_de_hoje['dinheiro'] + $valores_apurados_de_hoje['moeda'] + $valores_apurados_de_hoje['pix'] + $valores_apurados_de_hoje['cartao'] - $valores_apurados_de_hoje['sangria'];

                ?>
                <div class="input_valores">
                    <label for="dinheiro_informadas">Dinheiro: </label>
                    <div class="input_modal_fechamento_column">
                        <input type="text" disabled onkeyup="mascaraMoedaSemSeparacaoMilhar(this)" value="<?php echo number_format($valores_informados_de_hoje['dinheiro'], 2, '.', '') ?>" class="valores_informados input_princip_completo oders_inputs" name="dinheiro_informadas" id="dinheiro_informadas">
                        <input type="text" disabled onkeyup="mascaraMoedaSemSeparacaoMilhar(this)" value="<?php echo number_format($valores_apurados_de_hoje['dinheiro'], 2, '.', '') ?>" class="valores_informados input_princip_completo oders_inputs" name="dinheiro_apuradas" id="dinheiro_apuradas" style="border-radius:0 15px 15px 0; border-left:1px solid gray;">
                    </div>
                </div>
                <!-- <div class="input_valores">
                    <label for="moedas_informadas">Moeda: </label>
                    <div class="input_modal_fechamento_column">
                        <input type="text" disabled onkeyup="mascaraMoedaSemSeparacaoMilhar(this)" value="<?php echo number_format($valores_informados_de_hoje['moeda'], 2, '.', '') ?>" class="valores_informados input_princip_completo oders_inputs" name="moedas_informadas" id="moedas_informadas">
                        <input type="text" onkeyup="mascaraMoedaSemSeparacaoMilhar(this,event)" value="<?php echo number_format($valores_apurados_de_hoje['moeda'], 2, '.', '') ?>" class="valores_informados input_princip_completo oders_inputs" name="moedas_apuradas" id="moedas_apuradas" style="border-radius:0 15px 15px 0; border-left:1px solid gray;">
                    </div>
                </div> -->
                <div class="input_valores">
                    <label for="pix_informadas">Pix: </label>
                    <div class="input_modal_fechamento_column">
                        <input disabled onkeyup="mascaraMoedaSemSeparacaoMilhar(this)" type="text" value="<?php echo number_format($valores_informados_de_hoje['pix'], 2, '.', '') ?>" class="valores_informados input_princip_completo oders_inputs" name="pix_informadas" id="pix_informadas">
                        <input disabled onkeyup="mascaraMoedaSemSeparacaoMilhar(this)" type="text" value="<?php echo number_format($valores_apurados_de_hoje['pix'], 2, '.', '') ?>" class="valores_informados input_princip_completo oders_inputs" name="pix_apuradas" id="pix_apuradas" style="border-radius:0 15px 15px 0; border-left:1px solid gray;">
                    </div>
                </div>
            </div>
            <div class="first_column">
                <span class="column_title">Informados/Apurados</span>

                <div class="input_valores">
                    <label for="cartao_informadas">Cartão: </label>
                    <div class="input_modal_fechamento_column">
                        <input disabled onkeyup="mascaraMoedaSemSeparacaoMilhar(this)" type="text" class="valores_informados input_princip_completo oders_inputs" value="<?php echo number_format($valores_informados_de_hoje['cartao'], 2, '.', '') ?>" name="cartao_informadas" id="cartao_informadas">
                        <input disabled onkeyup="mascaraMoedaSemSeparacaoMilhar(this)" value="<?php echo number_format($valores_apurados_de_hoje['cartao'], 2, '.', '') ?>" type="text" class="valores_informados input_princip_completo oders_inputs" name="cartao_apuradas" id="cartao_apuradas" style="border-radius:0 15px 15px 0; border-left:1px solid gray;">
                    </div>
                </div>

                <div class="input_valores">
                    <label for="sangria_informadas">Sangria: </label>
                    <div class="input_modal_fechamento_column">
                        <input type="text" disabled onkeyup="mascaraMoedaSemSeparacaoMilhar(this)" class="valores_informados input_princip_completo oders_inputs" value="<?php echo number_format($valores_informados_de_hoje['sangria'], 2, '.', '') ?>" name="sangria_informadas" id="sangria_informadas">
                        <input type="text" disabled onkeyup="mascaraMoedaSemSeparacaoMilhar(this)" value="<?php echo number_format($valores_apurados_de_hoje['sangria'], 2, '.', '') ?>" class="valores_informados input_princip_completo oders_inputs" name="sangria_apuradas" id="sangria_apuradas" style="border-radius:0 15px 15px 0; border-left:1px solid gray;">
                    </div>
                </div>
                <div class="input_valores">
                    <label for="codigo_funcionario_fechamento_caixa">Funcionário: </label>
                    <input disabled onkeyup="mascaraMoedaSemSeparacaoMilhar(this)" type="text" class="valores_informados  input_princip_completo_apurados oders_inputs" name="codigo_funcionario_fechamento_caixa" id="codigo_funcionario_fechamento_caixa">
                </div>
            </div>


        </div>

        <span class="valores_informados_footer">Valor Informado: <red> R$<?php echo number_format($total_valores_informados_de_hoje, 2, ',', '') ?></red></span>
        <span class="valores_apurados_footer">Valor Apurado: <red> R$<?php echo number_format($total_valores_apurados_de_hoje, 2, ',', '') ?></red></span>

    </div>
    <span class="edit_fechamento_button" onclick="editarFechamento(this)">Editar fechamento </span>

    <!-- <h4 id="mostrar">Mostrar valores apurados</h4>
    <div class="valores_informados_box">
        <span class="valores_informados_title">Valores Apurados:</span>
        <div class="body_valores">
            <div class="first_column">
                <div class="input_valores">
                    <label for="troco_inicial_fechar">Troco Inicial: </label>
                    <input onKeyUp="mascaraMoeda(this, event)" type="text" class=" input_princip_completo_apurados oders_inputs" name="troco_inicial_fechar" id="troco_inicial_fechar">
                </div>
                <div class="input_valores">
                    <label for="total_vendas">Total de Vendas: </label>
                    <input onKeyUp="mascaraMoeda(this, event)" type="text" class=" input_princip_completo_apurados oders_inputs" name="total_vendas" id="total_vendas">
                </div>
                <div class="input_valores">
                    <label for="troco_final">Troco Final: </label>
                    <input onKeyUp="mascaraMoeda(this, event)" type="text" class=" input_princip_completo_apurados oders_inputs" name="troco_final" id="troco_final">
                </div>
            </div>
            <div class="first_column">
                <div class="input_valores">
                    <label for="total_apurado">Total Apurado: </label>
                    <input onKeyUp="mascaraMoeda(this, event)" type="text" class="input_princip_completo_apurados oders_inputs" name="total_apurado" id="total_apurado">
                </div>
                <div class="input_valores">
                    <label for="total_informado">Total Informado: </label>
                    <input onKeyUp="mascaraMoeda(this, event)" type="text" class="input_princip_completo_apurados oders_inputs" name="total_informado" id="total_informado">
                </div>
                <div class="input_valores">
                    <label for="diferenca">Diferença: </label>
                    <input onKeyUp="mascaraMoeda(this, event)" type="text" class="input_princip_completo_apurados oders_inputs" name="diferenca" id="diferenca">
                </div>
            </div>
            <div class="second_column">

            </div>
        </div>
        <span class="valores_apurados_footer">Valor Total: <red> R$00,00</red> </span>
    </div> -->
    <!-- <div class="valores_apurados_box">
        <span class="valores_apurados_title">Valores Informados:</span>
        <span class="valores_apurados_footer">Valor Total: <red> R$00,00</red></span>
    </div> -->
    <button id="salvar_form_fechamento_caixa">Salvar</button>
</form>
<div class="header_section">
    <div class="left_side">
        <section style="width: 100%;">
            Selecione o <red>Período</red>:
            <div class="inputs_header_section">
                <input type="date" class="datas" name="data_minima" value="<?php echo  date('Y-m-d') ?>" max="<?php echo  date('Y-m-d') ?>" id="data_minima">
                <input type="date" class="datas" value="<?php echo  date('Y-m-d') ?>" name="data_maxima" max="<?php echo  date('Y-m-d') ?>" id="data_maxima">

            </div>
        </section>
        <section class="pesquisar_venda">
            <div>
            <label for="pesquisar_venda">Pesquisar <red>Veículo</red> :</label>
                <input type="text" name="pesquisar_venda_carro" id="pesquisar_venda_carro" placeholder="Placa do carro">
            </div>
            <div>
            <!-- <label for="pesquisar_venda">Pesquisar <red>Cliente</red> :</label> -->
                <input type="text" name="pesquisar_venda_cliente" id="pesquisar_venda_cliente" placeholder="Nome do cliente">
            </div>
        </section>

    </div>
    <div class="right_side">
        <div class="right_subdivision">
            <h3>Algumas <red>Métricas<red>:</h3>
            <span>Tipo de Pagamento mais recorrente:</span>
            <red class="pagamento_recorrente"><?php \Models\PainelControleModel::buscarDados('formaPagamentoMaisRepetida') ?></red>
            <span>Quantidade de Atendimentos no período:</span>
            <red class="quant_vendas"><?php \Models\PainelControleModel::buscarDados('quantidadeVendas') ?> Atendimentos</red>
            <span>Veículo Mais Atendido no Período:</span>
            <red class="top_produto"><?php \Models\PainelControleModel::buscarDados('produtoMaisVendido') ?></red>
            <span>Valor Total de Atendimentos:</span>
            <red class="valor_total">R$<?php \Models\PainelControleModel::buscarDados('totalValor') ?></red>
        </div>
        <div class="left_subdivision">
            <h3>Realizar Fechamento do caixa</h3>
            <span>Do dia <?php echo date('d/m/Y') ?></span>
            <button onclick="abrirModal('modal_fechar_caixa')"><i class="fa-solid fa-cart-shopping"></i> Fechar Caixa</button>

        </div>
    </div>

</div>
<div class="chart_father" style="margin-left: 7.5%; height:230px">

</div>
<div class="tabela_father">
    <div class="tabela_header">

        <i id="voltar_semana" onclick="mudarTempo(this)" class="fa-solid fa-angle-left modificadores_tempo "></i> <span> Atendimentos no dia: <yellow> <?php echo date('d/m/Y') ?></yellow> <i onclick='printTable();' class="gerar_pdf fa-regular fa-file-pdf"></i></span><i onclick="mudarTempo(this)" id='adiantar_semana' class="fa-solid fa-angle-right modificadores_tempo adiantar_semana"></i>
    </div>
    <table id="table_tabela">
        <thead>
            <tr>
                <th>Data Cadastro</th>
                <th>Valor Pago</th>
                <th>Placa do Carro</th>
                <th>Quilometragem do Carro</th>
                <th>Cliente</th>
                <th>Notas</th>
                <th>Método de Pagamento</th>
                <th>Editar</th>
                <th>Excluir</th>
            </tr>
        </thead>
        <tbody>
            <?php \Models\PainelControleModel::formarTabela() ?>

        </tbody>
    </table>
</div>

<input type="hidden" id="include_path" value="<?php echo INCLUDE_PATH ?>">
<script src="<?php echo INCLUDE_PATH ?>js/shortcut.js"></script>

<script src="<?php echo INCLUDE_PATH ?>js/criar_pdf_tabela.js"></script>
<script src="<?php echo INCLUDE_PATH ?>js/atualizar_sistema.js"></script>

<script src="<?php echo INCLUDE_PATH ?>js/index.js"></script>
<script src="<?php echo INCLUDE_PATH ?>js/graficos.js"></script>
<script src="<?php echo INCLUDE_PATH ?>js/editar_fechamento.js"></script>
<script src="<?php echo INCLUDE_PATH ?>js/pre_venda.js"></script>

<script src="<?php echo INCLUDE_PATH ?>js/posts_senders.js"></script>