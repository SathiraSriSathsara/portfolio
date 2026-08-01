<?php
declare(strict_types=1);
namespace Tests;
use App\Core\Csrf;use PHPUnit\Framework\TestCase;
final class CsrfTest extends TestCase{protected function setUp():void{$_SESSION=[];}public function testValidatesOnlyCurrentToken():void{$token=Csrf::token();self::assertTrue(Csrf::valid($token));self::assertFalse(Csrf::valid('wrong'));Csrf::rotate();self::assertFalse(Csrf::valid($token));}}
