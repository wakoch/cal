<?php
if (! defined ('TYPO3_MODE'))
	die ('Access denied.');

$extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath ($_EXTKEY);
$extRelPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath ($_EXTKEY);

	// Include the class for getting custom labels.
// if (TYPO3_MODE == 'BE') {
// 	include_once ($extRelPath . 'Classes/Backend/TCA/Labels.php');
// 	include_once ($extRelPath . 'Classes/Backend/TCA/ItemsProcFunc.php');
// }

// Allow all calendar records on standard pages, in addition to SysFolders.
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages ('tx_cal_event');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages ('tx_cal_category');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages ('tx_cal_calendar');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages ('tx_cal_exception_event');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages ('tx_cal_exception_event_group');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages ('tx_cal_location');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages ('tx_cal_organizer');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages ('tx_cal_unknown_users');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages ('tx_cal_attendee');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages ('tx_cal_fe_user_event_monitor_mm');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages ('tx_cal_event_deviation');

$confArr = unserialize ($GLOBALS ['TYPO3_CONF_VARS'] ['EXT'] ['extConf'] ['cal']);

// TCA Definitions.
$TCA ['tx_cal_event'] = Array (
		'ctrl' => Array (
				'requestUpdate' => 'calendar_id,freq,rdate_type,allday',
				'title' => 'LLL:EXT:cal/Resources/Private/Language/locallang_db.xml:tx_cal_event',
				'label' => 'title',
				'tstamp' => 'tstamp',
				'crdate' => 'crdate',
				'cruser_id' => 'cruser_id',
				'default_sortby' => 'ORDER BY start_date DESC, start_time DESC',
				'delete' => 'deleted',
				'versioningWS' => TRUE,
				'origUid' => 't3_origuid',
				'shadowColumnsForNewPlaceholders' => 'sys_language_uid,l18n_parent',
				'transOrigPointerField' => 'l18n_parent',
				'transOrigDiffSourceField' => 'l18n_diffsource',
				'languageField' => 'sys_language_uid',
				'type' => 'type',
				'typeicon_column' => 'type',
				'typeicons' => Array (
						'1' => $extRelPath . 'Resources/Public/icons/icon_tx_cal_events_intlnk.gif',
						'2' => $extRelPath . 'Resources/Public/icons/icon_tx_cal_events_exturl.gif',
						'3' => $extRelPath . 'Resources/Public/icons/icon_tx_cal_events_meeting.gif',
						'4' => $extRelPath . 'Resources/Public/icons/icon_tx_cal_events_todo.gif' 
				),
				'dividers2tabs' => $confArr ['noTabDividers'] ? FALSE : TRUE,
				'enablecolumns' => Array (
						'disabled' => 'hidden',
						'starttime' => 'starttime',
						'endtime' => 'endtime' 
				),
				'dynamicConfigFile' => $extPath . 'tca.php',
				'iconfile' => $extRelPath . 'Resources/Public/icons/icon_tx_cal_events.gif',
				'searchFields' => 'title,organizer,organizer_link,location,location_link,teaser,description,ext_url,image,imagecaption,imagealttext,imagetitletext,attachment,attachmentcaption' 
		),
		'feInterface' => Array (
				'fe_admin_fieldList' => 'hidden, title, starttime, endtime, start_date, start_time, end_date, end_time, relation_cnt, organizer, organizer_id, organizer_pid, location, location_id, location_pid, description, freq, byday, bymonthday, bymonth, until, count, interval, rdate_type, rdate, notify_cnt' 
		) 
);

