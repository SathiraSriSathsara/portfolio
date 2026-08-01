<?php
declare(strict_types=1);
namespace App\Validation;
final class UploadValidator
{
 private const TYPES=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
 public static function validate(array $file,int $maxBytes=5242880):array{$errors=[];if(($file['error']??UPLOAD_ERR_NO_FILE)!==UPLOAD_ERR_OK)return ['Upload failed.'];if(($file['size']??0)>$maxBytes)$errors[]='Image exceeds 5 MB.';$mime=(new \finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name'])?:'';if(!isset(self::TYPES[$mime]))$errors[]='Only JPEG, PNG, and WebP images are allowed.';$dimensions=@getimagesize($file['tmp_name']);if(!$dimensions)$errors[]='The file is not a valid image.';elseif($dimensions[0]>6000||$dimensions[1]>6000)$errors[]='Image dimensions exceed 6000 × 6000.';return $errors;}
 public static function extension(string $mime):?string{return self::TYPES[$mime]??null;}
}
