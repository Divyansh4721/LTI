$("#addLink").on('click', function() {
    request_id = $("#addLink").val();
    $.ajax({
        url: "ltilaunch/postresponsedata",
        contentType: 'application/json; charset=utf-8',
        data: JSON.stringify({
            request_id,
            selectedAssets
        }),
        type: 'POST',
        success: function(result) {
            $("#addform").append(result);
            $('#auto_submit').submit();
        }
    });
});

var count = 0;
if(selectedAssets.length >= 1) {
    var countCheckedcheckboxs = $.unique(selectedAssets).length;
    count = countCheckedcheckboxs;
    $('#non_selected_div').show();
    $('#addLink').removeAttr('disabled');
    $('#count-checked-checkboxs').text(countCheckedcheckboxs);
    $('#edit-count-checked-checkboxs').val(countCheckedcheckboxs);
}

var $checkboxs = $('input[type="checkbox"]');
$checkboxs.change(function() {
    var assetType = $(this).data('type');
    var assetTitle = $(this).data('title');
    var assetURL = $(this).data('url');
    var assetThumbnail = $(this).data('thumbnail');
    var id = $(this).data('id');
    var sectionType = $(this).data('section_type');
    var chapterId = $(this).data('chapter_id');
    var CoverImageUrl = $(this).data('cover_image_url');
    var UniqueId = $(this).attr('id');
    
    if($(this).is(":checked")) {
        $("input:checkbox[name='assetcheckbox']:checked").each(function() {
            selectedAssets.push({
                "assetType": assetType,
                "assetTitle": assetTitle,
                "assetURL": assetURL,
                "assetThumbnail": assetThumbnail,
                "id": id,
                "sectionType": sectionType,
                "chapterId": chapterId,
                "CoverImageUrl": CoverImageUrl,
                "UniqueId": UniqueId
            });
            selectedAssets = uniqBy(selectedAssets, JSON.stringify);
        });
    } else {
        selectedAssets = removeObjectWithId(selectedAssets, UniqueId);
        count = selectedAssets.length;
    }

    var countCheckedcheckboxs = selectedAssets.length;
    if (countCheckedcheckboxs >= 1) {
        $('#non_selected_div').show();
        $('#addLink').removeAttr('disabled');
    } else {
        $('#non_selected_div').hide();
        $('#addLink').attr('disabled', true);
    }
    $('#count-checked-checkboxs').text(countCheckedcheckboxs);
    $('#edit-count-checked-checkboxs').val(countCheckedcheckboxs);
});

function removeObjectWithId(arr, id) {
    const objWithIdIndex = arr.findIndex((obj) => obj.UniqueId === id);
    arr.splice(objWithIdIndex, 1);
    return arr;
}

function uniqBy(a, key) {
    var index = [];
    return a.filter(function (item) {
        var k = key(item);
        return index.indexOf(k) >= 0 ? false : index.push(k);
    });
}

$("#UncheckAll").click(function() {
    if (confirm("Are you sure want to remove all resources?") == true) {
        selectedAssets = [];
        $("input[type='checkbox']").prop('checked', false);
        $('#non_selected_div').hide();
        $('#addLink').attr('disabled', true);
        count = selectedAssets.length;
        if(count == 0) {
            $('.modal-body').html("<h6 class='text-align-center'>You don't have any selected items.</h6>");
        }
    }
});

$('#sort').on('change', function(e) {
    $(this).closest('form').submit();
});


$('#selected_view').on('click', function(ev){
    ev.preventDefault();
    $('.modal-body').empty();
    if(selectedAssets.length == '' || selectedAssets.length == 0) {
        $('.modal-body').html("<h6 class='text-align-center'>You don't have any selected items.</h6>");
        $('#non_selected_div').hide();
    }
    var selectedAssetsReverse = selectedAssets.slice().reverse();
    $.each(selectedAssetsReverse, function(key, value) {
        $('.modal-body').append("<div class='row' id='hide_"+value.UniqueId+"'>\
            <div class='row'>\
                <div class='col-2'><a href='"+value.assetURL+"' target='_blank'><img src="+value.CoverImageUrl+" onerror=this.src='/images/not-found-image.png' alt='' class='img-responsive'/></a>\
                </div>\
                <div class='col-9'><h6><a href='"+value.assetURL+"' target='_blank'>"+value.assetTitle+"</a></h6></div>\
                <div class='col-1'><input type='checkbox' class='checkbox-height-width' name='"+value.UniqueId+"'  id='remove_"+value.UniqueId+"' onclick='remove_items(this.id, this.name);' data-id='"+value.UniqueId+"' data-cover_image_url='"+value.CoverImageUrl+"' data-url='"+value.assetURL+"' data-type='link' data-content_type='"+value.ContentType+"' data-section_type='"+value.sectionType+"' data-chapter_id='"+value.chapterId+"' data-title='"+value.Title+"' data-thumbnail='"+value.CoverImageUrl+"' checked/></div>\
            </div>\
            <hr/>\
        </div>");
    });
});


function remove_items(id, uniqueId) {
    if (confirm("Are you sure want to remove this resource?") == true) {
        $('#hide_'+uniqueId).hide();
        $("input:checkbox[id='"+uniqueId+"']").prop('checked', false);
        selectedAssets = removeObjectWithId(selectedAssets, uniqueId);
        count = selectedAssets.length;
        if(count == 0) {
            $('.modal-body').html("<h6 class='text-align-center'>You don't have any selected items.</h6>");
            $('#non_selected_div').hide();
        }
        $('#count-checked-checkboxs').text(count);
        $('#edit-count-checked-checkboxs').val(count);
    } else {
        $("input:checkbox[data-id='"+uniqueId+"']").prop('checked', true);
    }
}