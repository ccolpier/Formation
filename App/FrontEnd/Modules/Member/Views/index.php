<?php
    //mode public: on affiche les informations détaillées et on affiche l'update
    if($mode == 'public'){ ?>
        <div>
            <img style="top; left;" src="/formation/images/profiles/<?php echo $member->id() ?>/<?php echo $member->photo() ?>" alt="Image non renseignée">
            <h2>Informations du membre <?php echo $member->nickname() ?></h2>
            <p>
                <li>Email: <?php echo $member->email() ?></li>
                <li>Date d'inscription: <?php echo $member->dateofregister() ?></li>
            </p>
        </div>
    <?php }
    //mode privé: on affiche juste le minimum d'informations
    elseif($mode == 'private'){ ?>
        <div>
            <img style="top; left;" src="/formation/images/profiles/<?php echo $member->id() ?>/<?php echo $member->photo() ?>" alt="Image non renseignée">
            <h2>Informations du membre <?php echo $member->nickname() ?></h2>
            <p>
                <li>Email: <?php echo $member->email() ?></li>
                <li>Prénom: <?php echo $member->firstname() ?></li>
                <li>Nom de famille: <?php echo $member->lastname() ?></li>
                <li>Date de naissance: <?php echo $member->dateofbirth() ?></li>
                <li>Date d'inscription: <?php echo $member->dateofregister() ?></li>
            </p>
            <a href="/formation/update-profile.html">Mise à jour des informations du profil</a>
        </div>
    <?php }
?>