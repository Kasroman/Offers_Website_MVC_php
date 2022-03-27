<h1>Liste des annonces</h1>

<?php foreach($annonces as $annonce): ?>
    <article>
        <h2><a href="annonces/read/<?= $annonce->id ?>"><?= $annonce->title ?></a></h2>
        <p><?= $annonce->description ?></p>
        <p><?= $annonce->created_at ?></p>
    </article>
<?php endforeach; ?>