<?php

namespace App\Form\Model;

use App\Entity\Person;

class PersonDto
{
	public $id;
	public $name;
	public $club;
	public $profile;
	public $salary;
	public $position;
	public $birthDate;

	public static function createEmpty()
	{
		return new self;
	}

	public static function createFromPerson(Person $person): self
	{
		$dto = new self;
		$dto->id = $person->getId();
		$dto->name = $person->getName();
		$dto->profile = $person->getProfile();
		$dto->salary = $person->getSalary();
		$dto->position = $person->getPosition();
		$dto->birthDate = $person->getBirthDate();
		return $dto;
	}
}