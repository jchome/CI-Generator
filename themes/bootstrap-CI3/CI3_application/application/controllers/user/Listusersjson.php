<?php
/*
 * Created by generator
 *
 */

class ListUsersJson extends CI_Controller {

	/**
	 * Constructeur
	 */
	function __construct(){
		parent::__construct();
		$this->load->model('User_model');
		$this->load->library('UserService');
		$this->load->library('session');
		$this->load->database();

	}

	/**
	 * Affichage des Users
	 */
	public function index(){
		// recuperation des donnees
		$data['users'] = $this->userservice->getAll($this->db);
		
		$this->load->view('user/jsonifyList_view', $data);
	}


	public function findBy_usridusr($usridusr, $orderBy = null, $limit = 50, $offset = 0){
		$asc = null;
		$data['users'] = $this->userservice->getAllBy_usridusr($this->db, urldecode($usridusr), $orderBy, $asc, $limit, $offset);
		$this->load->view('user/jsonifyList_view', $data);
	}
	public function findBy_usrlbnom($usrlbnom, $orderBy = null, $limit = 50, $offset = 0){
		$asc = null;
		$data['users'] = $this->userservice->getAllBy_usrlbnom($this->db, urldecode($usrlbnom), $orderBy, $asc, $limit, $offset);
		$this->load->view('user/jsonifyList_view', $data);
	}
	public function findBy_usrlbprn($usrlbprn, $orderBy = null, $limit = 50, $offset = 0){
		$asc = null;
		$data['users'] = $this->userservice->getAllBy_usrlbprn($this->db, urldecode($usrlbprn), $orderBy, $asc, $limit, $offset);
		$this->load->view('user/jsonifyList_view', $data);
	}
	public function findBy_usrlblgn($usrlblgn, $orderBy = null, $limit = 50, $offset = 0){
		$asc = null;
		$data['users'] = $this->userservice->getAllBy_usrlblgn($this->db, urldecode($usrlblgn), $orderBy, $asc, $limit, $offset);
		$this->load->view('user/jsonifyList_view', $data);
	}
	public function findBy_usrlbpwd($usrlbpwd, $orderBy = null, $limit = 50, $offset = 0){
		$asc = null;
		$data['users'] = $this->userservice->getAllBy_usrlbpwd($this->db, urldecode($usrlbpwd), $orderBy, $asc, $limit, $offset);
		$this->load->view('user/jsonifyList_view', $data);
	}
	public function findBy_usrlbmai($usrlbmai, $orderBy = null, $limit = 50, $offset = 0){
		$asc = null;
		$data['users'] = $this->userservice->getAllBy_usrlbmai($this->db, urldecode($usrlbmai), $orderBy, $asc, $limit, $offset);
		$this->load->view('user/jsonifyList_view', $data);
	}


	public function countBy_usridusr($usridusr){
		$data['count'] = $this->userservice->countBy_usridusr($this->db, urldecode($usridusr));
		$this->load->view('user/jsonifyCount_view', $data);
	}
	public function countBy_usrlbnom($usrlbnom){
		$data['count'] = $this->userservice->countBy_usrlbnom($this->db, urldecode($usrlbnom));
		$this->load->view('user/jsonifyCount_view', $data);
	}
	public function countBy_usrlbprn($usrlbprn){
		$data['count'] = $this->userservice->countBy_usrlbprn($this->db, urldecode($usrlbprn));
		$this->load->view('user/jsonifyCount_view', $data);
	}
	public function countBy_usrlblgn($usrlblgn){
		$data['count'] = $this->userservice->countBy_usrlblgn($this->db, urldecode($usrlblgn));
		$this->load->view('user/jsonifyCount_view', $data);
	}
	public function countBy_usrlbpwd($usrlbpwd){
		$data['count'] = $this->userservice->countBy_usrlbpwd($this->db, urldecode($usrlbpwd));
		$this->load->view('user/jsonifyCount_view', $data);
	}
	public function countBy_usrlbmai($usrlbmai){
		$data['count'] = $this->userservice->countBy_usrlbmai($this->db, urldecode($usrlbmai));
		$this->load->view('user/jsonifyCount_view', $data);
	}



	public function findLike_usrlbnom($usrlbnom){
		$data['users'] = $this->userservice->getAllLike_usrlbnom($this->db, urldecode($usrlbnom));
		$this->load->view('user/jsonifyList_view', $data);
	}
	public function findLike_usrlbprn($usrlbprn){
		$data['users'] = $this->userservice->getAllLike_usrlbprn($this->db, urldecode($usrlbprn));
		$this->load->view('user/jsonifyList_view', $data);
	}
	public function findLike_usrlblgn($usrlblgn){
		$data['users'] = $this->userservice->getAllLike_usrlblgn($this->db, urldecode($usrlblgn));
		$this->load->view('user/jsonifyList_view', $data);
	}
	public function findLike_usrlbmai($usrlbmai){
		$data['users'] = $this->userservice->getAllLike_usrlbmai($this->db, urldecode($usrlbmai));
		$this->load->view('user/jsonifyList_view', $data);
	}

}
?>