$TCA ['tx_cal_category'] = Array (
		'ctrl' => Array (
				'requestUpdate' => 'calendar_id',
				'title' => 'LLL:EXT:cal/Resources/Private/Language/locallang_db.xml:tx_cal_category',
				'label' => 'title',
				'tstamp' => 'tstamp',
				'crdate' => 'crdate',
				'cruser_id' => 'cruser_id',
				'default_sortby' => 'ORDER BY title',
				'delete' => 'deleted',
				'enablecolumns' => Array (
						'disabled' => 'hidden',
						'starttime' => 'starttime',
						'endtime' => 'endtime' 
				),
				'versioningWS' => TRUE,
				'origUid' => 't3_origuid',
				'shadowColumnsForNewPlaceholders' => 'sys_language_uid,l18n_parent',
				'transOrigPointerField' => 'l18n_parent',
				'transOrigDiffSourceField' => 'l18n_diffsource',
				'languageField' => 'sys_language_uid',
				'dynamicConfigFile' => $extPath . 'tca.php',
				'iconfile' => $extRelPath . 'Resources/Public/icons/icon_tx_cal_category.gif',
				// 'treeParentField' => 'calendar_id',
				'searchFields' => 'title,notification_emails' 
		),
		'feInterface' => Array (
				'fe_admin_fieldList' => 'hidden, title, starttime, endtime' 
		) 
);

$TCA ['tx_cal_calendar'] = Array (
		'ctrl' => Array (
				'requestUpdate' => 'activate_fnb',
				'title' => 'LLL:EXT:cal/Resources/Private/Language/locallang_db.xml:tx_cal_calendar',
				'label' => 'title',
				'tstamp' => 'tstamp',
				'crdate' => 'crdate',
				'cruser_id' => 'cruser_id',
				'default_sortby' => 'ORDER BY title',
				'delete' => 'deleted',
				'enablecolumns' => Array (
						'disabled' => 'hidden',
						'starttime' => 'starttime',
						'endtime' => 'endtime' 
				),
				'type' => 'type',
				'typeicon_column' => 'type',
				'typeicons' => Array (
						'1' => $extRelPath . 'Resources/Public/icons/icon_tx_cal_calendar_exturl.gif',
						'2' => $extRelPath . 'Resources/Public/icons/icon_tx_cal_calendar_ics.gif' 
				),
				'versioningWS' => TRUE,
				'origUid' => 't3_origuid',
				'shadowColumnsForNewPlaceholders' => 'sys_language_uid,l18n_parent',
				'transOrigPointerField' => 'l18n_parent',
				'transOrigDiffSourceField' => 'l18n_diffsource',
				'languageField' => 'sys_language_uid',
				'dynamicConfigFile' => $extPath . 'tca.php',
				'iconfile' => $extRelPath . 'Resources/Public/icons/icon_tx_cal_calendar.gif',
				'searchFields' => 'title,ext_url,ext_url_notes,ics_file' 
		),
		'feInterface' => Array (
				'fe_admin_fieldList' => 'hidden, title, starttime, endtime' 
		) 
);

$TCA ['tx_cal_exception_event'] = Array (
		'ctrl' => Array (
				'requestUpdate' => 'calendar_id,freq,rdate_type,allday',
				'title' => 'LLL:EXT:cal/Resources/Private/Language/locallang_db.xml:tx_cal_exception_event',
				'label' => 'title',
				'tstamp' => 'tstamp',
				'crdate' => 'crdate',
				'cruser_id' => 'cruser_id',
				'default_sortby' => 'ORDER BY start_date DESC',
				'delete' => 'deleted',
				'enablecolumns' => Array (
						'disabled' => 'hidden',
						'starttime' => 'starttime',
						'endtime' => 'endtime' 
				),
				'versioningWS' => TRUE,
				'dynamicConfigFile' => $extPath . 'tca.php',
				'iconfile' => $extRelPath . 'Resources/Public/icons/icon_tx_cal_exception_event.gif',
				'searchFields' => 'title' 
		),
		'feInterface' => Array (
				'fe_admin_fieldList' => 'hidden, title, starttime, endtime, start_date, end_date, relation_cnt, freq, byday, bymonthday, bymonth, until, count, interval' 
		) 
);

