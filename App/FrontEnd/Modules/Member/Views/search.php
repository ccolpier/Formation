<h2>Recherche de membre</h2>
<form action="" method="post">
    <p>
        <?= $form ?>

        <input type="submit" value="Rechercher un membre" />
    </p>
</form>
<?php
if(!empty($listeMembres)) { ?>
    <h2>Résultats de la recherche</h2>
    <?php
    foreach ($listeMembres as $member) {
        ?>
        <p><a href="/formation/member-<?= $member['id'] ?>.html"><?= $member['nickname'] ?></a></p>
        <hr>
        <?php
    }
}?>