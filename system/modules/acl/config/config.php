<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Access Control List Manager
 * Copyright (C) 2010,2011 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    ACL
 * @license    LGPL
 * @filesource
 */


/**
 * ACL Rules Example
 */
$GLOBALS['TL_ACL']['ACL'] = array('read', 'grant', 'revoke', 'forbid');
$GLOBALS['TL_ACL']['ACL:members'] = array('read', 'grant', 'revoke', 'forbid');
$GLOBALS['TL_ACL']['ACL:member_groups'] = array('read', 'grant', 'revoke', 'forbid');
$GLOBALS['TL_ACL']['ACL:users'] = array('read', 'grant', 'revoke', 'forbid');
$GLOBALS['TL_ACL']['ACL:user_groups'] = array('read', 'grant', 'revoke', 'forbid');

// More Examples
/*
$GLOBALS['TL_ACL']['MyModule'] = array('read', 'write', 'delete');
$GLOBALS['TL_ACL']['MyModule:MyTable'] = array('read', 'write', 'delete');
$GLOBALS['TL_ACL']['MyModule:MyTable:MyField'] = array('read', 'write', 'delete');

$GLOBALS['TL_ACL']['MyModule:MyMail'] = array('send');
$GLOBALS['TL_ACL']['MyModule:MyComponent'] = array('execute');
*/
