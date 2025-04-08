// ------------- ajuste de viewport -------------
(function () {
    function applyScale(scale) {
      const vp = document.querySelector('meta[name="viewport"]');
      if (!vp) return;
      const content = `width=device-width, initial-scale=${scale}, ` +
                      `minimum-scale=${scale}, maximum-scale=${scale}`;
      if (vp.getAttribute('content') !== content) {
        vp.setAttribute('content', content);
      }
    }
    if (window.matchMedia('(max-width: 767px)').matches) {
      applyScale(0.6);
    }
    window.addEventListener('orientationchange', () => applyScale(0.8));
  })();
  // ------------- fim do ajuste de viewport -------------
  

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
