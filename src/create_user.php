<?php

require_once "bootstrap.php";

require_once "src/models/User.php";
require_once "src/models/Person.php";
require_once "src/models/Role.php";

if (!isset($entityManager)) {
	echo "Entity manager is not set.\n";
	return;
}

$user = new User("eormeno", "eormeno@gmail.com", "123456");
$person = new Person("Emilio", "Ormeño");

$user->setPerson($person);

$entityManager->persist($user);
$entityManager->persist($person);

$entityManager->flush();

