<?php

declare(strict_types=1);

namespace App\Service\Normalizer;

use App\Transaction;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TransactionNormalizer implements DenormalizerInterface, NormalizerInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;

    public function denormalize($data, $type, $format = null, array $context = []): Transaction
    {
        return new Transaction(
            $data['bin'],
            $data['amount'],
            $data['currency']
        );
    }

    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        return Transaction::class === $type;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            'json' => true,
            Transaction::class => true,
        ];
    }

    /**
     * @param Transaction $object
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): null|array|\ArrayObject|bool|float|int|string
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        $data['transactionCost'] = $object->transactionCost;
        $data['exchangeRate'] = $object->exchangeRate;
        $data['bin'] = $object->bin;
        $data['amount'] = $object->amount;
        $data['currency'] = $object->currency;

        return $data;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Transaction;
    }
}
