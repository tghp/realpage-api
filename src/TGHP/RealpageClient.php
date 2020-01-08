<?php

namespace TGHP;

use TGHP\RealpageClient\Auth;
use TGHP\RealpageClient\Service\PricingAndAvailability;

class RealpageClient
{

    protected $defaultAuth;

    protected $defaultServiceUrl;

    public function setDefaultAuth(Auth $auth)
    {
        $this->defaultAuth = $auth;

        return $this;
    }

    public function setDefaultServiceUrl($url)
    {
        $this->defaultServiceUrl = $url;

        return $this;
    }

    protected function _getService($service, $serviceUrl = null)
    {
        if (!$serviceUrl) {
            $serviceUrl = $this->defaultServiceUrl;
        }

        $class = "\TGHP\RealpageClient\Service\\$service";
        $service = new $class($serviceUrl);

        if($this->defaultAuth) {
            $service->authenticate($this->defaultAuth);
        }

        return $service;
    }

    /**
     * @param null $serviceUrl
     * @return PricingAndAvailability
     */
    public function getPricingAndAvailability($serviceUrl = null)
    {
        return $this->_getService('PricingAndAvailability', $serviceUrl);
    }

}