<?php

namespace TGHP;

use TGHP\RealpageClient\Auth;
use TGHP\RealpageClient\Service\PricingAndAvailability;

class RealpageClient
{

    protected $defaultAuth;

    protected $defaultServiceUrl;

    protected $outputRequests = false;
    protected $outputResponses = false;

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

        if ($this->outputRequests) {
            $service->setOutputRequests($this->outputRequests);
        }

        if ($this->outputResponses) {
            $service->setOutputResponses($this->outputResponses);
        }

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

    /**
     * @param $output
     * @return $this
     */
    public function setOutputRequests($output)
    {
        $this->outputRequests = $output;
        return $this;
    }

    /**
     * @param $output
     * @return $this
     */
    public function setOutputResponses($output)
    {
        $this->outputResponses = $output;
        return $this;
    }
}