$TCA ['tx_cal_exception_event_group'] = Array (
		'ctrl' => Array (
				'title' => 'LLL:EXT:cal/Resources/Private/Language/locallang_db.xml:tx_cal_exception_event_group',
				'label' => 'title',
				'tstamp' => 'tstamp',
				'crdate' => 'crdate',
				'cruser_id' => 'cruser_id',
				'default_sortby' => 'ORDER BY title',
				'delete' => 'deleted',
				
				'dynamicConfigFile' => $extPath . 'tca.php',
				'iconfile' => $extRelPath . 'Resources/Public/icons/icon_tx_cal_exception_event_group.gif',
				'enablecolumns' => Array (
						'disabled' => 'hidden' 
				),
				'versioningWS' => TRUE,
				'searchFields' => 'title' 
		),
		'feInterface' => Array (
				'fe_admin_fieldList' => 'title' 
		) 
);

$TCA ['tx_cal_location'] = Array (
		'ctrl' => Array (
				'title' => 'LLL:EXT:cal/Resources/Private/Language/locallang_db.xml:tx_cal_location',
				'label' => 'name',
				'tstamp' => 'tstamp',
				'crdate' => 'crdate',
				'cruser_id' => 'cruser_id',
				'default_sortby' => 'ORDER BY name',
				'delete' => 'deleted',
				
				'dynamicConfigFile' => $extPath . 'tca.php',
				'iconfile' => $extRelPath . 'Resources/Public/icons/icon_tx_cal_location.gif',
				'enablecolumns' => Array (
						'disabled' => 'hidden' 
				),
				'versioningWS' => TRUE,
				'origUid' => 't3_origuid',
				'shadowColumnsForNewPlaceholders' => 'sys_language_uid,l18n_parent',
				'transOrigPointerField' => 'l18n_parent',
				'transOrigDiffSourceField' => 'l18n_diffsource',
				'languageField' => 'sys_language_uid',
				'searchFields' => 'name,description,street,zip,city,country_zone,country,phone,fax,email,image,imagecaption,imagealttext,imagetitletext,link,latitute,longitute' 
		),
		'feInterface' => Array (
				'fe_admin_fieldList' => 'name' 
		) 
);

$TCA ['tx_cal_organizer'] = Array (
		'ctrl' => Array (
				'title' => 'LLL:EXT:cal/Resources/Private/Language/locallang_db.xml:tx_cal_organizer',
				'label' => 'name',
				'tstamp' => 'tstamp',
				'crdate' => 'crdate',
				'cruser_id' => 'cruser_id',
				'default_sortby' => 'ORDER BY name',
				'delete' => 'deleted',
				
				'dynamicConfigFile' => $extPath . 'tca.php',
				'iconfile' => $extRelPath . 'Resources/Public/icons/icon_tx_cal_organizer.gif',
				'enablecolumns' => Array (
						'disabled' => 'hidden' 
				),
				'versioningWS' => TRUE,
				'origUid' => 't3_origuid',
				'shadowColumnsForNewPlaceholders' => 'sys_language_uid,l18n_parent',
				'transOrigPointerField' => 'l18n_parent',
				'transOrigDiffSourceField' => 'l18n_diffsource',
				'languageField' => 'sys_language_uid',
				'searchFields' => 'name,description,street,zip,city,country_zone,country,phone,fax,email,image,imagecaption,imagealttext,imagetitletext,link' 
		),
		'feInterface' => Array (
				'fe_admin_fieldList' => 'name' 
		) 
);

$TCA ['tx_cal_unknown_users'] = Array (
		'ctrl' => Array (
				'title' => 'LLL:EXT:cal/Resources/Private/Language/locallang_db.xml:tx_cal_unknown_users',
				'label' => 'email',
				'tstamp' => 'tstamp',
				'crdate' => 'crdate',
				'cruser_id' => 'cruser_id',
				'default_sortby' => 'ORDER BY email',
				'delete' => 'deleted',
				'enablecolumns' => Array (),
				'versioningWS' => TRUE,
				'dynamicConfigFile' => $extPath . 'tca.php',
				'iconfile' => $extRelPath . 'Resources/Public/icons/icon_tx_cal_unknown_users.gif',
				'searchFields' => 'email' 
		),
		'feInterface' => Array (
				'fe_admin_fieldList' => 'hidden, email' 
		) 
);

