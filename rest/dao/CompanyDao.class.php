<?php

require_once dirname(__FILE__)."/BaseDao.class.php";

class CompanyDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("company");
    }

    public function getCompany_by_name($name)
    {
        return $this->query_unique("SELECT * FROM company WHERE name = :name", ["name"=>$name]);
    }
}
