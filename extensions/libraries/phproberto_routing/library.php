<?php
/**
 * @package     Phproberto.Phproberto_Routing
 * @subpackage  Library
 *
 * @copyright   Copyright (C) 2018 Roberto Segura López. All rights reserved.
 * @license     GNU General Public License version 2 or later; http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') || die;

use Joomla\CMS\Log\Log;

$composerAutoload = __DIR__ . '/vendor/autoload.php';

if (file_exists($composerAutoload))
{
	require_once $composerAutoload;
}

Log::addLogger(['text_file' => 'routing-debug.php'], Log::DEBUG, ['routing']);
Log::addLogger(['text_file' => 'routing-error.php'], Log::ERROR, ['routing']);

$lang = JFactory::getLanguage();
$lang->load('lib_phproberto_routing', __DIR__);
