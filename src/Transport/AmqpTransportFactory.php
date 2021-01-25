<?php

namespace Omnilog\AmqpMessengerBundle\Transport;

use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class AmqpTransportFactory implements TransportFactoryInterface
{
    public function createTransport(string $dsn, array $options, SerializerInterface $serializer): TransportInterface
    {
        unset($options['transport_name']);

        return new AmqpTransport(Connection::fromDsn($dsn, $options), $serializer);
    }

    public function supports(string $dsn, array $options): bool
    {
        return 0 === strpos($dsn, 'amqp://') || 0 === strpos($dsn, 'amqps://');
    }
}
//class_alias(AmqpTransportFactory::class, \Omnilog\AmqpMessengerBundle\Transport\AmqpTransportFactory::class);
