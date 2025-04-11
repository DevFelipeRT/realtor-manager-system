<?php
// Versão 1.0.0

use App\Core\Router;

require_once __DIR__ . '/../config/config.php';

// Instância do roteador
$router = new Router();

// Definição das rotas de navegação
$router->get('/', 'RealtorController@index');
$router->get('/home', 'RealtorController@index');

// Definição das rotas de métodos POST
$router->post('/realtor/add', 'RealtorController@addRealtor');
$router->post('/realtor/update', 'RealtorController@updateRealtor');
$router->post('/realtor/delete', 'RealtorController@deleteRealtor');

// Execução do roteador
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

?>

