<?php
/**
 * Created by PhpStorm.
 * User: yousan
 * Date: 2017/01/02
 * Time: 0:08
 */

Class PhotoStocker {
	private static $sourcedir = __DIR__.'../storage/';
	private $destdir = __DIR__.'../storage/';
	public static function stock() {
		$file = self::sourcedir . 'P1017630.JPG';
		$exifdata = exif_read_data("画像パス", 0, true);
		$date = isset($exifdata["EXIF"]['DateTimeOriginal']) ? $exifdata["EXIF"]['DateTimeOriginal'] : "";
		echo $date;
	}
}

PhotoStocker::stock();