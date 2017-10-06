<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Teste Webservice API Fipe consulta
 *
 * @category Api
 * @package  ApiFipe
 * @author   Adão Duque <adaoduquesn@gmail.com>
 * @license  http://opensource.org/licenses/MIT	MIT License
 * @link     https://github.com/adaoduque/dicascodeigniter/apifipe
 */
class Teste extends CI_Controller
{
    /**
     * Construct
     */
    public function __construct() 
    {
        parent::__construct();
    }

    /**
     * Page initial
     * 
     * @return void
     */    
    public function index()
    {
        $this->load->view('teste');
    }
}