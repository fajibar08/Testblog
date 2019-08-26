//To preview post/user image
$(document).ready(function() {
    $('#preview_post_img').hide();
    $('#img-br').hide();
    $('body').on('change', '#file', function(){
        $('#preview_post_img').fadeIn();
        $('#img-br').show();
        var reader = new FileReader();
        reader.onload = imageIsLoaded;
        reader.readAsDataURL(this.files[0]);
                    
    });
     
    function imageIsLoaded(e) {
        $('#previewimg').attr('src', e.target.result);
        $('#preview_post_img').attr('src', e.target.result);
    };


//To preview edit post image    
    $('body').on('change', '#file-edit', function(){
        var reader = new FileReader();
        reader.onload = imageIsLoaded;
        reader.readAsDataURL(this.files[0]);
                    
    });
 
    function imageIsLoaded(e) {
        $("img[id]").each(function(){
            $(this).val( $(this).attr('src', e.target.result));
        });

        //$('#previewimg-edit').attr('src', e.target.result);
    };

    $(".alert-success").delay(500).show(10, function() {
        $(this).delay(3000).fadeOut(10, function() {
            $(this).fadeOut();
        });
    });

    $(".alert-danger").delay(500).show(10, function() {
        $(this).delay(3000).fadeOut(10, function() {
            $(this).fadeOut();
        });
    });

});
