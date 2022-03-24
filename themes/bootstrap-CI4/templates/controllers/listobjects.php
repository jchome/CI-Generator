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

		if(session()->get('user_name') == "") {
			return redirect()->to('welcome/index');
		}
		
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
		$limit = 5;
		$pager = \Config\Services::pager();
		// recuperation des donnees
		$this->%%(self.obName.lower())%%Model = new \App\Models\%%(self.obName.title())%%Model();

		$data['%%(self.obName.lower())%%s'] = $this->%%(self.obName.lower())%%Model
			->orderBy($orderBy, $asc)->paginate($limit, '', null, $offset);
		$data['pager'] = $this->%%(self.obName.lower())%%Model->pager;

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
		enumTypes = field.sqlType[5:-1]
		attributeCode = """
		$data["enum_%(dbName)s"] = array(""" % {'dbName' : field.dbName}
		for enum in enumTypes.split(','):
			valueAndText = enum.replace('"','').replace("'","").split(':')
			attributeCode += """"%(value)s" => "%(text)s",""" % {'value': valueAndText[0].strip(), 'text': valueAndText[1].strip()}
		attributeCode = attributeCode[:-1] + ");"
	if attributeCode != "":
		allAttributeCode += attributeCode
	
RETURN = allAttributeCode
%%

		return $this->view('%%(self.obName.title())%%/list%%(self.obName.lower())%%s', $data);
	}

	
	/**
	 * Suppression d'un %%(self.obName)%%
	 * @param $%%(self.keyFields[0].dbName)%% identifiant a supprimer
	 */
	function delete($%%(self.keyFields[0].dbName)%%){
		$this->%%(self.obName.lower())%%Model = new \App\Models\%%(self.obName.title())%%Model();
		$this->%%(self.obName.lower())%%Model->delete($%%(self.keyFields[0].dbName)%%);
		session()->setFlashData('msg_confirm', lang('%%(self.obName.title())%%.message.confirm.deleted'));
		return redirect()->to('%%(self.obName.title())%%/list%%(self.obName.lower())%%s/index'); 
	}

	public function view($page, $data = [])
	{
		if (! is_file(APPPATH . 'Views/' . $page . '.php')) {
			print("Cannot open view to ". $page);
			// Whoops, we don't have a page for that!
			throw new \CodeIgniter\Exceptions\PageNotFoundException($page);
		}

		echo view('templates/header', $data);
		echo view($page, $data);
		echo view('templates/footer', $data);
	}

}
?>
