<?php

include $_SERVER['DOCUMENT_ROOT'].'/../conf.php';
include PATH_INCLUDES.'header.php';

use Managers\ClubManager;

$clubManager = new ClubManager();
$clubs = $clubManager->list();

?>

<div class="d-flex justify-content-between align-items-center mb-3">
	<h1 class="display-5 m-0">Clubs</h1>
	<a class="btn btn-primary" href="/clubs/crear/">
		Crear club
	</a>
</div>

<p class="lead text-secondary">Lista de clubs</p>

<?php if ($clubs) { ?>
	<ul class="list-group">
		<?php foreach ($clubs as $club) { ?>
			<li class="list-group-item border-0 mb-3">
				<div class="d-flex justify-content-between align-items-center text-secondary">
					<div class="d-flex align-items-center" style="flex-basis: 80%">
						<img class="shield mr-3" src="<?=$club['shield']?>" alt="Escudo">
						<span class="font-weight-bold ml-3 mr-5" style="flex-basis: 30%">
							<?=$club['name']?>
						</span>
						<span class="mr-3">Límite salarial:<br><?=$club['salary_limit']?></span>
					</div>
					<a class="btn btn-secondary btn-sm" href="/jugadores/?club=<?=$club['id']?>">Jugadores</a>
					<a class="btn btn-primary btn-sm ml-2" href="/clubs/editar/?id=<?=$club['id']?>">Editar</a>
					<a class="btn btn-danger btn-sm ml-2" href="/clubs/borrar/?id=<?=$club['id']?>">Borrar</a>
				</div>
			</li>
		<?php } ?>
	</ul>
<?php } else { ?>
	<p>No se han encontrado resultados.</p>
<?php } ?>

<?php include PATH_INCLUDES.'footer.php'; ?>