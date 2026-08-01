<?php
declare(strict_types=1);
namespace Tests;
use PHPUnit\Framework\TestCase;
final class SeoOutputTest extends TestCase{public function testCanonicalUrlJoinsCleanly():void{$_ENV['APP_URL']='https://portfolio.test';self::assertSame('https://portfolio.test/blog/example',url('blog/example'));}}
