class OctopusXML {
    async saveFile(type, date, port) {
        let sucesso = false;

        const sendRequest = await new Promise((resolve, reject) => {
            // Caminho para o arquivo existente
            let caminhoArquivo = 'Models/post_receivers/temp/nota.' + type;

            // Crie uma nova requisição XMLHttpRequest para buscar o conteúdo do arquivo
            let xhr = new XMLHttpRequest();
            xhr.open('GET', caminhoArquivo, true);
            xhr.responseType = 'blob';
            xhr.onload = function () {
                if (xhr.status === 200) {
                    // Arquivo baixado com sucesso
                    let blob = xhr.response;
                    // Crie um novo objeto de tipo de arquivo
                    let arquivo = new File([blob], 'nota.xml');
                    let formData = new FormData();
                    formData.append('file' + type.toUpperCase(), arquivo); // Adiciona o arquivo
                    formData.append('dataXML', date); // Adiciona o text
                    // Faça a requisição AJAX usando jQuery
                    $.ajax({
                        url: "http://localhost:" + port + "/save" + type.toUpperCase(), // URL do seu endpoint
                        type: 'POST',
                        data: formData,
                        processData: false, // Não processar os dados (o FormData já está no formato correto)
                        contentType: false, // Não defina o tipo de conteúdo (o navegador irá definir automaticamente como 'multipart/form-data')
                        success: function (response) {
                            console.log('Resposta do servidor:', response);
                            sucesso = true;
                            resolve();
                        },
                        error: function (xhr, status, error) {
                            alert("Erro ao imprimir, verifique se a porta do sistema e do Octopus XML Printer");
                            reject(error);
                        }
                    });

                } else {
                    console.error('Erro ao baixar o arquivo:', xhr.statusText);
                    reject('Erro ao baixar o arquivo');
                }
            };

            xhr.onerror = function () {
                console.error('Erro de rede');
                reject('Erro de rede');
            };
            // Envie a requisição para baixar o arquivo
            xhr.send();
        });
        return sucesso;
    }
    async printFile(port, date, vID, pID) {
        let sucesso = false;
        const sendRequest = await new Promise((resolve, reject) => {
            const data = {
                "dataXML": date,
                "vID": vID,
                "pID": pID
            }
            console.log(data)

            $.post(`http://localhost:${port}/printXML`, data, (ret) => {
                console.log(ret)
            }).fail(()=>{
                $.alert({
                    title: 'Erro!',
                    content: "Erro ao imprimir.",
                    boxWidth: '500px',
                    useBootstrap: false,
                  });
            })

        })
        return sucesso;
    }
    async printSangria(data, vID, pID, port) {
        let sucesso = false;
        console.log(data)
        $.post(`http://localhost:${port}/printSangria`, { infoOrder: JSON.stringify(data), vID: vID, pID: pID }, (ret) => {
            if (ret.status == "Sucesso") {
                sucesso = true
                alert("Sucesso")
            } else {
                alert("Erro ao comunicar com o Octopus")
            }
        }).fail(()=>{
            $.alert({
                title: 'Erro!',
                content: "Erro ao imprimir.",
                boxWidth: '500px',
                useBootstrap: false,
              });
        })
        return sucesso;
    }
    async printLastVenda(data, vID, pID, port) {
        let sucesso = false;
        $.post(`http://localhost:${port}/printLastVenda`, { infoOrder: data, vID: vID, pID: pID }, (ret) => {
            if (ret.status == "Sucesso") {
                sucesso = true
                alert("Sucesso")
            } else {
                alert("Erro ao comunicar com o Octopus")
            }
        }).fail(()=>{
            $.alert({
                title: 'Erro!',
                content: "Erro ao imprimir.",
                boxWidth: '500px',
                useBootstrap: false,
              });
        })
        return sucesso;
    }
    async printOrder(data, vID, pID, port) {
        let sucesso = false;

        $.post(`http://localhost:${port}/printOrder`, { infoOrder: JSON.stringify(data), vID: vID, pID: pID }, (ret) => {
            if (ret.status == "Sucesso") {
                alert("Sucesso")
                sucesso = true

            } else {
                alert("Erro ao comunicar com o Octopus")
            }
        }).fail(()=>{
            $.alert({
                title: 'Erro!',
                content: "Erro ao imprimir.",
                boxWidth: '500px',
                useBootstrap: false,
              });
        })
        return sucesso;

    }
    async printFechamento(data, vID, pID, port) {
        let object = {};
        data.forEach(function (value, key) {
            object[key] = value;
        });
        let json = JSON.stringify(object);
        let dataFull = { infoOrder:json, vID: vID, pID: pID }

        $.post(`http://localhost:${port}/printFechamento`, dataFull, (ret) => {
            console.log(ret)

        }).fail(()=>{
            $.alert({
                title: 'Erro!',
                content: "Erro ao imprimir.",
                boxWidth: '500px',
                useBootstrap: false,
              });
        })
    }
}
