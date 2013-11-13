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

    use ServiceLocatorAwareTrait,
        MailServiceOptionsTrait;

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
     * @param  array $variables
     * @return Template
     */
    public function createTemplate($template, array $variables = [])
    {
        $template = new Template($this->getRenderer(), $template);
        foreach ($variables as $key => $value) {
            $template->setVariable($key, $value);
        }

        return $template;
    }

    /**
     * @param Template $template
     * @param array $recipients
     *
     * @return Message
     */
    public function createMessageFromTemplate(Template $template, $recipients = null)
    {
        $message = $this->factoryMessage();
        if (null !== $recipients) {
            $message->setTo($recipients);
        }

        $template->render($message);

        return $message;
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
     * @param Template $template
     * @param string|array $recipients
     * @param array $files
     */
    public function sendTemplate(Template $template, $recipients, $files = [])
    {
        $message = $this->createMessageFromTemplate($template, $recipients);

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
        $options = $this->getMailServiceOptions();

        $message = new Message();
        if ($options->getFromMail()) {
            $message->setFrom($options->getFromMail(), $options->getFromName());
        }

        return $message;
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
