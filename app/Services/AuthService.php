<?php
declare(strict_types=1);
namespace App\Services;
use PDO;
final class AuthService
{
 public function __construct(private readonly PDO $pdo){}
 public function attempt(string $email,string $password,string $ip):bool{$hash=hash('sha256',$ip);$q=$this->pdo->prepare('SELECT COUNT(*) FROM login_attempts WHERE email=? AND ip_hash=? AND successful=0 AND attempted_at > DATE_SUB(NOW(),INTERVAL 15 MINUTE)');$q->execute([$email,$hash]);if((int)$q->fetchColumn()>=5)return false;$s=$this->pdo->prepare("SELECT * FROM users WHERE email=? AND status='active' LIMIT 1");$s->execute([$email]);$u=$s->fetch();$ok=$u&&password_verify($password,$u['password_hash']);$this->pdo->prepare('INSERT INTO login_attempts(email,ip_hash,attempted_at,successful) VALUES(?,?,NOW(),?)')->execute([$email,$hash,$ok?1:0]);if(!$ok)return false;session_regenerate_id(true);$_SESSION['user']=['id'=>(int)$u['id'],'name'=>$u['name'],'role'=>$u['role']];$this->pdo->prepare('UPDATE users SET last_login_at=NOW() WHERE id=?')->execute([$u['id']]);return true;}
 public static function check():bool{return isset($_SESSION['user']['id']);} public static function logout():void{$_SESSION=[];session_regenerate_id(true);}
}
