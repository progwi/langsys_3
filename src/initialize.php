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

// Querying for all users
$userRepository = $entityManager->getRepository(User::class);
$users = $userRepository->findAll();

foreach ($users as $user) {
	echo $user . PHP_EOL;
}

// Querying for a single user
$user = $userRepository->findOneBy(['id' => 1]);
echo $user . PHP_EOL;

// Querying with DQL
$dql = "SELECT u FROM User u WHERE u.id = ?1 ORDER BY u.name ASC";
$query = $entityManager->createQuery($dql);
$query->setParameter(1, 3);
$user = $query->getSingleResult();

echo $user . " is " . $user->maxRole() . PHP_EOL;

// Query  one user and his bigger role according its id
$dql = "SELECT u, r.id FROM User u JOIN u.roles r WHERE u.id = ?1 ORDER BY r.id DESC";
$query = $entityManager->createQuery($dql);
$query->setParameter(1, 3);
$userRole = $query->getSingleResult();

echo $userRole[0] . ' is ' . $userRole['id'] . PHP_EOL;

