%[kind : js]
%[file : list%%(self.obName.lower())%%s.js]
%[path : ../public/js/Generated/%%(self.obName.lower())%%]
/* Javascript for list%%(self.obName.lower())%%s_view.php */

function delete%%(self.obName)%%(id){
    if(!confirm('Voulez-vous supprimer ce %%(self.displayName)%% ?')){
		return;
	}
	$.ajax({
		url: base_url() + "Generated/%%(self.obName.lower())%%/Get%%(self.obName.lower())%%json/delete/" + id,
		method: "GET",
		headers: {'X-Requested-With': 'XMLHttpRequest'},
		success: function (data) {
            // Reload the page
			document.location.href = document.location.origin + document.location.pathname + "?";
        }
    });
}
