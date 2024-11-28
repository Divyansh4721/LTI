$('document').ready(function(){
    $("p").click(function() {
        let id = this.id;
        let value = $(this).data('value');
        let input_type = $(this).data('input_type');
        var temp = '';
        if(input_type == 'input') {
            temp = $("<input>");
        } else {
            temp = $("<textarea>");
        }
        
        $("body").append(temp);
        temp.val(value).select();
        document.execCommand("copy");
        temp.remove();
        setTimeout(function() {
            $('#copied_tip').remove();
        }, 800);
        $('#'+id).append("<div class='tip' id='copied_tip'>Copied!</div>");
    });
});