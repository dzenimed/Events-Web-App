<?php

require_once dirname(__FILE__).'/BaseService.class.php';
require_once dirname(__FILE__).'/../dao/CompanyDao.class.php';
require_once dirname(__FILE__).'/../../vendor/autoload.php';

class CompanyService extends BaseService
{
    private $companyDao;

    public function __construct()
    {
        $this->dao = new CompanyDao();
    }

    public function get_company_by_name($name)
    {
        return $this->dao->getCompany_by_name($name);
    }
}
