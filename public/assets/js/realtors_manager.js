// ======= Módulo de Realtors Manager =======

// Inicialização do módulo
export function init() {
    console.log("Módulo 'realtors_manager' iniciado.");
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            console.log("DOM completamente carregado e analisado.");
            executarRotinaDeInicializacao();
        });
    } else {
        console.log("DOM já estava carregado.");
        executarRotinaDeInicializacao();
    }
}

// ======= Funções de Inicialização =======

function executarRotinaDeInicializacao() {
    console.log("Iniciando lógica de inicialização...");

    // Seleciona os elementos do formulário
    const formElements = selecionarElementosFormulario();
    const {
        form, realtorIdField, nameField, cpfField, creciNumberField,
        creciUfField, creciTypeField, formTitle, formSubmit
    } = formElements;

    // Verifica se os elementos obrigatórios estão presentes
    verificarElementosObrigatorios(formElements);

    // Configura o formulário com base nas mensagens de atualização/erro
    configurarFormularioComMensagens({ formTitle, formSubmit });

    // Configura os botões de edição
    configurarBotoesEdicao(formElements);
}

// Verifica se os elementos obrigatórios do formulário estão presentes
function verificarElementosObrigatorios(formElements) {
    const elementNames = [
        'form', 'realtorIdField', 'nameField', 'cpfField', 'creciNumberField',
        'creciUfField', 'creciTypeField', 'formTitle', 'formSubmit'
    ];
    elementNames.forEach(name => {
        if (!formElements[name]) {
            console.error(`Elemento obrigatório ausente: ${name}`);
        }
    });
}

// Configura o formulário com base nas mensagens de atualização/erro
function configurarFormularioComMensagens({ formTitle, formSubmit }) {
    const updateMessages = document.querySelectorAll('.message p[id^="update"]');
    if (updateMessages.length > 0) {
        console.log("Mensagem de atualização/erro detectada:", updateMessages[0].id);
        atualizarFormularioParaEdicao({ formTitle, formSubmit });
        adicionarBotaoCancelar(formSubmit);
    } else {
        console.log("Nenhuma mensagem de atualização encontrada.");
    }
}

// Configura os botões de edição
function configurarBotoesEdicao(formElements) {
    const editButtons = document.querySelectorAll('.btn.edit-btn');
    if (editButtons.length > 0) {
        console.log(`Configurando ${editButtons.length} botões de edição.`);
        registrarEventosDeEdicao(editButtons, formElements);
    } else {
        console.log("Nenhum botão de edição encontrado.");
    }
}

// ======= Funções de Configuração do Formulário =======

function selecionarElementosFormulario() {
    return {
        form: document.getElementById('register-realtor-form'),
        realtorIdField: document.getElementById('realtor_id'),
        nameField: document.getElementById('name'),
        cpfField: document.getElementById('cpf'),
        creciNumberField: document.getElementById('creci_number'),
        creciUfField: document.getElementById('creci_uf'),
        creciTypeField: document.getElementById('creci_type'),
        formTitle: document.getElementById('form-title'),
        formSubmit: document.getElementById('form-submit'),
        formSection: document.querySelector('.form-section')
    };
}

function restaurarFormulario() {
    const { form, formTitle, formSubmit } = selecionarElementosFormulario();
    redefinirValoresInputs(); // Redefine os valores dos inputs
    redefinirValoresSelects(); // Redefine os valores dos selects
    form.action = form.action.replace(/\/realtor\/update$/, '/realtor/add'); // Restaura o action do formulário
    formTitle.textContent = 'Cadastro de Novo Corretor';
    formSubmit.value = 'Cadastrar Corretor';

    document.querySelectorAll('.form-section .form-message').forEach(message => {
        const currentParam = message.getAttribute('data-param');
        if (currentParam?.includes('update')) {
            message.setAttribute('data-param', currentParam.replace('update', 'register'));
        }
    });

    const cancelButton = document.getElementById('cancel-button');
    if (cancelButton) {
        cancelButton.remove();
    }
}

