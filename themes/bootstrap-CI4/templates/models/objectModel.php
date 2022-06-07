%[kind : models]
%[file : %%(self.obName.title())%%Model.php]
%[path : Models]
<?php
/*
 * Created by generator
 *
 */

/***************************************************************************
 * DO NOT MODIFY THIS FILE, IT IS GENERATED
 ***************************************************************************/

namespace App\Models;
use CodeIgniter\Model;

class %%(self.obName.title())%%Model extends Model {
	
    protected $table      = '%%(self.dbTableName)%%';
    protected $primaryKey = '%%(self.keyFields[0].dbName)%%';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
%%
RETURN = self.dbVariablesList("\t'(instVar)s', // (descrVar)s", 'instVar',  'typeVar', 'descrVar', 1)
%%
	];
    public static $empty = [
%%
RETURN = self.dbVariablesList("\t'(instVar)s' => '',", 'instVar',  'typeVar', 'descrVar', 1)
%%        
    ];

	/***************************************************************************
	 * DO NOT MODIFY THIS FILE, IT IS GENERATED
	 ***************************************************************************/

}

?>
