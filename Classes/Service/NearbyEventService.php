<?php
namespace TYPO3\CMS\Cal\Service;
/**
 * *************************************************************
 * Copyright notice
 *
 * (c) 2005-2008 Mario Matzulla
 * (c) 2005-2008 Christian Technology Ministries International Inc.
 * All rights reserved
 *
 * This file is part of the Web-Empowered Church (WEC)
 * (http://WebEmpoweredChurch.org) ministry of Christian Technology Ministries
 * International (http://CTMIinc.org). The WEC is developing TYPO3-based
 * (http://typo3.org) free software for churches around the world. Our desire
 * is to use the Internet to help offer new life through Jesus Christ. Please
 * see http://WebEmpoweredChurch.org/Jesus.
 *
 * You can redistribute this file and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software Foundation;
 * either version 2 of the License, or (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This file is distributed in the hope that it will be useful for ministry,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the file!
 * *************************************************************
 */

/**
 * A concrete model for the calendar.
 *
 * @author Mario Matzulla <mario(at)matzullas.de>
 */
class NearbyEventService extends \TYPO3\CMS\Cal\Service\EventService {
	
	public function __construct() {
		parent::__construct();
		
		// Lets see if the user is logged in
		if ($this->rightsObj->isLoggedIn () && ! $this->rightsObj->isCalAdmin () && \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded ('wec_map') && $this->conf ['view.'] ['calendar.'] ['nearbyDistance'] > 0) {
			require_once (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath ('wec_map') . 'class.tx_wecmap_cache.php');
			$user = $GLOBALS ['TSFE']->fe_user->user;
			
			/* Geocode the address */
			$latlong = \tx_wecmap_cache::lookup ($user ['street'], $user ['city'], $user ['state'], $user ['zip'], $user ['country']);
			if (isset ($latlong ['long']) && isset ($latlong ['lat'])) {
				$this->internalAdditionTable = ',' . $this->conf ['view.'] ['calendar.'] ['nearbyAdditionalTable'];
				$this->internalAdditionWhere = ' ' . str_replace (Array (
						'###LONGITUDE###',
						'###LATITUDE###',
						'###DISTANCE###' 
				), Array (
						$latlong ['long'],
						$latlong ['lat'],
						$this->conf ['view.'] ['calendar.'] ['nearbyDistance'] 
				), $this->conf ['view.'] ['calendar.'] ['nearbyAdditionalWhere']);
			} else {
				$this->internalAdditionWhere = ' AND 1=2';
			}
		} else {
			// not logged in -> we can't localize
			$this->internalAdditionWhere = ' AND 1=2';
		}
	}
}

?>