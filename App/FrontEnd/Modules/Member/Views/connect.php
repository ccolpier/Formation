<h2>Se connecter</h2>
<script><?= $form->generateAjaxValidation() ?></script>
<form action="" method="post" onsubmit="return false;" id="mainForm">
    <p>
        <?= $form->createView() ?>
        <a href="/formation/restore-pass.html">Mot de passe oublié?</a></br>
        <input type="submit" value="Se connecter" onclick="return <?= $form->ajaxFunctionName() ?>;" />
    </p>
</form>