<?php
/*
 * Created by generator
 *
 */

class EditMyself extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('User_model');
		$this->load->library('UserService');
		$this->load->library('session');
		$this->load->helper('template');
		$this->load->helper('url');
		$this->load->library('form_validation');
		$this->load->database();
		
		
	}


	/**
	 * Affichage des infos
	 */
	public function index(){
		$usridusr = $this->session->userdata('user_id');
		$model = $this->userservice->getUnique($this->db, $usridusr);
		$data['user'] = $model;

		$this->load->view('user/editmyself_view',$data);
	}

	/**
	 * Sauvegarde des modifications
	 */
	public function save(){
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		$this->form_validation->set_rules('usridusr', 'lang:user.form.usridusr.label', 'trim|required');
		$this->form_validation->set_rules('usrlbnom', 'lang:user.form.usrlbnom.label', 'trim|required');
		$this->form_validation->set_rules('usrlbprn', 'lang:user.form.usrlbprn.label', 'trim');
		$this->form_validation->set_rules('usrlblgn', 'lang:user.form.usrlblgn.label', 'trim|required');
		$this->form_validation->set_rules('usrlbpwd', 'lang:user.form.usrlbpwd.label', 'trim|required');
		$this->form_validation->set_rules('usrlbmai', 'lang:user.form.usrlbmai.label', 'trim|required');
		
		if($this->form_validation->run() == FALSE){
			$this->load->view('user/editmyself_view');
		}
		
		// Mise a jour des donnees en base
		$model = new User_model();
		$oldModel = $this->userservice->getUnique($this->db, $this->input->post('usridusr') );
		
		$model->usridusr = $this->input->post('usridusr');
		$model->usrlbnom = $this->input->post('usrlbnom');
		$model->usrlbprn = $this->input->post('usrlbprn');
		$model->usrlblgn = $this->input->post('usrlblgn');
		$model->usrlbpwd = $this->input->post('usrlbpwd');
		$model->usrlbmai = $this->input->post('usrlbmai');
		$this->userservice->update($this->db, $model);
		

		$this->session->set_flashdata('msg_confirm', $this->lang->line('user.message.confirm.modified'));

		redirect('user/listusers/index');
	}
	

}
?>
