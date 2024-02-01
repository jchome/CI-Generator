
$( document ).ready(function() {

    $('.img-zoom').on('click', function(event) {
        var fullImageSrc = $(this).data('full-img');
        if(!fullImageSrc){
            fullImageSrc = $(this).attr('src');
        }
        const width = $(this).width();
        var cssModalAddon = "modal-xl";
        if(width < 800){
            cssModalAddon = "modal-lg"
        }
        if(width < 300){
            cssModalAddon = "modal-sm"
        }
        templateModal = `
            <div class="modal" id="imgModal" tabindex="-1">
                <div class="modal-dialog ${cssModalAddon}">
                    <div class="modal-content">
                        <div class="modal-body">
                            <img class="img-fluid" src="${fullImageSrc}" alt="${$(this).attr('alt')}">
                        </div>
                        <div class="modal-footer justify-content-center">
                            ${$(this).attr('alt')}
                        </div>
                    </div>
                </div>
            </div>`;
        $(this).parent().append(templateModal);
        const imgModal = new bootstrap.Modal(document.getElementById('imgModal'), {});
        imgModal.show();
        $('#imgModal').on('hidden.bs.modal', event => {
            $('#imgModal').remove();
          });
    });

});
