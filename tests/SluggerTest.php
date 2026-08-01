<?php
declare(strict_types=1);
namespace Tests;
use App\Services\Slugger;use PHPUnit\Framework\TestCase;
final class SluggerTest extends TestCase{public function testCreatesHumanReadableSlug():void{self::assertSame('hello-php-world',Slugger::make(' Hello, PHP World! '));}public function testNeverReturnsEmptySlug():void{self::assertSame('post',Slugger::make('---'));}}
