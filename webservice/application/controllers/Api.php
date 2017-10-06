<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

/**
 * Webservice API Fipe consulta
 *
 * @category Api
 * @package  ApiFipe
 * @author   Adão Duque <adaoduquesn@gmail.com>
 * @license  http://opensource.org/licenses/MIT	MIT License
 * @link     https://github.com/adaoduque/dicascodeigniter/apifipe
 */
class Api extends REST_Controller
{
    //Secret key
    private static $_SECRET_KEY = "pMohCvX251LkZAxvyLyezMveJdQvLR810qwga4M2gykQza3fR5i6xuNjBaYY";

    /**
     * Construct
     */
    public function __construct() 
    {
        parent::__construct();
    }

    /**
     * Get token to authentication application
     *
     * @link   api/token
     * @method GET
     * @return json object
     */
    public function token_get()
    {
        //Initialize array
        $tokenData = array();

        //Set token secret key
        $tokenData['secret_key']  =  self::$_SECRET_KEY;

        //Make token and get it
        $output['token']      =  AUTHORIZATION::generateToken($tokenData);

        //Return response
        $this->set_response($output, REST_Controller::HTTP_OK);        
    }

    /**
     * Get last table from consult fipe table
     *
     * @link   api/tabela
     * @method GET
     * @return json object
     */    
    public function tabela_get()
    {
        //Checks if requisition is authenticated by token
        if ($this->authenticate()) {

            //Load library api fipe
            $this->load->library('api_fipe');

            //Get code for type consult table (Carro, moto ou caminhão)
            $code   =  $this->get('code');            

            //Check if code is 1, 2 or 3
            if ($code > 0 && $code < 4) {            

                //Get table
                $table  =  $this->api_fipe->getLastOptionTable($code);

                //Check if code is 1, 2 or 3, and validade brand code
                if (!is_bool($table)) {

                    $response  =  array('table' => $table);             

                    //Return response
                    $this->set_response($response, REST_Controller::HTTP_OK);
                    return;

                }
            }

        }

        //Not authenticated, send HTTP_UNAUTHORIZED 
        $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
    }    

    /**
     * Obtem as marcas dos veículos
     *
     * @link   api/marcas
     * @method POST
     * @return array json
     */
    public function marcas_post()
    {
        //Checks if requisition is authenticated by token
        if ($this->authenticate()) {

            //Load library api fipe
            $this->load->library('api_fipe');
            
            //Get code for type consult table (Carro, moto ou caminhão)
            $code   =  $this->input->post('code');
            
            //Get code table
            $tableCode  =  $this->input->post('tablecode'); 

            //Check if code is 1, 2 or 3
            if ($code > 0 && $code < 4 && !is_bool($tableCode)) {

                //Parameters to send in post
                $param   =  array( 
                                'codigoTabelaReferencia' => $tableCode, 
                                'codigoTipoVeiculo'      => $code 
                            );

                //Serialize data to send in post
                $stringParam  =  $this->urlify($param);

                //Get brands
                $data      =  $this->api_fipe->getBrands($stringParam);

                $data      =  json_decode($data);

                $response  =  array();
                
                $i = 0;

                foreach ($data as $key) {
                    $response[$i]['label']  =  $key->Label;
                    $response[$i]['value']  =  $key->Value;
                    $i++;
                }

                //Return response
                $this->set_response($response, REST_Controller::HTTP_OK);
                return;

            }

        }

        //Not authenticated, send HTTP_UNAUTHORIZED 
        $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
    }

    /**
     * Obtem os modelos da marca selecionada
     *
     * @link   api/modelos
     * @method POST
     * @return array json
     */    
    public function modelos_post()
    {
        //Checks if requisition is authenticated by token
        if ($this->authenticate()) {

            //Load library api fipe
            $this->load->library('api_fipe');

            //Get code table
            $tableCode  =  $this->input->post('tablecode');            
            
            //Get code for type consult table (Carro, moto ou caminhão)
            $code       =  $this->input->post('code');

            //Get brand code
            $brandCode  =  $this->input->post('brandcode');

            //Check if code is 1, 2 or 3, and validade brand code
            if ($code > 0 && $code < 4 && $tableCode > 0 && strlen(trim($brandCode)) > 0) {

                //Parameters to send in post
                $param   =  array( 
                                'codigoTabelaReferencia' =>  $tableCode, 
                                'codigoTipoVeiculo'      =>  $code,
                                'codigoMarca'            =>  $brandCode
                            );

                //Serialize data to send in post
                $stringParam  =  $this->urlify($param);

                //Get models of brand code
                $data      =  $this->api_fipe->getModels($stringParam);                

                $data      =  json_decode($data);

                $response  =  array();
                
                $i = 0;

                foreach ($data->Modelos as $key) {
                    $response[$i]['label']  =  $key->Label;
                    $response[$i]['value']  =  $key->Value;
                    $i++;
                }                

                //Return response
                $this->set_response($response, REST_Controller::HTTP_OK);
                return;

            }

        }

        //Not authenticated, send HTTP_UNAUTHORIZED 
        $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
    }

