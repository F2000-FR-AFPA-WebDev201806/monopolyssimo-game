$(document).ready(function () {
    
    console.log('READY TO GO !'); 

    $('main').on('click', '.annonce_pag', function () {
        console.log('Click sur TOTO');           

        $.ajax({
            async   : true,
            type    : 'GET',
            url     : 'ajax.php', //
            data    : 'numPage=' + $(this).attr('data-page'),
            success : function(data){
                $('main').html(data);
            } 
        });
    });
});
