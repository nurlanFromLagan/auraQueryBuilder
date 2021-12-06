<?php

namespace App;

use Aura\SqlQuery\QueryFactory;
use PDO;


class QueryBuilder
{
    private $pdo;
    private $queryFactory;

    public function __construct()
    {

        $this->pdo = new PDO('mysql:host=localhost; dbname=simple_query_builder;', 'root');

        $this->queryFactory = new QueryFactory('mysql');
    }


    public function getAll ($table) {

        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
                ->from($table);


        // prepare the statement
        $sth = $this->pdo->prepare($select->getStatement());

        // bind the values and execute
        $sth->execute($select->getBindValues());

        // get the results back as an associative array
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }


    public function insert ($table, $data) {

        $insert = $this->queryFactory->newInsert();

        $insert->into($table)             // insert into this table
        ->cols($data);

        // prepare the statement
        $sth = $this->pdo->prepare($insert->getStatement());

        // execute with bound values
        $sth->execute($insert->getBindValues());

    }


    public function update ($table, $data, $id) {

        $update = $this->queryFactory->newUpdate();

        $update
            ->table($table)                  // update this table
            ->cols($data)
            ->where('id = :id')
            ->bindValues([                  // bind these values to the query
                'id' => $id
            ]);

        // prepare the statement
        $sth = $this->pdo->prepare($update->getStatement());

        // execute with bound values
        $sth->execute($update->getBindValues());
    }


    public function delete ($table, $id) {

        $delete = $this->queryFactory->newDelete();

        $delete
            ->from($table)                   // FROM this table
            ->where('id = :id')           // AND WHERE these conditions
            ->bindValues([                  // bind these values to the query
                'id' => $id
            ]);

        // prepare the statement
        $sth = $this->pdo->prepare($delete->getStatement());

        // execute with bound values
        $sth->execute($delete->getBindValues());
    }


    public function getOne ($table, $id) {

        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from($table)
            ->where('id = :id')
            ->bindValues([
                'id' => $id
            ]);


        // prepare the statement
        $sth = $this->pdo->prepare($select->getStatement());

        // bind the values and execute
        $sth->execute($select->getBindValues());

        // get the results back as an associative array
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }


}