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

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Phproberto\Joomla\Routing\Router;
use Phproberto\Joomla\Routing\BasePlugin;
use Symfony\Component\Config\FileLocator;
use Joomla\CMS\Application\CMSApplication;
use Phproberto\Joomla\Routing\RouterFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Phproberto\Joomla\Routing\FolderPriorityQueue;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Phproberto\Joomla\Routing\MultipleYamlFileLoader;
use Phproberto\Joomla\Routing\FolderPriorityQueueInterface;

/**
 * Routing processor.
 *
 * @since  1.0.0
 */
class PlgSystemPhproberto_Routing extends BasePlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  $subject  The object to observe
	 * @param   array   $config   An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 *
	 */
	public function __construct(&$subject, $config = array())
	{
		$this->router = RouterFactory::getRouter();

		parent::__construct($subject, $config);
	}

	/**
	 * Can we inject symfony routing?
	 *
	 * @return  boolean
	 */
	private function canRoute()
	{
		return $this->app->isClient('site') && Factory::getDocument()->getType() === 'html';
	}

	/**
	 * Executed after the application is initialised.
	 *
	 * @return  void
	 */
	public function onAfterInitialise()
	{
		if (!$this->canRoute())
		{
			return;
		}

		$this->route();
		$this->app->getRouter()->attachBuildRule([$this->router, 'buildRoute']);
	}

	/**
	 * Add parse rule to router.
	 *
	 * @return  void
	 */
	public function route()
	{
		try
		{
			$request = Request::createFromGlobals();
			$url = rtrim($request->getPathInfo(), '/');

			$vars = $this->router->match($url);

			if (empty($vars))
			{
				return;
			}

			$redirection = isset($vars['_redirect']) ? $vars['_redirect'] : null;
			$permanent = isset($vars['_permanent']) ? $vars['_permanent'] : true;

			unset($vars['_redirect']);
			unset($vars['_permanent']);
			unset($vars['_route']);

			if ($redirection)
			{
				// Propagate extra query vars
				$vars = array_merge($request->query->all(), $vars);

				$redirectUrl = $this->router->generate($redirection, $vars);

				Factory::getApplication()->redirect($redirectUrl);
			}

			$this->loadInput($vars);
		}
		catch (\Exception $e)
		{
		}
	}

	/**
	 * Load router vars input.
	 *
	 * @param   array   $vars  Variables to set
	 *
	 * @return  void
	 */
	private function loadInput(array $vars)
	{
		foreach ($vars as $key => $value)
		{
			$this->app->input->def($key, $value);
		}
	}
}
