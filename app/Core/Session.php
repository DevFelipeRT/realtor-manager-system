<?php 

namespace App\Core;

require_once __DIR__ . '/../../config/config.php';

class Session
{
    private array $data = [];

    public function __construct()
    {
        $cookieDomain = defined('COOKIE_DOMAIN') && COOKIE_DOMAIN !== '' 
            ? COOKIE_DOMAIN 
            : $this->validateDomain($_SERVER['HTTP_HOST'] ?? '');

        // Configura os parâmetros do cookie antes de iniciar a sessão
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 0,
                'path'     => '/',
                'domain'   => $cookieDomain,
                'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
                'httponly' => true,
                'samesite' => 'Strict'
            ]);

            session_start();
        }

        $_SESSION['session_data'] ??= [];
        $this->data = &$_SESSION['session_data'];
    }

    private function validateDomain(string $host): string
    {
        // Remove eventuais portas e valida se é um domínio válido.
        $hostParts = explode(':', $host);
        $domain = $hostParts[0];

        // Verifica se o domínio possui caracteres inválidos ou se é um IP, por exemplo.
        if (filter_var($domain, FILTER_VALIDATE_IP)) {
            // Se for IP, é preferível não definir o domínio para que o cookie
            // seja enviado apenas ao host atual.
            return '';
        }
        
        // Pode ser incluída validação adicional (lista branca, regex, etc.)
        return $domain;
    }

    public function destroy(): void
    {
        // Verifica se a sessão está ativa antes de tentar destruí-la
        if (session_status() === PHP_SESSION_ACTIVE) {
            // Limpa os dados da propriedade local
            $this->data = [];
            
            // Remove dados específicos da sessão
            unset($_SESSION['session_data']);
            
            // Limpa todas as variáveis de sessão
            $_SESSION = [];
            
            // Remove o cookie da sessão apenas se os headers não foram enviados
            if (!headers_sent()) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    [
                        'expires' => 1,
                        'path' => $params['path'],
                        'domain' => $params['domain'],
                        'secure' => $params['secure'],
                        'httponly' => $params['httponly'],
                        'samesite' => 'Strict'
                    ]
                );
                
                // Destrói a sessão
                session_destroy();
            } else {
                // Se os headers já foram enviados, apenas limpa os dados da sessão
                $_SESSION = [];
                session_write_close();
            }
        }
    }

    public function regenerate(): void
    {
        if (headers_sent()) {
            return;
        }

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    // Setters e Getters para os dados da sessão

    public function setData(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }
    
    public function getData(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    public function getAllData(): array
    {
        return $this->data;
    }

    public function hasData(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    public function deleteData(string $key): void
    {
        unset($this->data[$key]);
    }

    public function unsetData(): void
    {
        $this->data = [];
    }

    // Métodos para mensagens de erro e sucesso
    public function setMessages(array $messages): void
    {
        if (!isset($this->data['__messages'])) {
            $this->data['__messages'] = [];
        }

        foreach ($messages as $type => $message) {
            if (!isset($this->data['__messages'][$type])) {
                $this->data['__messages'][$type] = [];
            }
            $this->data['__messages'][$type][] = $message;
        }
    }
    
    public function addMessage(string $type, array $message): void
    {

        $this->data['__messages'][$type][] = $message;
    }

    public function hasMessages(?string $type = null): bool
    {
        if (!isset($this->data['__messages'])) {
            return false;
        }

        if ($type === null) {
            return !empty($this->data['__messages']);
        }

        return !empty($this->data['__messages'][$type] ?? []);
    }

    public function getMessages(?string $type = null): array
    {
        if (!$this->hasMessages($type)) {
            return [];
        }

        if ($type === null) {
            return $this->data['__messages'];
        }

        return $this->data['__messages'][$type];
    }

    public function pullMessages(?string $type = null): array
    {
        if (!$this->hasMessages($type)) {
            return [];
        }

        if ($type === null) {
            $messages = $this->data['__messages'];
            unset($this->data['__messages']);
            return $messages;
        }

        $messages = $this->data['__messages'][$type];
        unset($this->data['__messages'][$type]);

        if (empty($this->data['__messages'])) {
            unset($this->data['__messages']);
        }

        return $messages;
    }

    public function showMessage(string $field): string
    {
        if (empty($this->data['__messages'])) {
            return '';
        }

        foreach ($this->data['__messages'] as $type => $messages) {
            foreach ($messages as $index => $message) {
                if (isset($message[$field])) {
                    // Define a classe baseada no tipo
                    $alertClass = match ($type) {
                        'success' => 'alert-success',
                        'error'   => 'alert-danger',
                        'warning' => 'alert-warning',
                        'info'    => 'alert-info',
                        default   => 'alert-secondary'
                    };

                    // Gera o id dinâmico no formato label_type em snake_case
                    $id = strtolower(str_replace(' ', '_', "{$field}_{$type}"));

                    // Remove a mensagem após exibir (comportamento flash)
                    unset($this->data['__messages'][$type][$index]);

                    // Se não restarem mensagens desse tipo, remove o array
                    if (empty($this->data['__messages'][$type])) {
                        unset($this->data['__messages'][$type]);
                    }

                    // Se não restarem mensagens no geral, limpa o container
                    if (empty($this->data['__messages'])) {
                        unset($this->data['__messages']);
                    }

                    return "<div class=\"message {$alertClass}\"><p id=\"{$id}\">{$message[$field]}</p></div>";
                }
            }
        }

        return '';
    }

    public function clearMessages(): void
    {
        unset($this->data['__messages']);
    }

}

?>