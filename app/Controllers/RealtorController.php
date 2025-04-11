<?php 

namespace App\Controllers;

use App\Core\Session;
use App\Entities\Realtor;
use App\Models\RealtorModel;
use App\Traits\DataValidation;

require_once __DIR__ . '/../../config/config.php';

class RealtorController extends Controller {
    use DataValidation;

    private RealtorModel $realtorModel;

    public function __construct() 
    {
        $this->realtorModel = new RealtorModel();
        $this->session = new Session();
        parent::__construct($this->realtorModel, $this->session);

    }

    public function index(?string $headerTitle = 'Gerenciar Corretores', ?string $fileName = 'realtors_manager', ?string $viewName = 'realtors_manager', ?array $viewData = []): void {
        $headerData = [
            'headerTitle' => $headerTitle,
            'fileName' => $fileName
        ];
        
        $viewData = $this->getRealtorsManagerData();

        try {
            $this->render->renderTemplate('header_template', $headerData);
            $this->render->renderView($viewName, $viewData);
            $this->render->renderTemplate('footer_template');
        } catch (\Throwable $e) {
            $this->logger->error('Erro ao renderizar a página de gerenciamento de corretores: ' . $e->getMessage());
            $this->render->renderTemplate('error_template', ['errorMessage' => 'Erro ao renderizar a página de gerenciamento de corretores.']);
        }

        // Apaga os dados da sessão após renderizar a página
        $this->session->unsetData();
        
        $this->endDatabaseConnection();
        $this->logger->info('Página de gerenciamento de corretores acessada.');
    }

