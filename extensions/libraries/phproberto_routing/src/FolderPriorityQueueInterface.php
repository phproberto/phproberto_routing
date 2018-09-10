<?php
/**
 * @package     Phproberto\Joomla\Routing
 * @subpackage  Library
 *
 * @copyright   Copyright (C) 2018 Roberto Segura López. All rights reserved.
 * @license     GNU General Public License version 2 or later; http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Phproberto\Joomla\Routing;

/**
 * Defines methods required by FolderPriorityQueue.
 *
 * @since  __DEPLOY_VERSION__
 */
interface FolderPriorityQueueInterface
{
	/**
	 * User language overrides.
	 *
	 * @const
	 */
	const PRIORITY_USER_LANGUAGE_OVERRIDE = 3000;

	/**
	 * User overrides.
	 *
	 * @const
	 */
	const PRIORITY_USER_OVERRIDE = 2000;

	/**
	 * Language overrides created by 3rd part extensions.
	 *
	 * @const
	 */
	const PRIORITY_LANGUAGE_OVERRIDE = 3000;

	/**
	 * Overrides created by 3rd part extensions.
	 *
	 * @const
	 */
	const PRIORITY_OVERRIDE = 2000;

	/**
	 * Language overrides provided by extensions.
	 *
	 * @const
	 */
	const PRIORITY_EXTENSION_LANGUAGE_OVERRIDE = 1000;

	/**
	 * Main routes provided by extensions.
	 *
	 * @const
	 */
	const PRIORITY_EXTENSION = 1;

	/**
	 * Insert an item in the queue.
	 *
	 * @param   mixed  $value     Value to insert.
	 * @param   mixed  $priority  Priority to assign.
	 *
	 * @return  void
	 */
	public function insert($value, $priority);
}
