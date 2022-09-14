<?php

require_once __DIR__ . '/../models/Role.php';
require_once __DIR__ . '/../models/Person.php';
require_once __DIR__ . '/../models/User.php';

class UserService
{
	private $entityManager;

	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function create($data)
	{
		$person = new Person($data);
		$user = new User($data);
		$user->setPerson($person);
		$role = $this->entityManager->find('Role', 1);
		$user->addRole($role);
		$this->entityManager->persist($user);
		$this->entityManager->flush();
	}

	public function update($id, $data)
	{
		$user = $this->entityManager->find('User', $id);
		$user->setName($data['name']);
		$user->setEmail($data['email']);
		$user->setPassword($data['password']);
		$person = $user->getPerson();
		$person->setFirstName($data['firstName']);
		$person->setLastName($data['lastName']);
		$person->setHeight($data['height']);
		$person->setBirthDate($data['birthDate']);
		$this->entityManager->flush();
	}

	public function delete($id)
	{
		$user = $this->entityManager->find('User', $id);
		$this->entityManager->remove($user);
		$this->entityManager->flush();
	}

	public function list()
	{
		$users = $this->entityManager->getRepository('User')->findAll();
		return $users;
	}

	public function find($id)
	{
		$user = $this->entityManager->find('User', $id);
		return $user;
	}
}
