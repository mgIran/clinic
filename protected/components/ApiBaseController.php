<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 *
 * @property Users $user
 */
class ApiBaseController extends Controller
{
    public $user;

    private $_client_id = 'UiRgEQt91vL60-8pixMTkxplOB-jAplL24rdfgDFgS6RTSf6eBGuFZ8ckmLXFT0nBRrz_6C5rGbmmY2f';
    private $_client_secret = 'huHxZTH6EZ_3Y8b71wcFetW34aFGc2P1x2H_yHVh9-uxB5lbcF12ds81mM8SB5IDWGZGpasd211';

    protected function _sendResponse($status = 200, $body = '', $content_type = 'text/html')
    {
        // set the status
        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
        header($status_header);
        // and the content type
        header('Content-type: ' . $content_type);

        // pages with body are easy
        if($body != ''){
            // send the body
            echo $body;
        } // we need to create the body if none is passed
        else{
            // create some body messages
            $message = '';

            // this is purely optional, but makes the pages a little nicer to read
            // for your users.  Since you won't likely send a lot of different status codes,
            // this also shouldn't be too ponderous to maintain
            switch($status){
                case 401:
                    $message = 'You must send token for authorized to view this page.';
                    break;
                case 404:
                    $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                    break;
                case 500:
                    $message = 'The server encountered an error processing your request.';
                    break;
                case 501:
                    $message = 'The requested method is not implemented.';
                    break;
            }

            // servers don't always have a signature turned on
            // (this is an apache directive "ServerSignature On")
            $signature = ($_SERVER['SERVER_SIGNATURE'] == '')?$_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT']:$_SERVER['SERVER_SIGNATURE'];

            // this should be templated in a real-world solution
            $body = '
				<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
				<html>
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
					<title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
				</head>
				<body>
					<h1>' . $this->_getStatusCodeMessage($status) . '</h1>
					<p>' . $message . '</p>
					<hr />
					<address>' . $signature . '</address>
				</body>
				</html>';

            echo $body;
        }
        Yii::app()->end();
    }

    protected function _getStatusCodeMessage($status)
    {
        // these could be stored in a .ini file and loaded
        // via parse_ini_file()... however, this will suffice
        // for an example
        $codes = Array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        );
        return (isset($codes[$status]))?$codes[$status]:'';
    }

    /**
     * The filter method for 'restAccessControl' filter.
     * This filter throws an exception (CHttpException with code 400) if the applied action is receiving a non-AJAX request.
     * @param CFilterChain $filterChain the filter chain that the filter is on.
     * @throws CHttpException if the current request is not an AJAX request.
     */
    public function filterRestAccessControl($filterChain)
    {
        if($this->_checkRest())
            $filterChain->run();
        else
            $this->_sendResponse(401, CJSON::encode(['status' => false, 'message' => 'Client ID Or Client Secret is invalid.']), 'application/json');
    }

    protected function _checkRest()
    {
        if(isset($_SERVER['PHP_AUTH_USER']) and isset($_SERVER['PHP_AUTH_PW']) and
            $_SERVER['PHP_AUTH_USER'] == $this->_client_id and $_SERVER['PHP_AUTH_PW'] == $this->_client_secret
        )
            return true;
        return false;
    }

    /**
     * The filter method for 'restAccessControl' filter.
     * This filter throws an exception (CHttpException with code 400) if the applied action is receiving a non-AJAX request.
     * @param CFilterChain $filterChain the filter chain that the filter is on.
     * @throws CHttpException if the current request is not an AJAX request.
     */
    public function filterRestAuthControl($filterChain)
    {
        if($this->_checkAuth())
            $filterChain->run();
        else
            $this->_sendResponse(401, ['status' => false, 'message' => 'Client ID Or Client Secret is invalid.']);
    }

    /**
     * @return bool
     */
    protected function _checkAuth()
    {
        if(function_exists('getallheaders') && !isset(getallheaders()['Authorization'])){
            $this->_sendResponse(401, CJSON::encode(['status' => false,
                'code' => 101,
                'message' => 'Access Token not sent.']), 'application/json');
        }
        $authorization = function_exists('getallheaders') && getallheaders()['Authorization']?getallheaders()['Authorization']:$_SERVER['HTTP_X_AUTHORIZATION'];
        if(strpos($authorization, 'Bearer') === false){
            $this->_sendResponse(401, CJSON::encode(['status' => false,
                'code' => 102,
                'message' => 'Access Token is invalid. Please Authorize again.']), 'application/json');
        }
        $access_token = str_ireplace('Bearer ', '', $authorization);
        if(!$access_token)
            $this->_sendResponse(401, CJSON::encode(['status' => false,
                'code' => 103,
                'message' => 'Access Token is invalid. Please Authorize again.']), 'application/json');
        // Find the api token
        $token = Yii::app()->JWT->decode($access_token);
        if(!$token)
            $this->_sendResponse(401, CJSON::encode(['status' => false,
                'code' => 104,
                'message' => 'Access Token is invalid. Please Authorize again.']), 'application/json');
        if(!$token->session_id)
            $this->_sendResponse(401, CJSON::encode(['status' => false,
                'code' => 104,
                'message' => 'Access Token is invalid. Please Authorize again.']), 'application/json');

        $session = Sessions::model()->findByPk($token->session_id);
        if(!$session)
            $this->_sendResponse(401, CJSON::encode(['status' => false,
                'code' => 104,
                'message' => 'Access Token is invalid or revoked. Please Authorize again.']), 'application/json');
        if(!$session->user)
            $this->_sendResponse(401, CJSON::encode(['status' => false,
                'code' => 104,
                'message' => 'Access Token is invalid. Please Authorize again.']), 'application/json');
        if($session->expire < time())
            $this->_sendResponse(401, CJSON::encode(['status' => false,
                'code' => 105,
                'message' => 'Access Token has expired in ' . date('Y/m/d H:i', $session->expire) . '. if you have refresh token, get new access token.']), 'application/json');
        if($session->user_type == 'user')
            $this->user = $session->user;
        if($session->user_type == 'admin')
            $this->user = $session->admin;
        return true;
    }

    /**
     * return array of sent parameters
     *
     * @return string
     */
    protected function getRequest()
    {
        return CJSON::decode(file_get_contents('php://input'));
    }
}