<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Euphoria extends Admin_Controller
{
    public function __construct()
	{
		parent::__construct();
    }
    
    public function index()
    {
        
    }

    public function products()
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
            
		}

		echo json_encode($data);
	}

    public function customers()
	{
		$result = array('data' => array());

        $sql = "SELECT * FROM customers ORDER BY id ";
        $query = $this->db->query($sql);
        $data = $query->result_array();

		echo json_encode($data);
	}

    public function orders()
	{
		$result = array();

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
        
        echo json_encode($result);
	}
}