$TCA ['tx_cal_attendee'] = Array (
		'ctrl' => Array (
				'title' => 'LLL:EXT:cal/Resources/Private/Language/locallang_db.xml:tx_cal_attendee',
				'label' => 'uid',
				'tstamp' => 'tstamp',
				'crdate' => 'crdate',
				'cruser_id' => 'cruser_id',
				'default_sortby' => 'uid',
				'delete' => 'deleted',
				
				'dynamicConfigFile' => $extPath . 'tca.php',
				'iconfile' => $extRelPath . 'Resources/Public/icons/icon_tx_cal_attendee.gif',
				'enablecolumns' => Array (
						'disabled' => 'hidden' 
				),
				'versioningWS' => TRUE,
				'searchFields' => 'email' 
		) 
);

$TCA ['tx_cal_fe_user_event_monitor_mm'] = Array (
		'ctrl' => Array (
				'requestUpdate' => '',
				'title' => 'LLL:EXT:cal/Resources/Private/Language/locallang_db.xml:tx_cal_fe_user_event.monitor',
				'label' => 'tablenames',
				'label_alt' => 'tablenames,offset',
				'label_alt_force' => 1,
				'dynamicConfigFile' => $extPath . 'tca.php',
				'iconfile' => $extRelPath . 'Resources/Public/icons/icon_tx_cal_fe_user_event_monitor_mm.gif' 
		),
		'feInterface' => Array (
				'fe_admin_fieldList' => '' 
		) 
);

$TCA ['tx_cal_event_deviation'] = Array (
		'ctrl' => Array (
				'title' => 'LLL:EXT:cal/Resources/Private/Language/locallang_db.xml:tx_cal_event.deviation',
				'label' => 'title',
				'tstamp' => 'tstamp',
				'crdate' => 'crdate',
				'cruser_id' => 'cruser_id',
				'default_sortby' => 'start_date',
				'delete' => 'deleted',
				
				'dynamicConfigFile' => $extPath . 'tca.php',
				'iconfile' => $extRelPath . 'Resources/Public/icons/icon_tx_cal_event_deviation.gif',
				'enablecolumns' => Array (
						'disabled' => 'hidden' 
				),
				'versioningWS' => TRUE,
				'hideTable' => $confArr ['hideDeviationRecords'],
				'searchFields' => 'title,organizer,organizer_link,location,location_link,teaser,description,image,imagecaption,imagealttext,imagetitletext,attachment,attachmentcaption' 
		) 
);

// enable label_userFunc only for TYPO3 v 4.1 and higher
if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger (TYPO3_version) >= 4001000) {
	$TCA ['tx_cal_attendee'] ['ctrl'] ['label_userFunc'] = "TYPO3\\CMS\\Cal\\Backend\\TCA\\Labels->getAttendeeRecordLabel";
	$TCA ['tx_cal_fe_user_event_monitor_mm'] ['ctrl'] ['label_userFunc'] = "TYPO3\\CMS\\Cal\\Backend\\TCA\\Labels->getMonitoringRecordLabel";
	$TCA ['tx_cal_event_deviation'] ['ctrl'] ['label_userFunc'] = "TYPO3\\CMS\\Cal\\Backend\\TCA\\Labels->getDeviationRecordLabel";
	$TCA ['tx_cal_event'] ['ctrl'] ['label_userFunc'] = "TYPO3\\CMS\\Cal\\Backend\\TCA\\Labels->getEventRecordLabel";
}

// Get the location and organizer structures.
$useLocationStructure = ($confArr ['useLocationStructure'] ? $confArr ['useLocationStructure'] : 'tx_cal_location');
$useOrganizerStructure = ($confArr ['useOrganizerStructure'] ? $confArr ['useOrganizerStructure'] : 'tx_cal_organizer');

