<?php

namespace Managers;

use Services\RequestService;

class ClubManager
{
	private $requestService;

	public function __construct()
	{
		$this->requestService = new RequestService;
	}

	public function list(): array
	{
		return $this->requestService->get(API_URL.'clubs');
	}

	public function getById(int $id): array
	{
		return $this->requestService->get(API_URL.'clubs/'.$id);
	}

	public function create(array $data, array $files): array
	{
		$base64Image = $this->encodeBase64Image($files['shield']['tmp_name']);
		$data = [
			'name' => $data['name'],
			'salary_limit' => $data['salary_limit'],
			'base64Image' => $base64Image,
		];
		$data = json_encode($data, JSON_NUMERIC_CHECK|JSON_FORCE_OBJECT);
		return $this->requestService->post(API_URL.'clubs', $data);
	}

	public function edit(array $data, array $files): array
	{
		$base64Image = null;
		if ($files['shield']['tmp_name']) {
			$base64Image = $this->encodeBase64Image($files['shield']['tmp_name']);
		}
		$data = [
			'name' => $data['name'],
			'salary_limit' => $data['salary_limit'],
			'base64Image' => $base64Image,
		];
		$data = json_encode($data, JSON_NUMERIC_CHECK|JSON_FORCE_OBJECT);
		return $this->requestService->post(API_URL.'clubs/'.$data['id'], $data);
	}

	public function encodeBase64Image(string $filename): string
	{
		$type = pathinfo($filename, PATHINFO_EXTENSION);
		$data = file_get_contents($filename);
		return 'data:image/'.$type.';base64,'.base64_encode($data);
	}

	public function delete(string $id)
	{
		return $this->requestService->delete(API_URL.'clubs/'.$id);
	}

	public function getRequestService(): RequestService
	{
		return $this->requestService;
	}
}