<?php
declare(strict_types=1);
namespace Tests;
use App\Services\MarkdownService;use PHPUnit\Framework\TestCase;
final class MarkdownServiceTest extends TestCase{public function testRendersMarkdownFeatures():void{$html=(new MarkdownService())->render("# Hello\n\n- [x] Done\n\n| A | B |\n|---|---|\n| 1 | 2 |");self::assertStringContainsString('<h1 id="hello">',$html);self::assertStringContainsString('table-wrap',$html);}public function testRemovesScriptsAndUnsafeLinks():void{$html=(new MarkdownService())->render("<script>alert(1)</script>\n\n[x](javascript:alert(1))");self::assertStringNotContainsString('<script',$html);self::assertStringNotContainsString('javascript:',$html);}public function testExternalLinksAreHardened():void{$html=(new MarkdownService())->render('[Example](https://example.com)');self::assertStringContainsString('noopener noreferrer',$html);}}
