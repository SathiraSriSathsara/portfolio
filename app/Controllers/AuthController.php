<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Services\AuthService;
final class AuthController extends BaseController
{
 public function loginForm():string{return $this->view('auth/login',['title'=>'Admin sign in','error'=>$_SESSION['_error']??null],'auth');}
 public function login():never{$this->csrf();$ok=(new AuthService($this->pdo))->attempt(strtolower(trim($_POST['email']??'')),(string)($_POST['password']??''),$_SERVER['REMOTE_ADDR']??'unknown');if(!$ok){$_SESSION['_error']='Unable to sign in with those credentials.';$this->redirect('admin/login');}unset($_SESSION['_error']);$this->redirect('admin');}
 public function logout():never{$this->csrf();AuthService::logout();$this->redirect('admin/login');}
}