    public function addRealtor(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = [
                'name' => $_POST['name'] ?? '',
                'cpf' => $_POST['cpf'] ?? '',
                'creci_number' => $_POST['creci_number'] ?? '',
                'creci_uf' => $_POST['creci_uf'] ?? '',
                'creci_type' => $_POST['creci_type'] ?? ''
            ];

            // Salva os dados do formulário na sessão
            $this->session->setData('register_form_data', $formData);

            try {
                $formData['name'] = $this->validateName($formData['name']);
            } catch (\InvalidArgumentException $e) {
                $this->session->addMessage('error', ['register-realtor-form-name' => $e->getMessage()]);
            }

            try {
                $formData['cpf'] = $this->validateCPF($formData['cpf']);
            } catch (\InvalidArgumentException $e) {
                $this->session->addMessage('error', ['register-realtor-form-cpf' => $e->getMessage()]);
            }

            try {
                $formData['creci_number'] = $this->validateCreciNumber($formData['creci_number']);
            } catch (\InvalidArgumentException $e) {
                $this->session->addMessage('error', ['register-realtor-form-creci-number' => $e->getMessage()]);
            }

            try {
                $formData['creci_uf'] = $this->validateCreciUf($formData['creci_uf']);
                $formData['creci_type'] = $this->validateCreciType($formData['creci_type']);
            } catch (\InvalidArgumentException $e) {
                $this->session->addMessage('error', ['register-realtor-form-creci-info' => $e->getMessage()]);
            }

            if ($this->session->hasMessages('error')) {
                header('Location: index.php?action=realtors_manager');
                exit;
            }

            try {
                $creci = $this->validateCreci($formData['creci_number'], $formData['creci_uf'], $formData['creci_type']);
            } catch (\Throwable $e) {
                $this->logger->error('Erro ao combinar dados do CRECI: ' . $e->getMessage());
                $this->session->addMessage('error', ['register-realtor-form-creci' => 'Erro ao processar dados do CRECI.']);
                header('Location: index.php?action=realtors_manager');
                exit;
            }

            try {
                $realtor = new Realtor($formData['name'], $formData['cpf'], $creci);
                $this->realtorModel->addRealtor($realtor);
                $realtor->setId($this->realtorModel->getLastInsertedId());

                // Remove os dados do formulário da sessão após sucesso
                $this->session->deleteData('register_form_data');
            } catch (\Throwable $e) {
                $this->logger->error('Erro ao cadastrar corretor: ' . $e->getMessage());
                $this->session->addMessage('error', ['register-realtor-form' => 'Erro ao cadastrar corretor.']);
                header('Location: index.php?action=realtors_manager');
                exit;
            }
        }
    }

    public function getRealtorsManagerData(): array {
        $realtors = $this->realtorModel->getAllRealtors();

        return [
            'realtors' => $realtors,
            'session'  => $this->session
        ];
    }

    public function updateRealtor(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = [
                'realtor_id' => $_POST['realtor_id'],
                'name' => $_POST['name'] ?? '',
                'cpf' => $_POST['cpf'] ?? '',
                'creci_number' => $_POST['creci_number'] ?? '',
                'creci_uf' => $_POST['creci_uf'] ?? '',
                'creci_type' => $_POST['creci_type'] ?? ''
            ];

            // Salva os dados do formulário na sessão
            $this->session->setData('update_form_data', $formData);

            try {
                $formData['realtor_id'] = $this->validateId($formData['realtor_id']);
            } catch (\InvalidArgumentException $e) {
                $this->session->addMessage('error', ['update-realtor-form-id' => $e->getMessage()]);
            }

            try {
                $formData['name'] = $this->validateName($formData['name']);
            } catch (\InvalidArgumentException $e) {
                $this->session->addMessage('error', ['update-realtor-form-name' => $e->getMessage()]);
            }

            try {
                $formData['cpf'] = $this->validateCPF($formData['cpf']);
            } catch (\InvalidArgumentException $e) {
                $this->session->addMessage('error', ['update-realtor-form-cpf' => $e->getMessage()]);
            }

            try {
                $formData['creci_number'] = $this->validateCreciNumber($formData['creci_number']);
            } catch (\InvalidArgumentException $e) {
                $this->session->addMessage('error', ['update-realtor-form-creci-number' => $e->getMessage()]);
            }

            try {
                $formData['creci_uf'] = $this->validateCreciUf($formData['creci_uf']);
                $formData['creci_type'] = $this->validateCreciType($formData['creci_type']);
            } catch (\InvalidArgumentException $e) {
                $this->session->addMessage('error', ['update-realtor-form-info' => $e->getMessage()]);
            }

            if ($this->session->hasMessages('error')) {
                header('Location: index.php?action=realtors_manager');
                exit;
            }

            try {
                $creci = $this->validateCreci($formData['creci_number'], $formData['creci_uf'], $formData['creci_type']);
            } catch (\Throwable $e) {
                $this->logger->error('Erro ao combinar dados do CRECI: ' . $e->getMessage());
                $this->session->addMessage('error', ['update-realtor-form-creci' => 'Erro ao processar dados do CRECI.']);
                header('Location: index.php?action=realtors_manager');
                exit;
            }

            try {
                $realtor = new Realtor($formData['name'], $formData['cpf'], $creci);
                $realtor->setId($formData['realtor_id']);

                $this->realtorModel->updateRealtor($realtor);

                // Remove os dados do formulário da sessão após sucesso
                $this->session->deleteData('update_form_data');
            } catch (\Throwable $e) {
                $this->logger->error('Erro ao atualizar cadastro: ' . $e->getMessage());
                $this->session->addMessage('error', ['update-realtor-form' => 'Erro ao atualizar cadastro.']);
                header('Location: index.php?action=realtors_manager');
                exit;
            }
        }
    }

    public function deleteRealtor(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            try {
                $id = $this->validateId($id);
            } catch (\InvalidArgumentException $e) {
                $this->session->addMessage('error', ['realtor-table' => $e->getMessage()]);
                header('Location: index.php?action=realtors_manager');
                exit;
            }

            try {
                $this->realtorModel->deleteRealtor($id);
            } catch (\Throwable $e) {
                $this->logger->error('Erro ao excluir corretor: ' . $e->getMessage());
                $this->session->addMessage('error', ['realtor-table' => 'Erro ao excluir corretor.']);
                header('Location: index.php?action=realtors_manager');
                exit;
            }
        }
    }
}

?>