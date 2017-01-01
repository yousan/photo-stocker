<?php
/**
 * Created by PhpStorm.
 * User: yousan
 * Date: 2017/01/02
 * Time: 0:08
 */

Class PhotoStocker {
	private static $sourcedir = __DIR__.'/../photos/';
	private static $destbasedir = __DIR__ . '/../storage/';
	private static $dirmode = 0755;

	public static function stock() {
		$sourcefile = self::$sourcedir . 'P1017630.JPG';
		self::move_file($sourcefile);
	}

	private static function move_file($sourcefile) {
		$exifdata = exif_read_data($sourcefile, 0, true);
		if ( !isset($exifdata["EXIF"]['DateTimeOriginal']) ) {
			return;
		}
		$date = DateTimeImmutable::createFromFormat('Y:m:d H:i:s', $exifdata["EXIF"]['DateTimeOriginal'];);
		$year = $date->format('Y');
		$month = $date->format('m');
		$destdir = self::$destbasedir . '/' . $year . '/' . $month . '/';
		$destfile = $destdir . basename($sourcefile);
		if ( !file_exists($destdir) ) { // ディレクトリがなかったら作成
			echo 'Creating Directory: '. $destdir;
			mkdir($destdir, self::$dirmode, true);
		}
		rename($sourcefile, $destfile);
	}
}

PhotoStocker::stock();