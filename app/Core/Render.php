<?php 

namespace App\Core;

require_once __DIR__ . '/../../config/config.php';

use Exception;

class Render {
    // Propriedades Privadas
    private string $templatesDir;
    private string $viewsDir;

    //Método Construtor
    public function __construct() {
        $this->templatesDir = TEMPLATES_PATH;
        $this->viewsDir = VIEWS_PATH;
    }

    // Métodos render
    public function renderTemplate($templateName, $data = []): void {
        $templatePath = $this->templatesDir . '/' . $templateName . '.php';

        if (!file_exists($templatePath)) {
            throw new Exception("Template não encontrado: $templateName ($templatePath)." );
        }
        extract($data);
        include($templatePath);
    }

    public function renderView($viewName, $data = []): void {
        $viewPath = $this->viewsDir . '/' . $viewName . '.php';

        if (!file_exists($viewPath)) {
            throw new Exception("View não encontrada: $viewName ($viewPath)." );
        }
        
        extract($data);
        include($viewPath);
    }

}

?>