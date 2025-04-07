<?php 

namespace App\Entities;

use App\Traits\DataValidation;

require_once __DIR__ . '/../../config/config.php';

class Realtor
{
    use DataValidation;
    private ?int $id = null;
    private string $name;
    private string $cpf;
    private string $creci;

    public function __construct(string $name, string $cpf, string $creci)
    {
        $this->name = $name;
        $this->cpf = $cpf;
        $this->creci = $creci;
    }

    // Getters e Setters para cada propriedade
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $this->validateId($id);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $this->validateName($name);
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): void
    {
        $this->cpf = $this->validateCpf($cpf);
    }

    public function getCreci(): int
    {
        return $this->creci;
    }

    public function setCreci(string $creci): void
    {
        $this->creci = $creci;
    }

    public function getData(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cpf' => $this->cpf,
            'creci' => $this->creci
        ];
    }

}


?>