<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Xavier Perseguers (typo3@perseguers.ch)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/*
 * Handles TS content object FILEINFO
 */
class user_metadata_cobj {
	
	private $data = array();
	private $has_exif = false;
	private $has_iptc = false;
	
	/**
	 * Rendering the cObject, FILEINFO
	 *
	 * @param	string		$name: name of the cObject ('FILEINFO')		
	 * @param	array		$conf: array of TypoScript properties
	 * @param	string		$TSkey: TS key set to this cObject
	 * @return	string		output
	 */
	function cObjGetSingleExt($name, $conf, $TSkey, &$oCObj) {
		$file = $conf['file'];
		$field = $conf['metadata.']['field'];
		
		if (!t3lib_div::isAbsPath($file)) {
			$file = t3lib_div::getFileAbsFileName($file);
		}
		
		$service = 'image:exif';
		if (is_object($serviceObj = t3lib_div::makeInstanceService('metaExtract', $service))) {
			$serviceObj->setInputFile($file, $service);

			if ($serviceObj->process('','',array('meta'=>$meta)) > 0 
				&& (is_array($svmeta = $serviceObj->getOutput()))) {
				$this->storeArray('metaExtract:', '', $svmeta);
			}
		}
		 
		$_fields = t3lib_div::trimExplode('//', $field, 1);
		foreach ($_fields as $f) {
			list($type, $metatag) = t3lib_div::trimExplode(':', $f);
			
			switch ($type) {
				case 'EXIF': $this->EXIF($file); break;
				case 'IPTC': $this->IPTC($file); break;
			}
		}
		
		if ($conf['debug']) debug($this->data);
		
		$content = $this->getFieldVal($field);
		
		return $oCObj->stdWrap($content, $conf);
	}
	
	/**
	 * Reads EXIF metatags.
	 *
	 * @param	string		$file: filename of the image
	 */
	function EXIF($file) {
		if ($this->has_exif) return;
		
		$conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['metadata_ts']);
		$exif_data = array();
		
		if (file_exists($file)) {
			if ($conf['exifTool']) {
				$cmd = t3lib_exec::getCommand($conf['exifTool']) . '  "' . $$file . '"';
				exec($cmd, $exif = '', $ret = '');
				
				if (!$ret AND is_array($exif)) {
					$exif_data = extractData($exif, $conf['exifColumnSeparator'], $conf['exifKeyColumn'], $conf['exifValueColumn']);
				}
				
			} else {
				$image_info = getimagesize($file);

				if ($image_info[2] == 2) { // check for correct image-type
					$exif_data = exif_read_data($file, TRUE, FALSE); // Load all EXIF informations from the original Picture in an array
					$exif_data['Comments'] = htmlentities(str_replace("\n", '<br />', $exif_array['Comments'])); // Linebreak
				}
			}
		}
			
			// Put all data into $this->data
		$this->storeArray('EXIF:', '', $exif_data);
	}
	
	/**
	 * Reads IPTC metatags.
	 *
	 * @param	string		$file: filename of the image
	 */
	function IPTC($file) {
		if ($this->has_iptc) return; 
		
		$conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['metadata_ts']);
		$iptc_data = array();
		
		if (file_exists($file)) {
			if ($conf['iptcTool']) {
				$cmd = t3lib_exec::getCommand($conf['iptcTool']) . '  "' . $$file . '"';
				exec($cmd, $iptc = '', $ret = '');
				
				if (!$ret AND is_array($iptc)) {
					$iptc_data = extractData($iptc, $conf['iptcColumnSeparator'], $conf['iptcKeyColumn'], $conf['iptcValueColumn']);
				}
				
			} else {
				getimagesize($file, $image_info);

				if (is_array($image_info)) {
					$iptc_data = iptcparse($image_info["APP13"]);
				}
			}
			
			
		}
			
		if (is_array($iptc_data)) {
			// Put all data into $this->data
			$this->storeArray('IPTC:', '', $iptc_data);
		}
	}
	
	/**
	 * Extracts key/value pairs from a command-line output ($data)
	 *
	 * @param	array	$data
	 * @param	string	$separator
	 * @param	integer	$keyCol
	 * @param	integer	$valueCol
	 */
	function extractData($data, $separator, $keyCol, $valueCol) {
		$info = array();
		foreach ($output as $content) {
			$separator = '\s+';
			$temp = preg_split("/$separator/", $content, $valueCol + 1);
			
			$key = $temp[$keyCol];
			$value = $temp[$valueCol];
			
			if (preg_match('/^lang=".*" (.*)$/', $value, $matches)) {
				$value = $matches[1];
			}
			
			$info[$key] = $value; 
		}
		
		return $info;
	}
	
	/**
	 * Stores metadata array into private data array.
	 *
	 * @param	string		$prefix
	 * @param	string		$key
	 * @param	array		$values
	 */
	function storeArray($prefix, $key, $values) {
		foreach ($values as $subkey => $value) {
			$datakey = '';
			if ($key) $datakey = $key . '|';
			$datakey .= $subkey;
			
			if (is_array($value)) {
				$this->storeArray($prefix, $datakey, $value);
			} else {
				$this->data[$prefix. $datakey] = utf8_decode(utf8_decode($value));
			}
		}
	}
	
	/**
	 * Returns the value for the field from $this->data. If "//" is found in the $field value that token will split the field values apart and the first field having a non-blank value will be returned.
	 *
	 * @param	string		The fieldname, eg. "title" or "navtitle // title" (in the latter case the value of $this->data[navtitle] is returned if not blank, otherwise $this->data[title] will be)
	 * @return	string
	 */
	function getFieldVal($field) {
		if (!strstr($field,'//')) {
			return $this->data[trim($field)];
		} else {
			$sections = t3lib_div::trimExplode('//', $field, 1);
			while (list(,$k) = each($sections)) {
				if (strcmp($this->data[$k],''))	return $this->data[$k];
			}
		}
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/metadata_ts/api/class.user_metadata_cobj.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/metadata_ts/api/class.user_metadata_cobj.php']);
}
?>