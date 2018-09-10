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

use Phproberto\Joomla\Routing\Traits\HasEvents;
use Phproberto\Joomla\Routing\FolderPriorityQueueInterface;

/**
 * Folder priority queue to search for routes files.
 *
 * @since  __DEPLOY_VERSION__
 */
final class FolderPriorityQueue extends \SplPriorityQueue implements FolderPriorityQueueInterface
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
	 */
	private function __construct()
	{
		$this->triggerEvent('onPhprobertoRoutingLoadFolderPriorityQueue');
	}

	/**
	 * Get the cached instance
	 *
	 * @return  static
	 */
	public static function instance() : FolderPriorityQueue
	{
		if (null === self::$instance)
		{
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Export queue to array.
	 *
	 * @return  array
	 */
	public function toArray()
	{
		$folders = [];

		foreach (clone $this as $folder)
		{
				$folders[] = $folder;
		}

		return $folders;
	}
}
