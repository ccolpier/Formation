<h2>Recherche de membre</h2>
<script><?= $form->generateAjaxValidation() ?></script>
<form action="" method="post" onsubmit="return false;" id="mainForm">
    <p>
        <?= $form->createView() ?>
        <a href="/formation/restore-pass.html">Mot de passe oublié?</a></br>
        <input type="submit" value="Rechercher un membre" onclick="return <?= $form->ajaxFunctionName() ?>;" />
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