if ($useLocationStructure == 'tx_tt_address') {
	$tempColumns = Array (
			'tx_cal_controller_islocation' => Array (
					'exclude' => 1,
					'label' => 'LLL:EXT:cal/Resources/Private/Language/locallang_db.xml:tx_cal_location.islocation',
					'config' => Array (
							'type' => 'check',
							'default' => 1 
					) 
			) 
	);
	
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns ('tt_address', $tempColumns);
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes ('tt_address', 'tx_cal_controller_islocation,');
}

if ($useOrganizerStructure == 'tx_tt_address') {
	$tempColumns = Array (
			'tx_cal_controller_isorganizer' => Array (
					'exclude' => 1,
					'label' => 'LLL:EXT:cal/Resources/Private/Language/locallang_db.xml:tx_cal_organizer.isorganizer',
					'config' => Array (
							'type' => 'check',
							'default' => 0 
					) 
			) 
	);
	
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns ('tt_address', $tempColumns);
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes ('tt_address', 'tx_cal_controller_isorganizer,');
}

// Define the TCA for a checkbox to enable access control.
$tempColumns = Array (
		'tx_cal_enable_accesscontroll' => Array (
				'exclude' => 1,
				'label' => 'LLL:EXT:cal/Resources/Private/Language/locallang_db.xml:tx_cal_enable_accesscontroll',
				'config' => Array (
						'type' => 'check',
						'default' => 0 
				) 
		) 
);

