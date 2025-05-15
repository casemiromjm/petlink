<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="../stylesheets/style.css">

<?php function drawAdCreate() { ?>
    <section class="form-container">
        <h2>Anunciar Serviço</h2>
        <form id="ad-form" action="../actions/action_adCreate.php" method="post" enctype="multipart/form-data">
            <label for="upload-box">Carregar fotografias</label>
            <div class="upload-box">
                <input type="file" id="imageUpload" name="image" accept="image/*">
            </div>
            <label for="titulo">Título</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" required></textarea>

            <label for="tipo">Tipo de serviço</label>
            <select id="tipo" name="tipo" required>
                <option disabled selected>Selecionar</option>
                <option value="1">Passeio</option>
                <option value="2">Tosquia</option>
                <option value="3">Petsitting</option>
                <option value="4">Treino</option>
                <option value="5">Alojamento</option>
                <option value="6">Veterinário</option>
                <option value="7">Transporte</option>
            </select>

            <label for="preco">Preço</label>
            <div id="preco-container">
                <input type="number" id="preco" name="preco" required>
                <label for="preco-por">€ /</label>
                <select id="preco-por" name="preco-por" required>
                    <option disabled selected>Selecionar</option>
                    <option>hora</option>
                    <option>dia</option>
                    <option>semana</option>
                    <option>mês</option>
                </select>
            </div>

            <label for="animais">Animais</label>
            <div class="animal-checkboxes">
                <label><input type="checkbox" name="animais[]" value="1">Cães</label>
                <label><input type="checkbox" name="animais[]" value="2">Gatos</label>
                <label><input type="checkbox" name="animais[]" value="3">Pássaros</label>
                <label><input type="checkbox" name="animais[]" value="4">Roedores</label>
                <label><input type="checkbox" name="animais[]" value="5">Répteis</label>
                <label><input type="checkbox" name="animais[]" value="6">Peixes</label>
                <label><input type="checkbox" name="animais[]" value="7">Furões</label>
                <label><input type="checkbox" name="animais[]" value="8">Coelhos</label>
            </div>

            <button type="submit">Criar Anúncio</button>
        </form>
    </section>
<?php } ?>