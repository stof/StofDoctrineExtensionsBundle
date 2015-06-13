<?php

namespace Stof\DoctrineExtensionsBundle\EventListener;

use Doctrine\ODM\PHPCR\DocumentManager;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\References\ReferencesListener;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * ReferenceListener
 *
 * @author Aram Alipoor <aram.alipoor@gmail.org>
 */
class ReferenceListener implements EventSubscriberInterface
{
    /**
     * @var ReferencesListener
     */
    private $referencesListener;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var DocumentManager
     */
    private $documentManager;

    /**
     * ReferencesListener constructor.
     *
     * @param ReferencesListener $referencesListener
     * @param EntityManagerInterface $entityManager
     * @param DocumentManager $documentManager
     */
    public function __construct(
        ReferencesListener $referencesListener,
        EntityManagerInterface $entityManager,
        DocumentManager $documentManager
    ) {
        $this->referencesListener = $referencesListener;
        $this->entityManager = $entityManager;
        $this->documentManager = $documentManager;
    }

    /**
     * Register object managers on references listener
     */
    public function onInit()
    {
        $this->referencesListener->registerManager('document', $this->documentManager);
        $this->referencesListener->registerManager('entity', $this->entityManager);
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => 'onInit',
            ConsoleEvents::COMMAND => 'onInit'
        );
    }
}
