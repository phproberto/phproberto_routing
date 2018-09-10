<?php
/**
 * @package     Phproberto.Phproberto_Routing
 * @subpackage  Plugin.System.Phproberto_Routing
 *
 * @copyright   Copyright (C) 2018 Roberto Segura López. All rights reserved.
 * @license     GNU General Public License version 2 or later; http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') || die;

JLoader::import('phproberto_routing.library');

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Phproberto\Joomla\Routing\FolderPriorityQueueInterface;

/**
 * Automatically connect component routes.
 *
 * @since  1.0.0
 */
class PlgPhproberto_RoutingComponents extends CMSPlugin
{
	/**
	 * Triggered before the router is initialised.
	 *
	 * @param   FolderPriorityQueueInterface  $folderPriorityQueue  Folder priority queue.
	 *
	 * @return  void
	 */
	public function onPhprobertoRoutingLoadFolderPriorityQueue(FolderPriorityQueueInterface $folderPriorityQueue)
	{
		foreach (glob(JPATH_SITE . '/components/*') as $file)
		{
			if (!is_dir($file) || 0 === substr_count($file, 'com_') || !is_dir($file . '/routes'))
			{
				continue;
			}

			$folderPriorityQueue->insert(
				$file . '/routes/' . Factory::getLanguage()->getTag(),
				FolderPriorityQueueInterface::PRIORITY_EXTENSION_LANGUAGE_OVERRIDE
			);

			$folderPriorityQueue->insert(
				$file . '/routes',
				FolderPriorityQueueInterface::PRIORITY_EXTENSION
			);
		}
	}
}
