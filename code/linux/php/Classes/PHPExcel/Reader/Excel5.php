<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel_Reader_Excel5
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */

// Original file header of ParseXL (used as the base for this class):
// --------------------------------------------------------------------------------
// Adapted from Excel_Spreadsheet_Reader developed by users bizon153,
// trex005, and mmp11 (SourceForge.net)
// http://sourceforge.net/projects/phpexcelreader/
// Primary changes made by canyoncasa (dvc) for ParseXL 1.00 ...
//	 Modelled moreso after Perl Excel Parse/Write modules
//	 Added Parse_Excel_Spreadsheet object
//		 Reads a whole worksheet or tab as row,column array or as
//		 associated hash of indexed rows and named column fields
//	 Added variables for worksheet (tab) indexes and names
//	 Added an object call for loading individual woorksheets
//	 Changed default indexing defaults to 0 based arrays
//	 Fixed date/time and percent formats
//	 Includes patches found at SourceForge...
//		 unicode patch by nobody
//		 unpack("d") machine depedency patch by matchy
//		 boundsheet utf16 patch by bjaenichen
//	 Renamed functions for shorter names
//	 General code cleanup and rigor, including <80 column width
//	 Included a testcase Excel file and PHP example calls
//	 Code works for PHP 5.x

// Primary changes made by canyoncasa (dvc) for ParseXL 1.10 ...
// http://sourceforge.net/tracker/index.php?func=detail&aid=1466964&group_id=99160&atid=623334
//	 Decoding of formula conditions, results, and tokens.
//	 Support for user-defined named cells added as an array "namedcells"
//		 Patch code for user-defined named cells supports single cells only.
//		 NOTE: this patch only works for BIFF8 as BIFF5-7 use a different
//		 external sheet reference structure


/** PHPExcel root directory */
if (!defined('PHPEXCEL_ROOT')) {
	/**
	 * @ignore
	 */
	define('PHPEXCEL_ROOT', dirname(__FILE__) . '/../../');