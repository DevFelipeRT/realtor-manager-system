<?php 

namespace App\Traits;

require_once __DIR__ . '/../../config/config.php';

use Exception;

trait PostRequestProcessor
{
    /**
     * Método para processar requisições POST e acionar métodos de controladores específicos.
     */
    public function postRequestProcess(): void
    {
        // Verifica se a requisição é do tipo POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtém os valores do POST, se existirem
            $postAction = $_POST['action'] ?? '';
            $postController = $_POST['controller'] ?? '';

            // Sanitiza os valores
            $postAction = htmlspecialchars($postAction, ENT_QUOTES, 'UTF-8');
            $postController = htmlspecialchars($postController, ENT_QUOTES, 'UTF-8');

            // Valida a estrutura dos dados
            if (empty($postAction) || empty($postController)) {
                throw new Exception("Ação ou controlador não definidos corretamente.");
            }

            // Converte o nome do controlador para PascalCase e adiciona "Controller"
            $category = $this->convertToPascalCase($postController);
            $controllerClass = "App\\Controllers\\{$category}Controller";

            // Verifica se a classe do controlador existe
            if (!class_exists($controllerClass)) {
                throw new Exception("Controlador '{$controllerClass}' não encontrado.");
            }

            // Obtém a instância do controlador
            $controller = $this;

            // Converte o nome da ação para camelCase
            $method = $this->convertToCamelCase($postAction);

            // Verifica se o método existe no controlador correspondente
            if (!method_exists($controller, $method)) {
                throw new Exception("Método '{$method}' não encontrado no controlador '{$controllerClass}'.");
            }

            // Tratamento específico para a ação de delete (caso precise)
            if ($method === 'delete') {
                if (!isset($_POST['id'])) {
                    throw new Exception("ID não fornecido para exclusão.");
                }
                $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
                if ($id === false) {
                    throw new Exception("ID inválido para exclusão.");
                }
                $controller->$method($id);
            } else {
                $controller->$method();
            }

            // Redireciona para a página após o processamento
            $this->index();
            exit();
        }
    }

    /**
     * Converte um nome em kebab-case ou camelCase para PascalCase.
     * Exemplo: "meu-controlador" ou "meuControlador" → "MeuControlador"
     */
    private function convertToPascalCase(string $input): string
    {
        $words = preg_split('/[-_]/', $input); // Divide por hífen ou sublinhado
        $words = array_map('ucfirst', $words); // Primeira letra maiúscula em cada palavra
        return implode('', $words);
    }

    /**
     * Converte um nome em kebab-case ou camelCase para camelCase.
     * Exemplo: "minha-acao" ou "minhaAcao" → "minhaAcao"
     */
    private function convertToCamelCase(string $input): string
    {
        $words = preg_split('/[-_]/', $input); // Divide por hífen ou sublinhado
        $camelCased = lcfirst(implode('', array_map('ucfirst', $words))); // Primeira palavra minúscula
        return $camelCased;
    }
}



?>