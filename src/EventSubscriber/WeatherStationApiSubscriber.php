<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\ApiLogs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class WeatherStationApiSubscriber implements EventSubscriberInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onGetRequestRecordApiRequest', EventPriorities::POST_READ]
        ];
    }

    public function onGetRequestRecordApiRequest(RequestEvent $event)
    {
        $apiLog = new ApiLogs();
        $apiLog->setMethod($event->getRequest()->getMethod());
        $apiLog->setRequestUri($event->getRequest()->getRequestUri());
        $apiLog->setResponse(null);
        $apiLog->setCreatedAt(new \DateTimeImmutable());
        $apiLog->setUsername('Mepcuk');

        if (!$this->entityManager->isOpen()) {
            $this->entityManager = $this->entityManager->create(
                $this->entityManager->getConnection(),
                $this->entityManager->getConfiguration()
            );
        }

        $this->entityManager->persist($apiLog);
        $this->entityManager->flush();
    }
}