// Add the checkbox for backend users.
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns ('be_users', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes ('be_users', 'tx_cal_enable_accesscontroll;;;;1-1-1', '0');
$TCA ['be_users'] ['ctrl'] ['requestUpdate'] = $TCA ['be_users'] ['ctrl'] ['requestUpdate'] . ',tx_cal_enable_accesscontroll';

// Add the checkbox for backend groups.
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns ('be_groups', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes ('be_groups', 'tx_cal_enable_accesscontroll;;;;1-1-1');
$TCA ['be_groups'] ['ctrl'] ['requestUpdate'] = $TCA ['be_groups'] ['ctrl'] ['requestUpdate'] . ',tx_cal_enable_accesscontroll';

// Define the TCA for the access control calendar selector.
$tempColumns = Array (
		'tx_cal_calendar' => Array (
				'exclude' => 1,
				'label' => 'LLL:EXT:cal/Resources/Private/Language/locallang_db.xml:tx_cal_calendar_accesscontroll',
				'displayCond' => 'FIELD:tx_cal_enable_accesscontroll:REQ:true',
				'config' => Array (
						'type' => 'select',
						'size' => 10,
						'minitems' => 0,
						'maxitems' => 100,
						'autoSizeMax' => 20,
						'itemListStyle' => 'height:130px;',
						'foreign_table' => 'tx_cal_calendar' 
				) 
		) 
);

// Add the calendar selector for backend users.
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns ('be_users', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes ('be_users', 'tx_cal_calendar;;;;1-1-1', '0');

// Add the calendar selector for backend groups.
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns ('be_groups', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes ('be_groups', 'tx_cal_calendar;;;;1-1-1');


// Define the TCA for the access control category selector.
$tempColumns = Array (
		'tx_cal_category' => Array (
				'exclude' => 1,
				'label' => 'LLL:EXT:cal/Resources/Private/Language/locallang_db.xml:tx_cal_category_accesscontroll',
				'displayCond' => 'FIELD:tx_cal_enable_accesscontroll:REQ:true',
				'config' => Array (
						'type' => 'select',
						'form_type' => 'user',
						'userFunc' => 'TYPO3\CMS\Cal\TreeProvider\TreeView->displayCategoryTree',
						'treeView' => 1,
						'size' => 20,
						'minitems' => 0,
						'maxitems' => 100,
						'autoSizeMax' => 20,
						'itemListStyle' => 'height:270px;',
						'foreign_table' => 'tx_cal_category' 
				) 
		) 
);

// Add the category selecor for backend users.
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns ('be_users', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes ('be_users', 'tx_cal_category;;;;1-1-1', '0');

// Add the category selector for backeng groups.
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns ('be_groups', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes ('be_groups', 'tx_cal_category;;;;1-1-1');

// Define the TCA for the access control calendar selector.
$tempColumns = Array (
		'tx_cal_calendar' => Array (
				'exclude' => 1,
				'label' => 'LLL:EXT:cal/Resources/Private/Language/locallang_db.xml:tx_cal_calendar_private',
				'config' => Array (
						'type' => 'group',
						'internal_type' => 'db',
						'allowed' => 'tx_cal_calendar',
						'minitems' => 0,
						'maxitems' => 99,
						'wizards' => Array (
								'_PADDING' => 2,
								'_VERTICAL' => 1,
								'add' => Array (
										'type' => 'script',
										'title' => 'LLL:EXT:cal/Resources/Private/Language/locallang_db.xml:tx_cal_calendar.createNew',
										'icon' => 'EXT:cal/Resources/Public/icons/icon_tx_cal_calendar.gif',
										'params' => Array (
												'table' => 'tx_cal_calendar',
												'pid' => $sPid,
												'setValue' => 'set' 
										),
										'script' => 'wizard_add.php' 
								) 
						) 
				) 
		),
		'tx_cal_calendar_subscription' => Array (
				'exclude' => 1,
				'label' => 'LLL:EXT:cal/Resources/Private/Language/locallang_db.xml:tx_cal_calendar_subscription',
				'config' => Array (
						'type' => 'group',
						'internal_type' => 'db',
						'allowed' => 'tx_cal_calendar',
						'minitems' => 0,
						'maxitems' => 99,
						'wizards' => Array (
								'_PADDING' => 2,
								'_VERTICAL' => 1,
								'add' => Array (
										'type' => 'script',
										'title' => 'LLL:EXT:cal/Resources/Private/Language/locallang_db.xml:tx_cal_calendar.createNew',
										'icon' => 'EXT:cal/Resources/Public/icons/icon_tx_cal_calendar.gif',
										'params' => Array (
												'table' => 'tx_cal_calendar',
												'pid' => $sPid,
												'setValue' => 'set' 
										),
										'script' => 'wizard_add.php' 
								) 
						) 
				) 
		) 
);

// Add the calendar selector for backend users.
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns ('fe_users', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes ('fe_users', 'tx_cal_calendar,tx_cal_calendar_subscription;;;;1-1-1');

// Add Calendar Events to the "Insert Records" content element
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords ('tx_cal_event');

// initalize 'context sensitive help' (csh)
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr ('tx_cal_event', 'EXT:cal/Resources/Private/Help/locallang_csh_txcalevent.php');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr ('tx_cal_calendar', 'EXT:cal/Resources/Private/Help/locallang_csh_txcalcal.php');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr ('tx_cal_category', 'EXT:cal/Resources/Private/Help/locallang_csh_txcalcat.php');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr ('tx_cal_exception_event', 'EXT:cal/Resources/Private/Help/locallang_csh_txcalexceptionevent.php');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr ('tx_cal_exception_event_group', 'EXT:cal/Resources/Private/Help/locallang_csh_txcalexceptioneventgroup.php');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr ('tx_cal_location', 'EXT:cal/Resources/Private/Help/locallang_csh_txcallocation.php');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr ('tx_cal_organizer', 'EXT:cal/Resources/Private/Help/locallang_csh_txcalorganizer.php');

if (TYPO3_MODE == "BE") {
	$extConf = unserialize ($GLOBALS ['TYPO3_CONF_VARS'] ['EXT'] ['extConf'] ['cal']);
	if ($extConf ['useNewRecurringModel']) {
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule ("tools", "calrecurrencegenerator", "", $extPath . "Classes/Backend/Modul/");
	}
	$GLOBALS['TBE_MODULES_EXT'] ['xMOD_db_new_content_el'] ['addElClasses'] ['TYPO3\CMS\Cal\Backend\CalWizIcon'] = $extPath . 'Classes/Backend/CalWizIcon.php';
}
?>