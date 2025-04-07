<?php 

namespace App\Traits;

use InvalidArgumentException;

require_once __DIR__ . '/../../config/config.php';

trait DataValidation
{
    /**
     * Valida se o ID é um inteiro maior que zero.
     *
     * @param int $id
     * @return int
     * @throws InvalidArgumentException
     */
    public function validateId(int $id): int
    {
        if (empty($id) || $id <= 0) {
            throw new InvalidArgumentException('O ID fornecido é inválido.');
        }

        return $id;
    }

    /**
     * Valida e retorna um nome próprio.
     *
     * @param string $name O nome a ser validado.
     * @return string O próprio nome validado.
     * @throws InvalidArgumentException Se o nome for inválido.
     */
    public function validateName(string $name): string
    {
        // Valida a string com comprimento mínimo de 2 caracteres
        $name = $this->validateString($name, 2);

        // Expressão regular segura para nomes com suporte a acentos e símbolos comuns
        if (!preg_match("/^[\p{L}\s'-]+$/u", $name)) {
            throw new InvalidArgumentException('O nome contém caracteres inválidos.');
        }

        return $name;
    }

    /**
     * Valida e retorna um CPF.
     *
     * @param string $cpf O CPF a ser validado.
     * @return string O CPF validado.
     * @throws InvalidArgumentException Se o CPF for inválido.
     */
    public function validateCpf(string $cpf): string
    {
        // Verifica se o CPF contém letras
        if (preg_match('/[a-zA-Z]/', $cpf)) {
            throw new InvalidArgumentException('O CPF não pode conter letras.');
        }

        // Remove caracteres não numéricos
        $cpf = preg_replace('/\D/', '', $cpf);

        // Valida a string com comprimento exato de 11 caracteres
        $cpf = $this->validateString($cpf, 11, 11);

        // Verifica se o CPF é um número repetido (ex: 111.111.111-11)
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            throw new InvalidArgumentException('O CPF não pode ser um número repetido.');
        }

        return $cpf;
    }

    /**
     * Valida o formato do número do CRECI.
     *
     * @param string $number Número do CRECI.
     * @param string $state Estado do CRECI (2 letras maiúsculas).
     * @param string $type Tipo de Inscrição do CRECI (F, J, FS, JS).
     * @return string Retorna o número do CRECI no formato correto e sanitizado.
     * @throws InvalidArgumentException Se qualquer campo do CRECI for inválido.
     */
    public function validateCreci(string $number, string $state, string $type): string
    {
        $number = $this->validateCreciNumber($number);
        $state = $this->validateCreciUf($state);
        $type = $this->validateCreciType($type);

        // Concatena os valores no formato correto
        return "{$state}-{$number}-{$type}";
    }

    /**
     * Valida o número do CRECI.
     *
     * @param string $number Número do CRECI.
     * @return string Número do CRECI validado.
     * @throws InvalidArgumentException Se o número for inválido.
     */
    private function validateCreciNumber(string $number): string
    {
        $number = $this->validateString($number, 2, 6);
        if (!preg_match('/^\d+$/', $number)) {
            throw new InvalidArgumentException('O número do CRECI deve conter apenas dígitos.');
        }
        return $number;
    }

    /**
     * Valida o estado do CRECI.
     *
     * @param string $state Estado do CRECI.
     * @return string Estado do CRECI validado.
     * @throws InvalidArgumentException Se o estado for inválido.
     */
    private function validateCreciUf(string $state): string
    {
        $state = $this->validateString($state, 2, 2);
        if (!preg_match('/^[A-Z]{2}$/', $state)) {
            throw new InvalidArgumentException('O estado deve conter exatamente 2 letras maiúsculas.');
        }
        return $state;
    }

    /**
     * Valida o tipo de inscrição do CRECI.
     *
     * @param string $type Tipo de inscrição do CRECI.
     * @return string Tipo de inscrição do CRECI validado.
     * @throws InvalidArgumentException Se o tipo for inválido.
     */
    private function validateCreciType(string $type): string
    {
        $type = $this->validateString($type, 1, 2); // Valida o comprimento da string
        $validTypes = ['F', 'J', 'FS', 'JS'];
        if (!in_array($type, $validTypes, true)) {
            throw new InvalidArgumentException('O tipo de inscrição do CRECI é inválido.');
        }
        return $type;
    }

    /**
     * Valida e retorna uma string com segurança.
     *
     * @param string $string A string a ser validada.
     * @param int $minLength Comprimento mínimo permitido para a string.
     * @param int $maxLength Comprimento máximo permitido para a string.
     * @return string A string validada.
     * @throws InvalidArgumentException Se a string for inválida.
     */
    public function validateString(string $string, int $minLength = 1, int $maxLength = 255): string
    {
        // Remove espaços desnecessários no início e fim
        $string = trim($string);

        // Verifica se a string não está vazia
        if (empty($string)) {
            throw new InvalidArgumentException('Deve ser preenchido.');
        }

        // Verifica o comprimento mínimo
        if (mb_strlen($string, 'UTF-8') < $minLength) {
            throw new InvalidArgumentException("Deve conter pelo menos {$minLength} caracteres.");
        }

        // Verifica o comprimento máximo
        if (mb_strlen($string, 'UTF-8') > $maxLength) {
            throw new InvalidArgumentException("Não pode exceder {$maxLength} caracteres.");
        }

        // Verifica se a string contém apenas caracteres seguros
        if (!preg_match('/^[\p{L}\p{N}\p{P}\p{Zs}]+$/u', $string)) {
            throw new InvalidArgumentException('Contém caracteres inválidos.');
        }

        return $string;
    }
}

?>