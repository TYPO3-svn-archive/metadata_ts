<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008-2010 Xavier Perseguers (typo3@perseguers.ch)
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


/**
 * Handles TS content object FILEINFO.
 *
 * $Id$
 */
class user_metadata_cobj {

	protected $data = array();
	protected $conf = array();
	protected $has_exif = false;
	protected $has_iptc = false;

	/**
	 * Rendering the cObject, FILEINFO
	 *
	 * @param	string		$name: name of the cObject ('FILEINFO')
	 * @param	array		$conf: array of TypoScript properties
	 * @param	string		$TSkey: TS key set to this cObject
	 * @return	string		output
	 */
	function cObjGetSingleExt($name, $conf, $TSkey, &$oCObj) {
		$this->conf = $conf;

		$file = $oCObj->stdWrap($conf['file'], $conf['file.']);
		$field = $this->conf['metadata.']['field'];

		if (!t3lib_div::isAbsPath($file)) {
			$file = t3lib_div::getFileAbsFileName($file);
		}

		$fields = t3lib_div::trimExplode('//', $field, 1);
		if ($this->conf['debug'] && count($fields) == 0) {
				// Make sure both EXIF and IPTC are available
			$fields[] = 'EXIF:';
			$fields[] = 'IPTC:';
			$fields[] = 'XMP:';
		}

		foreach ($fields as $f) {
			list($type, $metatag) = t3lib_div::trimExplode(':', $f);

			switch ($type) {
				case 'EXIF': $this->EXIF($file); break;
				case 'IPTC': $this->IPTC($file); break;
				case 'XMP': $this->XMP($file); break;
			}
		}

		if ($this->conf['debug']) {
			t3lib_div::debug($this->data);
		}

		$content = $this->getFieldVal($field);

		return $oCObj->stdWrap($content, $this->conf);
	}

	/**
	 * Reads EXIF metatags.
	 *
	 * @param string filename of the image
	 * @return void
	 */
	protected function EXIF($file) {
		if ($this->has_exif) return;

		$extconf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['metadata_ts']);
		$exif_data = array();

		if (file_exists($file)) {
			if ($extconf['exifTool']) {
				//$cmd = t3lib_exec::getCommand($extconf['exifTool']) . ' "' . $file . '"';
				$cmd = $extconf['exifTool'] . ' "' . $file . '"';
				exec($cmd, $exif, $ret = '');

				if (!$ret && is_array($exif)) {
					$exif_data = self::extractData($exif, $extconf['exifColumnSeparator'], $extconf['exifKeyColumn'], $extconf['exifValueColumn']);
				} else {
					// Debug this problem
				}

			} else {
				$image_info = getimagesize($file);

				if ($image_info[2] == 2) { // check for correct image-type
					$exif_data = exif_read_data($file, TRUE, FALSE); // Load all EXIF informations from the original Picture in an array
					$exif_data['Comments'] = htmlentities(str_replace("\n", '<br />', $exif_array['Comments'])); // Linebreak
					$exif_data = self::formatGpsData($exif_data);
				}
			}
		}

