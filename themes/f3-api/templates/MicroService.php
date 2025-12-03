%[kind : controllers]
%[file : %%(self.obName.title())%%.php] 
%[path : v1]
<?php
/*
 * Created by generator
 * 
 */
namespace V1;

use Exception;
use Helper;
use TypeSafeQueryBuilder;

class %%(self.obName.title())%%
{
    private $log;
    private $db;
    private $tableName;
    
    public static function defineRoutes($f3)
    {
        $f3->route("GET /%%(self.obName.lower())%%s", 'V1\\%%(self.obName.title())%%->get_list');
        $f3->route("GET /%%(self.obName.lower())%%s/@item", 'V1\\%%(self.obName.title())%%->get_item');
        $f3->route("POST /%%(self.obName.lower())%%s", 'V1\\%%(self.obName.title())%%->post');
        $f3->route("PUT /%%(self.obName.lower())%%s/@item", 'V1\\%%(self.obName.title())%%->put');
        $f3->route("DELETE /%%(self.obName.lower())%%s/@item", 'V1\\%%(self.obName.title())%%->delete');
    }

    public function __construct()
    {
        $this->log = \Base::instance()->get('LOG')->startup('../logs/log_%%(self.obName.lower())%%s.log');
        $this->db = \Base::instance()->get('DB');
        $this->tableName = \Base::instance()->get('db.prefix') . '%%(self.dbTableName)%%';
    }

    
    /**
    * Get list of %%(self.obName.lower())%%s
    * GET /%%(self.obName.lower())%%s/
    */
    function get_list()
    {
        header('Content-Type: application/json; charset=utf-8');
        //$this->log->info('App/get_list', 'Selecting list of %%(self.obName.lower())%%s');
        $sortBy = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id';
        $order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = isset($_GET['page']) ? ((int)$_GET['page'] - 1) * $limit : 0;
        $filters = isset($_GET['filters']) ? $_GET['filters'] : [];

        $builder = new TypeSafeQueryBuilder($this->db->pdo());
        $builder->select($this->tableName);
        
        foreach ($filters as $filter) {
            $filterData = explode('~', $filter);
            if (sizeof($filterData) != 3) {
                continue;
            }
            $value = urldecode($filterData[2]);
            // ^ will be replaced by "~"
            $value = str_replace('^', '~', $value);

            $this->manageFilter($filterData, $value, $builder);
        }
        //$this->log->info('App/get_list', "Query \n" . $builder->query());
        $builder->orderBy($sortBy, $order);
        $builder->limit($limit, $offset);
        try {
            $rows = $builder->execute();
        } catch (\Throwable $th) {
            $this->log->error('App/get_list', "Error: " . $th->getMessage());
            throw $th;
        }
        //$this->log->info('App/get_list', "Query result: \n" . print_r($rows, true));

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

    protected function manageFilter($filterData, $value, $builder)
    {
        //$this->log->info('App/manageFilter', 'Managing filter ' . $filterData[0] . ' ' . $filterData[1] . ' ' . $value);
        if ($filterData[1] == "lk") {
            $builder->where($filterData[0], $value, 'LIKE');
        } elseif ($filterData[1] == "eq") {
            $builder->where($filterData[0], $value);
        } elseif ($filterData[1] == "gt") {
            $builder->where($filterData[0], $value, '>=');
        } elseif ($filterData[1] == "lt") {
            $builder->where($filterData[0], $value, '<=');
        } else {
            $this->log->warning('App/manageFilter', 'Unknown filter condition ' . $filterData[1]);
        }
    }
    
    /**
    * Get one %%(self.obName.lower())%%
    * GET /%%(self.obName.lower())%%s/{key}
    */
    function get_item($f3)
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
        //$this->log->info('App/get_item', 'Selecting one %%(self.obName.lower())%%');
        $data = new \DB\SQL\Mapper($this->db, $this->tableName);
        $data->load(['%%(self.keyFields[0].dbName)%%=?', $key]);
        if ($data->dry()) {
            http_response_code(404);
            $this->log->error('App/get_item', 'Object not found');
            throw new Exception('Object not found');
        }
        $data = $data->cast();
        //$this->log->info('App/get_item', 'Object found!');
        $data = $this->afterRead($data);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Override this function to add more data or remove non-visible data
     */
    protected function afterRead($data)
    {
        //unset($data['lat_lon_coord']);
        return $data;
    }

    /**
    * Create a new object
    * POST /%%(self.obName.lower())%%s
    */
    function post($f3)
    {
        //$this->log->info('App/post', 'Creating a new %%(self.obName.lower())%%');
        header('Content-Type: application/json; charset=utf-8');
        $data = new \DB\SQL\Mapper($this->db, $this->tableName);
%%
allAttributesCode = ""
for field in self.fields:
    if field in self.keyFields:
        continue
    elif field.sqlType.upper()[0:4] == "FILE":
        continue
    elif field.sqlType.upper()[0:8] == "PASSWORD":
        allAttributesCode += """
        $data->%(dbName)s = password_hash($f3->get('POST.%(dbName)s'), PASSWORD_DEFAULT);""" % { 'dbName' : field.dbName }
    elif field.sqlType.upper()[0:3] == "INT":
        allAttributesCode += """
        $data->%(dbName)s =  empty($f3->get('POST.%(dbName)s')) ? null : intval($f3->get('POST.%(dbName)s'));""" % { 'dbName' : field.dbName }
    else:
        allAttributesCode += """
        $data->%(dbName)s = $f3->get('POST.%(dbName)s');""" % { 'dbName' : field.dbName }
RETURN = allAttributesCode
%%
        $this->beforeSave($data);
        $data->save();
        $this->afterSave($data);

        echo json_encode($data->cast(), JSON_UNESCAPED_UNICODE);
    }
    
    /**
    * Update an object
    * PUT /%%(self.obName.lower())%%/{key}
    */
    function put($f3)
    {
        //$this->log->info('App/put', 'Updating one %%(self.obName.lower())%%');
        $key = $f3->get('PARAMS.item');
        header('Content-Type: application/json; charset=utf-8');
        $data = new \DB\SQL\Mapper($this->db, $this->tableName);
        $data->load(['%%(self.keyFields[0].dbName)%%=?', $key]);
        if ($data->dry()) {
            http_response_code(404);
            throw new Exception('Object not found');
        }
        $existingObjectArr = $data->cast();
%%
allAttributesCode = ""
for field in self.fields:
    if field in self.keyFields:
        continue
    elif field.sqlType.upper()[0:4] == "FILE":
        continue
    elif field.sqlType.upper()[0:8] == "PASSWORD":
        allAttributesCode += """
        $data->%(dbName)s = password_hash($f3->get('POST.%(dbName)s'), PASSWORD_DEFAULT);""" % { 'dbName' : field.dbName }
    elif field.sqlType.upper()[0:3] == "INT":
        allAttributesCode += """
        $data->%(dbName)s =  empty($f3->get('POST.%(dbName)s')) ? null : intval($f3->get('POST.%(dbName)s'));""" % { 'dbName' : field.dbName }
    else:
        allAttributesCode += """
        $data->%(dbName)s = $f3->get('POST.%(dbName)s');""" % { 'dbName' : field.dbName }
RETURN = allAttributesCode
%%
        $this->beforeSave($data);
        $data->save();
        $this->afterSave($data, $existingObjectArr);
        //$this->log->info('App/put', 'Object updated!');
        
        echo json_encode($data->cast(), JSON_UNESCAPED_UNICODE);
    }


    /**
     * Override this function to manage data BEFORE saving in database
     */
    protected function beforeSave($data)
    {
        // Nothing to do here
    }

    /**
     * Override this function to manage data AFTER the object is saved in database
     */
    protected function afterSave($data, $existingObject = null)
    {
%%
allAttributesCode = ""
for field in self.fields:
    if field.sqlType.upper()[0:4] == "FILE":
        allAttributesCode += """
        if($data['%(dbName)s'] != "") {
            Helper::manageFileUpload($data->cast(), '%(objectName)s', '%(dbName)s', $existingObject);
        }""" % { 'dbName' : field.dbName,
            'objectName' : self.obName,
            'dbName' : field.dbName }
RETURN = allAttributesCode
%%
        // TODO: Update the lat_lon_coord
        
    }

    
    /**
    * Delete an object
    * DELETE /%%(self.obName.lower())%%s/{key}
    */
    function delete($f3)
    {
        //$this->log->info('App/delete', 'Deleting one %%(self.obName.lower())%%');
        $key = $f3->get('PARAMS.item');
        header('Content-Type: application/json; charset=utf-8');
        $data = new \DB\SQL\Mapper($this->db, $this->tableName);
        $data->load(['%%(self.keyFields[0].dbName)%%=?', $key]);
        if ($data->dry()) {
            http_response_code(404);
            throw new Exception('Object not found');
        }
        $this->beforeDelete($data);
        $data->erase();
        $this->afterDelete($data);
        //$this->log->info('App/put', 'Object deleted!');

        echo json_encode($data->cast(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Override this function to manage links to this object BEFORE the deletion
     */
    protected function beforeDelete($data){
        // Nothing to do here
    }

    /**
     * Override this function to manage links to this object AFTER the deletion
     */
    protected function afterDelete($data){
        // Nothing to do here
    }
}
