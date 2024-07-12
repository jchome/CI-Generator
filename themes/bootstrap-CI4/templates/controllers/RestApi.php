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

    public function index(){
        $objects = $this->model->findAll();
        return $this->respond($objects);
    }

    public function show($id = null){
        $object = $this->model->find($id);
        if (!$object) {
            return $this->failNotFound('Object not found');
        }
        return $this->respond($object);
    }


    public function create(){
        $data = $this->request->getPost();
        
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

        $this->model->save($data);
        $data['id'] = $this->model->insertID();
        return $this->respondCreated($data);
    }

    public function update($id = null){
        $data = $this->request->getRawInput();
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

        $this->model->update($id, $data);
        return $this->respond($data);
    }

    public function delete($id = null){
        $object = $this->model->find($id);
        if (!$object) {
            return $this->failNotFound('Object not found');
        }

        $this->model->delete($id);
        return $this->respondDeleted($object);
    }

}