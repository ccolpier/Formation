<h2>S'inscrire</h2>
<script><?= $form->generateAjaxValidation() ?></script>
<form action="" method="post" onsubmit="return false;" id="mainForm">
    <p>
        <?= $form->createView() ?>
        <a href="/formation/restore-pass.html">Mot de passe oublié?</a></br>
        <input type="submit" value="S'inscrire" onclick="return <?= $form->ajaxFunctionName() ?>;" />
    </p>
</form>