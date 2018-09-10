<?php
/**
 * @package     Phproberto.Phproberto_Routing
 * @subpackage  Plugin.System.Phproberto_Routing
 *
 * @copyright   Copyright (C) 2018 Roberto Segura López. All rights reserved.
 * @license     GNU General Public License version 2 or later; http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die;

JLoader::import('phproberto_routing.library');

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Phproberto\Joomla\Routing\Router;
use Psr\Log\LoggerInterface;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

/**
 * Enable/Disable debug for routes.
 *
 * @since  1.0.0
 */
class PlgPhproberto_RoutingDebug extends CMSPlugin
{
	/**
	 * Status inherited.
	 *
	 * @const
	 */
	const STATUS_INHERITED = 0;

	/**
	 * Status enabled.
	 *
	 * @const
	 */
	const STATUS_ENABLED = 1;

	/**
	 * Status disabled.
	 *
	 * @const
	 */
	const STATUS_DISABLED = 2;

	/**
	 * Triggered before the router is initialised.
	 *
	 * @param   Router           $router   [description]
	 * @param   LoaderInterface  $loader   [description]
	 * @param   array            $options  [description]
	 * @param   RequestContext   $context  [description]
	 * @param   LoggerInterface  $logger   [description]
	 *
	 * @return  void
	 */
	public function onPhprobertoRoutingBeforeLoadRouter(
		Router $router, LoaderInterface &$loader = null, array &$options = [],
		RequestContext &$context = null, LoggerInterface &$logger = null
	)
	{
		$options['debug'] = $this->isEnabled();
	}

	/**
	 * Check if debug is enabled.
	 *
	 * @return  boolean
	 */
	private function isEnabled()
	{
		$status = (int) $this->params->get('enabled', 0);

		if (self::STATUS_INHERITED === $status)
		{
			return (0 !== (int) Factory::getConfig()->get('debug'));
		}

		return $status === self::STATUS_ENABLED;
	}
}
