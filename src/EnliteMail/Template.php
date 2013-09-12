<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace EnliteMail;

use Zend\Mail\Message;
use Zend\Mime\Mime;
use Zend\Mime\Part;
use Zend\View\Helper\HeadTitle;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\RendererInterface;
use Zend\Mime\Message as MimeMessage;

class Template
{

    /**
     * @var array
     */
    protected $variables = array();

    /**
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * @var string
     */
    protected $template;

    /**
     * @param RendererInterface $renderer
     * @param string            $template
     */
    public function __construct(RendererInterface $renderer, $template)
    {
        $this->renderer = $renderer;
        $this->template = $template;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function setVariable($key, $value)
    {
        $this->variables[$key] = $value;
    }

    /**
     * @param Message $message
     * @return Message
     */
    public function render(Message $message)
    {
        $viewModel = new ViewModel($this->variables);
        $viewModel->setTemplate($this->template);

        /** @var HeadTitle $helper */
        $helper = $this->renderer->getHelperPluginManager()->get('HeadTitle');

        if (!$message->getBody()) {
            $message->setBody(new MimeMessage());
        }

        $text = new Part($this->renderer->render($viewModel));
        $text->charset = 'UTF-8';
        $text->boundary = $message->getBody()->getMime()->boundary();
        $text->encoding = Mime::ENCODING_BASE64;
        $text->type = Mime::TYPE_HTML;

        $message->getBody()->addPart($text);
        $message->setSubject($helper->renderTitle());

        return $message;
    }

}
