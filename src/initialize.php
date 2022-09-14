<?php

require_once "bootstrap.php";
require_once __DIR__ . '/models/Role.php';

if (!isset($entityManager)) {
	echo "Entity manager is not set.\n";
	return;
}

// First, we take the roles.json file which contains a list of roles, and proccess each
$initialRoles = json_decode(file_get_contents(__DIR__ . '/models/roles.json'), true);

foreach ($initialRoles as $role) {
	$newRole = new Role($role['id'], $role['name']);
	$entityManager->persist($newRole);
}

$entityManager->flush();
