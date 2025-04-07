<?php

// Função para ativar o modo de depuração
function debugCard($debugMode, $data): void {
    if ($debugMode) {
        echo '<div class="section debug scroll-box">';
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
        echo '</div>';
    }
}

?>
