<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\Csrf;use App\Repositories\PostRepository;use App\Repositories\SettingsRepository;use App\Services\MarkdownService;use PDO;
abstract class BaseController
{
 public function __construct(protected PDO $pdo,protected PostRepository $posts,protected SettingsRepository $settings,protected MarkdownService $markdown,protected array $config){}
 protected function view(string $name,array $data=[],string $layout='public'):string{$shared=['profile'=>array_replace($this->config['profile'],$this->settings->allPublic()),'recentPosts'=>$this->posts->recent(),'siteUrl'=>$this->config['url']];return \App\Core\View::render($name,array_merge($shared,$data),$layout);}
 protected function redirect(string $path):never{header('Location: '.url($path),true,303);exit;}
 protected function csrf():void{if(!Csrf::valid($_POST['_token']??null)){http_response_code(419);echo $this->view('errors/419',['title'=>'Page expired']);exit;}}
}
