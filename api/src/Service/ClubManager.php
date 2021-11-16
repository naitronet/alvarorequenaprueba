<?php

namespace App\Service;

use App\Entity\Club;
use App\Repository\ClubRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ClubManager
{
	private $em;
	private $clubRepository;
	private $personManager;

	public function __construct(
		EntityManagerInterface $em,
		ClubRepository $clubRepository,
		PersonManager $personManager
	)
	{
		$this->em = $em;
		$this->clubRepository = $clubRepository;
		$this->personManager = $personManager;
	}

	public function find(int $id): ?Club
	{
		return $this->clubRepository->find($id);
	}

	public function findAll(): array
	{
		return $this->clubRepository->findAll();
	}

	public function getRepository(): ClubRepository
	{
		return $this->clubRepository;
	}

	public function create(): Club
	{
		return new Club;
	}

	public function persist(Club $club): Club
	{
		$this->em->persist($club);
		return $club;
	}

	public function save(Club $club): Club
	{
		$this->em->persist($club);
		$this->em->flush();
		return $club;
	}

	public function reload(Club $club): Club
	{
		$this->em->refresh($club);
		return $club;
	}

	public function delete(Club $club): void
	{
		$this->em->remove($club);
		$this->em->flush();

		$this->deleteImageShield($club);
	}

	public function deleteImageShield(Club $club): void
	{
		$shield = $club->getShield();
		$filename = $_SERVER['DOCUMENT_ROOT'].'storage/default/'.$shield;

		if (file_exists($filename)) {
			unlink($filename);
		}
	}

	public function checkSalaryLimit(Club $club, int $currentSalary, int $newSalary): bool
	{
		// Obtener la suma de todos los salarios de las personas del club
		$salaryTotal = $this->personManager->getTotalSalary($club->getId());

		if (($salaryTotal - $currentSalary + $newSalary) > $club->getSalaryLimit()) {
			return FALSE;
		}

		return TRUE;
	}

	public function checkPersonLimit(int $clubId, string $profile): bool
	{
		$totalPlayers = $this->personManager->getTotalPersons($clubId, $profile);

		if (($profile === 'Jugador' && $totalPlayers >= 5) ||
			($profile === 'Entrenador' && $totalPlayers >= 1)) {
			return FALSE;
		}

		return TRUE;
	}
}