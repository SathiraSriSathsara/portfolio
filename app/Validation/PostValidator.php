<?php
declare(strict_types=1);
namespace App\Validation;
final class PostValidator{public static function validate(array $data):array{$errors=[];if(trim((string)($data['title']??''))==='')$errors['title']='A title is required.';if(mb_strlen((string)($data['title']??''))>255)$errors['title']='The title is too long.';if(trim((string)($data['markdown_content']??''))==='')$errors['markdown_content']='Content is required.';if(strlen((string)($data['markdown_content']??''))>500000)$errors['markdown_content']='Content must be under 500 KB.';if(isset($data['status'])&&!in_array($data['status'],['draft','scheduled','published','archived'],true))$errors['status']='Invalid status.';return $errors;}}
