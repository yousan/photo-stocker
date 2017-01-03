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

	/**
	 * 移動対象とするファイル名のパターン。
	 * デフォルトは.jpeg, .jpgで大文字小文字を判定しない。
	 *
	 * @var string
	 */
	private static $filename_pattern = '/.(jpg|jpeg)$/i';

	public static function stock() {
		foreach ( scandir(self::$sourcedir) as $filename ) {
			if ( in_array($filename, array(".","..")) ) {
				continue; // .と..
			}
			if ( preg_match(self::$filename_pattern, $filename) ) {
				$sourcefile = self::$sourcedir . $filename;
				//var_dump($sourcefile);
				self::move_file($sourcefile);
			}
		}
	}

	/**
	 * ファイルを移動する
	 *
	 * @param $sourcefile
	 */
	private static function move_file($sourcefile) {
		$exifdata = exif_read_data($sourcefile, 0, true);
		if ( !isset($exifdata["EXIF"]['DateTimeOriginal']) ) {
			return;
		}
		$date = DateTimeImmutable::createFromFormat('Y:m:d H:i:s', $exifdata["EXIF"]['DateTimeOriginal']);
		$year = $date->format('Y');
		$month = $date->format('Ym');
		$destdir = self::$destbasedir . '/' . $year . '/' . $month . '/';
		$destfile = $destdir . basename($sourcefile);
		if ( !file_exists($destdir) ) { // ディレクトリがなかったら作成
			echo 'Creating Directory: '. $destdir . PHP_EOL;
			mkdir($destdir, self::$dirmode, true);
		}
		if ( file_exists($destfile) ) {
			echo 'File already exists: '. $destfile . PHP_EOL;
		} else {
			rename($sourcefile, $destfile);
		}
	}
}

PhotoStocker::stock();