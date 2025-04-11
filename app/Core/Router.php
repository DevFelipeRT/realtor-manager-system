<?php

namespace App\Core;

require_once __DIR__ . '/../../config/config.php';

use App\Utils\Logger;

class Router
{
    protected array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
    ];
    protected Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger();
    }

    public function get(string $uri, $action)
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post(string $uri, $action)
    {
        $this->addRoute('POST', $uri, $action);
    }

    protected function addRoute(string $method, string $uri, $action)
    {
        $this->routes[$method][$this->normalize($uri)] = $action;
    }

    public function dispatch(string $requestUri, string $requestMethod)
    {
        $uri = $this->normalize($requestUri);

        if (!isset($this->routes[$requestMethod][$uri])) {
            http_response_code(404);
            $this->logger->error("404 - Rota não encontrada: {$uri}");
            echo "404 - Rota não encontrada.";
            exit;
        }

        $action = $this->routes[$requestMethod][$uri];

        try {
            if (is_callable($action)) {
                call_user_func($action);
                return;
            }

            if (is_string($action)) {
                [$controller, $method] = explode('@', $action);
                $controllerClass = "App\\Controllers\\" . $controller;

                if (!class_exists($controllerClass)) {
                    $this->logger->error("Erro: Controlador {$controllerClass} não encontrado.");
                    throw new \Exception("Erro: Controlador {$controllerClass} não encontrado.");
                }

                $instance = new $controllerClass();

                if (!method_exists($instance, $method)) {
                    $this->logger->error("Erro: Método {$method} não encontrado em {$controllerClass}.");
                    throw new \Exception("Erro: Método {$method} não encontrado em {$controllerClass}.");
                }

                call_user_func([$instance, $method]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            $this->logger->error($e->getMessage());
            echo "Erro interno do servidor: " . $e->getMessage();
            exit;
        }
    }

    protected function normalize(string $uri): string
    {
        // Remove query string
        $uri = parse_url($uri, PHP_URL_PATH);

        // Caminho base dinâmico
        $basePath = $this->getBasePath();

        // Remove o basePath da URI se estiver presente
        if (str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }

        // Remove barras extras e normaliza para "/"
        $uri = '/' . trim($uri, '/');

        // Se vazio, define como "/"
        return $uri === '' ? '/' : $uri;
    }

    protected function getBasePath(): string
    {
        // Caminho até o diretório onde o index.php reside
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);

        // Remove "/public" do final se estiver presente
        if (str_ends_with($scriptDir, '/public')) {
            $scriptDir = substr($scriptDir, 0, -7);
        }

        return rtrim($scriptDir, '/');
    }
}
