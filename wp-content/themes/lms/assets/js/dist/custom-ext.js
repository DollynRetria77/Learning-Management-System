jQuery.noConflict();
(function($){
    $(function(){
        //alert('ici présent');
        var $result = $('.result');
        var $listeDesEtudiants = $('.listeDesEtudiants');

        $('#je_participe').on('click', function(e){
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url,
                success : function(data, textStatus, jqXHR){
                    console.log(jqXHR);
                    $result.addClass('alert alert-success');
                    $result.text(data);
                },
                error : function(jqXHR){
                    //console.log(jqXHR);
                    $result.addClass('alert alert-danger');
                    $result.text(jqXHR.responseText);
                }
            })
        });

        $('.nb_etudiant').on('click', function(e){
            e.preventDefault();

            var post_id = $(this).attr('data-post-id');
            $.ajax({
                url: ajaxurl,
                type: "GET",
                data: {
                    'action': 'liste_etudiants', //fonction lancée côté PHP
                    'post_id': post_id //identifiant
                }
            }).done(function(response) {
                //console.log(response);
                $('.listeDesEtudiants').html(response); // Afficher le HTML
                $('#btn-popup').trigger('click');
            });
        });

        $('.slider-hp').slick({
            infinite: true,
            speed: 200,
            slidesToShow: 1,
            slidesToScroll: 1,
            adaptiveHeight: true,
            arrows: false, 
            dots: false, 
            autoplay:true,
        
            responsive: [{
              breakpoint: 800,
              settings: {
                arrows : false,
                dots: false
              }
            }]
          });
       
    });
})(jQuery);



