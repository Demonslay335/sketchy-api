<?php
namespace Sketchy;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;

/**
 * Light-weight Factory to construct HTTP calls
 */
class ApiBase
{
    /**
     * @var string - Sketchy API endpoint prefix
     */
    protected $_apiUrl;

    /**
     * @var ClientInterface - http client
     */
    protected $_client;

    /**
     * @var string - Authentication token
     */
    protected $_token;

    /**
     * Constructor
     * @param string          $apiUrl
     * @param string          $token
     * @param ClientInterface $client
     */
    public function __construct($apiUrl, $token, ClientInterface $client = null) {
    	$this->_apiUrl = $apiUrl;
    	$this->_token = $token;

        if ( empty( $client) ) {
            $this->_client = new Client(array(
                'base_uri' => $this->_apiUrl,
            	'headers' => array(
            		'Token' => $this->_token
            	)
            ));
        }
    }

    protected function to_json($response) {
        $jsonified_response = json_decode($response->getBody(), true);
        return $jsonified_response;
    }

    /**
     * Util function to make post request
     * @param string          $endpoint
     * @param array           $params
     * @return (?)
     * @see Client
     * @throw \Sketchy\Exceptions\NotAuthorizedException
     * @throw \Sketchy\Exceptions\TaskNotFoundException
     */
    protected function makePostRequest($endpoint, array $params) {
        try {
            $response = $this->_client->post($endpoint, $params);
            $this->validateResponse($response->getStatusCode());
            return $this->to_json($response);
        } catch(ClientException $e) {
            $this->validateResponse($e->getResponse()->getStatusCode());
        }
    }


    /**
     * Util function to make get request
     * @param string          $endpoint
     * @param array           $params
     * @return (?)
     * @throw \Sketchy\Exceptions\NotAuthorizedException
     * @throw \Sketchy\Exceptions\TaskNotFoundException
     */
    protected function makeGetRequest($endpoint, array $params) {
        // Constructs get url
        // e.g:
        // endpoint = 'ip-address/report'
        //
        // params => array(
        //                'ip'       => '192.168.2.1',
        //                'apikey'   => 'supersecureapikey'
        //            )
        //
        // It maps to:
        // <apiUrl>/ip-address/report?ip=192.168.2.1&apikey=supersecureapikey
        try {
            $url = $this->_apiUrl . $endpoint . '?'. http_build_query($params);
            $response = $this->_client->get($url);
            $this->validateResponse($response->getStatusCode());
            return $this->to_json($response);
        } catch(ClientException $e) {
            $this->validateResponse($e->getResponse()->getStatusCode());
        }
    }

    /**
     * Validate response by looking up the http status code
     * @param int $statusCode - http status code
	 * @throw \Sketchy\Exceptions\NotAuthorizedException
     * @throw \Sketchy\Exceptions\TaskNotFoundException
     */
    protected function validateResponse($statusCode) {
        switch($statusCode) {
        	case 401:
        		throw new Exceptions\NotAuthorizedException;
            case 404:
            	throw new Exceptions\TaskNotFoundException;
            default:
                return;
        }
    }
}

?>
