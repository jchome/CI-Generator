%[kind : json0]
%[file : jsonifyCount_view.php] 
%[path : Views/Generated/%%(self.obName.title())%%]
<?php
/*
 * Created by generator
 *
 */

$this->load->helper('jsonwrapper/jsonwrapper');
header('Access-Control-Allow-Origin: *');
echo json_encode($count); ?>
