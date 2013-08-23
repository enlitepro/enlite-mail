<?php
/**
 * The factory for options mail service
 *
 * @category   Factory
 * @package    EnliteMail
 * @author     Vladimir Struc <Sysaninster@gmail.com>
 * @license    LICENSE.txt
 * @date       23.08.13
 */

namespace EnliteMail\Service;

class MailServiceOptionsFactory implements FactoryInterface
{

    /**
     * Create options
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        return new MailServiceOptions(
            isset($config['enlite_mail'])
                ? $config['enlite_mail']
                : []
        );
    }

}