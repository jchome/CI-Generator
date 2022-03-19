%[kind : json0]
%[file : jsonifyUnique_view0.php] 
%[path : Views/%%(self.obName.title())%%]
<?php
/*
 * Created by generator
 *
 */

$this->load->helper('jsonwrapper/jsonwrapper');
header('Access-Control-Allow-Origin: *');
echo json_encode($%%(self.obName.lower())%%); ?>
