This extension lets you extract metadata from (yet) pictures containing IPTC or EXIF data.

Example:

10 = FILEINFO
10 {
	#debug = 1
	
	file = fileadmin/myfile.jpg
	metadata.field = EXIF:Author // IPTC:2#080|0	
	
	wrap = <strong> | </strong>
}

This will first try to extract author from EXIF, and if not found, will read it from IPTC.
IPTC stores the photograph in 2#080, subarray 0, that is IPTC:2#080|0 with this extension.

You'll find a description of IPTC field on

http://ch2.php.net/manual/en/function.iptcparse.php

or both for EXIF and IPTC if you activate the debugging option in TS.

Enjoy!