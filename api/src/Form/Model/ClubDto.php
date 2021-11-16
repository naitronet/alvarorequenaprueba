<?php

namespace App\Form\Model;

use App\Entity\Club;

class ClubDto
{
	public $name;
	public $base64Image;
	public $salary_limit;
	public $persons;

	public function __construct()
	{
		$this->persons = [];
	}

	public static function createFromClub(Club $club): self
	{
		$dto = new self;
		$dto->name = $club->getName();
		$dto->salary_limit = $club->getSalaryLimit();
		return $dto;
	}
}