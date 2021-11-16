<?php

namespace Managers;

use Services\RequestService;

class PersonManager
{
	private $requestService;

	public function __construct()
	{
		$this->requestService = new RequestService;
	}

	public function list(): array
	{
		return $this->requestService->get(API_URL.'persons');
	}

	public function getById(int $id): array
	{
		$persons = $this->list();

		foreach ($persons as $person) {
			if ($person['id'] == $id) {
				return $person;
			}
		}
	}

	public function listByClub(int $club): array
	{
		return $this->requestService->get(API_URL.'persons/club/'.$club);
	}

	public function create(array $data): array
	{
		$data = $this->mapData($data);
		$data = json_encode($data, JSON_NUMERIC_CHECK);
		return $this->requestService->post(API_URL.'persons', $data);
	}

	public function edit(array $data): array
	{
		$id = $data['id'];
		$data = $this->mapData($data);
		$data = json_encode($data, JSON_NUMERIC_CHECK|JSON_FORCE_OBJECT);
		return $this->requestService->post(API_URL.'persons/'.$id, $data);
	}

	public function mapData($data): array
	{
		return [
			'id' => $data['id'] <> '' ? $data['id'] : null,
			'club' => $data['club'] <> '' ? $data['club'] : null,
			'name' => $data['name'] <> '' ? $data['name'] : null,
			'profile' => $data['profile'] <> '' ? $data['profile'] : null,
			'salary' => $data['salary'] <> '' ? $data['salary'] : null,
			'position' => $data['position'] <> '' ? $data['position'] : null,
			'birthDate' => $data['birthDate'] <> '' ? $data['birthDate'] : null,
		];
	}

	public function fixDate(string $date): ?string
	{
		if ($date == '') {
			return null;
		}

		return substr($date, 0, strpos($date, 'T'));
	}

	public function delete(string $id)
	{
		return $this->requestService->delete(API_URL.'persons/'.$id);
	}
}