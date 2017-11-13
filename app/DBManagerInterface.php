<?php


namespace App;


interface DBManagerInterface
{
    /**
     * @return mixed
     */
    public function createTable();

    public function insertNewDomain(array $data);

    public function increaseHits($domain);

    public function increaseUniqueUser($domain);

    /**
     * @param $domain
     * @return bool
     */
    public function checkDomainExist($domain);

    public function getAll();

    public function getByDomain($domain);
}