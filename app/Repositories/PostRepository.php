<?php
declare(strict_types=1);
namespace App\Repositories;
use PDO;
final class PostRepository
{
    public function __construct(private readonly PDO $pdo) {}
    private function publishedWhere(): string { return "p.deleted_at IS NULL AND p.status='published' AND p.published_at <= NOW()"; }
    public function recent(int $limit = 6): array { $s=$this->pdo->prepare('SELECT id,title,slug,published_at FROM posts p WHERE '.$this->publishedWhere().' ORDER BY published_at DESC LIMIT ?'); $s->bindValue(1,$limit,PDO::PARAM_INT);$s->execute();return $s->fetchAll(); }
    public function findPublished(string $slug): ?array { $s=$this->pdo->prepare('SELECT p.*,c.name category_name,c.slug category_slug,u.name author_name FROM posts p LEFT JOIN categories c ON c.id=p.category_id JOIN users u ON u.id=p.author_id WHERE p.slug=? AND '.$this->publishedWhere().' LIMIT 1');$s->execute([$slug]);$p=$s->fetch();if(!$p)return null;$p['tags']=$this->tags((int)$p['id']);return $p; }
    public function paginate(int $page, int $perPage, string $query='', ?string $category=null, ?string $tag=null): array
    {
        $where=[$this->publishedWhere()];$params=[];$joins=' LEFT JOIN categories c ON c.id=p.category_id';
        if($query!==''){ $where[]='(p.title LIKE ? OR p.excerpt LIKE ? OR p.markdown_content LIKE ? OR c.name LIKE ? OR EXISTS(SELECT 1 FROM post_tags pt JOIN tags t ON t.id=pt.tag_id WHERE pt.post_id=p.id AND t.name LIKE ?))';$like='%'.$query.'%';$params=array_fill(0,5,$like); }
        if($category){$where[]='c.slug=?';$params[]=$category;} if($tag){$joins.=' JOIN post_tags fpt ON fpt.post_id=p.id JOIN tags ft ON ft.id=fpt.tag_id';$where[]='ft.slug=?';$params[]=$tag;}
        $base=' FROM posts p'.$joins.' WHERE '.implode(' AND ',$where);$c=$this->pdo->prepare('SELECT COUNT(DISTINCT p.id)'.$base);$c->execute($params);$total=(int)$c->fetchColumn();
        $sql='SELECT DISTINCT p.*,c.name category_name,c.slug category_slug'.$base.' ORDER BY p.published_at DESC LIMIT ? OFFSET ?';$s=$this->pdo->prepare($sql);$i=1;foreach($params as $v)$s->bindValue($i++,$v);$s->bindValue($i++,$perPage,PDO::PARAM_INT);$s->bindValue($i,($page-1)*$perPage,PDO::PARAM_INT);$s->execute();
        return ['items'=>$s->fetchAll(),'total'=>$total,'page'=>$page,'pages'=>max(1,(int)ceil($total/$perPage))];
    }
    public function tags(int $postId): array {$s=$this->pdo->prepare('SELECT t.name,t.slug FROM tags t JOIN post_tags pt ON pt.tag_id=t.id WHERE pt.post_id=? ORDER BY t.name');$s->execute([$postId]);return $s->fetchAll();}
    public function adjacent(array $post): array {$prev=$this->pdo->prepare('SELECT title,slug FROM posts p WHERE '.$this->publishedWhere().' AND published_at < ? ORDER BY published_at DESC LIMIT 1');$next=$this->pdo->prepare('SELECT title,slug FROM posts p WHERE '.$this->publishedWhere().' AND published_at > ? ORDER BY published_at ASC LIMIT 1');$prev->execute([$post['published_at']]);$next->execute([$post['published_at']]);return ['previous'=>$prev->fetch()?:null,'next'=>$next->fetch()?:null];}
    public function adminList(): array {return $this->pdo->query('SELECT p.id,p.title,p.slug,p.status,p.updated_at,p.published_at,u.name author_name FROM posts p JOIN users u ON u.id=p.author_id WHERE p.deleted_at IS NULL ORDER BY p.updated_at DESC')->fetchAll();}
    public function adminFind(int $id): ?array {$s=$this->pdo->prepare('SELECT * FROM posts WHERE id=? AND deleted_at IS NULL');$s->execute([$id]);return $s->fetch()?:null;}
}
