$(document).ready(function() {

    //Ajout option nouvelle catégorie
    var list = $('#gsquad_blogbundle_post_category');
    $(list).append( '<option id="newCategory">Nouvelle catégorie</option>' );

    var newCategory = $('#newCategory');

    //Si l'utilisateur choisit nouvelle catégorie
    $(list).on('change', function(){
        if($(newCategory).is(':selected')) {
            //Modale avec input pour saisir le titre de la nouvelle catégorie
            $('#addCategory').modal('show');

            $('#addNewCategory').on('click', function(){
               var newCategoryTitle = $('#category_name').val();
               newCategoryTitle = newCategoryTitle.charAt(0).toUpperCase() + newCategoryTitle.substring(1).toLowerCase();

                //On ajoute celle-ci à la liste déroulante et on ferme la modale
               $(list).prepend( '<option selected>' + newCategoryTitle +'</option>' );
               $('#addCategory').modal('hide');
            });
        }
    });
});
