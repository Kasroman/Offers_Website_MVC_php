<h1>Mes annonces</h1>
<?php foreach($annonces as $annonce): ?>
<article>
    <h2><a href="read/<?= $annonce->id ?>"><?= $annonce->title ?></a></h2>
    <p><?= $annonce->description ?></p>
    <p><?= $annonce->created_at ?></p>
    <button type="button" class="btn btn-primary"><a style = "color:white; text-decoration:none;" href="update/<?= $annonce->id ?>">Modifier</a></button>
    <button type="button" class="btn btn-danger"><a style = "color:white; text-decoration:none;" href="remove/<?= $annonce->id ?>">Supprimer</a></button>
</article>
<?php endforeach; ?>