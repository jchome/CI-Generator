<?php
/*
 * Created by generator
 *
 */

class ListUsers extends CI_Controller {

	/**
	 * Constructeur
	 */
	function __construct(){
		parent::__construct();
		$this->load->model('User_model');
		$this->load->library('UserService');
		$this->load->library('session');
		$this->load->library('pagination');
		$this->load->helper('url');
		$this->load->database();


	}

	/**
	 * Affichage des Users
	 */
	public function index($orderBy = null, $asc = null, $offset = 0){
		// preparer le tri
		if($orderBy == null) {
			$orderBy = 'usridusr';
		}
		if($asc == null) {
			$asc = 'asc';
		}
		$data['orderBy'] = $orderBy;
		$data['asc'] = $asc;
		
		// preparer la pagination
		$config['base_url'] = base_url().'index.php/user/listusers/index/'.$orderBy.'/'.$asc.'/';
		$config['total_rows'] = $this->userservice->count($this->db);
		$config['per_page'] = 15;
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="prev">';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li class="next">';
		$config['next_tag_close'] = '</li>';
		$config['first_link'] = '&lt;&lt;';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = '&gt;&gt;';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['num_links'] = 5;
		$config['uri_segment'] = '6'; // where the offset is in the URI segment 
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination;
		
		// recuperation des donnees
		$data['users'] = $this->userservice->getAll($this->db, $orderBy, $asc, $config['per_page'], $offset);
		
		
		$this->load->view('user/listusers_view', $data);
	}

	
	/**
	 * Suppression d'un User
	 * @param $usridusr identifiant a supprimer
	 */
	function delete($usridusr){

		$model = $this->userservice->getUnique($this->db, $usridusr);
		$path = realpath('www/uploads/');
		if( $model->usrfipho && file_exists( $path . $model->usrfipho ) ){
			unlink($path . $model->usrfipho);
		}

		$this->userservice->deleteByKey($this->db, $usridusr);
		
		$this->session->set_flashdata('msg_confirm', $this->lang->line('user.message.confirm.deleted'));

		redirect('user/listusers/index'); 
	}

}
?>
