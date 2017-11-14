<?php
namespace App;


class DBManager implements DBManagerInterface
{
    private $dbConnection;

    public function __construct(DBConnectionInterface $dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    public function createTable()
    {
        $this->dbConnection->createTable(Config::DOMAIN_TABLE_NAME);
    }

    public function insertNewDomain(array $data)
    {
        $this->dbConnection->insert(Config::DOMAIN_TABLE_NAME, $data);
    }

    public function increaseHits($domain)
    {
        $this->dbConnection->increaseFieldBy(Config::DOMAIN_TABLE_NAME, Config::HITS_FIELD_NAME, Config::DOMAIN_FIELD_NAME, $domain);
    }

    public function increaseUniqueUser($domain)
    {
        $this->dbConnection->increaseFieldBy(Config::DOMAIN_TABLE_NAME, Config::UNIQUE_USER_FIELD_NAME, Config::DOMAIN_FIELD_NAME, $domain);
    }

    public function checkDomainExist($domain)
    {
        $result = $this->dbConnection->getByField(Config::DOMAIN_TABLE_NAME, Config::DOMAIN_FIELD_NAME, $domain);
        return count($result) > 0;
    }

    public function getAll()
    {
        return $this->dbConnection->query(Config::DOMAIN_TABLE_NAME, array('id', 'domain', 'hits', 'unique_users'));
    }

    public function getByDomain($domain)
    {
        return $this->dbConnection->getByField(Config::DOMAIN_TABLE_NAME, Config::DOMAIN_FIELD_NAME, $domain);
    }
}