<?php
/**
 * Publist
 * 
 * @package    Publist
 * @author     Moritz Lindner
 * @license        GNU/GPL, see LICENSE.php
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// No direct access
defined('_JEXEC' ) or die;
use Joomla\CMS\Helper\ModuleHelper;

// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';

$publist = ModpublistHelper::getpublist($params);
//require(JModuleHelper::getLayoutPath('mod_publist'));
require ModuleHelper::getLayoutPath('mod_publist', $params->get('layout', 'default'));
?>
