<?php
declare(strict_types=1);
namespace Tests;
use App\Validation\UploadValidator;use PHPUnit\Framework\TestCase;
final class UploadValidatorTest extends TestCase{public function testRejectsMissingUpload():void{self::assertNotEmpty(UploadValidator::validate(['error'=>UPLOAD_ERR_NO_FILE]));}public function testAllowsOnlyMappedImageExtensions():void{self::assertSame('jpg',UploadValidator::extension('image/jpeg'));self::assertNull(UploadValidator::extension('image/svg+xml'));}}
