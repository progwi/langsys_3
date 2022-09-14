<?php

require_once "bootstrap.php";
require_once __DIR__ . '/services/user.service.php';

$userService = new UserService($entityManager);

$list = $userService->list();

foreach ($list as $user) {
	echo $user->getName() . "\n";
}