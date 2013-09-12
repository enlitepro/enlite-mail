<?php

namespace EnliteMail\Service;

use EnliteMail\Template;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;
use Zend\Mime\Mime;
use Zend\Mime\Part;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Renderer\RendererInterface;

class MailService implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

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
     * @param  string $template
     * @return Template
     */
    public function createTemplate($template)
    {
        return new Template($this->getRenderer(), $template);
    }

    /**
     * Inject files to message
     *
     * @param Message $message
     * @param array $files Paths to files
     */
    public function injectFiles(Message $message, $files)
    {
        foreach ($files as $file) {
            $attachment = new Part(fopen($file, 'r'));
            $attachment->type = mime_content_type($file);
            $attachment->encoding = Mime::ENCODING_BASE64;
            $attachment->disposition = Mime::DISPOSITION_ATTACHMENT;
            $attachment->filename = basename($file);
            $attachment->boundary = $message->getBody()->getMime()->boundary();

            $message->getBody()->addPart($attachment);
        }
    }

    /**
     * Send a template by mail
     *
     * @param string|array $recipients
     * @param Template $template
     * @param array $files
     */
    public function sendTemplate($recipients, Template $template, $files = [])
    {
        $message = $this->factoryMessage();
        $message->setTo($recipients);
        $template->render($message);

        if (count($files)) {
            $this->injectFiles($message, $files);
        }

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
