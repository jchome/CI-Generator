%[kind : controllers]
%[file : List%%(self.obName.lower())%%s.php] 
%[path : Controllers/Generated/%%(self.obName.title())%%]
<?php
/*
 * Created by generator
 *
 */
namespace App\Controllers\Generated\%%(self.obName.title())%%;

class List%%(self.obName)%%s extends \App\Controllers\HtmlController {

	/**
	 * Affichage des %%(self.obName)%%s
	 */
	public function index($orderBy = null, $asc = null, $offset = 0){

		if(session()->get('user_id') == "") {
			return redirect()->to('welcome/index');
		}
		
		helper(['database']);

		// preparer le tri
		if($orderBy == null) {
			$orderBy = '%%(self.nonKeyFields[0].dbName)%%';
		}
		if($asc == null) {
			$asc = 'asc';
		}
		$data['orderBy'] = $orderBy;
		$data['asc'] = $asc;
		$limit = 10;
		$pager = \Config\Services::pager();
		// recuperation des donnees
		$%%(self.obName.lower())%%Model = new \App\Models\%%(self.obName.title())%%Model();

		$data['%%(self.obName.lower())%%s'] = $%%(self.obName.lower())%%Model
			->asObject()->orderBy($orderBy, $asc)
			->paginate($limit, 'bootstrap', null, $offset);
		$data['pager'] = $%%(self.obName.lower())%%Model->pager;

%%allAttributeCode = ""
# inclure les objets référencés dans l'objet $data

for field in self.fields:
	attributeCode = ""
	if field.referencedObject and field.access == "default":
		attributeCode += """
		$%(referencedObjectLower)sModel = new \App\Models\%(referencedObjectTitle)sModel();
		$data['%(referencedObjectLower)sCollection'] = index_data($%(referencedObjectLower)sModel->asObject()->orderBy('%(fieldDisplay)s', 'asc')
			->findAll(), '%(fieldKey)s');""" % {
			'referencedObjectLower' : field.referencedObject.obName.lower(),
			'referencedObjectTitle' : field.referencedObject.obName.title(),
			'fieldDisplay': field.display,
			'fieldKey' : field.referencedObject.keyFields[0].dbName
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

		return $this->view('Generated/%%(self.obName.title())%%/list%%(self.obName.lower())%%s', $data, '%%(self.obName.title())%%');
	}

	
	/**
	 * Suppression d'un %%(self.obName)%%
	 * @param $%%(self.keyFields[0].dbName)%% identifiant a supprimer
	 */
	function delete($%%(self.keyFields[0].dbName)%%){
		$%%(self.obName.lower())%%Model = new \App\Models\%%(self.obName.title())%%Model();
		$%%(self.obName.lower())%%Model->delete($%%(self.keyFields[0].dbName)%%);
		session()->setFlashData('msg_confirm', lang('generated/%%(self.obName.title())%%.message.confirm.deleted'));
		return redirect()->to('Generated/%%(self.obName.title())%%/list%%(self.obName.lower())%%s/index'); 
	}

}
?>
