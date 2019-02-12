<?php

namespace App\Blog\Table;

use App\Blog\Entity\Post;
use Framework\Database\PaginatedQuery;
use Pagerfanta\Pagerfanta;

class PostTable
{
    /**
     * @var \PDO
     */
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Paginated articles
     * @param int $perPage
     * @param int $currentPage
     * @return Pagerfanta
     */
    public function findPaginated(int $perPage, int $currentPage): Pagerfanta
    {
        $query = new PaginatedQuery(
            $this->pdo,
            'SELECT * FROM posts ORDER BY created_at DESC',
            'SELECT COUNT(id) FROM posts',
            Post::class
        );

        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }

    /**
     * Get article by id
     * @param int $id
     * @return Post|null
     */
    public function find(int $id): ?Post
    {
        $query = $this->pdo->prepare('SELECT * FROM posts WHERE id = ?');
        $query->execute([$id]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Post::class);
        // If results found return it else return null
        return $query->fetch() ?: null;
    }

    /**
     * Update post fields
     * @param int $id
     * @param array $params
     * @return bool
     */
    public function update(int $id, array $params): bool
    {
        $fieldQuery = join(', ', array_map(function ($field) {
            return "$field = :$field";
        }, array_keys($params)));

        $params['id'] = $id;

        $statement = $this->pdo->prepare("UPDATE posts SET $fieldQuery WHERE id = :id");
        return $statement->execute($params);
    }
}
