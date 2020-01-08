<?php

namespace TGHP\RealpageClient;

class Auth
{

    public $pmcId;

    public $siteId;

    public $username;

    public $password;

    public $licenseKey;

    public $system = 'OneSite';

    public function __construct($pmcId, $siteId, $username, $password, $licenseKey) {
        $this->pmcId = $pmcId;
        $this->siteId = $siteId;
        $this->username = $username;
        $this->password = $password;
        $this->licenseKey = $licenseKey;
    }

}