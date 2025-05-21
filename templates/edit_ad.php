<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="../stylesheets/style.css">

<?php function drawEditAd(array $animal, array $speciesList): void { ?>
<main class="animals-layout">
    <aside class="side-nav">
        <?php drawNavbar(); ?>
    </aside>
    <!-- tlvz isso seja uma classe ja utilizada, mas not sure -->
    <section class="ad-content">
        <div class="form-container">
            <h2>Editar Animal</h2>
        </div>
    </section>
</main>

<?php } ?>