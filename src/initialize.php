<?php

require_once "bootstrap.php";
require_once __DIR__ . '/models/Role.php';
require_once __DIR__ . '/models/Person.php';
require_once __DIR__ . '/models/User.php';

if (!isset($entityManager)) {
	echo "Entity manager is not set.\n";
	return;
}

// First, we take the roles.json file which contains a list of roles, and proccess each
$initialRoles = json_decode(file_get_contents(__DIR__ . '/models/roles.json'), true);

foreach ($initialRoles as $role) {

	$existingRole = $entityManager->getRepository(Role::class)->findOneBy(
		['id' => $role['id']]
	);

	if ($existingRole) {
		continue;
	}

	$newRole = new Role($role['id'], $role['name']);
	$entityManager->persist($newRole);
}

$entityManager->flush();

// Second, we take the users.json file which contains a list of users, and proccess each
$initialUsers = json_decode(file_get_contents(__DIR__ . '/models/users.json'), true);

foreach ($initialUsers as $user) {
	$userInfo = [
		'name' => $user['name'],
		'email' => $user['email'],
		'password' => $user['password'] ?? '123456'
	];

	$existingUser = $entityManager->getRepository(User::class)->findOneBy(
		['email' => $userInfo['email']]
	);

	if ($existingUser) {
		continue;
	}

	$userRolesData = $user['roles'];
	$userPersonData = $user['person'];

	$newUser = new User($userInfo);
	$newPerson = new Person($userPersonData);

	$newUser->setPerson($newPerson);

	foreach ($userRolesData as $roleId) {
		$roleEntity = $entityManager->getRepository(Role::class)->findOneBy(
			['id' => $roleId]
		);

		if ($roleEntity) {
			$newUser->addRole($roleEntity);
		}
	}

	$entityManager->persist($newUser);
	$entityManager->persist($newPerson);
}


$entityManager->flush();
