<?php

use Managers\ClubManager;

include $_SERVER['DOCUMENT_ROOT'].'/../conf.php';
include PATH_INCLUDES.'header.php';

$error = null;
$clubManager = new ClubManager;
$club = $clubManager->getById((int)$_REQUEST['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
	$response = $clubManager->edit($_POST, $_FILES);

	if (isset($response['id'])) {
		header('Location: /clubs');
		die;
	}

	$error = $response['message'];
}

?>

<div class="row">
	<div class="col-12 col-sm-10 col-lg-6 mx-auto">
		<?php if ($error) { ?>
			<div class="alert alert-danger"><?=$error?></div>
		<?php } ?>

		<form class="bg-white shadow rounded py-3 px-4"
			method="POST"
			autocomplete="on"
			enctype="multipart/form-data"
			>
			<input type="hidden" name="MAX_FILE_SIZE" value="10485760"><!-- 10 MB -->
			<input type="hidden" name="id" value="<?=$_REQUEST['id']?>">

			<h1>Editar club</h1>
			<hr>
			<div class="form-group">
				<label for="name">Nombre</label>
				<input class="form-control bg-light shadow-sm border-0"
					type="text"
					id="name"
					name="name"
					placeholder="Nombre"
					required
					value="<?=$club['name']??''?>">
			</div>

			<div class="form-group">
				<label for="salary_limit">Límite salarial</label>
				<input class="form-control bg-light shadow-sm border-0"
					type="number"
					id="salary_limit"
					name="salary_limit"
					placeholder="Límite salarial"
					min="0"
					required
					value="<?=$club['salary_limit']??''?>">
			</div>

			<div class="form-group">
				<label for="shield">Escudo</label>
				<input class="form-control bg-light shadow-sm border-0"
					id="shield"
					type="file"
					name="shield"
					accept="image/png, image/jpeg">
			</div>

			<div class="pt-3">
				<button class="btn btn-primary btn-block">Enviar</button>
			</div>
		</form>
	</div>
</div>

<script>
	if (window.history.replaceState) {
		window.history.replaceState(null, null, window.location.href);
	}
</script>

<?php include PATH_INCLUDES.'footer.php'; ?>