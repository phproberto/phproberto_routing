<?php
/**
 * @package     Phproberto\Joomla\Routing
 * @subpackage  Library
 *
 * @copyright   Copyright (C) 2018 Roberto Segura López. All rights reserved.
 * @license     GNU General Public License version 2 or later; http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Phproberto\Joomla\Routing\Traits;

defined('_JEXEC') || die;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;

/**
 * Trait for classes with events.
 *
 * @since  __DEPLOY_VERSION__
 */
trait HasEvents
{
	/**
	 * Type of plugins that will imported to trigger events.
	 *
	 * @return  array
	 */
	protected function eventPluginTypes()
	{
		return ['phproberto_routing'];
	}

	/**
	 * Import plugin types for events.
	 *
	 * @return  void
	 */
	protected function importEventPluginTypes()
	{
		foreach ($this->eventPluginTypes() as $pluginType)
		{
			PluginHelper::importPlugin($pluginType);
		}
	}

	/**
	 * Trigger an event.
	 *
	 * @param   string  $event   Event to trigger
	 * @param   array   $params  Optional parameters for the event
	 *
	 * @return  array
	 */
	public function triggerEvent($event, $params = array())
	{
		$this->importEventPluginTypes();

		array_unshift($params, $this);

		return Factory::getApplication()->triggerEvent($event, $params);
	}
}
