</div>
<footer>
        <div class="footer-container">
            
            <!-- Informações -->
            <div class="footer-content">
                <!-- Informações de contato -->
                <div class="contact-info">
                    <p>Email: <?= CONTACT_EMAIL ?></p>
                    <p>Telefone: <?= CONTACT_PHONE ?></p>
                </div>
    
                <!-- Informações da aplicação -->
                <div class="app-info">
                    <p><?= APP_NAME ?></p>
                    <p>Versão: <?= APP_VERSION ?></p>
                </div>
            </div>

            <!-- Direitos autorais -->
            <div class="copyright">
                    <p>&copy; 2025 <?= APP_AUTHOR ?>. Todos os direitos reservados.</p>
                </div>
        </div>
    </footer>
    <script src="<?= JAVASCRIPT_PATH ?>/main.js" type="module" defer></script>
</body>
</html>