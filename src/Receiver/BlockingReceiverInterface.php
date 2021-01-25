<?php

namespace Omnilog\AmqpMessengerBundle\Receiver;

use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Component\Messenger\Transport\Receiver\ReceiverInterface;

interface BlockingReceiverInterface extends ReceiverInterface
{
    /**
     * @throws TransportException If there is an issue communicating with the transport
     */
    public function pull(callable $callback): void;
}
