<?php 

namespace App\Controllers;

use App\Core\Render;
use App\Utils\Logger;
use App\Models\Model;
use App\Core\Session;
use App\Traits\PostRequestProcessor;

require_once __DIR__ . '/../../config/config.php';

class Controller {
    use PostRequestProcessor;
    protected static array $routes = [];
    protected Render $render;
    protected Logger $logger;
    protected Model $model;
    protected ?Session $session = null;
    
    public static function setRoutes(array $routes): void {
        self::$routes = $routes;
    }

    public function __construct(?Model $model = null, ? Session $session = null) {
        $this->render = new Render();
        $this->logger = new Logger(LOGS_PATH);
        $this->model = $model ?: new Model();
        $this->session = $session;
    }

    public function index(?string $headerTitle = '', ?string $fileName = '', ?string $viewName = 'not_found', ?array $viewData = []): void {
        $headerData = [
            'headerTitle' => $headerTitle,
            'fileName' => $fileName,
            'routes' => self::$routes
        ];

        $this->render->renderTemplate('header_template', $headerData);
        $this->render->renderView($viewName, $viewData);
        $this->render->renderTemplate('footer_template');
        $this->endDatabaseConnection();
    }

    public function endDatabaseConnection(): void {
        $this->model->endDatabaseConnection();
    }

}

?>