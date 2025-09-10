%[kind : controllers]
%[file : List%%(self.obName.lower())%%s.php] 
%[path : controllers/%%(self.obName.lower())%%]
<?php
/*
 * Created by generator
 *
 */

class List%%(self.obName)%%s extends CI_Controller {

	/**
	 * Constructeur
	 */
	function __construct(){
		parent::__construct();
		$this->load->model('%%(self.obName)%%Model');
		$this->load->library('%%(self.obName)%%Service');
		$this->load->library('session');
		$this->load->library('pagination');
		$this->load->helper('url');
		$this->load->database();
		
%%allAttributeCode = ""
# inclure les modeles des objets référencés

for field in self.fields:
	attributeCode = ""
	if field.referencedObject:
		attributeCode += """
		$this->load->model('%(referencedObject)sModel');
		$this->load->library('%(referencedObject)sService');""" % {'referencedObject': field.referencedObject.obName}
	allAttributeCode += attributeCode
	
RETURN = allAttributeCode
%%

	}

	/**
	 * Affichage des %%(self.obName)%%s
	 */
	public function index($orderBy = null, $asc = null, $offset = 0){
		// preparer le tri
		if($orderBy == null) {
			$orderBy = '%%(self.nonKeyFields[0].dbName)%%';
		}
		if($asc == null) {
			$asc = 'asc';
		}
		$data['orderBy'] = $orderBy;
		$data['asc'] = $asc;
		
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
		
		// recuperation des donnees
		$data['%%(self.obName.lower())%%s'] = $this->%%(self.obName.lower())%%service->getAll($this->db, $orderBy, $asc, $config['per_page'], $offset);
		
%%allAttributeCode = ""
# inclure les objets référencés dans l'objet $data

for field in self.fields:
	attributeCode = ""
	if field.referencedObject and field.access == "default":
		attributeCode += """
		$data['%(referencedObjectLower)sCollection'] = $this->%(referencedObjectLower)sservice->getAll($this->db,'%(fieldDisplay)s');""" % {
			'referencedObjectLower' : field.referencedObject.obName.lower(),
			'fieldDisplay': field.display
		}
	elif field.sqlType.upper()[0:4] == "ENUM":
		enumTypes = field.sqlType[5:-1].replace(';',',')
		attributeCode = """
		$data["enum_%(dbName)s"] = array( """ % {'dbName' : field.dbName}
		for enum in enumTypes.split(','):
			valueAndText = enum.replace('"','').replace("'","").split(':')
			attributeCode += """"%(value)s" => "%(text)s",""" % {'value': valueAndText[0].strip(), 'text': valueAndText[1].strip()}
		attributeCode = attributeCode[:-1] + ");"
	if attributeCode != "":
		allAttributeCode += attributeCode
	
RETURN = allAttributeCode
%%
		
		$this->load->view('%%(self.obName.lower())%%/list%%(self.obName.lower())%%s_view', $data);
	}

	
	/**
	 * Suppression d'un %%(self.obName)%%
	 * @param $%%(self.keyFields[0].dbName)%% identifiant a supprimer
	 */
	function delete($%%(self.keyFields[0].dbName)%%){
%%allAttributeCode = ""
for field in self.fields:
	attributeCode = ""
	if field.sqlType.upper()[0:4] == "FILE":
		attributeCode += """
		$model = $this->%(obName_lower)sservice->getUnique($this->db, $%(keyfield_dbname)s);
		$path = realpath('www/uploads/');
		if( $model->%(field_dbName)s && file_exists( $path . $model->%(field_dbName)s ) ){
			unlink($path . $model->%(field_dbName)s);
		}
""" % { 'obName' : self.obName,
		'obName_lower' : self.obName.lower(), 
		'field_dbName' : field.dbName,
		'keyfield_dbname' : self.keyFields[0].dbName
	}
	if attributeCode != "":
		allAttributeCode += attributeCode

RETURN = allAttributeCode
%%
		$this->%%(self.obName.lower())%%service->deleteByKey($this->db, $%%(self.keyFields[0].dbName)%%);
		
		$this->session->set_flashdata('msg_confirm', $this->lang->line('%%(self.obName.lower())%%.message.confirm.deleted'));

		redirect('%%(self.obName.lower())%%/list%%(self.obName.lower())%%s/index'); 
	}

}
?>
