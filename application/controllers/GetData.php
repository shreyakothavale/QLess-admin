<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class GetData extends Admin_Controller
{
    public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

    }
    
    public function index()
    {
        
    }

    public function getProducts()
	{
		$result = array('data' => array());

        $sql = "SELECT id,name,sku,image,description,brand_id FROM products ORDER BY id DESC";
        $query = $this->db->query($sql);
        $data = $query->result_array();

        foreach ($data as $key => $value) {

            $brand_id = $value['brand_id'];
            $brand_id = substr($brand_id,2,-2);

            $sql = "SELECT name FROM brands WHERE id = $brand_id";
            $query = $this->db->query($sql);
            $brand = $query->result_array();

            // Removing brand_id from data
            array_pop($data[$key]);

            $data[$key]['brand'] = $brand[0]['name'];

			// $result['data'][$key] = array(
			// 	$value['id'],
            //     $value['name'],
            //     $value['sku'],
            //     $value['image'],
            //     $brand[0],
			// 	$value['description'],
			// );
		} // /foreach

		echo json_encode($data,JSON_FORCE_OBJECT);
	}

    public function getCustomers()
	{
		$result = array('data' => array());

        $sql = "SELECT * FROM customers ORDER BY id ";
        $query = $this->db->query($sql);
        $data = $query->result_array();

		echo json_encode($data,JSON_FORCE_OBJECT);
	}

    public function getOrders()
	{
		$result = array();

		//$data = $this->model_orders->getOrdersData();
        $sql = "SELECT id,customer_name,customer_phone as contact,gross_amount as amount FROM orders ORDER BY id DESC";
        $query = $this->db->query($sql);
        $data = $query->result_array();

        $sql = "SELECT DISTINCT order_id FROM orders_item";
        $query = $this->db->query($sql);
        $order_ids = $query->result_array();
        
        foreach ($order_ids as $key => $value) {
            
            $id = $value['order_id'];
            $sql = "SELECT product_id FROM orders_item WHERE order_id = $id";
            $query = $this->db->query($sql);
            $products_id = $query->result_array();
            //echo json_encode($products_id,JSON_FORCE_OBJECT);
            $list = array();
            foreach ($products_id as $k => $v) {
                
                $id = $v['product_id'];
                $sql = "SELECT name AS product_name from products WHERE id = $id";
                $query = $this->db->query($sql);
                $products = $query->result_array();
                array_push($list,$products[0]['product_name']);
            }
            array_push($result,$list);
        }
        echo json_encode($result,JSON_FORCE_OBJECT);
		// echo json_encode($data,JSON_FORCE_OBJECT);
	}
}