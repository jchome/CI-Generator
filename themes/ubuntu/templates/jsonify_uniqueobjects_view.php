%[kind : json]
%[file : jsonifyUnique_view.php] 
%[path : views/%%(self.obName.lower())%%]
<?php
/*
 * Created by generator
 *
 */

$this->load->helper('jsonwrapper/jsonwrapper');

echo json_encode($%%(self.obName.lower())%%); ?>
