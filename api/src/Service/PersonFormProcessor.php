<?php

namespace App\Service;

use App\Entity\Club;
use App\Form\Model\PersonDto;
use App\Form\Type\PersonFormType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\UrlHelper;

class PersonFormProcessor
{
	private $personManager;
	private $clubManager;
	private $formFactory;
    private UrlHelper $urlHelper;

	public function __construct(
		PersonManager $personManager,
		ClubManager $clubManager,
		FormFactoryInterface $formFactory,
		UrlHelper $urlHelper
	)
	{
		$this->personManager = $personManager;
		$this->clubManager = $clubManager;
		$this->formFactory = $formFactory;
		$this->urlHelper = $urlHelper;
	}

	public function __invoke(Request $request, ?int $personId = null): array
	{
		if (!$personId) {
			$person = $this->personManager->create();
			$personDto = PersonDto::createEmpty();
		} else {
			$person = $this->personManager->find($personId);
			$personDto = PersonDto::createFromPerson($person);
		}

		$form = $this->formFactory->create(PersonFormType::class, $personDto);
		$form->handleRequest($request);

		if (!$form->isSubmitted()) {
			return [null, 'El formulario no ha sido enviado'];
		}

		if (!$form->isValid()) {
			return [null, $form];
		}

		if ($personDto->profile === 'Canterano') {
			if (!$personDto->birthDate) {
				return [null, 'Los canteranos deben tener fecha de nacimiento'];
			}

			if ($personDto->salary > 0) {
				return [null, 'Los canteranos no pueden tener salario'];
			}

			// Comprobar que la edad no sea más de 24 años
			if (strtotime($personDto->birthDate->format('Y-m-d').' +24 years') > time()) {
				return [null, 'Los canteranos no pueden tener más de 24 años'];
			}
		}

		if ($personDto->profile === 'Entrenador' && $personDto->position) {
			return [null, 'El entrenador no puede tener posición'];
		}

		if ($personDto->club) {
			$club = $this->clubManager->find($personDto->club);

			if (!$club) {
				return [null, 'El club con ID "'.$personDto->club.'" no existe'];
			}

			// Comprobar si se sobrepasaría el límite de jugadores del club
			if (
				!$personId &&
				!$this->clubManager->checkPersonLimit($club->getId(), $personDto->profile)
			) {
				return [null, 'Se ha sobrepasado el límite de personas del perfil "'.$personDto->profile.'"'];
			}

			if (
				in_array($personDto->profile, ['Entrenador', 'Jugador']) &&
				!$personDto->salary
			) {
				return [null, 'La persona debe tener un salario'];
			}

			// Comprobar si, el salario del jugador, sobrepasaría el límite salarial del club
			if (!$this->clubManager->checkSalaryLimit($club, (int)$person->getSalary(), (int)$personDto->salary)) {
				return [null, 'Se ha sobrepasado el límite salarial del club'];
			}

			$person->setClub($club);
			$person->setClubId($club->getId());
			$person->setClubName($club->getName());
			if (!empty($club->getShield())) {
				$shield = $club->getShield();
				$shield = $this->urlHelper->getAbsoluteUrl('/storage/default/'.$shield);
				$person->setClubShield($shield);
			}
		} else {
			$person->setClub(null);
		}

		$person->setName($personDto->name);
		$person->setProfile($personDto->profile);
		$person->setSalary($personDto->salary);
		$person->setPosition($personDto->position);
		$person->setBirthDate($personDto->birthDate);

		try {
			$this->personManager->save($person);
			$this->personManager->reload($person);

			return [$person, null];
		} catch (UniqueConstraintViolationException $e) {
			return [null, 'Ya existe otra persona con el mismo nombre'];
		}
	}
}