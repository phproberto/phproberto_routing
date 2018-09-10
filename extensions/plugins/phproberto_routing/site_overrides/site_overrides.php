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
use Symfony\Component\Routing\Route;
use Phproberto\Joomla\Routing\FolderPriorityQueueInterface;

/**
 * Site routes overrides.
 *
 * @since  1.0.0
 */
class PlgPhproberto_RoutingSite_Overrides extends CMSPlugin
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
		$folderPriorityQueue->insert(
			JPATH_SITE . '/routes',
			FolderPriorityQueueInterface::PRIORITY_USER_OVERRIDE
		);

		$folderPriorityQueue->insert(
			JPATH_SITE . '/routes/' . Factory::getLanguage()->getTag(),
			FolderPriorityQueueInterface::PRIORITY_USER_LANGUAGE_OVERRIDE
		);
	}

	public function onPhprobertoRoutingSearchUrl($router, &$url)
	{
		if (substr_count($url, 'phproberto'))
		{
			return [
				'_redirect' => 'com_content.miarticulo',
				'_route' => 'phproberto.home'
			];
		}
	}
}
