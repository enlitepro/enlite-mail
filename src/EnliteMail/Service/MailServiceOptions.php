<?php
/**
 * The options for mail service
 *
 * @category   Options
 * @package    EnliteMail
 * @author     Vladimir Struc <Sysaninster@gmail.com>
 * @license    LICENSE.txt
 * @date       23.08.13
 */

namespace EnliteMail\Service;

use Zend\Stdlib\AbstractOptions;

class MailServiceOptions extends AbstractOptions
{

    /**
     * The transport
     *
     * @var string
     */
    protected $transport = 'MailTransport';
    
    /**
     * The renderer
     *
     * @var string
     */
    protected $renderer = 'ViewRenderer';

    /**
     * @var string
     */
    protected $fromMail;

    /**
     * @var string
     */
    protected $fromName;
    
    /**
     * @param string $renderer
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @return string
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param string $transport
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;
    }

    /**
     * @return string
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * @param string $fromMail
     */
    public function setFromMail($fromMail)
    {
        $this->fromMail = $fromMail;
    }

    /**
     * @return string
     */
    public function getFromMail()
    {
        return $this->fromMail;
    }

    /**
     * @param string $fromName
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;
    }

    /**
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }



}