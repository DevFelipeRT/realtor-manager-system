<?php

// Descrição: Este arquivo contém as constantes do sistema.

// Informações do aplicativo
    // APP_NAME
    $appName = 'Sistema de Corretores';

    // APP_VERSION
    $appVersion = '1.0.0';

    // APP_AUTHOR
    $appAuthor = 'Felipe Ruiz Terrazas';

    // APP_DESCRIPTION
    $appDescription = 'Aplicação para gerenciamento de corretores de imóveis, com controle de dados pessoais e registro profissional.';

    // CONTACT_EMAIL
    $contactEmail = 'ruizfelipefr@outlook.com';

    // CONTACT_PHONE
    $contactPhone = '+55 11 97361-3744';

// URLs do aplicativo
    // Detecta o esquema de protocolo
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

    // Captura o host atual
    $host = $_SERVER['HTTP_HOST'];

    // Extrai o caminho da URL até o diretório onde está o index.php
    $uri = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

    // Remove "/public" do final, se estiver presente
    if (str_ends_with($uri, '/public')) {
        $uri = substr($uri, 0, -7); // Remove exatamente "/public"
    }

    // BASE_URL – URL visível no navegador, usada para montar todos os links do sistema
    $baseUrl = $scheme . '://' . $host . $uri;

    // PUBLIC_URL – caminho até a pasta /public, útil para assets absolutos (JS, CSS)
    $publicUrl = rtrim($baseUrl, '/') . '/public/';

    // INDEX_URL – caminho direto para o index.php, se necessário
    $indexUrl = rtrim($baseUrl, '/') . '/index.php';

    // IMAGES_URL
    $imagesUrl = $baseUrl . '/assets/images/';

    // CSS_URL
    $cssUrl = $baseUrl . '/assets/css/';

    // JAVASCRIPT_URL
    $javascriptUrl = $baseUrl . '/assets/js/';

// Caminhos da aplicação
    // BASE_PATH
    $basePath = dirname(__DIR__);

    // APP_PATH
    $appPath = dirname(__DIR__) . '/app';

    // CONFIG_PATH
    $configPath = dirname(__DIR__) . '/config';

    // PUBLIC_PATH
    $publicPath = dirname(__DIR__) . '/public';

    // JAVASCRIPT_PATH
    $javascriptPath = dirname(__DIR__) . '/public/assets/js';

    // TEMPLATES_PATH
    $templatesPath = dirname(__DIR__) . '/public/assets/templates';

    // VIEWS_PATH
    $viewsPath = dirname(__DIR__) . '/app/Views';

    // STORAGE_PATH
    $storagePath = dirname(__DIR__) . '/storage';

    // LOGS_PATH
    $logsPath = dirname(__DIR__) . '/storage/logs/logs.txt';

    // ENV_FILE_PATH
    $envFilePath = dirname(__DIR__) . '/.env';

    //COOKIE_DOMAIN
    $cookieDomain = '';

// Estrutura da tabela de corretores
    // REALTORS_TABLE_NAME
    $realtorsTableName = 'realtors';

    // REALTORS_ID_COLUMN
    $realtorsIdColumn = 'realtor_id';

    // REALTORS_NAME_COLUMN
    $realtorsNameColumn = 'name';

    // REALTORS_CPF_COLUMN
    $realtorsCpfColumn = 'cpf';

    // REALTORS_CRECI_COLUMN
    $realtorsCreciColumn = 'creci';

    // REALTORS_UNIQUE_KEY_CPF
    $realtorsUniqueKeyCpf = 'unique_cpf';

    // REALTORS_UNIQUE_KEY_CRECI
    $realtorsUniqueKeyCreci = 'unique_creci';

    // REALTORS_COMMENT_ID
    $realtorsCommentId = 'Identificador único do corretor';

    // REALTORS_COMMENT_NAME
    $realtorsCommentName = 'Nome completo do corretor';

    // REALTORS_COMMENT_CPF
    $realtorsCommentCpf = 'Cadastro de Pessoa Física do corretor';

    // REALTORS_COMMENT_CRECI
    $realtorsCommentCreci = 'Registro CRECI do corretor';


// Definição das constantes do sistema

define('APP_NAME', $appName);
define('APP_VERSION', $appVersion);
define('APP_AUTHOR', $appAuthor);
define('APP_DESCRIPTION', $appDescription);
define('CONTACT_EMAIL', $contactEmail);
define('CONTACT_PHONE', $contactPhone);

define('BASE_URL', $baseUrl);
define('PUBLIC_URL', $publicUrl);
define('INDEX_URL', $indexUrl);
define('IMAGES_URL', $imagesUrl);
define('CSS_URL', $cssUrl);
define('JAVASCRIPT_URL', $javascriptUrl);

define('BASE_PATH', $basePath);
define('APP_PATH', $appPath);
define('CONFIG_PATH', $configPath);
define('PUBLIC_PATH', $publicPath);
define('JAVASCRIPT_PATH', $javascriptPath);
define('TEMPLATES_PATH', $templatesPath);
define('VIEWS_PATH', $viewsPath);
define('STORAGE_PATH', $storagePath);
define('LOGS_PATH', $logsPath);
define('ENV_FILE_PATH', $envFilePath);

define('REALTORS_TABLE_NAME', $realtorsTableName);
define('REALTORS_ID_COLUMN', $realtorsIdColumn);
define('REALTORS_NAME_COLUMN', $realtorsNameColumn);
define('REALTORS_CPF_COLUMN', $realtorsCpfColumn);
define('REALTORS_CRECI_COLUMN', $realtorsCreciColumn);
define('REALTORS_UNIQUE_KEY_CPF', $realtorsUniqueKeyCpf);
define('REALTORS_UNIQUE_KEY_CRECI', $realtorsUniqueKeyCreci);
define('REALTORS_COMMENT_ID', $realtorsCommentId);
define('REALTORS_COMMENT_NAME', $realtorsCommentName);
define('REALTORS_COMMENT_CPF', $realtorsCommentCpf);
define('REALTORS_COMMENT_CRECI', $realtorsCommentCreci);
define('COOKIE_DOMAIN', $cookieDomain);

?>