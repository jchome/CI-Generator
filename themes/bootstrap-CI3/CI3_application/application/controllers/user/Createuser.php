<?php
/*
 * Created by generator
 * 
 */

class CreateUser extends CI_Controller {
	
	/**
	 * Constructeur
	 */
	function __construct(){
		parent::__construct();
		$this->load->model('User_model');
		$this->load->library('UserService');
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->library('form_validation');
		$this->load->database();

		
	}
	
	/**
	 * page de creation d'un user
	 */	
	public function index(){
		$data = Array();
		// Recuperation des objets references

		$this->load->view('user/createuser_view', $data);
	}
	
	/**
	 * Ajout d'un User
	 */
	public function add(){
	
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		$this->form_validation->set_rules('usridusr', 'lang:user.form.usridusr.label', 'trim|required');
		$this->form_validation->set_rules('usrlbnom', 'lang:user.form.usrlbnom.label', 'trim|required');
		$this->form_validation->set_rules('usrlbprn', 'lang:user.form.usrlbprn.label', 'trim');
		$this->form_validation->set_rules('usrlblgn', 'lang:user.form.usrlblgn.label', 'trim|required');
		$this->form_validation->set_rules('usrlbpwd', 'lang:user.form.usrlbpwd.label', 'trim|required');
		$this->form_validation->set_rules('usrlbmai', 'lang:user.form.usrlbmai.label', 'trim|required');
		$this->form_validation->set_rules('usrfipho_file', 'lang:user.form.usrfipho.label', 'trim');
		
		if($this->form_validation->run() == FALSE){
			$this->load->view('user/createuser_view');
		}
		
		// Insertion en base
		$model = new User_model();
		$model->usridusr = $this->input->post('usridusr');
		$model->usrlbnom = $this->input->post('usrlbnom');
		$model->usrlbprn = $this->input->post('usrlbprn');
		$model->usrlblgn = $this->input->post('usrlblgn');
		$model->usrlbpwd = $this->input->post('usrlbpwd');
		$model->usrlbmai = $this->input->post('usrlbmai');
		$model->usrfipho = $this->input->post('usrfipho');
		$this->userservice->insertNew($this->db, $model);
		

		// Configuration pour chargement des fichiers
		// Chemin de stockage des fichiers : doit etre WRITABLE pour tous
		$config['upload_path'] = realpath('www/uploads/');
		// Voir la configuration des types mimes s'il y a un probleme avec l'extension
		$config['allowed_types'] = 'doc|docx|xls|xlsx|pdf|gif|jpg|png|jpeg|zip|rar|ppt|pptx|mp3';
		$config['max_size']	= '2000';
		$config['max_width']  = '0';
		$config['max_height']  = '0';
		$this->load->library('upload', $config);
		$path = $config['upload_path'] . "/";
		
		
		$this->upload->initialize($config); // RAZ des erreurs
		// Upload du fichier usrfipho : Photo ou avatar de l'utilisateur
		$codeErrors = null;
		if ( ! $this->upload->do_upload('usrfipho_file')) {
			$codeErrors = $this->upload->display_errors() . "ext: [" . $this->upload->data('file_ext') ."] type mime: [" . $this->upload->data('file_type') . "]";
			if($this->upload->display_errors() == '<p>'.$this->lang->line('upload_no_file_selected').'</p>'
					|| $this->upload->display_errors() == '<p>upload_no_file_selected</p>'){ // if not translated
				$codeErrors = "NO_FILE";
			}
		}else{
			$uploadDataFile_usrfipho = $this->upload->data('file_name');
		}
	
		if($codeErrors != null && $codeErrors != "NO_FILE") {
			$this->session->set_flashdata('msg_error', $codeErrors);
		} else {
			$model->usrfipho = "";
			if($uploadDataFile_usrfipho != null && $uploadDataFile_usrfipho != "") {
				$model->usrfipho = 'User_usrfipho_' . $model->usridusr . '_file' . $this->upload->data('file_ext');
				rename($path . $uploadDataFile_usrfipho['file_name'], $path . $model->usrfipho);
				// suppression du fichier temporaire telecharge
				if( file_exists( $path . $uploadDataFile_usrfipho ) ){
					unlink($path . $uploadDataFile_usrfipho);
				}
			}
			$this->userservice->update($this->db, $model);
		}
	
		$this->session->set_flashdata('msg_info', $this->lang->line('user.message.confirm.added'));
	
		// Recharge la page avec les nouvelles infos
		redirect('user/listusers/index');
	}
}
?>