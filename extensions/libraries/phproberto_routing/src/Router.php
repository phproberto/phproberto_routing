<?php
/**
 * @package     Phproberto\Joomla\Routing
 * @subpackage  Library
 *
 * @copyright   Copyright (C) 2018 Roberto Segura López. All rights reserved.
 * @license     GNU General Public License version 2 or later; http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Phproberto\Joomla\Routing;

defined('_JEXEC') || die;

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Psr\Log\LoggerInterface;
use Joomla\CMS\Router\SiteRouter;
use Joomla\CMS\Plugin\PluginHelper;
use Symfony\Component\Config\FileLocator;
use Joomla\CMS\Application\CMSApplication;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Phproberto\Joomla\Routing\Traits\HasEvents;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Phproberto\Joomla\Routing\FolderPriorityQueue;
use Symfony\Component\Routing\Router as BaseRouter;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Phproberto\Joomla\Routing\MultipleYamlFileLoader;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * Router.
 *
 * @since  __DEPLOY_VERSION__
 */
final class Router extends BaseRouter
{
	use HasEvents;

	/**
	 * Renderer instance.
	 *
	 * @var  $this
	 */
	private static $instance;

	/**
	 * Constructor.
	 *
	 * @param   array            $options  An array of options
	 * @param   RequestContext   $context  The context
	 * @param   LoggerInterface  $logger   A logger instance
	 */
	public function __construct(array $options = [], RequestContext $context = null, LoggerInterface $logger = null)
	{
		$context = $context ?: new RequestContext(Uri::root(true));

		$loader = new MultipleYamlFileLoader(
			new FileLocator(
				FolderPriorityQueue::instance()->toArray()
			)
		);

		$this->triggerEvent('onPhprobertoRoutingBeforeLoadRouter', [&$loader, &$options, &$context, &$logger]);

		parent::__construct($loader, 'routes.yml', $options, $context, $logger);

		$this->triggerEvent('onPhprobertoRoutingAfterLoadRouter', [$loader, $options, $context, $logger]);
	}

	/**
	 * Build a SEF URL.
	 *
	 * @param   SiteRouter  $siteRouter  Joomla! site router
	 * @param   Uri         $uri         Active URI
	 *
	 * @return  void
	 */
	public function buildRoute(SiteRouter &$siteRouter, Uri &$uri)
	{
		$this->triggerEvent('onPhprobertoRoutingBuildRoute', [&$uri, &$siteRouter]);
	}

	/**
	 * Gets the UrlGenerator instance associated with this Router.
	 *
	 * @return   UrlGeneratorInterface  A UrlGeneratorInterface instance
	 */
	public function generator()
	{
		return $this->getGenerator();
	}

	/**
	 * Get the cached instance
	 *
	 * @return  static
	 */
	public static function instance() : Router
	{
		if (null === self::$instance)
		{
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Gets the UrlMatcher instance associated with this Router.
	 *
	 * @return   UrlMatcherInterface  A UrlMatcherInterface instance
	 */
	public function matcher()
	{
		return $this->getMatcher();
	}

	/**
	 * [match description]
	 *
	 * @return  [type]  [description]
	 */
	public function match($pathinfo)
	{
		$matches = $this->triggerEvent('onPhprobertoRoutingSearchUrl', [&$pathinfo]);

		if ($matches)
		{
			return $matches[0];
		}

		return parent::match($pathinfo);
	}
}
