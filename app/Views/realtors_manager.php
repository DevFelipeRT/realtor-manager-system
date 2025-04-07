<?php 

require_once __DIR__ . '/../../config/config.php';

$formDataName = '';

if (isset($_SESSION['session_data']['update_form_data'])) {
    $formDataName = 'update_form_data';
} else {
    $formDataName = 'register_form_data';
}

?>

<h1>Gerenciar Corretores</h1>

<div class="container">
    <section class="section form-section">
        <h2 id="form-title">Cadastro de Novo Corretor</h2>

        <form id="register-realtor-form" action="index.php" method="POST">
            <input type="hidden" name="action" value="add-realtor">
            <input type="hidden" name="controller" value="realtor">
            <input type="hidden" id="realtor_id" name="realtor_id" value="<?= $session->getData($formDataName)['realtor_id'] ?? '' ?>">

            <label for="name">Nome completo:</label>
            <input type="text" id="name" name="name" autocomplete="name" value="<?= $session->getData($formDataName)['name'] ?? '' ?>">
            <?= $session->showMessage('register-realtor-form-name') ?>
            <?= $session->showMessage('update-realtor-form-name') ?>

            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" autocomplete="" value="<?= $session->getData($formDataName)['cpf'] ?? '' ?>">
            <?= $session->showMessage('register-realtor-form-cpf') ?>
            <?= $session->showMessage('update-realtor-form-cpf') ?>

            <!-- CRECI: Número + UF + Categoria -->
            <label for="creci_number">Número do CRECI:</label>
            <input type="text" id="creci_number" name="creci_number" autocomplete="off" value="<?= $session->getData($formDataName)['creci_number'] ?? '' ?>">
            <?= $session->showMessage('register-realtor-form-creci-number') ?>
            <?= $session->showMessage('update-realtor-form-creci-number') ?>

            <div class="creci-info-container">

                <div class="creci-info">
                    <label for="creci_uf">UF:</label>
                    <select id="creci_uf" name="creci_uf" autocomplete="off">
                        <option value="">Selecione o estado</option>
                        <?php
                        $ufs = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG',
                                'PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];
                        foreach ($ufs as $uf) {
                            $selected = ($session->getData($formDataName)['creci_uf'] ?? '') === $uf ? 'selected' : '';
                            echo "<option value=\"$uf\" $selected>$uf</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="creci-info">
                    <label for="creci_type">Categoria:</label>
                    <select id="creci_type" name="creci_type" autocomplete="off">
                        <option value="">Selecione a categoria</option>
                        <?php 
                        $categories = [
                            'F' => 'Pessoa Física (F)',
                            'J' => 'Pessoa Jurídica (J)',
                            'FS' => 'Secundária (Física)',
                            'JS' => 'Secundária (Jurídica)'
                        ];
                        foreach ($categories as $value => $label) {
                            $selected = ($session->getData($formDataName)['creci_type'] ?? '') === $value ? 'selected' : '';
                            echo "<option value=\"$value\" $selected>$label</option>";
                        }
                        ?>
                    </select>
                </div>

                <?= $session->showMessage('register-realtor-form-creci') ?>
                <?= $session->showMessage('register-realtor-form-creci-info') ?>
                <?= $session->showMessage('update-realtor-form-creci') ?>
                <?= $session->showMessage('update-realtor-form-creci-info') ?>
            </div>

            <div class="btn-container">
                <input type="submit" id="form-submit" value="Cadastrar Corretor" class="btn btn-submit">
            </div>
        </form>

        <?= $session->showMessage('register-realtor-form') ?>
        <?= $session->showMessage('update-realtor-form') ?>
    </section>

    <section class="section table-section">
        <h2 style="text-align: center;">Corretores Cadastrados</h2>

        <div class="scroll-box">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>CRECI</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($realtors as $realtor): ?>
                        <tr>
                            <td><?= htmlspecialchars($realtor["realtor_id"]) ?></td>
                            <td><?= htmlspecialchars($realtor["name"]) ?></td>
                            <td><?= htmlspecialchars($realtor["cpf"]) ?></td>
                            <td><?= htmlspecialchars($realtor["creci"]) ?></td>
                            <td>
                                <div class="btn-container">
                                    <button
                                        type="button"
                                        class="btn edit-btn btn-form btn-primary"
                                        data-id="<?= htmlspecialchars($realtor["realtor_id"]) ?>"
                                        data-name="<?= htmlspecialchars($realtor["name"]) ?>"
                                        data-cpf="<?= htmlspecialchars($realtor["cpf"]) ?>"
                                        data-creci="<?= htmlspecialchars($realtor["creci"]) ?>"
                                    >
                                        Editar
                                    </button>
                                    <form action="index.php" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este corretor?');">
                                        <input type="hidden" name="action" value="delete-realtor">
                                        <input type="hidden" name="controller" value="realtor">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($realtor["realtor_id"]) ?>">
                                        <button type="submit" class="btn btn-form btn-danger">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?= $session->showMessage('realtor-table') ?>
        </div>
    </section>
</div>

