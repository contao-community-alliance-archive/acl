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
class ACL extends System
{
	/**
	 * Singleton instance
	 * @var ACL
	 */
	protected static $singleton = null;

	/**
	 * Get singleton instance
	 * @return ACL
	 */
	public static function getInstance()
	{
		if (!self::$singleton)
		{
			if (TL_MODE == 'BE')
			{
				$objUser = BackendUser::getInstance();
				$strTable = 'tl_user';
			}
			else
			{
				$objUser = FrontendUser::getInstance();
				$strTable = 'tl_member';
			}
			$objUser->authenticate();
			self::$singleton = new ACL($strTable, $objUser->id);
		}
		return self::$singleton;
	}

	/**
	 * Get acl for backend user
	 *
	 * @param $id int
	 */
	public static function getByUser($id)
	{
		return new ACL('tl_user', $id);
	}

	/**
	 * Get acl for backend user group
	 *
	 * @param $id int
	 */
	public static function getByUserGroup($id)
	{
		return new ACL('tl_user_group', $id);
	}

	/**
	 * Get acl for frontend member
	 *
	 * @param $id int
	 */
	public static function getByMember($id)
	{
		return new ACL('tl_member', $id);
	}

	/**
	 * Get acl for frontend member group
	 *
	 * @param $id int
	 */
	public static function getByMemberGroup($id)
	{
		return new ACL('tl_member_group', $id);
	}

	/**
	 * @var Database
	 */
	protected $Database;

	/**
	 * @var ACL
	 */
	protected $ACL;

	/**
	 * @var string
	 */
	protected $strObjectTable;

	/**
	 * @var string
	 */
	protected $strTable;

	/**
	 * @var BackendUser|FrontendUser|Database_Result
	 */
	protected $objObject;

	/**
	 * Initialize ACL object.
	 */
	protected function __construct($strObjectTable, $intObjectId)
	{
		$this->import('Database');
		$this->strObjectTable = $strObjectTable;
		$this->strTable  = $strObjectTable . '_acl';

		$objObject = $this->Database
			->prepare("SELECT * FROM $strObjectTable WHERE id=?")
			->execute($intObjectId);
		if ($objObject->next())
		{
			$this->objObject = $objObject;
		}
		else
		{
			throw new Exception("Object ID $intObjectId in table $strObjectTable not found!");
		}
	}

	/**
	 * Check if a user has a specific right, otherwise thorw an exception.
	 *
	 * @param $acl string
	 * @param $right string
	 * @param $recursive bool
	 * @throw Exception
	 * @return bool
	 */
	public function requires($acl, $right, $recursive = true)
	{
		if (!$this->has($acl, $right, $recursive))
		{
			$this->log(sprintf("Object ID %s from %s does not have the right %s :: %s!",
			                   $this->objObject->id, $this->strObjectTable, $acl, $right), 'ACL::requires()', TL_ERROR);
			throw new Exception("Access denied!");
		}
		return true;
	}

	/**
	 * Check if a user has a specific right.
	 *
	 * @param $acl string
	 * @param $right string
	 * @param $recursive bool
	 * @return bool
	 */
	public function has($acl, $right, $recursive = true)
	{
		$forbidden = '!' . strtolower($right);
		$objAcl = $this->Database
			->prepare("SELECT * FROM " . $this->strTable . " WHERE user=? AND acl=? AND (right=? OR right=?)")
			->execute($this->User->id, strtolower($acl), strtolower($right), $forbidden);
		if ($objAcl->next())
		{
			return $objAcl->right != $forbidden;
		}
		else if ($recursive && ($pos = strrpos($acl, ':')) !== false)
		{
			return $this->has(sbstr($acl, 0, $pos), $right);
		}
		return $this->User->isAdmin;
	}

	/**
	 * Grant a right.
	 *
	 * @param $acl string
	 * @param $right string
	 * @return void
	 */
	public function grant($acl, $right)
	{
		$this->import('ACL');
		$this->ACL->require('ACL:' . substr($this->strObjectTable, 3) . 's', 'grant');

		if (!$this->has($acl, $right, false))
		{
			$this->Database
				->prepare("INSERT INTO " . $this->strTable . " (tstamp, user, acl, right) VALUES (?, ?, ?, ?)")
				->execute(time(), $this->User->id, strtolower($acl), strtolower($right));
		}
	}

	/**
	 * Revoke a right.
	 *
	 * @param $acl string
	 * @param $right string
	 * @return void
	 */
	public function revoke($acl, $right)
	{
		$this->import('ACL');
		$this->ACL->require('ACL:' . substr($this->strObjectTable, 3) . 's', 'revoke');

		$this->Database
			->prepare("DELETE FROM " . $this->strTable . " WHERE tstamp=? AND user=? AND acl=? AND right=?")
			->execute(time(), $this->User->id, strtolower($acl), strtolower($right));
	}

	/**
	 * Forbid a right.
	 *
	 * @param $acl string
	 * @param $right string
	 * @return void
	 */
	public function forbid($acl, $right)
	{
		$this->import('ACL');
		$this->ACL->require('ACL:' . substr($this->strObjectTable, 3) . 's', 'forbid');

		if (!$this->has($acl, $right, false))
		{
			$this->Database
				->prepare("INSERT INTO " . $this->strTable . " (tstamp, user, acl, right) VALUES (?, ?, ?, ?)")
				->execute(time(), $this->User->id, strtolower($acl), strtolower($right));
		}
	}
}
