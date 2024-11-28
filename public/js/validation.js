$('document').ready(function(){
    // $('input[type="submit"]').prop("disabled", true);
    var count = 0;
    //binds to onchange event of your input field
    $('#logo').bind('change', function() {
    if ($('input:submit').attr('disabled',false)){
        $('input:submit').attr('disabled',true);
        }
    var ext = $('#logo').val().split('.').pop().toLowerCase();
    if ($.inArray(ext, ['gif','png','jpg','jpeg','svg']) == -1){
        $('#error1').slideDown("slow");
        $('#error2').slideUp("slow");
        $('#error3').slideUp("slow");
        count = 0;
        $('#show_logo').hide();
        $('#show_logo_old').show();
        }else{
        var picsize = (this.files[0].size);
        //console.log(picsize);
        if (picsize > 150000){
        $('#error2').slideDown("slow");
        count = 0;
        $('#show_logo').hide();
        $('#show_logo_old').show();
        }else{
        count = 1;
        $('#error2').slideUp("slow");
        }
        $('#error1').slideUp("slow");
        $('#error3').slideUp("slow");
        if (count == 1){
            $('input:submit').attr('disabled',false);
        }
    }
    let img = new Image()
    img.src = window.URL.createObjectURL(event.target.files[0])
    img.onload = () => {
        if( (img.width < 50 || img.width > 150) || (img.height < 50 || img.height > 80)) {
            $('#error3').slideDown("slow");
            count = 1;
            $('#show_logo').hide();
            $('#show_logo_old').show();
            $('input:submit').attr('disabled',false);
            return false;
        }
    }
    });

});


var loadFile = function(event) {
    $('#show_logo').show();
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src)
    }
  };