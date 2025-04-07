document.addEventListener("DOMContentLoaded", function () {
    const page = document.body.getAttribute("file-name");

    if (page && page.trim() !== "") { // Verifica se o atributo não é nulo ou vazio
        console.log(`Carregando o módulo: ${page}.js`); // Log para depuração
        import(`./${page}.js`)
            .then(module => {
                if (module.init) {
                    console.log(`Inicializando o módulo: ${page}.js`); // Log para depuração
                    module.init();
                } else {
                    console.error(`O módulo ${page}.js não possui uma função init.`);
                }
            })
            .catch(error => {
                console.error(`Erro ao carregar o módulo ${page}.js:`, error);
            });
    } else {
        console.warn("Atributo 'file-name' não encontrado ou vazio no elemento body.");
    }
});
