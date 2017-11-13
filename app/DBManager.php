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
        $this->dbConnection->createTable(DBConnectionInterface::DOMAIN_TABLE_NAME);
    }

    public function insertNewDomain(array $data)
    {
        $this->dbConnection->insert(DBConnectionInterface::DOMAIN_TABLE_NAME, $data);
    }

    public function increaseHits($domain)
    {
        $this->dbConnection->increaseFieldBy(DBConnectionInterface::DOMAIN_TABLE_NAME, DBConnectionInterface::HITS_FIELD_NAME, DBConnectionInterface::DOMAIN_FIELD_NAME, $domain);
    }

    public function increaseUniqueUser($domain)
    {
        $this->dbConnection->increaseFieldBy(DBConnectionInterface::DOMAIN_TABLE_NAME, DBConnectionInterface::UNIQUE_USER_FIELD_NAME, DBConnectionInterface::DOMAIN_FIELD_NAME, $domain);
    }

    public function checkDomainExist($domain)
    {
        $result = $this->dbConnection->getByField(DBConnectionInterface::DOMAIN_TABLE_NAME, DBConnectionInterface::DOMAIN_FIELD_NAME, $domain);
        return count($result) > 0;
    }

    public function getAll()
    {
        return $this->dbConnection->query(DBConnectionInterface::DOMAIN_TABLE_NAME, array('id', 'domain', 'hits', 'unique_users'));
    }

    public function getByDomain($domain)
    {
        return $this->dbConnection->getByField(DBConnectionInterface::DOMAIN_TABLE_NAME, DBConnectionInterface::DOMAIN_FIELD_NAME, $domain);
    }
}