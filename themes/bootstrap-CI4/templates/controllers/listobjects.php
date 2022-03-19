%[kind : controllers]
%[file : List%%(self.obName.lower())%%s.php] 
%[path : Controllers/%%(self.obName.title())%%]
<?php
/*
 * Created by generator
 *
 */
namespace App\Controllers\%%(self.obName.title())%%;

class List%%(self.obName)%%s extends \App\Controllers\BaseController {

	/**
	 * Affichage des %%(self.obName)%%s
	 */
	public function index($orderBy = null, $asc = null, $offset = 0){
		helper(['url']);

		// preparer le tri
		if($orderBy == null) {
			$orderBy = '%%(self.nonKeyFields[0].dbName)%%';
		}
		if($asc == null) {
			$asc = 'asc';
		}
		$data['orderBy'] = $orderBy;
		$data['asc'] = $asc;
		$limit = 15;
		/*
		// preparer la pagination
		$config['base_url'] = base_url().'index.php/%%(self.obName.lower())%%/list%%(self.obName.lower())%%s/index/'.$orderBy.'/'.$asc.'/';
		$config['total_rows'] = $this->%%(self.obName.lower())%%service->count($this->db);
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
		*/
		// recuperation des donnees
		$this->%%(self.obName.lower())%%Model = new \App\Models\%%(self.obName.title())%%Model();

		$data['%%(self.obName.lower())%%s'] = $this->%%(self.obName.lower())%%Model->orderBy($orderBy, $asc)->findAll($limit, $offset);

		return $this->view('%%(self.obName.lower())%%/list%%(self.obName.lower())%%s_view', $data);
	}

	
	/**
	 * Suppression d'un %%(self.obName)%%
	 * @param $%%(self.keyFields[0].dbName)%% identifiant a supprimer
	 */
	function delete($%%(self.keyFields[0].dbName)%%){

		$this->%%(self.obName.lower())%%service->deleteByKey($this->db, $%%(self.keyFields[0].dbName)%%);
		
		$this->session->set_flashdata('msg_confirm', $this->lang->line('%%(self.obName.lower())%%.message.confirm.deleted'));

		redirect('%%(self.obName.lower())%%/list%%(self.obName.lower())%%s/index'); 
	}

	public function view($page)
	{
		if (! is_file(APPPATH . 'Views/pages/' . $page . '.php')) {
			// Whoops, we don't have a page for that!
			throw new \CodeIgniter\Exceptions\PageNotFoundException($page);
		}

		$data['title'] = ucfirst($page); // Capitalize the first letter

		echo view('templates/header', $data);
		echo view('pages/' . $page, $data);
		echo view('templates/footer', $data);
	}

}
?>
