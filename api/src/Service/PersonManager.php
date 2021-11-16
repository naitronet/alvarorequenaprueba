<?php

namespace App\Service;

use App\Entity\Person;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\UrlHelper;

class PersonManager
{
	private $em;
	private $personRepository;
    private UrlHelper $urlHelper;

	public function __construct(
		EntityManagerInterface $em,
		PersonRepository $personRepository,
		UrlHelper $urlHelper
	)
	{
		$this->em = $em;
		$this->personRepository = $personRepository;
		$this->urlHelper = $urlHelper;
	}

	public function find(int $id): ?Person
	{
		return $this->personRepository->find($id);
	}

	public function findAll(): array
	{
		$persons = $this->personRepository->findAll();

		if ($persons) {
			$this->completeDataClub($persons);
		}

		return $persons;
	}

	public function findByClub(int $clubId): array
	{
		$persons = $this->personRepository->findBy(['club' => $clubId]);

		if ($persons) {
			$this->completeDataClub($persons);
		}

		return $persons;
	}

	public function completeDataClub(array $persons): array
	{
		foreach ($persons as $k => $person) {
			if (!$person->getClub()) {
				continue;
			}
			$persons[$k]->setClubId($person->getClub()->getId());
			$persons[$k]->setClubName($person->getClub()->getName());
			if (!empty($person->getClub()->getShield())) {
				$shield = $person->getClub()->getShield();
				$shield = $this->urlHelper->getAbsoluteUrl('/storage/default/'.$shield);
				$persons[$k]->setClubShield($shield);
			}
		}

		return $persons;
	}

	public function create(): Person
	{
		return new Person;
	}

	public function persist(Person $person): Person
	{
		$this->em->persist($person);
		return $person;
	}

	public function save(Person $person): Person
	{
		$this->em->persist($person);
		$this->em->flush();
		return $person;
	}

	public function reload(Person $person): Person
	{
		$this->em->refresh($person);
		return $person;
	}

	public function delete(Person $person): void
	{
		$this->em->remove($person);
		$this->em->flush();
	}

	public function getTotalSalary(int $clubId): int
	{
		return $this->personRepository->getTotalSalary($clubId);
	}

	public function getTotalPersons(int $clubId, string $profile): int
	{
		return $this->personRepository->getTotalPersons($clubId, $profile);
	}
}