<?php

namespace App\Service;

use App\Entity\Club;
use App\Form\Model\ClubDto;
use App\Form\Model\PersonDto;
use App\Form\Type\ClubFormType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class ClubFormProcessor
{
	private $clubManager;
	private $personManager;
	private $fileUploader;
	private $formFactory;

	public function __construct(
		ClubManager $clubManager,
		PersonManager $personManager,
		FileUploader $fileUploader,
		FormFactoryInterface $formFactory
	)
	{
		$this->clubManager = $clubManager;
		$this->personManager = $personManager;
		$this->fileUploader = $fileUploader;
		$this->formFactory = $formFactory;
	}

	public function __invoke(Club $club, Request $request): array
	{
		$clubDto = ClubDto::createFromClub($club);
		$originalPersons = new ArrayCollection();

		foreach ($club->getPersons() as $person) {
			$personDto = PersonDto::createFromPerson($person);
			$clubDto->persons[] = $personDto;
			$originalPersons->add($personDto);
		}

		$form = $this->formFactory->create(ClubFormType::class, $clubDto);
		$form->handleRequest($request);

		if (!$form->isSubmitted()) {
			return [null, 'El formulario no ha sido enviado'];
		}

		if (!$form->isValid()) {
			return [null, $form];
		}

		// Remove persons
		foreach ($originalPersons as $originalPersonDto) {
			if (!in_array($originalPersonDto, $clubDto->persons)) {
				$person = $this->personManager->find($originalPersonDto->id);
				$club->removePerson($person);
			}
		}

		// Add persons
		foreach ($clubDto->persons as $newPersonDto) {
			if (!$originalPersons->contains($newPersonDto)) {
				$person = $this->personManager->find($newPersonDto->id ?? 0);

				if (!$person) {
					$person = $this->personManager->create();
					$person->setName($newPersonDto->name);
					$person->setProfile($newPersonDto->profile);
					$person->setSalary($newPersonDto->salary);
					$person->setPosition($newPersonDto->position);
					$this->personManager->persist($person);
				}

				$club->addPerson($person);
			}
		}

		$club->setName($clubDto->name);
		$club->setSalaryLimit($clubDto->salary_limit);

		if ($clubDto->base64Image) {
			$filename = $this->fileUploader->uploadBase64File($clubDto->base64Image);
			$club->setShield($filename);
		}

		// echo '<pre>$club->getId(): '.var_export($club->getId(), 1).'</pre>';

		try {
			$this->clubManager->save($club);
			$this->clubManager->reload($club);
			return [$club, null];
		} catch (UniqueConstraintViolationException $e) {
			return [null, 'Ya existe otro club con el mismo nombre'];
		}
	}
}