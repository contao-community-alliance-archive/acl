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
 * Class ACL
 *
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    ACL
 */
class ACLBackend extends BackendModule
{
	/**
	 * @var string
	 */
	protected $strTemplate = 'be_acl';

	/**
	 * @var Database
	 */
	protected $Database;

	/**
	 * @var Session
	 */
	protected $Session;

	/**
	 * @var ACL
	 */
	protected $ACL;

	/**
	 * Compile the current element
	 */
	protected function compile()
	{
		$this->import('ACL');
		$this->loadLanguageFile('tl_acl');

		$arrSession = $this->Session->get('TL_ACL');
		if (!$arrSession)
		{
			$arrSession = array
			(
				'object' => 'user',
				'id'     => $this->User->id
			);
		}
		if ($this->Input->post('tl_acl'))
		{
			// get the object type and id
			$arrParts = explode(':', $this->Input->post('who'));
			$arrSession['object'] = $arrParts[0];
			$arrSession['id']     = $arrParts[1];

			// TODO Write new rights
		}

		$this->Session->set('TL_ACL', $arrSession);

		$objMembers = $this->Database->execute("SELECT * FROM tl_member ORDER BY firstname, lastname");
		$this->Template->members = $objMembers;

		$objMemberGroups = $this->Database->execute("SELECT * FROM tl_member_group ORDER BY name");
		$this->Template->member_groups = $objMemberGroups;

		$objUsers = $this->Database->prepare("SELECT * FROM tl_user ORDER BY name")->execute(1);
		$this->Template->users = $objUsers;

		$objUserGroups = $this->Database->execute("SELECT * FROM tl_user_group ORDER BY name");
		$this->Template->user_groups = $objUserGroups;
	}
}
