<?php

declare(strict_types = 1);

function drawRetrievePassword($csrf_token) : void {
?>

    <?php if (isset($_GET['success'])): ?>
    <div id="success-message" class="success-message" style="margin-bottom: 0;">Alterações guardadas com sucesso.</div>
    <?php endif; ?>

    <div class='form-container'>
        <h2>Recuperação de senha</h2>
        <?php if (isset($_GET['error'])): ?>
        <div class="error-message">
        <?php
            echo 'Ocorreu um erro ao alterar a password.';
        ?>
        </div>
        <?php endif; ?>
        <form id="config-form" action="../actions/action_retrievepassword.php" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">

            <label for="email">Confirme seu email cadastrado</label>
            <input type="email" id="email" name="email" required>

            <label for="username">Confirme seu username cadastrado</label>
            <input type="text" id="username" name="username" required>

            <label for="district">Confirme seu distrito</label>
                <select id="district" name="district" required>
                    <option value="" disabled selected>Selecione o seu distrito</option>
                    <option value="Açores">Açores</option>
                    <option value="Aveiro">Aveiro</option>
                    <option value="Beja">Beja</option>
                    <option value="Braga">Braga</option>
                    <option value="Bragança">Bragança</option>
                    <option value="Castelo Branco">Castelo Branco</option>
                    <option value="Coimbra">Coimbra</option>
                    <option value="Évora">Évora</option>
                    <option value="Faro">Faro</option>
                    <option value="Guarda">Guarda</option>
                    <option value="Leiria">Leiria</option>
                    <option value="Lisboa">Lisboa</option>
                    <option value="Madeira">Madeira</option>
                    <option value="Portalegre">Portalegre</option>
                    <option value="Porto">Porto</option>
                    <option value="Santarém">Santarém</option>
                    <option value="Setúbal">Setúbal</option>
                    <option value="Viana do Castelo">Viana do Castelo</option>
                    <option value="Vila Real">Vila Real</option>
                    <option value="Viseu">Viseu</option>
                </select>

            <label for="new_password">Nova Password</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">Confirmar Nova Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Guardar Alterações</button>
        </form>
    </div>

<?php } ?>
