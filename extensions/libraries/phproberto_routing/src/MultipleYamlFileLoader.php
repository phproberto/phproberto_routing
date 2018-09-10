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

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Routing\RouteCollection;
use Phproberto\Joomla\Routing\Traits\HasEvents;
use Symfony\Component\Yaml\Parser as YamlParser;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Exception\ParseException;

/**
 * Router.
 *
 * @since  __DEPLOY_VERSION__
 */
final class MultipleYamlFileLoader extends YamlFileLoader
{
	use HasEvents;

	/**
	 * Available keys accepted.
	 *
	 * @var  array
	 */
	private static $availableKeys = [
		'resource', 'type', 'prefix', 'path', 'host', 'schemes', 'methods', 'defaults', 'requirements', 'options', 'condition',
	];

	/**
	 * Yaml parser instance.
	 *
	 * @var  YamlParser
	 */
	private $yamlParser;

	/**
	 * Loads a Yaml file.
	 *
	 * @param   string       $file  A Yaml file path
	 * @param   string|null  $type  The resource type
	 *
	 * @return RouteCollection A RouteCollection instance
	 *
	 * @throws \InvalidArgumentException When a route can't be parsed because YAML is invalid
	 */
	public function load($file, $type = null)
	{
		$collection = new RouteCollection;

		$this->triggerEvent('onPhprobertoRoutingBeforeLoadRoutes', [&$collection]);

		$paths = array_reverse((array) $this->locator->locate($file, null, false));

		foreach ($paths as $path)
		{
			if (!stream_is_local($path))
			{
				throw new \InvalidArgumentException(sprintf('This is not a local file "%s".', $path));
			}

			if (!file_exists($path))
			{
				throw new \InvalidArgumentException(sprintf('File "%s" not found.', $path));
			}

			if (null === $this->yamlParser)
			{
				$this->yamlParser = new YamlParser;
			}

			try
			{
				$parsedConfig = $this->yamlParser->parse(file_get_contents($path), Yaml::PARSE_KEYS_AS_STRINGS);
			}
			catch (ParseException $e)
			{
				throw new \InvalidArgumentException(sprintf('The file "%s" does not contain valid YAML.', $path), 0, $e);
			}

			$collection->addResource(new FileResource($path));

			// Empty file
			if (null === $parsedConfig)
			{
				continue;
			}

			// Not an array
			if (!is_array($parsedConfig))
			{
				throw new \InvalidArgumentException(sprintf('The file "%s" must contain a YAML array.', $path));
			}

			foreach ($parsedConfig as $name => $config)
			{
				$this->validate($config, $name, $path);

				if (isset($config['resource']))
				{
					$this->parseImport($collection, $config, $path, $file);
				}
				else
				{
					$this->parseRoute($collection, $name, $config, $path);
				}
			}
		}

		$this->triggerEvent('onPhprobertoRoutingAfterLoadRoutes', [&$collection]);

		return $collection;
	}
}
