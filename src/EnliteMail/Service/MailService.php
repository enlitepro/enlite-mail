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
use Zend\Mime\Message as MimeMessage;

class MailService implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

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
     * Inject files to message
     *
     * @param Message $message
     * @param array $files Paths to files
     * @param string $typeBody
     */
    public function injectFiles(Message $message, $files, $typeBody = Mime::TYPE_TEXT) {
        $content = new MimeMessage();
        $body = new MimeMessage();

        $htmlPart = new Part($message->getBody());
        $htmlPart->type = $typeBody;
        $content->addPart($htmlPart);

        $contentPart = new Part($content->generateMessage());
        $contentPart->type = 'multipart/alternative;' . PHP_EOL . ' boundary="' . $content->getMime()->boundary() . '"';
        $body->addPart($contentPart);

        if (count($files)) {
            foreach ($files as $file) {
                $attachment = new Part(fopen($file, 'r'));
                $attachment->type = mime_content_type($file);
                $attachment->encoding = Mime::ENCODING_BASE64;
                $attachment->disposition = Mime::DISPOSITION_ATTACHMENT;

                $body->addPart($attachment);
            }
        }

        $message->setBody($body);

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
            $this->injectFiles($message, $files, Mime::TYPE_HTML);
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
