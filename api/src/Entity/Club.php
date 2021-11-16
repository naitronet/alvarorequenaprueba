<?php

namespace App\Entity;

use App\Repository\ClubRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ClubRepository::class)
 * @UniqueEntity(fields="name", message="El nombre del equipo ya existe.")
 */
class Club
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
	 * @ORM\Column(type="string", length=512, nullable=true)
	 */
	private $shield;

	/**
	 * @ORM\Column(type="integer", options={"unsigned"=true})
	 */
	private $salary_limit;

	/**
	 * @ORM\OneToMany(targetEntity=Person::class, mappedBy="club", orphanRemoval=true)
	 */
	private $persons;

	public function __construct()
	{
		$this->persons = new ArrayCollection();
	}

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

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

	public function getShield(): ?string
	{
		return $this->shield;
	}

	public function setShield(?string $shield): self
	{
		$this->shield = $shield;
		return $this;
	}

	public function getSalaryLimit(): ?int
	{
		return $this->salary_limit;
	}

	public function setSalaryLimit(int $salary_limit): self
	{
		$this->salary_limit = $salary_limit;
		return $this;
	}

	/**
	 * @return Collection|Person[]
	 */
	public function getPersons(): Collection
	{
		return $this->persons;
	}

	public function addPerson(Person $person): self
	{
		if (!$this->persons->contains($person)) {
			$this->persons[] = $person;
			$person->setClub($this);
		}

		return $this;
	}

	public function removePerson(Person $person): self
	{
		if ($this->persons->removeElement($person)) {
			// set the owning side to null (unless already changed)
			if ($person->getClub() === $this) {
				$person->setClub(null);
			}
		}

		return $this;
	}
}
