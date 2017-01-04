<?php

include_once __DIR__."/../vendor/autoload.php";
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase {
	public function testDateTimeTimeZone() {
		$filepath = __DIR__.'/../photos/P1017630.ORF';
		$unixtimestamp = filemtime($filepath);
		//$unixtimestamp = '1483225520'; // 2017-01-01 08:05:20
		$this->assertEquals('2017-01-01 08:05:20', date('Y-m-d H:i:s', $unixtimestamp));


		$date = DateTimeImmutable::createFromFormat( 'U', $unixtimestamp, new DateTimeZone('Asia/Tokyo'));
		$this->assertEquals('2017-01-01 08:05:20', $date->format('Y-m-d H:i:s'));

		$date = DateTimeImmutable::createFromFormat( 'U', $unixtimestamp);
		$this->assertEquals('2017-01-01 08:05:20', $date->format('Y-m-d H:i:s'));
	}
}