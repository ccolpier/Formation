<!DOCTYPE html>
<html>
<head>
    <title>
        <?= isset($title) ? $title : 'Mon super site' ?>
    </title>

    <meta charset="utf-8" />

    <link rel="stylesheet" href="/formation/css/Envision.css" type="text/css" />
    <script src="scripts/formValidate.js"></script>
    <script src="scripts/jquery-2.1.4.js"></script>
</head>

<body>
<div id="wrap">
    <header>
        <h1><a href="/formation/">Mon super site</a></h1>
        <p>Comment �a, il n'y a presque rien ?</p>
    </header>

    <nav>
        <ul>
            <li><a href="/formation/">Accueil</a></li>
            <?php if ($user->isAuthenticated()) { ?>
                <li><a href="/formation/admin/">Admin</a></li>
                <li><a href="/formation/admin/news-insert.html">Ajouter une news</a></li>
                <li><a href="/formation/logout.html">Se d�connecter</a></li>
            <?php } else {?>
                <li><a href="/formation/register.html">Inscription</a></li>
                <li><a href="/formation/connect.html">Connexion</a></li>
            <?php }?>
            <li><a href="/formation/news.html">Voir les News</a></li>
            <li><a href="/formation/search.html">Rechercher un membre</a></li>
        </ul>
    </nav>

    <div id="content-wrap">
        <section id="main">
            <?php if ($user->hasFlash()) echo '<p style="text-align: center;">', $user->getFlash(), '</p>'; ?>

            <?= $content ?>
        </section>
    </div>

    <footer></footer>
</div>
</body>
</html>