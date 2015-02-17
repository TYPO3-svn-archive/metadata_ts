<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "metadata_ts".
 *
 * Auto generated 17-02-2015 09:51
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Metadata in TS',
	'description' => 'Lets you extract metadata from different file formats using TypoScript.',
	'category' => 'misc',
	'author' => 'Xavier Perseguers (Causal)',
	'author_email' => 'xavier@causal.ch',
	'shy' => '',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'obsolete',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.4.0',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.5.0-4.7.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:66:{s:9:"ChangeLog";s:4:"788e";s:21:"ext_conf_template.txt";s:4:"7d1d";s:12:"ext_icon.gif";s:4:"5807";s:17:"ext_localconf.php";s:4:"253c";s:10:"README.txt";s:4:"3935";s:32:"api/class.user_metadata_cobj.php";s:4:"4194";s:14:"doc/manual.sxw";s:4:"a1fc";s:41:"lib/PHP_JPEG_Metadata_Toolkit/COPYING.txt";s:4:"393a";s:48:"lib/PHP_JPEG_Metadata_Toolkit/Edit_File_Info.php";s:4:"dc98";s:56:"lib/PHP_JPEG_Metadata_Toolkit/Edit_File_Info_Example.php";s:4:"b16f";s:41:"lib/PHP_JPEG_Metadata_Toolkit/Example.php";s:4:"a948";s:38:"lib/PHP_JPEG_Metadata_Toolkit/EXIF.php";s:4:"3996";s:48:"lib/PHP_JPEG_Metadata_Toolkit/EXIF_Makernote.php";s:4:"e8cd";s:43:"lib/PHP_JPEG_Metadata_Toolkit/EXIF_Tags.php";s:4:"0354";s:49:"lib/PHP_JPEG_Metadata_Toolkit/get_casio_thumb.php";s:4:"27cf";s:48:"lib/PHP_JPEG_Metadata_Toolkit/get_exif_thumb.php";s:4:"daae";s:48:"lib/PHP_JPEG_Metadata_Toolkit/get_JFXX_thumb.php";s:4:"3a77";s:51:"lib/PHP_JPEG_Metadata_Toolkit/get_minolta_thumb.php";s:4:"c610";s:46:"lib/PHP_JPEG_Metadata_Toolkit/get_ps_thumb.php";s:4:"541e";s:38:"lib/PHP_JPEG_Metadata_Toolkit/IPTC.php";s:4:"7922";s:38:"lib/PHP_JPEG_Metadata_Toolkit/JFIF.php";s:4:"a209";s:38:"lib/PHP_JPEG_Metadata_Toolkit/JPEG.php";s:4:"2647";s:53:"lib/PHP_JPEG_Metadata_Toolkit/Photoshop_File_Info.php";s:4:"3eae";s:47:"lib/PHP_JPEG_Metadata_Toolkit/Photoshop_IRB.php";s:4:"5a0b";s:45:"lib/PHP_JPEG_Metadata_Toolkit/PictureInfo.php";s:4:"3ac8";s:37:"lib/PHP_JPEG_Metadata_Toolkit/PIM.php";s:4:"0e01";s:44:"lib/PHP_JPEG_Metadata_Toolkit/pjmt_utils.php";s:4:"18cb";s:38:"lib/PHP_JPEG_Metadata_Toolkit/test.jpg";s:4:"5b34";s:45:"lib/PHP_JPEG_Metadata_Toolkit/TIFFExample.php";s:4:"b185";s:49:"lib/PHP_JPEG_Metadata_Toolkit/Toolkit_Version.php";s:4:"bd03";s:41:"lib/PHP_JPEG_Metadata_Toolkit/Unicode.php";s:4:"4fd3";s:49:"lib/PHP_JPEG_Metadata_Toolkit/Write_File_Info.php";s:4:"1caa";s:37:"lib/PHP_JPEG_Metadata_Toolkit/XML.php";s:4:"d7d1";s:37:"lib/PHP_JPEG_Metadata_Toolkit/XMP.php";s:4:"2efe";s:49:"lib/PHP_JPEG_Metadata_Toolkit/Makernotes/agfa.php";s:4:"6491";s:50:"lib/PHP_JPEG_Metadata_Toolkit/Makernotes/canon.php";s:4:"ddba";s:50:"lib/PHP_JPEG_Metadata_Toolkit/Makernotes/casio.php";s:4:"9a9f";s:50:"lib/PHP_JPEG_Metadata_Toolkit/Makernotes/epson.php";s:4:"a378";s:53:"lib/PHP_JPEG_Metadata_Toolkit/Makernotes/fujifilm.php";s:4:"e9fb";s:59:"lib/PHP_JPEG_Metadata_Toolkit/Makernotes/konica_minolta.php";s:4:"2c01";s:52:"lib/PHP_JPEG_Metadata_Toolkit/Makernotes/kyocera.php";s:4:"9c8a";s:50:"lib/PHP_JPEG_Metadata_Toolkit/Makernotes/nikon.php";s:4:"2917";s:52:"lib/PHP_JPEG_Metadata_Toolkit/Makernotes/olympus.php";s:4:"c283";s:54:"lib/PHP_JPEG_Metadata_Toolkit/Makernotes/panasonic.php";s:4:"c5be";s:51:"lib/PHP_JPEG_Metadata_Toolkit/Makernotes/Pentax.php";s:4:"ed06";s:50:"lib/PHP_JPEG_Metadata_Toolkit/Makernotes/ricoh.php";s:4:"c2d0";s:49:"lib/PHP_JPEG_Metadata_Toolkit/Makernotes/sony.php";s:4:"5c0b";s:63:"lib/PHP_JPEG_Metadata_Toolkit/documentation/Camera_List_1.0.pdf";s:4:"d82d";s:56:"lib/PHP_JPEG_Metadata_Toolkit/documentation/changes.html";s:4:"bda3";s:58:"lib/PHP_JPEG_Metadata_Toolkit/documentation/css_terms.html";s:4:"ab40";s:69:"lib/PHP_JPEG_Metadata_Toolkit/documentation/edit_write_file_info.html";s:4:"885c";s:56:"lib/PHP_JPEG_Metadata_Toolkit/documentation/example.html";s:4:"f5f4";s:57:"lib/PHP_JPEG_Metadata_Toolkit/documentation/examples.html";s:4:"1f64";s:53:"lib/PHP_JPEG_Metadata_Toolkit/documentation/exif.html";s:4:"513b";s:54:"lib/PHP_JPEG_Metadata_Toolkit/documentation/index.html";s:4:"9c64";s:54:"lib/PHP_JPEG_Metadata_Toolkit/documentation/intro.html";s:4:"eadc";s:53:"lib/PHP_JPEG_Metadata_Toolkit/documentation/jfif.html";s:4:"a2ac";s:53:"lib/PHP_JPEG_Metadata_Toolkit/documentation/jpeg.html";s:4:"bb9a";s:58:"lib/PHP_JPEG_Metadata_Toolkit/documentation/photoshop.html";s:4:"14a8";s:68:"lib/PHP_JPEG_Metadata_Toolkit/documentation/photoshop_file_info.html";s:4:"a64e";s:61:"lib/PHP_JPEG_Metadata_Toolkit/documentation/picture_info.html";s:4:"734a";s:53:"lib/PHP_JPEG_Metadata_Toolkit/documentation/style.css";s:4:"9b8b";s:60:"lib/PHP_JPEG_Metadata_Toolkit/documentation/tiffexample.html";s:4:"6e11";s:53:"lib/PHP_JPEG_Metadata_Toolkit/documentation/todo.html";s:4:"8876";s:52:"lib/PHP_JPEG_Metadata_Toolkit/documentation/xmp.html";s:4:"4ccd";s:18:"samples/wbtc05.jpg";s:4:"52b1";}',
	'suggests' => array(
	),
);

?>