<?php namespace Configs;

use \RedBeanPHP\R as R;

class Configs {
    
    public function __construct() {
        $this->load_config_file();
    }
    
    private function create_db_connection(){
        R::setup(
                 'mysql:host='.$_ENV['DB_HOST'].';dbname='.$_ENV['DB_NAME'],
                 $_ENV['DB_USER'],
                 $_ENV['DB_PASSWORD']
                 );
    }
    
    private function load_config_file() {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__.'/../');
        $dotenv->load();
        $this->create_db_connection();
    }
    
    public static function getInstance() {
        static $instance = array();
        if(!$instance) {
            $instance[0] = new Config();
        }
        return $instance[0];
    }
}


?>