<?php
declare(strict_types=1);
namespace App\Middleware;
use App\Services\AuthService;
final class AuthMiddleware{public static function handle():void{if(!AuthService::check()){header('Location: '.url('admin/login'));exit;}if(($_SESSION['user']['role']??'')!=='admin'){http_response_code(403);exit('Forbidden');}}}
