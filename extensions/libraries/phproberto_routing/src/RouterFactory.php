<?php
/**
 * @package     Phproberto\Joomla\Routing
 * @subpackage  Library
 *
 * @copyright   Copyright (C) 2018 Roberto Segura López. All rights reserved.
 * @license     GNU General Public License version 2 or later; http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Phproberto\Joomla\Routing;

use Joomla\CMS\Uri\Uri;
use Phproberto\Joomla\Routing\Router;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\RequestContext;
use Phproberto\Joomla\Routing\FolderPriorityQueue;
use Phproberto\Joomla\Routing\MultipleYamlFileLoader;

defined('_JEXEC') || die;

/**
 * Router factory.
 *
 * @since  __DEPLOY_VERSION__
 */
abstract class RouterFactory
{
	/**
	 * Active router.
	 *
	 * @var  Router
	 */
	protected static $router;

	/**
	 * Get the router.
	 *
	 * @return  Router
	 */
	public static function getRouter()
	{
		if (null === static::$router)
		{
			$context = new RequestContext(Uri::root(true));

			$loader = new MultipleYamlFileLoader(
				new FileLocator(
					FolderPriorityQueue::instance()->toArray()
				)
			);

			static::$router = new Router($loader, 'routes.yml', [], $context);
		}

		return static::$router;
	}
}
