<p>Par <em><?= $news['auteur'] ?></em>, le <?= $news['dateAjout']->format('d/m/Y � H\hi') ?></p>
<h2><?= $news['titre'] ?></h2>
<p><?= nl2br($news['contenu']) ?></p>

<?php if ($news['dateAjout'] != $news['dateModif']) { ?>
    <p style="text-align: right;"><small><em>Modifi�e le <?= $news['dateModif']->format('d/m/Y � H\hi') ?></em></small></p>
<?php } ?>