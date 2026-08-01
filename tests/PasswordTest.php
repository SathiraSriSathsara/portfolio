<?php
declare(strict_types=1);
namespace Tests;
use PHPUnit\Framework\TestCase;
final class PasswordTest extends TestCase{public function testPasswordHashIsVerifiable():void{$hash=password_hash('a very strong password',defined('PASSWORD_ARGON2ID')?PASSWORD_ARGON2ID:PASSWORD_DEFAULT);self::assertTrue(password_verify('a very strong password',$hash));self::assertFalse(password_verify('wrong',$hash));}}
