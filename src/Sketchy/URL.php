<?php
namespace Sketchy;

class URL extends ApiBase{

    /**
    *	Retrieve capture
    *	@param	string	$id		- ID of capture
    *	@return	array	$data	- Capture data
    *
    */
    public function retrieveCapture($id){

        $data = $this->makeGetRequest('capture/' . $id, array());

        // Check for URLs
        foreach(array('sketch', 'scrape', 'html') as $source){

        	if(!empty($data[$source . '_url'])){

        		// Add token
        		$data[$source . '_url'] .= '?token=' . $this->_token;

        	}

        }

        return $data;

    }

    /**
    *	Create new capture
    *	@param	string	$url			- URL to sketch
    *	@param	boolean $status_only	- Get status only
    *	@return	array	$response		- Response data
    */
    public function capture($url, $status_only = false){

    	// Fix URL if missing scheme, because Sketchy will outright fail without one
    	if(!preg_match("~^(?:f|ht)tps?://~i", $url)){

    		$url = 'http://' . $url;

    	}

    	return $this->makePostRequest('capture', array(
    		'json' => array(
	    		'url' => $url,
    			'status_only' => $status_only
    		)
    	));

    }

}

?>