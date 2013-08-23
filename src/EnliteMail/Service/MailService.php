<?php

namespace EnliteMail\Service;

use Message\Template;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Renderer\RendererInterface;

class MailService implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait,
        MailRepositoryTrait;

    /**
     * @var EntityManager
     */
    protected $entityManager = null;

    /**
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * The transport
     *
     * @var TransportInterface
     */
    protected $transport;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Create template
     *
     * @param  string   $template
     * @return Template
     */
    public function createTemplate($template)
    {
        return new Template($this->getRenderer(), $template);
    }

    /**
     * Send a template by mail
     *
     * @param string|array $recipients
     * @param Template $template
     */
    public function sendTemplate($recipients, Template $template)
    {
        $message = $this->factoryMessage();
        $message->setTo($recipients);
        $template->render($message);

        $this->sendMessage($message);
    }

    /**
     * @return Message
     */
    public function factoryMessage()
    {
        return new Message();
    }

    /**
     * Send any message
     *
     * @param Message $mail
     */
    public function sendMessage(Message $mail)
    {
        $this->getTransport()->send($mail);
    }

    /**
     * @param \Zend\Mail\Transport\TransportInterface $transport
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;
    }

    /**
     * @return \Zend\Mail\Transport\TransportInterface
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * @param \Zend\View\Renderer\RendererInterface $renderer
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @return \Zend\View\Renderer\RendererInterface
     */
    public function getRenderer()
    {
        return $this->renderer;
    }


}
