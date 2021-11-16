<?php

namespace App\Controller\Api;

use App\Service\ClubFormProcessor;
use App\Service\ClubManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClubController extends AbstractFOSRestController
{
	/**
	 * @Rest\Get(path="/clubs/{id}", requirements={"id"="\d+"})
	 * @Rest\View(serializerGroups={"club"}, serializerEnableMaxDepthChecks=true)
	 */
	public function getSingleAction(int $id, ClubManager $clubManager)
	{
		$club = $clubManager->find($id);

		if (!$club) {
			return View::create('Club no encontrado', Response::HTTP_BAD_REQUEST);
		}

		return $club;
	}

	/**
	 * @Rest\Get(path="/clubs")
	 * @Rest\View(serializerGroups={"club"}, serializerEnableMaxDepthChecks=true)
	 */
	public function getAllAction(ClubManager $clubManager)
	{
		return $clubManager->findAll();
	}

	/**
	 * @Rest\Post(path="/clubs")
	 * @Rest\View(serializerGroups={"club"}, serializerEnableMaxDepthChecks=true)
	 */
	public function postAction(
		ClubManager $clubManager,
		ClubFormProcessor $clubFormProcessor,
		Request $request)
	{
		$club = $clubManager->create();
		[$club, $error] = ($clubFormProcessor)($club, $request);
		$statusCode = $club ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
		$data = $club ?? $error;

		return View::create($data, $statusCode);
	}

	/**
	 * @Rest\Post(path="/clubs/{id}", requirements={"id"="\d+"})
	 * @Rest\View(serializerGroups={"club"}, serializerEnableMaxDepthChecks=true)
	 */
	public function editAction(
		int $id,
		ClubFormProcessor $clubFormProcessor,
		ClubManager $clubManager,
		Request $request
	)
	{
		$club = $clubManager->find($id);

		if (!$club) {
			return View::create('Club no encontrado', Response::HTTP_BAD_REQUEST);
		}

		[$club, $error] = ($clubFormProcessor)($club, $request);
		$statusCode = $club ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
		$data = $club ?? $error;

		return View::create($data, $statusCode);
	}

	/**
	 * @Rest\Delete(path="/clubs/{id}", requirements={"id"="\d+"})
	 * @Rest\View(serializerGroups={"club"}, serializerEnableMaxDepthChecks=true)
	 */
	public function deleteAction(int $id, ClubManager $clubManager)
	{
		$club = $clubManager->find($id);

		if (!$club) {
			return View::create('Club no encontrado', Response::HTTP_BAD_REQUEST);
		}

		$clubManager->delete($club);

		return View::create(null, Response::HTTP_NO_CONTENT);
	}
}