var loadImageFile = function (fileInput) {
    if (window.FileReader) {
        var oPreviewImg = null, oFReader = new window.FileReader(),
            rFilter = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;

        oFReader.onload = function (oFREvent) {
            if (!oPreviewImg) {
                var newPreview = document.getElementById("imagePreview");
                oPreviewImg = new Image();
                oPreviewImg.style.width = (newPreview.offsetWidth).toString() + "px";
                oPreviewImg.style.height = (newPreview.offsetHeight).toString() + "px";
                newPreview.appendChild(oPreviewImg);
            }
            oPreviewImg.src = oFREvent.target.result;
        };

        return function () {
            var aFiles = fileInput.files;
            if (aFiles.length === 0) { return; }
            if (!rFilter.test(aFiles[0].type)) { alert("You must select a valid image file!"); return; }
            oFReader.readAsDataURL(aFiles[0]);
        }
    }
    if (navigator.appName === "Microsoft Internet Explorer") {
        return function () {
            alert(document.getElementById("imageInput").value);
            document.getElementById("imagePreview").filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src
                = document.getElementById("imageInput").value;

        }
    }
};

// by fileupload
function ajaxUpload(inputFile,url,fileName){
    if(!url || url.trim().length<=0){
        return;
    }
    if(!fileName){
        fileName = '';
    }
    if($('#ajaxframeFile').length>0){
        $('#ajaxframeFile').remove();
    }
    $("<iframe id='ajaxframeFile' name='frameFile' style='display: none;'></iframe>").appendTo('body');
    var html = "<form id='ajaxformFile' name='formFile' action='"+url+"' method='post' target='ajaxframeFile' \
        enctype='multipart/form-data'> \
            </form>";
    if($('#ajaxframeFile').length>0){
        $('#ajaxframeFile').remove();
    }
    $(html).appendTo('body');
    $(inputFile).clone().css('display','none').appendTo('#ajaxframeFile');
    $('<input type="hidden" name="fileName" value="'+fileName+'" />').appendTo('#ajaxframeFile');
    $('#ajaxframeFile').submit();
}

function ajaxDownload(url,params){
    params = params || {};
    var html = '<div style="display: none" id="download-fm"><form method="post" action="'+url+'">';
    for(var key in params){
        html += '<input type="hidden" name="'+key+'" value = "'+params[key]+'" />';
    }
    html += '</form></div>';
    if($('#download-fm').length>1){
        $('#download-fm').remove();
    }
    $(html).appendTo('body');
    $('#download-fm form').submit();
}

function dealWithAjaxUpload(url,before,complete,callback,cancelCb){
    if($('#form-upload input[name=\'file\']').val() != '') {

            $.ajax({
                url: url ,
                type: 'post',
                dataType: 'json',
                data: new FormData($('#form-upload')[0]),
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    if(before){
                        before();
                    }
                },
                complete: function() {
                    if(complete){
                        complete();
                    }
                },
                success: function(json) {
                    if(json.success===true){
                        if(callback){
                            callback(json);
                        }
                    }else{
                        showErrorText(json.error);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    showErrorText(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
    }else{
        if(cancelCb && $.isFunction(cancelCb)){
            cancelCb();
        }
    }
}

function commonFileUpload(url,data,callback,before,complete,cancelCb){
    $('#form-upload').remove();
    var html =
        '<form enctype="multipart/form-data" id="form-upload" method="post" style="display: none;">' +
        '<input type="file" name="file" value=""/>';
        for(var key in data){
            html = html + '<input type="hidden" name="'+key+'" value="'+data[key]+'" />';
        }
        html
            +='</form>';
    $('body').prepend(html);
    $('#form-upload input[name=\'file\']').on('change',function(){
        dealWithAjaxUpload(url,before,complete,callback,cancelCb);
    });
    $('#form-upload input[name=\'file\']').trigger('click');

}
