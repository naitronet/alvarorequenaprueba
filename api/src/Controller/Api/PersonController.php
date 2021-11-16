<?php

namespace App\Controller\Api;

use App\Service\PersonFormProcessor;
use App\Service\PersonManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PersonController extends AbstractFOSRestController
{
	/**
	 * @Rest\Get(path="/persons")
	 * @Rest\View(serializerGroups={"club"}, serializerEnableMaxDepthChecks=true)
	 */
	public function getAllAction(PersonManager $personManager)
	{
		return $personManager->findAll();
	}

	/**
	 * @Rest\Get(path="/persons/club/{clubId}", requirements={"clubId"="\d+"})
	 * @Rest\View(serializerGroups={"club"}, serializerEnableMaxDepthChecks=true)
	 */
	public function getByClubAction(int $clubId, PersonManager $personManager)
	{
		return $personManager->findByClub($clubId);
	}

	/**
	 * @Rest\Get(path="/persons/{id}", name="getPersonById")
	 * @Rest\View(serializerGroups={"club"}, serializerEnableMaxDepthChecks=true)
	 */
	public function getSingleAction(int $id, PersonManager $personManager)
	{
		$person = $personManager->find($id);

		if (!$person) {
			return View::create('Persona no encontrada', Response::HTTP_BAD_REQUEST);
		}

		return $person;
	}

	/**
	 * @Rest\Post(path="/persons")
	 * @Rest\View(serializerGroups={"club"}, serializerEnableMaxDepthChecks=true)
	 */
	public function postAction(
		PersonFormProcessor $personFormProcessor,
		Request $request)
	{
		[$person, $error] = ($personFormProcessor)($request);
		$statusCode = $person ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
		$data = $person ?? $error;

		return View::create($data, $statusCode);
	}

	/**
	 * @Rest\Post(path="/persons/{id}", requirements={"id"="\d+"}))
	 * @Rest\View(serializerGroups={"club"}, serializerEnableMaxDepthChecks=true)
	 */
	public function editAction(
		int $id,
		PersonFormProcessor $personFormProcessor,
		Request $request)
	{
		try {
			[$person, $error] = ($personFormProcessor)($request, $id);
			$statusCode = $person ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
			$data = $person ?? $error;
			return View::create($data, $statusCode);
		} catch (\Throwable $t) {
			return View::create('Persona no encontrada', Response::HTTP_BAD_REQUEST);
		}
	}

	/**
	 * @Rest\Delete(path="/persons/{id}", requirements={"id"="\d+"})
	 * @Rest\View(serializerGroups={"club"}, serializerEnableMaxDepthChecks=true)
	 */
	public function deleteAction(int $id, PersonManager $personManager)
	{
		$person = $personManager->find($id);

		if (!$person) {
			return View::create('Persona no encontrada', Response::HTTP_BAD_REQUEST);
		}

		$personManager->delete($person);

		return View::create(null, Response::HTTP_NO_CONTENT);
	}
}