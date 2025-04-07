<?php 

namespace App\Models;

use App\Entities\Realtor;

require_once __DIR__ . '/../../config/config.php';

class RealtorModel extends Model
{
    private string $tableName;
    private array $columns = ['id', 'name', 'cpf', 'creci'];

    public function __construct()
    {
        parent::__construct();
        $this->tableName = REALTORS_TABLE_NAME;
        $this->columns = [
            'id' => REALTORS_ID_COLUMN,
            'name' => REALTORS_NAME_COLUMN,
            'cpf' => REALTORS_CPF_COLUMN,
            'creci' => REALTORS_CRECI_COLUMN
        ];
    }

    public function addRealtor(Realtor $realtor): bool
    {
        $data = [];
        $realtorData = $realtor->getData();

        foreach ($realtorData as $key => $value) {
            if (array_key_exists($key, $this->columns)) {
                $data[$this->columns[$key]] = $value;
            }
        }

        return $this->executeQuery->insert($this->tableName, $data);
    }

    public function getAllRealtors(): array
    {
        return $this->executeQuery->select($this->tableName, ['*']);
    }

    public function getRealtorById(Realtor $realtor): array
    {
        $where = $this->columns['id'] . ' = ?';
        $params = [$realtor->getId()]; 

        return $this->executeQuery->select($this->tableName, ['*'], $where, $params);
    }

    public function updateRealtor(Realtor $realtor): bool
    {
        $data = [];
        $realtorData = $realtor->getData();

        foreach ($realtorData as $key => $value) {
            if (array_key_exists($key, $this->columns)) {
                $data[$this->columns[$key]] = $value;
            }
        }

        $where = $this->columns['id'] . ' = ?';
        $params = [$realtor->getId()];

        return $this->executeQuery->update($this->tableName, $data, $where, $params);
    }

    public function deleteRealtor(int $id): bool
    {
        $where = $this->columns['id'] . ' = ?';
        $params = [$id];
        return $this->executeQuery->delete($this->tableName, $where, $params);
    }
}
?>