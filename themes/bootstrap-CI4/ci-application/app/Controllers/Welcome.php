<?php 

namespace App\Controllers;


class Welcome extends HtmlController {

	public function index(){
		helper(['form', 'security']);
		$formSend = $this->request->getPost('formSend');

		$session = session();

		// on est déjà connecté et on repart sur l'accueil
		if($formSend == ""){
			// The form is not sent

		 	if( $session->get('user_id') != null){
				 return redirect()->to('app/listapps/index');
			 }else{
				echo view('welcome');
				return;
			 }
		}


		if (! $this->validate([
			'login' => 'required',
			'password' => 'required',
		])) {
			log_message('debug','[welcome.php] : no parameter.');
			echo view('welcome');
			return;
		}
		
		$login = $this->request->getPost('login'); 
		$password = $this->request->getPost('password');
		$data = Array();
	
		if ($login == "admin" && $password == "/xx*xx*xx/") {
			$session->set('user_id', -1);
			log_message('debug','[welcome.php] : ADMIN is connected.');
			return redirect()->to('app/listapps/index');
			
		} 
		
	}
	
	/**
	 * Deconnexion
	 */
	function logout(){
		$session = session();
		$session->remove('user_id');
		return redirect()->to('welcome/index'); 
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
