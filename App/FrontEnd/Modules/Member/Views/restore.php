<h2>R�cup�rer Mot de passe</h2>
<script><?= $form->generateAjaxValidation() ?></script>
<form action="" method="post" onsubmit="return false;" id="mainForm">
    <p>
        <?= $form->createView() ?>
        <a href="/formation/restore-pass.html">Mot de passe oubli�?</a></br>
        <input type="submit" value="R�cup�rer" onclick="return <?= $form->ajaxFunctionName() ?>;" />
    </p>
</form>