<?php namespace Services;

use \RedBeanPHP\R as R;

abstract class Service {}

abstract class ServiceOrder extends Service {
    
    protected function _update_create(object $order, array $items): array
    {
        R::begin();
        try {
            if($order->id){
                $items_orders  = R::find( 'items_orders', 'orders_id = ?', [$order->id]);
                R::trashAll( $items_orders );
            }
            foreach($items as $item){
                $item_instance = R::load('items', $item);
                $order->sharedTagList[] = $item_instance;
            }
            R::store($order);
            R::commit();
        } catch (Exception $e){
            R::rollback();
            throw new \CustomExceptions\DatabaseCreateOrderError($e->getMessage());
        }
        sort($items);
        $result = array(
                "order_id" => $order->order_id,
                "items" => $items,
                "done" => False
            );
        return $result;
    }
    
    protected function _get_order(string $order_id, int $done=0): object
    {
        $params = [$order_id];
        if($done == 0){
            $query_filters = 'WHERE order_id = ? AND done = ?';
            $params[] = 0; 
        } else {
            $query_filters = 'WHERE order_id = ?';
        }
        $order = R::findOne('orders', $query_filters, $params);
        if(!$order){
            throw new \CustomExceptions\OrderNotFound('Order Not Found');
        }
        return $order;
    }
    
    protected function _order_items(object $order): array
    {
        $items = [];
        foreach($order->sharedItems as $item){
            $items[] = (int) $item->id;
        }
        sort($items);
        return $items;
    }
    
}


class ServiceOrderUpdate extends ServiceOrder {
    
    private function _merge_items(object $order, array $items_new): array
    {
        $items_old = [];
        foreach($order->sharedItems as $item_instance){
            $items_old[] = (int) $item_instance->id;
        }
        foreach($items_new as $item){
            if(in_array($item, $items_old)){
                throw new \CustomExceptions\DuplicateItemOrder('Duplicate item in order');
            }
        }
        $items = array_merge($items_new, $items_old);
        return $items;
    }
    
    /*
     *Изменяет заказ - добавляет записи к заказу
     */
    public function update(string $order_id, array $items_new){
        $order = $this->_get_order($order_id);
        $items = $this->_merge_items($order, $items_new);
        return $this->_update_create($order, $items);
    }
    
}

class ServiceOrdersUpdateDone extends ServiceOrder {
    
    /*
     *Изменяет заказ - меняет статус на done
     */
    public function update(string $order_id): array
    {
        $order = $this->_get_order($order_id);
        $order->done = 1;
        R::store($order);
        $items = $this->_order_items($order);
        $result = array(
            "order_id" => $order->order_id,
            "items" => $items,
            "done" => True
            );
        return $result;
    }
    
}


class ServiceOrdersCreate extends ServiceOrder {
    
    private function _generate_order_id(): string
    {
        return uniqid();
    }
    
    private function _create(array $items): array
    {
        $order_id = $this->_generate_order_id();
        $order = R::dispense('orders');
        $order->order_id = $order_id;
        $order->done = 0;
        return $this->_update_create($order, $items);
    }
    
    private function _find_items(array $items_in_input): bool
    {
        $items_in_db = R::count( 'items', 'WHERE id IN ('.R::GenSlots($items_in_input).')', $items_in_input);
        if($items_in_db != count($items_in_input)){
            throw new \CustomExceptions\ItemsNotFound('Items not found');
        }
        return True;
    }
    
    /*
     *Создаёт один заказ
     */
    public function create(array $items): array
    {
        $this->_find_items($items);
        return $this->_create($items);
    }
    
}

class ServiceOrdersGetOne extends ServiceOrder {
    
    /*
     *Получает статус одного заказа
     */
    public function get_one(string $order_id): array
    {
        $order = $this->_get_order($order_id);
        $items = $this->_order_items($order);
        $result = array("order_id" => $order->order_id,
                        "items" => $items,
                        "done" => (bool) $order->done);
        return  $result;
    }
    
}

class ServiceOrdersGetAll extends Service {
    
    /*
     *Получает список всех заказов
     */
    public function get_all(int $done = null): array
    {
        $query_filters = 'SELECT `order_id`, `done` FROM `orders`';
        $params = [];
        if($done !== null){
            $query_filters = $query_filters.' WHERE `done` = ?';
            $params[] = $done;
        }
        $orders = R::getAll($query_filters, $params);
        $result = [];
        foreach($orders as $order){
            $result[] = array("order_id" => $order['order_id'],
                              "done" => (bool) $order['done']);
        }
        return  $result;
    }
}

?>