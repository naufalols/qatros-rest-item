<?php
require APPPATH . 'libraries/REST_Controller.php';
class Item extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    // 1. get all items
    // 2. get items by code or name
    // 3. get single item by code
    public function index_get()
    {
        $id = $this->get('id');
        $title = $this->get('title');
        // $combine = array('id' => $id, 'title' => $title );
        if (!empty($id) || !empty($title)) {
            $data = $this->db->select('*')->from('items')->where('id', $id)->or_where( 'title', $title)->get()->row_array();
        } else {
            $data = $this->db->get("items")->result();
        }
        $this->response($data, REST_Controller::HTTP_OK);
    }

    // 4. create new item (duplicate validation based on name will be required)
    public function index_post()
    {
         $data = array(
                    'id'           => $this->post('id'),
                    'title'          => $this->post('title'),
                    'description'    => $this->post('description'),
                    'created_at'    => $this->post('created_at'),
                    'updated_at'    => $this->post('updated_at'));
        $insert = $this->db->insert('items', $data);
        if ($insert) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    // 5. update existing item by code
    public function index_put()
    {
         $id = $this->put('id');
        $data = array(
                    'id'       => $this->put('id'),
                    'title'          => $this->put('title'),
                    'description'    => $this->put('description'));

        $this->db->where('id', $id);
        $update = $this->db->update('items', $data);
        if ($update) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }


    // 6. delete item by code
    public function index_delete()
    {
        $id = $this->delete('id');
        $this->db->where('id', $id);
        $delete = $this->db->delete('items');
        if ($delete) {
            $this->response(array('status' => 'success'), 201);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
}
