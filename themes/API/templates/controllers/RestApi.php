%[kind : controllers]
%[file : %%(self.obName.title())%%.php] 
%[path : Controllers/Api/v1]
<?php
/*
 * Created by generator
 * 
 */
namespace App\Controllers\Api\v1;
use CodeIgniter\RESTful\ResourceController;

class %%(self.obName.title())%% extends ResourceController {

    protected $modelName = 'App\Models\%%(self.obName.title())%%Model';
    protected $format    = 'json';

    /**
     * Get all objects
     * 
     * GET /api/v1/%%(self.obName.lower())%%s/
     * 
     */
    public function index(){
        $sortBy = $this->request->getGet('sort_by') ?? 'id'; // Par défaut, trier par ID
        $order  = $this->request->getGet('order') ?? 'asc';  // Ordre par défaut : ascendant
        if (!in_array($order, ['asc', 'desc'])) {
            return $this->fail('Invalid order parameter. Use "asc" or "desc".');
        }
        $page   = $this->request->getGet('page') ?? 1;       // Numéro de la page (1 par défaut)
        $limit  = $this->request->getGet('limit') ?? 10;     // Limite d'éléments par page (10 par défaut)
        $searchField = $this->request->getGet('search_on');
        $searchValue = $this->request->getGet('search_value');

        if (!empty($searchField) && !empty($searchValue)) {
            $items = $this->model
                ->orderBy($sortBy, $order)
                ->like($searchField, $searchValue)
                ->paginate($limit, 'default', $page);
        }else{
            $items = $this->model
                ->orderBy($sortBy, $order)
                ->paginate($limit, 'default', $page);
        }
            
        $response = [
            'data' => $items,
            'pager' => $this->model->pager->getDetails(),
        ];
        return $this->respond($response);
    }

    /**
     * Get one object by its id
     * 
     * GET /api/v1/%%(self.obName.lower())%%s/1
     * 
     */
    public function show($id = null){
        $object = $this->model->find($id);
        if (!$object) {
            return $this->failNotFound('Object not found');
        }
        return $this->respond($object);
    }


    /**
     * POST /api/v1/%%(self.obName.lower())%%s/
     * body: {...}
     */
    public function create(){
        $data = $this->request->getJSON(true);
        
        if (!$this->validate([%%allAttributeCode = ""
for field in self.fields:
    rule = "trim"
    if field.sqlType.upper()[0:4] == "FILE" or field.sqlType.upper()[0:4] == "FLAG":
        continue
    
    if field.autoincrement:
        continue

    if field.sqlType.upper()[0:4] != "FLAG" and not field.nullable:
        ## The Required attribute is not valid for FLAG field
        rule += "|required"

    attributeCode = """
            '%(dbName)s' => '%(rule)s',""" % {
        'dbName': field.dbName,
        'objectObName': self.obName.title(),
        'rule': rule
    }
    if attributeCode != "":
        allAttributeCode += attributeCode
RETURN = allAttributeCode
%%
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }
%%
allAttributesCode = ""
for field in self.fields:
    if field.sqlType.upper()[0:8] == "PASSWORD":
        allAttributesCode += """
        $data['%(dbName)s'] = password_hash($data['%(dbName)s'], PASSWORD_DEFAULT);
""" % {'dbName': field.dbName}
RETURN = allAttributesCode
%%

        helper(['image', 'database']);
%%allAttributeCode = ""
for field in self.fields:
    if field.sqlType.upper()[0:4] == "FILE":
        allAttributeCode += """
        $data = manageFileUpload($data, '%(dbName)s'); """ % {'dbName': field.dbName}

    elif field.sqlType.upper()[0:4] == "DATE":
        allAttributeCode += """
        $data['%(dbName)s'] = toSqlDate($data['%(dbName)s']);""" % {'dbName': field.dbName}

RETURN = allAttributeCode
%%
        $this->model->save($data);
        $data['id'] = $this->model->insertID();
        return $this->respondCreated($data);
    }
    
    /**
     * PUT /api/v1/%%(self.obName.lower())%%s/{id}
     * body: {...}
     */
    public function update($id = null){
        $data = $this->request->getJSON(true);
        if (!$this->validate([%%allAttributeCode = ""
for field in self.fields:
    rule = "trim"
    if field.sqlType.upper()[0:4] == "FILE" or field.sqlType.upper()[0:4] == "FLAG":
        continue
    
    if field.autoincrement:
        continue

    if field.sqlType.upper()[0:4] != "FLAG" and not field.nullable:
        ## The Required attribute is not valid for FLAG field
        rule += "|required"

    attributeCode = """
            '%(dbName)s' => '%(rule)s',""" % {
        'dbName': field.dbName,
        'objectObName': self.obName.title(),
        'rule': rule
    }
    if attributeCode != "":
        allAttributeCode += attributeCode
RETURN = allAttributeCode
%%
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $existingObject = $this->model->find($id);
        if (!$existingObject) {
            return $this->failNotFound('Object not found');
        }

        helper(['image', 'database']);
%%allAttributeCode = ""
for field in self.fields:
    if field.sqlType.upper()[0:4] == "FILE":
        allAttributeCode += """
        $data = manageFileUpload($data, '%(dbName)s', $existingObject); """ % {'dbName': field.dbName}

    elif field.sqlType.upper()[0:4] == "DATE":
        allAttributeCode += """
        $data['%(dbName)s'] = toSqlDate($data['%(dbName)s']);""" % {'dbName': field.dbName}

RETURN = allAttributeCode
%%

        $this->model->update($id, $data);
        return $this->respond($data);
    }

    
    /**
     * DELETE /api/v1/%%(self.obName.lower())%%s/
     * 
     */
    public function delete($id = null){
        $object = $this->model->find($id);
        if (!$object) {
            return $this->failNotFound('Object not found');
        }

        $this->model->delete($id);
        return $this->respondDeleted($object);
    }


}