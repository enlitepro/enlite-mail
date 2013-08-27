<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace EnliteMail;

use Zend\Mail\Message;
use Zend\View\Helper\HeadTitle;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\RendererInterface;

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

        $message->setBody($this->renderer->render($viewModel));
        $message->setSubject($helper->renderTitle());

        return $message;
    }

}
