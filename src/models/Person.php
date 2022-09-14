<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="person")
 */
class Person
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	private $id;

	/**
	 * @ORM\Column(type="string")
	 */
	private $firstName;

	/**
	 * @ORM\Column(type="string")
	 */
	private $lastName;
	
	/**
	 * @ORM\Column(type="float")
	 */
	private $height;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $birthDate;

	/**
	 * @ORM\OneToOne(targetEntity="User")
	 */
	private $user;

	public function __construct($firstName, $lastName)
	{
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->height = 1.80;
		$this->birthDate = new DateTime();
	}

	public function getId()
	{
		return $this->id;
	}

	public function getFirstName()
	{
		return $this->firstName;
	}

	public function getLastName()
	{
		return $this->lastName;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;
	}

	public function setLastName($lastName)
	{
		$this->lastName = $lastName;
	}

	public function setUser(User $user)
	{
		$this->user = $user;
	}

	/*
	public function __toString()
	{
		return $this->firstName . ' ' . $this->lastName . ' who is ' . $this->user->maxRole();
	}
	*/
}
