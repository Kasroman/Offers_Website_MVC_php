<h1>Liste des annonces</h1>

<table class="table table-striped">
    <thead>
        <th>ID</th>
        <th>Titre</th>
        <th>Contenu</th>
        <th>Actif</th>
        <th>Actions</th>
    </thead>
    <tbody>
        <?php foreach($annonces as $annonce): ?>
            <tr>
                <td><?= $annonce->id ?></td>
                <td><?= $annonce->title ?></td>
                <td><?= $annonce->description ?></td>
                <td>
                    <div class="custom-control custom-switch d-flex justify-content-center align-items-center">
                        <?php if($annonce->is_active == 1) : ?>
                            <a href="/site_annonces/public/admin/setActiveAnnonce/<?= $annonce->id ?>" class="btn btn-primary"></a>
                        <?php else : ?>
                            <a href="/site_annonces/public/admin/setActiveAnnonce/<?= $annonce->id ?>" class="btn btn-danger"></a>
                        <?php endif; ?>
                    </div>
                    
                </td>
                <td class="d-flex flex-row justify-content-evenly">
                    <a href="/site_annonces/public/annonces/update/<?= $annonce->id ?>" class="btn btn-primary">Modifier</a><a href="/site_annonces/public/annonces/remove/<?= $annonce->id ?>" class="btn btn-danger">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>