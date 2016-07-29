<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->database();
		$this->load->library('form_validation');
		$this->load->model('User_model');
		$this->load->library('UserService');
	}
	
	public function index(){
		
		$formSend = $this->input->post('formSend');

		// on est déjà connecté et on repart sur l'accueil
		if($formSend == "" && $this->session->userdata('user_id') != null){
			$this->redirectPage($this->session->userdata('user_id'), $this->session->userdata('user_name'));
		}
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('login', 'lang:login', 'trim|required|encode_php_tags');
		$this->form_validation->set_rules('password', 'lang:password', 'trim|required|encode_php_tags');
		
		if($this->form_validation->run() == FALSE){
			$data['message'] = 'Erreur dans la validation des données';
			$this->load->view('welcome_message');
			return;
		}
		
		
		$login = $this->input->post('login'); 
		$password = $this->input->post('password');
		$data = Array();
	
		if( $login == "" && $password == "") {
			if($formSend == "true") {
				$data['message'] = 'Veuillez saisir un identifiant et un mot de passe';
			}
			$this->load->view('welcome_message',$data);
		} else {
			$criteria = Array( new Criteria('usrlblgn', Criteria::$EQ, $login),
					new Criteria('usrlbpwd', Criteria::$EQ, $password)
			);
			$allUsers = $this->userservice->getAllByCriteria($this->db, $criteria);
			if(sizeOf($allUsers) == 1){
				$user = end($allUsers);
				$this->session->set_userdata('user_name', $user->usrlbnom);
				$this->session->set_userdata('user_id', $user->usridusr);
				log_message('debug','[welcome.php] : user connected: '. $user->usrlblgn);
				$this->redirectPage($user->usridusr);
			}else{
				$data['message'] = 'Identifiant ['.$login.'] ou mot de passe incorrect...';
				log_message('debug','[welcome.php] : user NOT connected');
				$this->load->view('welcome_message',$data);
			}
			
		}
		
	}
	
	private function redirectPage($user_id){
		if($user_id == 0){
			redirect('user/listusers/index');
		}else{
			redirect('dashboard/xxx/index');
		}
		
	}
	
	/**
	 * Deconnexion
	 */
	function logout(){
		$this->session->unset_userdata('user_name');
		$this->session->unset_userdata('user_id');
		redirect('welcome/index'); 
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
