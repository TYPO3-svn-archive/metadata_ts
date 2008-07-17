<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'][] = array(
	0 => 'FILEINFO',
	1 => 'EXT:metadata_ts/api/class.user_metadata_cobj.php:user_metadata_cobj',
);
?>
