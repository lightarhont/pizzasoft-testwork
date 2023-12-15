<?php namespace Controllers;

abstract class Controller {
    
    public function __construct(){
        new \Configs\Configs;
    }
    
    //Принимает json c POST запроса
    protected function get_json(): array
    {
        return json_decode(file_get_contents('php://input'), true);
    }
    
    //Возвращает итоговвый json который отдаст в АПИ
    protected function as_json(array $result): string
    {
        return json_encode($result);
    }
    
    protected function _verify_header(): bool
    {
        $headers = array();
        foreach($_SERVER as $key => $value) {
            if (substr($key, 0, 5) <> 'HTTP_') {
                continue;
            }
            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }
        if(!(array_key_exists('X-Auth-Key', $headers) && $headers['X-Auth-Key'] == $_ENV['XAuthKey'])){
            throw new \CustomExceptions\AuthenticationFailed('Header Key Authentication Failed');
        }
        return True;
    }
    
    protected function _validator(array $data): array {
        $msg = 'Not valid input data';
        if(!array_key_exists('items', $data)){
            throw new \CustomExceptions\InputDataNotValid($msg);
        }
        elseif(count(array_unique($data['items'])) != count($data['items'])){
            throw new \CustomExceptions\InputDataNotValid($msg);
        }
        else {
            $items = $data['items'];
            foreach($items as $k => $item){
                if(!is_numeric($item) || $item == 0)
                {
                    throw new \CustomExceptions\InputDataNotValid($msg);
                }
            }
        }
        return $items;
    }

}

class ControllerOrderUpdate extends Controller {
    
    /*
     *Изменяет заказ - добавляет записи к заказу
     */
    public function post(string $order_id): bool
    {
        $service_orders_instanse = new \Services\ServiceOrderUpdate();
        try {
            $items = $this->_validator($this->get_json());
            $response = $service_orders_instanse->update($order_id, $items);
        }
        catch (\CustomExceptions\InputDataNotValid $e) {
            http_response_code(400);
            $response = $e->error_response();
        }
        catch (\CustomExceptions\ItemsNotFound $e){
            http_response_code(400);
            $response = $e->error_response();
        }
        catch (\CustomExceptions\OrderNotFound $e){
            http_response_code(400);
            $response = $e->error_response();
        }
        echo $this->as_json($response);
        return True;
    }
    
}


class ControllerOrderCreate extends Controller {
    
    /*
     *Создаёт один заказ
     */
    public function post(): bool
    {
        $service_orders_instanse = new \Services\ServiceOrdersCreate();
        try {
            $items = $this->_validator($this->get_json());
            $response = $service_orders_instanse->create($items);
        }
        catch (\CustomExceptions\InputDataNotValid $e) {
            http_response_code(400);
            $response = $e->error_response();
        }
        catch (\CustomExceptions\ItemsNotFound $e){
            http_response_code(400);
            $response = $e->error_response();
        }
        echo $this->as_json($response);
        return True;
    }
}

class ControllerOrderSetDone extends Controller {
    
    /*
     *Изменяет заказ - меняет статус на done
     */
    public function post(string $order_id): bool
    {
        $service_orders_instanse = new \Services\ServiceOrdersUpdateDone();
        try {
            $this->_verify_header();
            $response = $service_orders_instanse->update($order_id);
        } catch (\CustomExceptions\OrderNotFound $e){
            http_response_code(400);
            $response = $e->error_response();
        } catch (\CustomExceptions\AuthenticationFailed $e){
            http_response_code(400);
            $response = $e->error_response();
        }
        echo $this->as_json($response);
        return True;
    }
}

class ControllerOrderGetOne extends Controller {
    
    /*
     *Получает статус одного заказа
     */
    public function get(string $order_id): bool
    {
        $service_orders_instanse = new \Services\ServiceOrdersGetOne();
        try {
            $response = $service_orders_instanse->get_one($order_id);
        } catch (\CustomExceptions\OrderNotFound $e){
            http_response_code(400);
            $response = $e->error_response();
        } 
        echo $this->as_json($response);
        return True;
    }
    
}

class ControllerOrderGetAll extends Controller {
    
    /*
     *Получает список всех заказов
     */
    public function get(): bool
    {
        if(array_key_exists('done', $_GET)){
            $done = (int) $_GET['done'];
        } else {
            $done = null;
        }
        $service_orders_instanse = new \Services\ServiceOrdersGetAll();
        try {
            $this->_verify_header();
            $response = $service_orders_instanse->get_all($done); 
        } catch (\CustomExceptions\AuthenticationFailed $e){
            http_response_code(400);
            $response = $e->error_response();
        }
        echo $this->as_json($response);
        return True;
    }
    
}

?>
