<?php

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 * @var int
	 */
	private $id;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $name;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $email;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $password;

	/**
	 * @ORM\OneToOne(targetEntity="Person", cascade={"remove"})
	 * 
	 * @var Person
	 */
	private $person;

	/**
	 * @ORM\ManyToMany(targetEntity="Role")
	 * @var Role[] An ArrayCollection of Role objects.
	 */
	private $roles;

	public function __construct(
		$data = [
			"name"		=> '',
			"email"		=> '',
			"password"	=> ''
		]
	) {
		$this->name = $data['name'];
		$this->email = $data['email'];
		$this->password = $data['password'] ?? '123456';
		$this->roles = new ArrayCollection();
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setPassword($password)
	{
		$this->password = $password;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function addRole(Role $role)
	{
		$this->roles[] = $role;
	}

	public function getRoles()
	{
		return $this->roles;
	}

	public function setPerson(Person $person)
	{
		$this->person = $person;
		$person->setUser($this);
	}

	public function getPerson()
	{
		return $this->person;
	}

	// to string
	public function __toString()
	{
		return $this->name . ' - ' . $this->getPerson()->getFirstName() ;
	}

	public function maxRole()
	{
		$max = 0;
		$maxRole = null;
		foreach ($this->getRoles() as $role) {
			if ($role->getId() > $max) {
				$max = $role->getId();
				$maxRole = $role;
			}
		}
		return $maxRole;
	}
}
