<?php
declare(strict_types=1);
namespace Tests;
use App\Core\Router;use PHPUnit\Framework\TestCase;
final class RouterTest extends TestCase{public function testMatchesNamedSegment():void{$r=new Router();$r->get('/blog/{slug}',fn()=>null);$out=$r->dispatch('GET','/blog/secure-php?ref=test',fn($handler,$params)=>$params['slug']);self::assertSame('secure-php',$out);}}
