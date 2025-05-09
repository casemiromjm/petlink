<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="../stylesheets/style.css">

<?php function drawAdCreate() { ?>
    <section class="ad-create">
        <h2>Anunciar Serviço</h2>
        <form id="ad-form" action="../actions/action_adCreate.php" method="post" enctype="multipart/form-data">
            <label for="upload-box">Carregar fotografias</label>
            <div class="upload-box">
                <input type="file" id="imageUpload" name="image" accept="image/*">
            </div>
            <label for="titulo">Título</label>
            <input type="text" id="titulo" name="titulo" required>
            <br>

            <label for="descricao">Descrição</label>
            <textarea type="text" id="descricao" name="descricao" required></textarea>
            <br>

            <label for="tipo">Tipo de serviço</label>
            <select id="tipo" name="tipo" required>
                <option disabled selected>Selecionar</option>
                <option>Passeio</option>
                <option>Tosquia</option>
                <option>Pet Sitting</option>
                <option>Banho</option>
                <option>Tosquia</option>
            </select>
            <br>

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
                <br>
            </div>

            <label for="animais">Animais</label>
            <div class="animal-checkboxes">
                <label><input type="checkbox" name="animais[]" value="Cães">Cães</label>
                <label><input type="checkbox" name="animais[]" value="Gatos">Gatos</label>
                <label><input type="checkbox" name="animais[]" value="Pássaros">Pássaros</label>
                <label><input type="checkbox" name="animais[]" value="Hamsters">Furões</label>
                <label><input type="checkbox" name="animais[]" value="Coelhos">Coelhos</label>
                <label><input type="checkbox" name="animais[]" value="Peixes">Peixes</label>
                <label><input type="checkbox" name="animais[]" value="Roedores">Roedores</label>
                <label><input type="checkbox" name="animais[]" value="Répteis">Répteis</label>
            </div>

            <button type="submit">Criar Anúncio</button>
        </form>
    </section>
<?php } ?>