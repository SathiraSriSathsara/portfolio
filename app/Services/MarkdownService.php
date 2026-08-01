<?php
declare(strict_types=1);
namespace App\Services;
use HTMLPurifier; use HTMLPurifier_Config; use League\CommonMark\Environment\Environment; use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension; use League\CommonMark\Extension\GithubFlavoredMarkdownExtension; use League\CommonMark\MarkdownConverter;
final class MarkdownService
{
    private MarkdownConverter $converter; private HTMLPurifier $purifier;
    public function __construct()
    {
        $env=new Environment(['html_input'=>'strip','allow_unsafe_links'=>false]);$env->addExtension(new CommonMarkCoreExtension())->addExtension(new GithubFlavoredMarkdownExtension());$this->converter=new MarkdownConverter($env);
        $c=HTMLPurifier_Config::createDefault();$c->set('HTML.Allowed','p,br,strong,em,del,a[href|title|rel|target],ul,ol,li[class],h1[id],h2[id],h3[id],h4[id],h5[id],h6[id],blockquote,pre,code[class],table,thead,tbody,tr,th,td,hr,img[src|alt|title|width|height|loading],input[type|checked|disabled]');$c->set('URI.DisableExternalResources',false);$base=defined('BASE_PATH')?BASE_PATH:dirname(__DIR__,2);$c->set('Cache.SerializerPath',$base.'/storage/cache');$this->purifier=new HTMLPurifier($c);
    }
    public function render(string $markdown): string
    {
        $html=$this->purifier->purify((string)$this->converter->convert($markdown));
        $used=[];$html=preg_replace_callback('/<h([1-6])>(.*?)<\/h\1>/s',function($m)use(&$used){$text=trim(strip_tags($m[2]));$id=\App\Services\Slugger::make($text);$base=$id;$n=2;while(isset($used[$id]))$id=$base.'-'.$n++;$used[$id]=true;return '<h'.$m[1].' id="'.e($id).'">'.$m[2].'</h'.$m[1].'>';},$html)??$html;
        $html=preg_replace('/<table>/', '<div class="table-wrap"><table>', $html)??$html;$html=preg_replace('/<\/table>/', '</table></div>', $html)??$html;
        $html=preg_replace('/<img /','<img loading="lazy" ',$html)??$html;
        return preg_replace_callback('/<a href="([^"]+)"/',fn($m)=>str_starts_with($m[1],'http')&&!str_starts_with($m[1],url())?'<a href="'.e($m[1]).'" rel="noopener noreferrer" target="_blank"':'<a href="'.e($m[1]).'"',$html)??$html;
    }
    public function readingMinutes(string $markdown): int { return max(1,(int)ceil(str_word_count(strip_tags($markdown))/220)); }
}
