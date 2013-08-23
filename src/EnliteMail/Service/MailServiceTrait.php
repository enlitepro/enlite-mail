<?php

namespace EnliteMail\Service;

use EnliteMail\Service\MailService;
use EnliteMail\Exception\RuntimeException;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

trait MailServiceTrait
{

    /**
     * @var MailService
     */
    protected $mailService = null;

    /**
     * @param MailService $mailService
     */
    public function setMailService(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    /**
     * @return MailService
     * @throws RuntimeException
     */
    public function getMailService()
    {
        if (null === $this->mailService) {
            if ($this instanceof ServiceLocatorAwareInterface || method_exists($this, 'getServiceLocator')) {
                $this->mailService = $this->getServiceLocator()->get('EnliteMailService');
            } else {
                if (property_exists($this, 'serviceLocator')
                    && $this->serviceLocator instanceof ServiceLocatorInterface
                ) {
                    $this->mailService = $this->serviceLocator->get('EnliteMailService');
                } else {
                    throw new RuntimeException('Service locator not found');
                }
            }
        }
        return $this->mailService;
    }


}
