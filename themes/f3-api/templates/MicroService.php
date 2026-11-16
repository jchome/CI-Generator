%[kind : controllers]
%[file : %%(self.obName.title())%%.php] 
%[path : v1]
<?php
/*
 * Created by generator
 * 
 */
namespace V1;

use Throwable;
use Exception;
use TypeSafeQueryBuilder;

class %%(self.obName.title())%% extends \API\FilteredApiController
{
    protected $db;
    protected $tableName;
    protected $helper;
    
    public static function defineRoutes($f3)
    {
        $f3->route("GET /%%(self.obName.lower())%%s", __NAMESPACE__ . '\\%%(self.obName.title())%%->get_list');
        $f3->route("GET /%%(self.obName.lower())%%s/@item", __NAMESPACE__ . '\\%%(self.obName.title())%%->get_item');
        $f3->route("POST /%%(self.obName.lower())%%s", __NAMESPACE__ . '\\%%(self.obName.title())%%->post');
        $f3->route("PUT /%%(self.obName.lower())%%s/@item", __NAMESPACE__ . '\\%%(self.obName.title())%%->put');
        $f3->route("DELETE /%%(self.obName.lower())%%s/@item", __NAMESPACE__ . '\\%%(self.obName.title())%%->delete');
    }

    public function __construct()
    {
        $this->log = \Base::instance()->get('LOG')->startup('../logs/log_%%(self.obName.lower())%%s.log');
        $this->db = \Base::instance()->get('DB');
        $this->tableName = \Base::instance()->get('db.prefix') . '%%(self.dbTableName)%%';
        $this->helper = \Base::instance()->get('HELPER');
        $this->helper->log = $this->log;
    }

    
    /**
    * Get list of %%(self.obName.lower())%%s
    * GET /%%(self.obName.lower())%%s/
    */
    public function get_list()
    {
        header('Content-Type: application/json; charset=utf-8');
        //$this->log->info('App/v1/%%(self.obName.title())%%get_list', 'Selecting list of %%(self.obName.lower())%%s');
        $sortBy = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id';
        $order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = isset($_GET['page']) ? ((int)$_GET['page'] - 1) * $limit : 0;
        $filters = isset($_GET['filters']) ? $_GET['filters'] : [];

        $builder = new TypeSafeQueryBuilder($this->db->pdo());
        $builder->select($this->tableName);

        $this->manageFiltersArray($filters, $builder);
        
        //$this->log->info('App/v1/%%(self.obName.title())%%/get_list', "Query \n" . $builder->query());
        $builder->orderBy($sortBy, $order);
        $builder->limit($limit, $offset);
        try {
            $rows = $builder->execute();
        } catch (\Throwable $th) {
            $this->log->error('App/v1/%%(self.obName.title())%%/get_list', "Error: " . $th->getMessage());
            throw $th;
        }
        //$this->log->info('App/v1/%%(self.obName.title())%%/get_list', "Query result: \n" . print_r($rows, true));

        $items = array_map(function ($row) {
            return $this->afterRead($row);
        }, $rows);

        $response = [
            'data' => $items,
            'pager' => [
                'hasMore' => $offset + $limit < $builder->count(),
                'total' => $builder->count(), // Total by query
                'page' => $offset / $limit + 1,
                'perPage' => $limit,
                'pageCount' => ceil($builder->count() / $limit),
                'pageSelector' => "page",
                'currentPage' => $offset / $limit + 1,
            ]
        ];
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    
    /**
    * Get one %%(self.obName.lower())%%
    * GET /%%(self.obName.lower())%%s/{key}
    */
    public function get_item($f3)
    {
        $key = $f3->get('PARAMS.item');
        if($key === null) {
            return $this->get_list();
        }%%test_key_int = ""
if self.keyFields[0].sqlType.upper()[0:3] == "INT":
    test_key_int = """
        $key = intval($key);
    """
RETURN = test_key_int
%%
        header('Content-Type: application/json; charset=utf-8');
        //$this->log->info('App/v1/%%(self.obName.title())%%/get_item', 'Selecting one %%(self.obName.lower())%%');
        $data = new \DB\SQL\Mapper($this->db, $this->tableName);
        $data->load(['%%(self.keyFields[0].dbName)%%=?', $key]);
        if ($data->dry()) {
            $this->log->error('App/v1/%%(self.obName.title())%%/get_item', 'Object not found');
            $response = [
                "status" => "error",
                "message" => 'Object not found',
                "input" => $key
            ];
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            return;
        }
        $data = $data->cast();
        //$this->log->info('App/v1/%%(self.obName.title())%%/get_item', 'Object found!');
        $data = $this->afterRead($data);

        $response = [
            "status" => "ok",
            "data" => $data,
        ];
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        return;
    }

    /**
     * Override this function to add more data or remove non-visible data
     */
    protected function afterRead($data)
    {
%%
allAttributesCode = ""
for field in self.fields:
    if field.sqlType.upper()[0:5] == "POINT":
        allAttributesCode += """
        unset($data['%(dbName)s']);""" % { 'dbName' : field.dbName }
    
RETURN = allAttributesCode
%%
        return $data;
    }

    /**
    * Create a new object
    * POST /%%(self.obName.lower())%%s
    */
    function post($f3)
    {
        //$this->log->info('App/v1/%%(self.obName.title())%%/post', 'Creating a new %%(self.obName.lower())%%');
        header('Content-Type: application/json; charset=utf-8');
        $data = new \DB\SQL\Mapper($this->db, $this->tableName);
        $dataArray = json_decode($f3->get('BODY'), true);
        try{
            $this->checkRequiredFields($dataArray, true);
        }catch(Throwable $th){
            $this->log->error("App/v1/%%(self.obName.title())%%/post", "Error on checkRequiredFields:" . $th->getMessage());
            $response = [
                "status" => "error",
                "message" => $th->getMessage(),
                "input" => $dataArray
            ];
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            return;
        }
%%
allAttributesCode = ""
for field in self.fields:
    if field in self.keyFields:
        continue
    elif field.sqlType.upper()[0:4] == "FILE":
        continue
    elif field.sqlType.upper()[0:5] == "POINT":
        continue
    elif field.sqlType.upper()[0:8] == "PASSWORD":
        allAttributesCode += """
        if ($dataArray['%(dbName)s'] != null && $dataArray['%(dbName)s'] != '') {
            $data->%(dbName)s = password_hash($dataArray['%(dbName)s'], PASSWORD_DEFAULT);
        }""" % { 'dbName' : field.dbName }
    elif field.sqlType.upper()[0:3] == "INT":
        allAttributesCode += """
        $data->%(dbName)s = empty($dataArray['%(dbName)s']) ? null : intval($dataArray['%(dbName)s']);""" % { 'dbName' : field.dbName }
    else:
        allAttributesCode += """
        $data->%(dbName)s = $dataArray['%(dbName)s'];""" % { 'dbName' : field.dbName }
RETURN = allAttributesCode
%%

        $this->beforeSave($data, $dataArray);
        $data->save();
        $this->afterSave($data, $dataArray);
        $response = [
	        "status" => "ok",
	        "data" => $data->cast()
        ];

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    /**
    * Update an object
    * PUT /%%(self.obName.lower())%%/{key}
    */
    public function put($f3)
    {
        //$this->log->info('App/v1/%%(self.obName.title())%%/put', 'Updating one %%(self.obName.lower())%%');
        $key = $f3->get('PARAMS.item');
        header('Content-Type: application/json; charset=utf-8');
        $data = new \DB\SQL\Mapper($this->db, $this->tableName);
        $data->load(['%%(self.keyFields[0].dbName)%%=?', $key]);
        if ($data->dry()) {
            $response = [
                "status" => "error",
                "message" => 'Object not found',
            ];
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            return;
        }
        $existingObjectArr = $data->cast();
        $dataArray = json_decode($f3->get('BODY'), true);
        try{
            $this->checkRequiredFields($dataArray, false);
        }catch(Throwable $th){
            $this->log->error("App/v1/%%(self.obName.title())%%/put", "Error on checkRequiredFields:" . $th->getMessage());
            $response = [
                "status" => "error",
                "message" => $th->getMessage(),
                "input" => $dataArray
            ];
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            return;
        }
%%
allAttributesCode = ""
for field in self.fields:
    if field in self.keyFields:
        continue
    elif field.sqlType.upper()[0:4] == "FILE":
        continue
    elif field.sqlType.upper()[0:5] == "POINT":
        continue
    elif field.sqlType.upper()[0:8] == "PASSWORD":
        allAttributesCode += """
        if ($dataArray['%(dbName)s'] != null && $dataArray['%(dbName)s'] != '') {
            $data->%(dbName)s = password_hash($dataArray['%(dbName)s'], PASSWORD_DEFAULT);
        }""" % { 'dbName' : field.dbName }
    elif field.sqlType.upper()[0:3] == "INT":
        allAttributesCode += """
        $data->%(dbName)s = empty($dataArray['%(dbName)s']) ? null : intval($dataArray['%(dbName)s']);""" % { 'dbName' : field.dbName }
    else:
        allAttributesCode += """
        $data->%(dbName)s = $dataArray['%(dbName)s'];""" % { 'dbName' : field.dbName }
RETURN = allAttributesCode
%%

        $this->beforeSave($data, $dataArray);
        $data->save();
        $this->afterSave($data, $dataArray, $existingObjectArr);
        //$this->log->info('App/v1/%%(self.obName.title())%%/put', 'Object updated!');
        
        $response = [
	        "status" => "ok",
	        "data" => $data->cast()
        ];
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Check that required fields are not empty
     * Else, an exception is thrown (that will generate a 500 Server error)
     */
    protected function checkRequiredFields(array $dataArray, bool $isCreation)
    {%%
allAttributesCode = ""
for field in self.fields:

    if field.sqlType.upper()[0:5] == "POINT":
        continue

    if not field.nullable:
        if field in self.keyFields:
            allAttributesCode += """
        if (!$isCreation && ($dataArray['%(dbName)s'] == null || $dataArray['%(dbName)s'] == '')) {
            throw new Exception('%(dbName)s is required');
        }""" % { 'dbName' : field.dbName }
        elif field.sqlType.upper()[0:8] == "PASSWORD":
            allAttributesCode += """
        if ($isCreation && ($dataArray['%(dbName)s'] == null || $dataArray['%(dbName)s'] == '')) {
            throw new Exception('%(dbName)s is required');
        }""" % { 'dbName' : field.dbName }
        else:
            allAttributesCode += """
        if ($dataArray['%(dbName)s'] == null || $dataArray['%(dbName)s'] == '') {
            throw new Exception('%(dbName)s is required');
        }""" % { 'dbName' : field.dbName }
RETURN = allAttributesCode
%%
    }


    /**
     * Override this function to manage data BEFORE saving in database
     */
    protected function beforeSave($data, $dataArray = null)
    {
        // Nothing to do here
    }

    /**
     * Override this function to manage data AFTER the object is saved in database
     */
    protected function afterSave($data, $dataArray = null, $existingObject = null)
    {%%
allAttributesCode = ""
for field in self.fields:
    if field.sqlType.upper()[0:4] == "FILE":
        allAttributesCode += """
        $fileContent = $dataArray['%(dbName)s']['file'];
        $extension = $dataArray['%(dbName)s']['extension'];
        $filename = $this->helper->manageFileUpload($fileContent, '%(objectName)s', '%(dbName)s', $existingObject, $extension);
        $data->%(dbName)s = $filename;
        $data->save();
        """ % { 
            'dbName' : field.dbName,
            'objectName' : self.obName
        }

    elif field.sqlType.upper()[0:5] == "POINT":
        allAttributesCode += """
        $this->updateGpsPoint($data->id, $dataArray, '%(dbName)s');
        """ % { 'dbName' : field.dbName }
RETURN = allAttributesCode
%%
    }

    private function updateGpsPoint($key, $dataArray, $fieldName)
    {
        $sourceFieldName = $fieldName.substr($fieldName, $fieldName.strpos($fieldName, '_coord'));
        [$lat, $lon] = array_map(
            function ($f) {return floatval($f);} , 
            explode('@', $dataArray[$sourceFieldName])
        );
        
        $this->db->exec(
            'UPDATE ' . $this->tableName . '
            SET '.$fieldName.' = ST_GeomFromText(
                CONCAT("POINT(", ?, " ", ?, ")"),
                4326
            )
            WHERE id = ?',
            [$lon, $lat, $key]
        );
    }

    
    /**
    * Delete an object
    * DELETE /%%(self.obName.lower())%%s/{key}
    */
    public function delete($f3)
    {
        //$this->log->info('App/v1/%%(self.obName.title())%%/delete', 'Deleting one %%(self.obName.lower())%%');
        $key = $f3->get('PARAMS.item');
        header('Content-Type: application/json; charset=utf-8');
        $data = new \DB\SQL\Mapper($this->db, $this->tableName);
        $data->load(['%%(self.keyFields[0].dbName)%%=?', $key]);
        if ($data->dry()) {
            $response = [
                "status" => "error",
                "message" => 'Object not found',
            ];
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            return;
        }
        $this->beforeDelete($data);
        $data->erase();
        $this->afterDelete($data);
        //$this->log->info('App/v1/%%(self.obName.title())%%/put', 'Object deleted!');
        $response = [
            "status" => "ok",
            "data" => $data->cast(),
        ];
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        return;
    }

    /**
     * Override this function to manage links to this object BEFORE the deletion
     */
    protected function beforeDelete($data){%%
allAttributesCode = ""
for field in self.fields:
    if field.sqlType.upper()[0:4] == "FILE":
        allAttributesCode += """
        // Delete file if exists
        $this->helper->manageFileUpload('DELETE', '%(objectName)s', '%(dbName)s', $data->cast());
        """ % {
            'dbName' : field.dbName,
            'objectName' : self.obName
        }
RETURN = allAttributesCode
%%
    }

    /**
     * Override this function to manage links to this object AFTER the deletion
     */
    protected function afterDelete($data){
        // Nothing to do here
    }
}
