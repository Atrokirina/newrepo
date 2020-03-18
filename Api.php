<?php

define('X_ACCESS_TOKEN','1234');
require 'main.php';
class Api{
    public $name = '';
    public $db = '';
    public $supportedMethods = array();
    protected $requestAction = '';

    public $requestUri = [];
    public $requestParams = [];

    public function __construct($name, $db, $supportedMethods = array('GET','POST')){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
        // header("Access-Control-Allow-Credentials: false");
        header("Content-Type: application/json");
        $this->db = $db;
        $this->name = $name;
        $this->supportedMethods = $supportedMethods;
        $this->requestAction = $_SERVER['PATH_INFO'];
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->actionMethods = array(
            'kekadd' => 'GET',
            'kekdel' => 'POST',
            'kekupdate' => 'GET',
            'kekshow' => 'POST'
        );
        $this->requestUri = explode('/', trim($_SERVER['REQUEST_URI'],'/'));
        $this->requestParams = $_REQUEST;
    }

    public function run(){
        if($this->checkHeaderToken()){
            if(in_array($this->requestMethod, $this->supportedMethods)){
                switch($this->requestAction){
                    case '/kekadd':
                        $this->kekadd($this->requestMethod, $this->requestParams);
                    break;
                    case '/kekdel':
                        $this->kekdel($this->requestMethod, $this->requestParams);
                    break;
                    case '/kekupdate':
                        $this->kekupdate($this->requestMethod, $this->requestParams);
                    break;
                    case '/kekshow':
                        $this->kekshow($this->requestMethod, $this->requestParams);
                    break;
                };
            }else{
                $this->response(405, 'unsupported method for this API');
            }
        }
    }
    public function response($statusCode = 500, $description, $data=null){
        if($statusCode > 299){
            $response = array(
                'success' => false,
                'status' => $statusCode ,
                'description' => $this->getDescriptionStatusCode($statusCode) . ': ' . $description
            );
        }else{
            $response = array(
                'success' => true,
                'status' => $statusCode ,
                'description' => $this->getDescriptionStatusCode($statusCode),
                'response' => array($data)
            );
        }
        http_response_code($statusCode);
        echo json_encode($response);
    }
    public function getDescriptionStatusCode($status){
        $descriptions = array(403 => 'Forbidden', 500 => 'Internal Server Error', 404 => 'Not Found', 405 => 'Method Not Allowed', 200 => 'OK!', 400 => 'Bad Request');
        return $descriptions[$status];
    }

    private function checkHeaderToken(){
        $headers = getallheaders();
        if(array_key_exists('X-Access-Token', $headers)){
           if(!strcmp($headers['X-Access-Token'], X_ACCESS_TOKEN)){
               return true;
           }else{
               $this->response(403, 'Invalid X-Access-Token');
           }
        }else{
            $this->response(400, 'X-Access-Token hasn`t found');
            return false;
        }
    }
    //methods
    private function kekadd($method, $request){
        if(!strcmp($this->actionMethods['kekadd'], $method)){
            $this->response(200,'OK!',$request);
            $this->db->adding($request);
        }else{
            $this->response(405,'invalid method for this action');
        }
    }
    private function kekdel($method, $request){
        if(!strcmp($this->actionMethods['kekdel'], $method)){
            $this->response(200,'OK!',$request);
            $this->db->deleting($request);
        }else{
            $this->response(405,'invalid method for this action');
        }
    }
    private function kekupdate($method, $request){
        if(!strcmp($this->actionMethods['kekupdate'], $method)){
            $this->response(200,'OK!',$request);
            $this->db->updating($request);
        }else{
            $this->response(405,'invalid method for this action');
        }
    }
    private function kekshow($method, $request){
        if(!strcmp($this->actionMethods['kekshow'], $method)){
            $this->response(200,'OK!',$this->db->showing($request));
        }else{
            $this->response(405,'invalid method for this action');
        }
    }
}

$api = new Api('users',$articles);
$api->run();
?>
