<?php

namespace TGHP\RealpageClient\Service;

use \SoapClient;
use \SoapFault;
use TGHP\RealpageClient\Auth;
use TGHP\RealpageClient\RPXApplicationFault;

abstract class AbstractService
{

    /** @var Auth */
    protected $auth;

    /** @var SoapClient */
    protected $soapClient;

    protected $outputRequests = false;
    protected $outputResponses = false;

    public function __construct($serviceUrl)
    {
        $soapOpts = ['trace' => 1, 'exceptions' => true];


        $this->soapClient = new SoapClient("$serviceUrl?singleWsdl", $soapOpts);
    }

    public function authenticate(Auth $auth)
    {
        $this->auth = $auth;
    }

    protected function call($function, $responseKey, $arguments = [])
    {
        $soapArguments = [
            'parameters' => array_merge([
                'auth' => [
                    'pmcid' => $this->auth->pmcId,
                    'siteid' => $this->auth->siteId,
                    'username' => $this->auth->username,
                    'password' => $this->auth->password,
                    'licensekey' => $this->auth->licenseKey,
                    'system' => $this->auth->system,
                ]
            ], $arguments)
        ];

        try {
            $response = $this->soapClient->__soapCall($function, (array) $soapArguments);

            if ($this->outputRequests) {
                echo "[REQUEST]" . PHP_EOL;
                echo trim($this->soapClient->__getLastRequestHeaders()) . PHP_EOL;
                echo '---' . PHP_EOL;
                echo trim($this->soapClient->__getLastRequest()) . PHP_EOL;
                echo "[END REQUEST]" . PHP_EOL;
            }

            if ($this->outputResponses) {
                echo "[RESPONSE]" . PHP_EOL;
                echo trim($this->soapClient->__getLastResponseHeaders()) . PHP_EOL;
                echo '---' . PHP_EOL;
                echo trim($this->soapClient->__getLastResponse()) . PHP_EOL;
                echo "[END RESPONSE]" . PHP_EOL;
            }

            $xml = $response->$responseKey->any;

            // Create a sabre XML service, where we will have full control of interpretation of keys/values
            $service = new \Sabre\Xml\Service();
            // Add the class powered element map to the service
            $service->elementMap = $this->getElementMap();

            // Parse the response using the XML service
            $response = $service->parse($xml);

            if($response) {
                // Convert bit true/false values to booleans
                array_walk_recursive($response, function (&$item, $key) {
                    if (preg_match('/[Bb]it$/', $key)) {
                        $item = ($item === 'true' ? true : false);
                    }
                });
            }
        } catch (SoapFault $e) {
            if(property_exists($e, 'detail') && property_exists($e->detail, 'RPXApplicationFault')) {
                throw new RPXApplicationFault(
                    $e->detail->RPXApplicationFault->CustomMessage . PHP_EOL . $e->detail->RPXApplicationFault->ExceptionMessage,
                    $e->detail->RPXApplicationFault->MessageLogId
                );
            } else {
                throw $e;
            }
        }

        return $response;
    }

    protected function getElementMap()
    {
        return [];
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