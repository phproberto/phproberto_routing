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

use Joomla\CMS\Plugin\CMSPlugin;

/**
 * Base plugin
 *
 * @since  __DEPLOY_VERSION__
 */
abstract class BasePlugin extends CMSPlugin
{
	/**
	 * Application object
	 *
	 * @var  CMSApplication
	 */
	protected $app;

	/**
	 * Load language files automatically.
	 *
	 * @var  boolean
	 */
	protected $autoloadLanguage = true;
}
