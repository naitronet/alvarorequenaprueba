<?php

use Managers\ClubManager;

include $_SERVER['DOCUMENT_ROOT'].'/../conf.php';
include PATH_INCLUDES.'header.php';

$clubManager = new ClubManager();
$response = $clubManager->delete($_GET['id']);

header('Location: /clubs');