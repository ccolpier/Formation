<?php
foreach ($listeNews as $news)
{
    ?>
    <h2><a href="news-<?= $news['id'] ?>.html"><?= $news['titre'] ?></a></h2>
    <p><?= nl2br($news['contenu']) ?></p>
    <?php
}?>

<p>Par <em><?= $news['auteur'] ?></em>, le <?= $news['dateAjout']->format('d/m/Y à H\hi') ?></p>
<h2><?= $news['titre'] ?></h2>
<p><?= nl2br($news['contenu']) ?></p>

<?php if ($news['dateAjout'] != $news['dateModif']) { ?>
    <p style="text-align: right;"><small><em>Modifiée le <?= $news['dateModif']->format('d/m/Y à H\hi') ?></em></small></p>
<?php } ?>

<h2>Ajouter un commentaire</h2>
<form action="" method="post">
    <p>
        <?= isset($erreurs) && in_array(\Entity\Comment::AUTEUR_INVALIDE, $erreurs) ? 'L\'auteur est invalide.<br />' : '' ?>
        <label>Pseudo</label>
        <input type="text" name="pseudo" value="<?= isset($comment) ? htmlspecialchars($comment['auteur']) : '' ?>" /><br />

        <?= (isset($erreurs) && in_array(\Entity\Comment::CONTENU_INVALIDE, $erreurs)) ? 'Le contenu est invalide.<br />' : '' ?>
        <label>Contenu</label>
        <textarea name="contenu" rows="7" cols="50"><?= isset($comment) ? htmlspecialchars($comment['contenu']) : '' ?></textarea><br />

        <input type="submit" value="Commenter" />
    </p>
</form>
