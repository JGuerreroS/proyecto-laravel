var url = 'http://proyecto-laravel.com.devel';

window.addEventListener("load", function(){

    like();
    dislike();

    $('.btn-like').css('cursor','pointer');
    $('.btn-dislike').css('cursor','pointer');

    // Boton like
    function like(){
        $('.btn-like').unbind('click').click(function(){
            console.log('Like');
            $(this).addClass('btn-dislike').removeClass('btn-like');
            $(this).attr('src','img/heart-red.png');

            dislike();

            $.ajax({
                type: "get",
                url: url + "/like/" + $(this).data('id'),
                success: function (response){
                    if (response.like) {
                        console.log('Like Ajax');
                    }else{
                        console.log('Error en el like');
                    }
                }
            });

        });
    }

    // Boton dislike
    function dislike(){
        $('.btn-dislike').unbind('click').click(function(){
            console.log('Dislike');
            $(this).addClass('btn-like').removeClass('btn-dislike');
            $(this).attr('src','img/heart-gray.png');

            like();

            $.ajax({
                type: "get",
                url: url + "/dislike/" + $(this).data('id'),
                success: function (response){
                    if (response.like) {
                        console.log('Dislike Ajax');
                    }else{
                        console.log('Error en el dislike');
                    }
                }
            });

        });
    }
    
});