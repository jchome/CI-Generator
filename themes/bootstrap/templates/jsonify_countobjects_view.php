%[kind : json]
%[file : jsonifyCount_view.php] 
%[path : views/%%(self.obName.lower())%%]
<?php
/*
 * Created by generator
 *
 */

$this->load->helper('jsonwrapper/jsonwrapper');

echo json_encode($count); ?>