			// Put all data into $this->data
		$this->storeArray('EXIF:', '', $exif_data);
	}

	/**
	 * Reads IPTC metatags.
	 *
	 * @param string filename of the image
	 * @return void
	 */
	protected function IPTC($file) {
		if ($this->has_iptc) return;

		$extconf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['metadata_ts']);
		$iptc_data = array();

		if (file_exists($file)) {
			if ($extconf['iptcTool']) {
				//$cmd = t3lib_exec::getCommand($extconf['iptcTool']) . ' "' . $file . '"';
				$cmd = $extconf['iptcTool'] . ' "' . $file . '"';
				exec($cmd, $iptc, $ret = '');

				if (!$ret && is_array($iptc)) {
					$iptc_data = self::extractData($iptc, $extconf['iptcColumnSeparator'], $extconf['iptcKeyColumn'], $extconf['iptcValueColumn']);
				} else {
					// Debug this problem
				}

			} else {
				getimagesize($file, $image_info);

				if (is_array($image_info)) {
					$iptc_data = iptcparse($image_info['APP13']);
				}
			}


		}

		if (is_array($iptc_data)) {
			// Put all data into $this->data
			$this->storeArray('IPTC:', '', $iptc_data);
		}
	}

	/**
	 * Reads XMP metatags from a PDF file.
	 *
	 * @param string $file
	 * @return void
	 */
	protected function XMP($file) {
		if (strtolower(substr($file, -4)) !== '.pdf') return;

		$extconf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['metadata_ts']);
		$xmp_data = array();

		if (file_exists($file)) {
			if ($extconf['pdfinfoTool']) {
				//$cmd = t3lib_exec::getCommand($extconf['pdfinfoTool']) . ' -meta "' . $file . '"';
				$cmd = $extconf['pdfinfoTool'] . ' -meta "' . $file . '"';

				exec($cmd, $pdfmeta, $ret = '');
				if (!$ret && is_array($pdfmeta)) {
					$xmlStr = '';
					$output = FALSE;
					foreach ($pdfmeta as $line) {
						if (t3lib_div::isFirstPartOfStr($line, '<x:xmpmeta')) {
							$output = TRUE;
						} elseif (t3lib_div::isFirstPartOfStr($line, '<'.'?xpacket')) {
							$output = FALSE;
						}
						if ($output) {
							$xmlStr .= $line;
						}
					}

					require_once(t3lib_extMgm::extPath('metadata_ts') . 'lib/PHP_JPEG_Metadata_Toolkit/XMP.php');

					$xmp = read_XMP_array_from_text($xmlStr);
					$xmp_data = Interpret_XMP_to_Array($xmp);
				}
			}
		}

		if ($xmp_data) {
			$this->storeArray('XMP:', '', $xmp_data);
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
	protected static function extractData($data, $separator, $keyCol, $valueCol) {
		$info = array();
		foreach ($data as $content) {
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
	 * Stores metadata array into protected data array.
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
				$this->data[$prefix. $datakey] = $value;
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
		if (!strstr($field, '//')) {
			return $this->data[trim($field)];
		} else {
			$sections = t3lib_div::trimExplode('//', $field, 1);
			foreach ($sections as $section) {
				if (strcmp($this->data[$section], '')) {
					return $this->data[$section];
				}
			}
		}
	}

	/**
	 * Formats GPS data.
	 *
	 * @param array $exifData
	 * @return array
	 */
	protected static function formatGpsData($exifData) {
		if (isset($exifData['GPSLatitude'])) {
			$latitude = $exifData['GPSLatitude'];
			$exifData['GPSLatitudePosition'] = ($exifData['GPSLatitudeRef'] !== 'N' ? '-' : '') . self::calcGpsPosition($latitude);
		}
		if (isset($exifData['GPSLongitude'])) {
			$longitude = $exifData['GPSLongitude'];
			$exifData['GPSLongitudePosition'] = ($exifData['GPSLongitudeRef'] !== 'E' ? '-' : '') . self::calcGpsPosition($longitude);
		}
		if (isset($exifData['GPSLatitudePosition']) && $exifData['GPSLongitudePosition']) {
			$exifData['GPSLink'] = sprintf('http://maps.google.com/maps?q=%s,%s', $exifData['GPSLatitudePosition'], $exifData['GPSLongitudePosition']);
		}
		return $exifData;
	}

	/**
	 * Calculates GPS position.
	 *
	 * @param array $gpsData
	 * @return double
	 */
	protected static function calcGpsPosition($gpsData) {
		if (function_exists('bcscale')) {
			bcscale(14);
			$pos = self::evalFloat($gpsData[0]);
			$pos = bcadd($pos, bcdiv(self::evalFloat($gpsData[1]), 60));
			$pos = bcadd($pos, bcdiv(self::evalFloat($gpsData[2]), 3600));
		} else {
			$pos = self::evalFloat($gpsData[0]);
			$pos += self::evalFloat($gpsData[1]) / 60;
			$pos += self::evalFloat($gpsData[2]) / 3600;
		}
		return $pos;
	}

	/**
	 * Evaluates a fractional value.
	 *
	 * @param $frac
	 * @return float
	 */
	protected static function evalFloat($frac) {
		$matches = array();
		if (preg_match('/(\d+)\/(\d+)/', $frac, $matches)) {
			return $matches[1] / $matches[2];
		} else {
			return 0;
		}
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/metadata_ts/api/class.user_metadata_cobj.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/metadata_ts/api/class.user_metadata_cobj.php']);
}
?>