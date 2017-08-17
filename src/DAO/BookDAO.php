<?php

namespace OCMyBooks\DAO;

use OCMyBooks\Domain\Book;

class BookDAO extends DAO 
{
    /**
     * @var \OCMyBook\DAO\ArticleDAO
     */
    private $authorDAO;

    public function setAuthorDAO(AuthorDAO $authorDAO) {
        $this->authorDAO = $authorDAO;
    }

    /**
     * Return a list of all comments for an article, sorted by date (most recent last).
     *
     * @param integer $articleId The article id.
     *
     * @return array A list of all comments for the article.
     */
    public function findAllByAuthor($authorId) {
        // The associated article is retrieved only once
        $author = $this->authorDAO->find($authorId);

        // art_id is not selected by the SQL query
        // The article won't be retrieved during domain objet construction
        $sql = "select book_id, book_title, book_isbn, book_summary from book where auth_id=? order by auth_id";
        $result = $this->getDb()->fetchAll($sql, array($authorId));

        // Convert query result to an array of domain objects
        $books = array();
        foreach ($result as $row) {
            $comId = $row['book_id'];
            $book = $this->buildDomainObject($row);
            // The associated article is defined for the constructed comment
            $book->setAuthor($author);
            $books[$comId] = $book;
        }
        return $books;
    }

    /**
     * Creates an Comment object based on a DB row.
     *
     * @param array $row The DB row containing Comment data.
     * @return \MyBooks\Domain\Comment
     */
    protected function buildDomainObject(array $row) {
        $book = new Book();
        $book->setId($row['book_id']);
        $book->setContent($row['book_title']);
        $book->setAuthor($row['book_isbn']);
        $book->setContent($row['book_summary']);

        if (array_key_exists('auth_id', $row)) {
            // Find and set the associated article
            $authorId = $row['auth_id'];
            $author = $this->authorDAO->find($authorId);
            $book->setArticle($author);
        }
        
        return $book;
    }
}