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
	private static $filename_pattern_jpg = '/.(jpg|jpeg)$/i';

	/**
	 * 移動対象とするファイル名のパターン。
	 *
	 * @var string
	 */
	private static $filename_pattern_orf = '/.(orf)$/i';

	public static function stock() {
		foreach ( scandir(self::$sourcedir) as $filename ) {
			if ( in_array($filename, array(".","..")) ) {
				continue; // .と..
			}
			$sourcefile = self::$sourcedir . $filename;
			self::move_file($sourcefile);
		}
	}

	/**
	 * ファイルを移動する
	 *
	 * @param $sourcefile
	 */
	private static function move_file($sourcefile) {
		var_dump(ini_set('date.timezone', 'Asia/Tokyo'));
		var_dump(ini_get('date.timezone'));
		if ( preg_match(self::$filename_pattern_jpg, $sourcefile) ) {
			$filetype = 'jpg';
			$exifdata = exif_read_data($sourcefile, 0, true); // EXIF情報を読み込む
			if ( isset($exifdata["EXIF"]['DateTimeOriginal']) ) {
				$date = DateTimeImmutable::createFromFormat( 'Y:m:d H:i:s', $exifdata["EXIF"]['DateTimeOriginal'] );
			} else {
				return;
			}
		} else if ( preg_match(self::$filename_pattern_orf, $sourcefile) ) {
			$filetype = 'orf';
			var_dump($dtz = new DateTimeZone('Asia/Tokyo'));
			if ( $unixtime = filemtime($sourcefile) ) {
				var_dump($unixtime);
				var_dump(date('Y-m-d H:i:s', $unixtime));
				$date = DateTimeImmutable::createFromFormat( 'U', $unixtime, $dtz
				);
			} else {
				return;
			}
		} else {
			return; // 拡張子が一致しない場合には何もしない
		}

		var_dump($date->format('Y-m-d H:i:s'));
		var_dump($filetype);
		$year = $date->format('Y'); // 年 ex. 2016
		$yearmonth = $date->format('Ym'); // 年月 ex. 201612

		$destdir = self::$destbasedir . '/' . $year . '/' . $yearmonth . '/';
		$destfile = $destdir . basename($sourcefile);

		if ( !file_exists($destdir) ) { // ディレクトリがなかったら作成
			echo 'Creating Directory: '. $destdir . PHP_EOL;
			mkdir($destdir, self::$dirmode, true);
		}
		if ( file_exists($destfile) ) {
			echo 'File already exists: '. $destfile . PHP_EOL;
		} else {
			//rename($sourcefile, $destfile);
		}
	}
}

PhotoStocker::stock();