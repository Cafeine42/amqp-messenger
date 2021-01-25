<?php

namespace Omnilog\AmqpMessengerBundle\Transport;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpSender;
use Symfony\Component\Messenger\Transport\Receiver\MessageCountAwareInterface;
use Symfony\Component\Messenger\Transport\Serialization\PhpSerializer;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\SetupableTransportInterface;

class AmqpTransport implements BlockingTransportInterface, SetupableTransportInterface, MessageCountAwareInterface
{
    private $serializer;
    private $connection;
    private $receiver;
    private $sender;

    public function __construct(Connection $connection, SerializerInterface $serializer = null)
    {
        $this->connection = $connection;
        $this->serializer = $serializer ?? new PhpSerializer();
    }

    /**
     * {@inheritdoc}
     */
    public function get(): iterable
    {
        return ($this->receiver ?? $this->getReceiver())->get();
    }

    /**
     * {@inheritdoc}
     */
    public function pull(callable $callback): void
    {
        ($this->receiver ?? $this->getReceiver())->pull($callback);
    }

    /**
     * {@inheritdoc}
     */
    public function ack(Envelope $envelope): void
    {
        ($this->receiver ?? $this->getReceiver())->ack($envelope);
    }

    /**
     * {@inheritdoc}
     */
    public function reject(Envelope $envelope): void
    {
        ($this->receiver ?? $this->getReceiver())->reject($envelope);
    }

    /**
     * {@inheritdoc}
     */
    public function send(Envelope $envelope): Envelope
    {
        return ($this->sender ?? $this->getSender())->send($envelope);
    }

    /**
     * {@inheritdoc}
     */
    public function setup(): void
    {
        $this->connection->setup();
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageCount(): int
    {
        return ($this->receiver ?? $this->getReceiver())->getMessageCount();
    }

    private function getReceiver(): AmqpReceiver
    {
        return $this->receiver = new AmqpReceiver($this->connection, $this->serializer);
    }

    private function getSender(): AmqpSender
    {
        return $this->sender = new AmqpSender($this->connection, $this->serializer);
    }
}
class_alias(AmqpTransport::class, \Symfony\Component\Messenger\Transport\AmqpExt\AmqpTransport::class);
