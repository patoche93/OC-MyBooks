<?php

namespace OCMyBooks\DAO;

use OCMyBooks\Domain\Author;

class AuthorDAO extends DAO
{
    /**
     * Return a list of all articles, sorted by date (most recent first).
     *
     * @return array A list of all articles.
     */
    public function findAll() {
        $sql = "select * from author order by auth_id desc";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $authors = array();
        foreach ($result as $row) {
            $authorId = $row['auth_id'];
            $authors[$authorId] = $this->buildDomainObject($row);
        }
        return $authors;
    }

    /**
     * Creates an Article object based on a DB row.
     *
     * @param array $row The DB row containing Article data.
     * @return \MicroCMS\Domain\Article
     */
    protected function buildDomainObject(array $row) {
        $author = new Author();
        $author->setId($row['auth_id']);
        $author->setTitle($row['auth_first_name']);
        $author->setContent($row['auth_last_name']);
        return $author;
    }

      public function find($id) {
        $sql = "select * from author where auth_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No author matching id " . $id);
    }
}