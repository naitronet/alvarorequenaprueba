<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 * @UniqueEntity(fields="name", message="El nombre de la persona ya existe.")
 */
class Person
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer", options={"unsigned"=true})
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255, unique=true)
	 */
	private $name;

	/**
	 * @ORM\Column(type="string", length=100)
	 */
	private $profile;

	/**
	 * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
	 */
	private $salary;

	/**
	 * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="persons")
	 * @ORM\JoinColumn(nullable=true)
	 */
	private $club;

	private $clubId;
	private $clubName;
	private $clubShield;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $position;

	/**
	 * @ORM\Column(type="date", nullable=true)
	 */
	private $birthDate;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setName(string $name): self
	{
		$this->name = $name;
		return $this;
	}

	public function getProfile(): ?string
	{
		return $this->profile;
	}

	public function setProfile(string $profile): self
	{
		$this->profile = $profile;
		return $this;
	}

	public function getSalary(): ?int
	{
		return $this->salary;
	}

	public function setSalary(?int $salary): self
	{
		$this->salary = $salary;
		return $this;
	}

	public function getClub(): ?Club
	{
		return $this->club;
	}

	public function setClub(?Club $club): self
	{
		$this->club = $club;
		return $this;
	}

	public function getClubId(): ?int
	{
		return $this->clubId;
	}

	public function setClubId(?int $id): self
	{
		$this->clubId = $id;
		return $this;
	}

	public function getClubName(): ?string
	{
		return $this->clubName;
	}

	public function setClubName(string $name)
	{
		$this->clubName = $name;
		return $this;
	}

	public function getClubShield(): ?string
	{
		return $this->clubShield;
	}

	public function setClubShield(?string $shield): self
	{
		$this->clubShield = $shield;
		return $this;
	}

	public function getPosition(): ?string
	{
		return $this->position;
	}

	public function setPosition(?string $position): self
	{
		$this->position = $position;
		return $this;
	}

	public function getBirthDate(): ?\DateTimeInterface
	{
		return $this->birthDate;
	}

	public function setBirthDate(?\DateTimeInterface $birthDate): self
	{
		$this->birthDate = $birthDate;
		return $this;
	}
}
