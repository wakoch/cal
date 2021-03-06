<?php
namespace TYPO3\CMS\Cal\Model;
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
class TodoModel extends \TYPO3\CMS\Cal\Model\EventModel {
	
	public function __construct($row, $serviceKey) {
		parent::__construct ($row, false, $serviceKey);
		$this->setEventType (\TYPO3\CMS\Cal\Model\Model::EVENT_TYPE_TODO);
		$this->setType ($serviceKey);
		$this->setObjectType ('todo');
	}
	
	public function createEvent(&$row, $isException) {
		parent::createEvent ($row, $isException);
		$this->setStatus ($row ['status']);
		$this->setPriority ($row ['priority']);
		$this->setCompleted ($row ['completed']);
	}
	
	public function renderEvent() {
		return $this->fillTemplate ('###TEMPLATE_TODO###');
	}
	
	public function renderTodo() {
		return $this->renderEvent ();
	}
	
	public function renderTodoFor($viewType) {
		return $this->renderEventFor ($viewType);
	}
	
	public function renderEventFor($viewType) {
		if ($this->conf ['view.'] ['freeAndBusy.'] ['enable'] == 1) {
			$viewType .= '_FNB';
		}
		// Need to check if _ALLDAY is already in viewType since handling changed from classic to new standard rendering
		if (($this->isAllday ()) && (strpos ($viewType, '_ALLDAY') < 1)) {
			$viewType .= '_ALLDAY';
		}
		return $this->fillTemplate ('###TEMPLATE_TODO_' . strtoupper ($viewType) . '###');
	}
	
	public function renderEventPreview() {
		$this->isPreview = true;
		return $this->fillTemplate ('###TEMPLATE_TODO_PREVIEW###');
	}
	
	public function renderTodoPreview() {
		return $this->renderEventPreview ();
	}
	
	public function renderTomorrowsEvent() {
		$this->isTomorrow = true;
		return $this->fillTemplate ('###TEMPLATE_TODO_TOMORROW###');
	}
	
	public function renderTomorrowsTodo() {
		return $this->renderTomorrowsEvent ();
	}
	
	public function fillTemplate($subpartMarker) {
		$cObj = &\TYPO3\CMS\Cal\Utility\Registry::Registry ('basic', 'cobj');
		$confArr = unserialize ($GLOBALS ['TYPO3_CONF_VARS'] ['EXT'] ['extConf'] ['cal']);
		$page = '';
		if ($confArr ['todoSubtype'] == 'event') {
			$resourcePath = $this->conf ['view.'] ['todo.'] ['todoInlineModelTemplate'];
		} else {
			$resourcePath = $this->conf ['view.'] ['todo.'] ['todoSeparateModelTemplate'];
		}
		$page = $cObj->fileResource ($resourcePath);
		if ($page == '') {
			return '<h3>calendar: no todo model template file found:</h3>' . $resourcePath;
		}
		$page = $cObj->getSubpart ($page, $subpartMarker);
		if (! $page) {
			return 'could not find the >' . str_replace ('###', '', $subpartMarker) . '< subpart-marker in ' . $resourcePath;
		}
		$rems = array ();
		$sims = array ();
		$wrapped = array ();
		$this->getMarker ($page, $sims, $rems, $wrapped, $this->conf ['view']);
		return $this->finish (\TYPO3\CMS\Cal\Utility\Functions::substituteMarkerArrayNotCached ($page, $sims, $rems, $wrapped));
	}
	
	public function getStatusMarker(& $template, & $sims, & $rems, & $wrapped, $view) {
		$sims ['###STATUS###'] = '';
		if ($this->getEventType () == \TYPO3\CMS\Cal\Model\Model::EVENT_TYPE_TODO) {
			$this->initLocalCObject ($this->getValuesAsArray ());
			$this->local_cObj->setCurrentVal ($this->getStatus ());
			$sims ['###STATUS###'] = $this->local_cObj->cObjGetSingle ($this->conf ['view.'] [$view . '.'] ['todo.'] ['status'], $this->conf ['view.'] [$view . '.'] ['todo.'] ['status.']);
		}
	}
	
	public function getPriorityMarker(& $template, & $sims, & $rems, & $wrapped, $view) {
		$sims ['###PRIORITY###'] = '';
		if ($this->getEventType () == \TYPO3\CMS\Cal\Model\Model::EVENT_TYPE_TODO) {
			$this->initLocalCObject ($this->getValuesAsArray ());
			$this->local_cObj->setCurrentVal ($this->getPriority ());
			$sims ['###PRIORITY###'] = $this->local_cObj->cObjGetSingle ($this->conf ['view.'] [$view . '.'] ['todo.'] ['priority'], $this->conf ['view.'] [$view . '.'] ['todo.'] ['priority.']);
		}
	}
	
	public function getCompletedMarker(& $template, & $sims, & $rems, & $wrapped, $view) {
		$sims ['###COMPLETED###'] = '';
		if ($this->getEventType () == \TYPO3\CMS\Cal\Model\Model::EVENT_TYPE_TODO) {
			$this->initLocalCObject ($this->getValuesAsArray ());
			$this->local_cObj->setCurrentVal ($this->getCompleted ());
			$sims ['###COMPLETED###'] = $this->local_cObj->cObjGetSingle ($this->conf ['view.'] [$view . '.'] ['todo.'] ['completed'], $this->conf ['view.'] [$view . '.'] ['todo.'] ['completed.']);
		}
	}
	
	public function updateWithPIVars(&$piVars) {
		$this->setStatus ($piVars ['status']);
		$this->setPriority ($piVars ['priority']);
		$this->setCompleted ($piVars ['completed']);
		parent::updateWithPIVars ($piVars);
	}
}

?>