// Redefine os valores de todos os inputs do formulário para ""
function redefinirValoresInputs() {
    document.querySelectorAll('input').forEach(input => {
        input.value = '';
    });
}

// Redefine os valores de todos os selects do formulário para o valor padrão
function redefinirValoresSelects() {
    document.querySelectorAll('select').forEach(select => {
        select.selectedIndex = 0; // Define o índice selecionado como o primeiro (valor padrão)
    });
}

function ocultarMensagens() {
    document.querySelectorAll('.message').forEach(div => {
        div.style.display = 'none';
    });
}

// ======= Funções de Manipulação e Eventos do Formulário =======

function registrarEventosDeEdicao(editButtons, formElements) {
    const {
        form, realtorIdField, nameField, cpfField, creciNumberField,
        creciUfField, creciTypeField, formTitle, formSubmit
    } = formElements;

    editButtons.forEach(button => {
        console.log("Registrando evento de clique para o botão:", button);
        button.addEventListener('click', function () {
            console.log("Botão 'Editar' clicado:", this);
            const { id: realtorId, name, cpf, creci } = this.dataset;

            if (!validarDadosCorretor({ realtorId, name, cpf, creci })) return;

            preencherFormulario({
                realtorId: parseInt(realtorId, 10),
                name, cpf, creci,
                realtorIdField, nameField, cpfField,
                creciNumberField, creciUfField, creciTypeField
            });

            atualizarFormularioParaEdicao({ form, formTitle, formSubmit });
            ocultarMensagens();
            adicionarBotaoCancelar(formSubmit);
        });
    });
}

function validarDadosCorretor({ realtorId, name, cpf, creci }) {
    const requiredFields = { realtorId, name, cpf, creci };
    let missingField = false;
    Object.entries(requiredFields).forEach(([key, value]) => {
        if (!value) {
            console.error(`${key.toUpperCase()} do corretor ausente.`);
            missingField = true;
        }
    });
    return !missingField;
}

function preencherFormulario({
    realtorId, name, cpf, creci,
    realtorIdField, nameField, cpfField,
    creciNumberField, creciUfField, creciTypeField
}) {
    const creciParts = creci.trim().split(/[\s-]+/);
    let creciUf = '', creciNumber = '', creciType = '';
    if (creciParts.length === 3) {
        [creciUf, creciNumber, creciType] = creciParts;
    } else {
        creciNumber = creci;
    }

    realtorIdField.value = parseInt(realtorId, 10);
    realtorIdField.setAttribute('autocomplete', 'off');
    nameField.value = name;
    nameField.setAttribute('autocomplete', 'off');
    cpfField.value = cpf;
    cpfField.setAttribute('autocomplete', 'off');
    creciNumberField.value = creciNumber;
    creciNumberField.setAttribute('autocomplete', 'off');
    creciUfField.value = creciUf;
    creciUfField.setAttribute('autocomplete', 'off');
    creciTypeField.value = creciType;
    creciTypeField.setAttribute('autocomplete', 'off');
}

function atualizarFormularioParaEdicao({ form, formTitle, formSubmit }) {
    form.action = form.action.replace(/\/realtor\/add$/, '/realtor/update'); // Atualiza o action do formulário
    formTitle.textContent = 'Editar Cadastro';
    formSubmit.value = 'Atualizar Cadastro';
}

function adicionarBotaoCancelar(formSubmit) {
    if (!document.getElementById('cancel-button')) {
        const cancelButton = document.createElement('button');
        Object.assign(cancelButton, {
            id: 'cancel-button',
            type: 'button',
            textContent: 'Cancelar',
            className: 'btn btn-primary',
            onclick: () => {
                ocultarMensagens();
                restaurarFormulario();
            }
        });
        formSubmit.parentNode.appendChild(cancelButton);
    }
}
