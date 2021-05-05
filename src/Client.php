<?php 

namespace RoiChamp;

class Client{

    const ENDPOINT = 'https://roichamp.com/api/v1/';

    protected $ch;    
    protected $warnings;
    protected $batchLimit;
    private $_token;

    public function __construct(array $config)
    {
        
        try {
            $this->_token = $config['token'];
        } catch (\Throwable $th) {
            throw $th;
        }

        $this->warnings = [];
        $this->batchLimit = 20;

    }

    public function getBatchLimit(){
        return $this->batchLimit;
    }

    public function setBatchLimit($limit){
        $this->batchLimit = $limit;
        return $this->batchLimit;
    }

    protected function endpoint($path){
        return self::ENDPOINT . $path;
    }

    protected function warning($msg){
        $this->_warnings[] = $msg;
        trigger_error($msg, E_USER_WARNING);
    }

    public function sendEmail(array $data){

        if( !isset( $data['identity'] ) ){
            $this->warning("Email identity is not defined.");            
        }

        if( !isset( $data['to'] ) ){
            $this->warning("Send to is not defined.");            
        }

        return $this->post('email/send', $data);
    }

    public function upsertProduct(array $data){

        if( !isset( $data['identity'] ) ){
            $this->warning("Identity is not defined.");            
        }

        if( !isset( $data['title'] ) ){
            $this->warning("Title is not defined.");            
        }

        return $this->post('product/upsert', $data);
    }

    public function batchUpsertProduct(array $data){
        return $this->post('product/batch-upsert', ['data' => $data]);
    }

    public function upsertCategory(array $data){

        if( !isset( $data['identity'] ) ){
            $this->warning("Identity is not defined.");            
        }

        if( !isset( $data['title'] ) ){
            $this->warning("Title is not defined.");            
        }

        return $this->post('category/upsert', $data);
    }

    public function batchUpsertCategory(array $data){
        return $this->post('category/batch-upsert', ['data' => $data]);
    }

    public function upsertSubscriber(array $data){

        if( !isset( $data['email'] ) ){
            $this->warning("Email is not defined.");            
        }

        if( filter_var($data['email'], FILTER_VALIDATE_EMAIL) === FALSE ){
            $this->warning("Email is not valid.");            
        }

        return $this->post('subscriber/upsert', $data);
    }

    public function batchUpsertSubscriber(array $data){
        return $this->post('subscriber/batch-upsert', ['data' => $data]);
    }

    public function unsubscribe($email){

        if( !isset( $email ) ){
            $this->warning("Email is not defined.");            
        }

        if( filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE ){
            $this->warning("Email is not valid.");            
        }

        return $this->post('subscriber/unsubscribe', ['email' => $email]);

    }

    public function upsertCart(array $data){

        if( !isset( $data['identity'] ) ){
            $this->warning("Identity is not defined.");            
        }

        if( !isset( $data['currency'] ) ){
            $this->warning("Currency is not defined.");            
        }

        if( isset( $data['items'] ) ){

            if( !is_array( $data['items'] ) ){
                $this->warning("Items must be array.");            
            }
    
            foreach( $data['items'] as $item ){
    
                if( !isset( $item['identity'] ) ){
                    $this->warning("Item identity is not defined.");            
                }
    
                if( !isset( $item['product_identity'] ) ){
                    $this->warning("Item product identity is not defined.");            
                }
    
            }

        }

        return $this->post('cart/upsert', $data);
    }

    public function batchUpsertCart(array $data){
        return $this->post('cart/batch-upsert', ['data' => $data]);
    }

    public function upsertOrder(array $data){

        if( !isset( $data['identity'] ) ){
            $this->warning("Identity is not defined.");            
        }

        if( !isset( $data['currency'] ) ){
            $this->warning("Currency is not defined.");            
        }

        if( !isset( $data['items'] ) ){
            $this->warning("Items is not defined.");            
        }
        else {

            if( !is_array( $data['items'] ) ){
                $this->warning("Items must be array.");            
            }
    
            foreach( $data['items'] as $item ){
    
                if( !isset( $item['identity'] ) ){
                    $this->warning("Item identity is not defined.");            
                }
    
                if( !isset( $item['product_identity'] ) ){
                    $this->warning("Item product identity is not defined.");            
                }
    
            }

        }        

        if( !isset( $data['email'] ) ){
            $this->warning("Email is not defined.");            
        }

        if( filter_var($data['email'], FILTER_VALIDATE_EMAIL) === FALSE ){
            $this->warning("Email is not valid.");            
        }

        return $this->post('order/upsert', $data);
    }

    public function batchUpsertOrder(array $data){
        return $this->post('order/batch-upsert', ['data' => $data]);
    }

    public function getCart($identity){
        return $this->get('cart/view', [
            'identity' => $identity
        ]);
    }

   
    protected function initCurl(){

        if( !$this->_token ){
            return false;
        }

        if( !$this->ch ){
            $this->ch = curl_init();
        }

        $authorization = "Authorization: Bearer ".$this->_token;
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, [$authorization]); 
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

        return true;
    }

    protected function post($path, $data){

        if(!$this->initCurl()){
            return;
        }

        curl_setopt($this->ch, CURLOPT_URL, $this->endpoint($path));
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($data));

        $response = curl_exec($this->ch);
        $statusCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        curl_reset($this->ch);

        if( $statusCode == 200 ){
            return true;
        }
        else {
            return false;
        }

    }

    protected function get($path, $data){

        if(!$this->initCurl()){
            return;
        }

        $path = $path . '?' . http_build_query($data);

        curl_setopt($this->ch, CURLOPT_URL, $this->endpoint($path));

        $response = curl_exec($this->ch);
        $statusCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        
        curl_reset($this->ch);

        if( $statusCode == 200 ){
            return json_decode($response, true);
        }
        else {
            return null;
        }

    }

    public function __destruct()
    {
        if( $this->ch ){
            curl_close($this->ch);
        }
    }


}