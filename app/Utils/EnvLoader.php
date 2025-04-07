<?php

namespace App\Utils;

require_once __DIR__ . '/../../config/config.php';

class EnvLoader {

    // Carrega as variáveis de ambiente de um arquivo .env e as retorna como um array
    public static function load($file, $types = []) {
        // Valida a existência do arquivo
        if (!file_exists($file)) {
            throw new \Exception("Arquivo .env não encontrado: {$file}");
        }

        // Tenta abrir o arquivo .env
        $lines = @file($file);
        if ($lines === false) {
            throw new \Exception("Erro ao ler o arquivo .env.");
        }

        $envVariables = [];

        // Processa cada linha do arquivo
        foreach ($lines as $line) {
            $line = trim($line);

            // Ignorar comentários e linhas vazias
            if (empty($line) || $line[0] === '#') {
                continue;
            }

            // Dividir a linha em chave e valor
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            // Validar chave para garantir que é uma variável válida
            if (!self::isValidEnvVarName($name)) {
                throw new \Exception("Nome de variável inválido em .env: {$name}");
            }

            // Se o tipo da variável foi especificado, converter o valor
            if (isset($types[$name])) {
                $value = self::convertValueByType($value, $types[$name], $name);
            }

            // Armazenar as variáveis de ambiente carregadas em um array
            $envVariables[$name] = $value;
        }

        return $envVariables;
    }

    // Valida se o nome da variável de ambiente segue um padrão adequado.
    private static function isValidEnvVarName($name)
    {
        // Permitir apenas letras, números e underscore
        return preg_match('/^[A-Z0-9_]+$/', $name);
    }

    // Converte o valor da variável para o tipo desejado.
    private static function convertValueByType($value, $type, $name) {
        switch (strtolower($type)) {
            case 'int':
                if (!is_numeric($value)) {
                    throw new \Exception("Valor para {$name} não é um número válido para o tipo 'int'.");
                }
                return (int) $value;
            case 'float':
                if (!is_numeric($value)) {
                    throw new \Exception("Valor para {$name} não é um número válido para o tipo 'float'.");
                }
                return (float) $value;
            case 'bool':
                $boolValue = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                if ($boolValue === null) {
                    throw new \Exception("Valor para {$name} não é um booleano válido.");
                }
                return $boolValue;
            case 'string':
                return (string) $value; // Para string, converte-se diretamente.
            default:
                throw new \Exception("Tipo inválido especificado para {$name}: {$type}");
        }
    }

    // Obtém o valor de uma variável de ambiente a partir do array passado
    public static function getFromArray($key, $envVariables)
    {
        return isset($envVariables[$key]) ? $envVariables[$key] : null;
    }

    // Carrega e retorna as variáveis de ambiente necessárias em um escopo controlado
    public static function loadEnvVariables($file, $types = []) {
        // Carrega as variáveis e retorna o array com as variáveis de ambiente
        return self::load($file, $types);
    }
}

?>