    /**
     * Obtem o ano/modelo do veículo selecionado
     *
     * @link   api/anomodelo
     * @method POST
     * @return array json
     */     
    public function anomodelo_post()
    {
        //Checks if requisition is authenticated by token
        if ($this->authenticate()) {

            //Load library api fipe
            $this->load->library('api_fipe');

            //Get code table
            $tableCode  =  $this->input->post('tablecode');             
            
            //Get code for type consult table (Carro, moto ou caminhão)
            $code       =  $this->input->post('code');

            //Get brand code
            $brandCode  =  $this->input->post('brandcode');
        
            //Get model code
            $modelCode  =  $this->input->post('modelcode');           

            //Check if code is 1, 2 or 3, and validade brand code
            if ($code > 0 && $code < 4 && $tableCode > 0 && strlen(trim($brandCode)) > 0 && strlen(trim($modelCode)) > 0) {

                //Parameters to send in post
                $param   =  array( 
                                'codigoTabelaReferencia' =>  $tableCode, 
                                'codigoTipoVeiculo'      =>  $code,
                                'codigoMarca'            =>  $brandCode,
                                'codigoModelo'           =>  $modelCode
                            );

                //Serialize data to send in post
                $stringParam  =  $this->urlify($param);

                //Get models of brand code
                $data   =  $this->api_fipe->getYearModels($stringParam);

                $data      =  json_decode($data);

                $response  =  array();
                
                $i = 0;

                foreach ($data as $key) {
                    $response[$i]['label']  =  $key->Label;
                    $response[$i]['value']  =  $key->Value;
                    $i++;
                }                

                //Return response
                $this->set_response($response, REST_Controller::HTTP_OK);
                
                return;

            }

        }

        //Not authenticated, send HTTP_UNAUTHORIZED 
        $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
    }

    /**
     * Get all info for vehicle
     * 
     * @link   api/info
     * @method POST
     * @return json object
     */
    public function info_post()
    {
        //Checks if requisition is authenticated by token
        if ($this->authenticate()) {

            //Load library api fipe
            $this->load->library('api_fipe');

            //Get code table
            $tableCode  =  $this->input->post('tablecode');             
            
            //Get code for type consult table (Carro, moto ou caminhão)
            $code       =  $this->input->post('code');

            //Get brand code
            $brandCode  =  $this->input->post('brandcode');
        
            //Get model code
            $modelCode  =  $this->input->post('modelcode');

            //Get year
            $year  =  $this->input->post('year');

            //Check if code is 1, 2 or 3, and validade brand code
            if ($code > 0 && $code < 4 && $tableCode > 0 && strlen(trim($brandCode)) > 0 && strlen(trim($modelCode)) > 0 && strlen(trim($year)) == 6) {

                //Code type fuel
                $codeTypeFuel  =  substr($year, -1);

                //Format year model vehicle
                $year  =  substr($year, 0, -2);

                //Check type
                switch ( $code ) {
                case 1:
                    //Car
                    $typeVehicle = 'carro';
                    break;
                case 2:
                    //moto
                    $typeVehicle = 'moto';
                    break;
                case 3:
                    //Truck
                    $typeVehicle = 'caminhao';
                    break;
                }                 

                //Parameters to send in post
                $param   =  array(
                                'tipoConsulta'           =>  "tradicional",
                                'codigoTabelaReferencia' =>  $tableCode, 
                                'codigoTipoVeiculo'      =>  $code,
                                'codigoMarca'            =>  $brandCode,
                                'codigoModelo'           =>  $modelCode,
                                'tipoVeiculo'            =>  $typeVehicle,
                                'codigoTipoCombustivel'  =>  $codeTypeFuel,
                                'anoModelo'              =>  $year
                            );

                //Serialize data to send in post
                $stringParam  =  $this->urlify($param);

                //Get models of brand code
                $data   =  $this->api_fipe->getInfoGeneral($stringParam);

                //Return response
                $this->set_response($data, REST_Controller::HTTP_OK);
                
                return;
            }

        }

        //Not authenticated, send HTTP_UNAUTHORIZED 
        $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
    }

    /**
     * Verify and validate token to authenticate aplication
     *
     * @return bool
     */
    protected function authenticate() 
    {
        //Get all headers
        $headers = $this->input->request_headers();

        //Checks if header Authorization exists and it not empty
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            
            //Decode token
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            
            //var_dump( $decodedToken->secret_key == self::$_SECRET_KEY ); exit;

            //Check if token is decoded
            if ($decodedToken != false) {

                //Checks if secret key is equal with constant
                if ($decodedToken->secret_key == self::$_SECRET_KEY) {
                    return true;
                }
                
            }
        }
        return false;
    }
    
    /**
     * Serealize data to url-ify
     *
     * @param ARRAY $data - Array with key and value
     * 
     * @return string
     */
    public function urlify( $data = array() )
    {

        //Init $post
        $post = '';

        //url-ify the data for the POST
        foreach ($data as $key => $value) {
            $post .= $key . '=' . $value . '&';
        }

        //Remove last &
        $post = substr($post, 0, -1);

        //Return
        return $post;
    }    
}