<?php
declare(strict_types=1);
namespace Tests;
use App\Validation\PostValidator;use PHPUnit\Framework\TestCase;
final class PostValidatorTest extends TestCase{public function testRequiresTitleAndContent():void{$e=PostValidator::validate([]);self::assertArrayHasKey('title',$e);self::assertArrayHasKey('markdown_content',$e);}public function testAcceptsValidPublishedPost():void{self::assertSame([],PostValidator::validate(['title'=>'Test','markdown_content'=>'Body','status'=>'published']));}}
