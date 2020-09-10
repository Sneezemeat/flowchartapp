<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Diagram;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class DiagramNormalizer.
 */
class DiagramNormalizer implements NormalizerInterface
{
    /**
     * DiagramNormalizer constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param mixed        $object
     * @param array<mixed> $context
     *
     * @return array<mixed>
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        return [
            'id'   => $object->getId(),
            'name' => $object->getName(),
            'data' => $object->getData(),
            'user' => [
                'id'    => $object->getUser()->getId(),
                'email' => $object->getUser()->getEmail(),
                'name'  => $object->getUser()->getName(),
            ],
        ];
    }

    /**
     * Checks whether the given class is supported for normalization by this normalizer.
     *
     * @param mixed  $data   Data to normalize
     * @param string $format The format being (de-)serialized from or into
     *
     * @return bool
     */
    public function supportsNormalization($data, string $format = null)
    {
        return \is_object($data) && Diagram::class === \get_class($data);
    }
}
