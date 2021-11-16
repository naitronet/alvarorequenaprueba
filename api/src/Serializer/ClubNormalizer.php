<?php

namespace App\Serializer;

use App\Entity\Club;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ClubNormalizer implements ContextAwareNormalizerInterface
{
	private ObjectNormalizer $normalizer;
	private UrlHelper $urlHelper;

	public function __construct(
		ObjectNormalizer $normalizer,
		UrlHelper $urlHelper
	) {
		$this->normalizer = $normalizer;
		$this->urlHelper = $urlHelper;
	}

	public function normalize($club, $format = null, array $context = [])
	{
		$data = $this->normalizer->normalize($club, $format, $context);

		if (!empty($club->getShield())) {
			$data['shield'] = $this->urlHelper->getAbsoluteUrl('/storage/default/'.$club->getShield());
		}

		return $data;
	}

	public function supportsNormalization($data, $format = null, array $context = [])
	{
		return $data instanceof Club;
	}
}