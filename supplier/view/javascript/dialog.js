function showErrorText(text){
    BootstrapDialog.show({
        title: '',
        message: text||'',
        type: BootstrapDialog.TYPE_DANGER, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        showHeader:false,
        callback: function(result) {
            // result will be true if button was click, while it will be false if users close the dialog directly.
            alert('Result is: ' + result);
            
        }
    });
}
function showSuccessText(text){
    BootstrapDialog.show({
        title: '',
        message: text||'',
        type: BootstrapDialog.TYPE_SUCCESS, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        showHeader:false,
        callback: function(result) {
            // result will be true if button was click, while it will be false if users close the dialog directly.
            alert('Result is: ' + result);
            
        }
    });
}
function showHtmlWin(title,html,callback){
	BootstrapDialog.show({
        title: title||'',
        message: html||'',
        type: BootstrapDialog.TYPE_DEFAULT, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        buttonLabel: '确定', // <-- Default value is 'OK',
        callback: function(result) {
            // result will be true if button was click, while it will be false if users close the dialog directly.
            if(result && callback){
				callback();
			} 
        }
    });
}
function showWideHtmlWin(title,html,callback){
	BootstrapDialog.show({
        size: BootstrapDialog.SIZE_WIDE,
        title: title||'',
        message: html||'',
        type: BootstrapDialog.TYPE_DEFAULT, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        buttonLabel: '确定', // <-- Default value is 'OK',
        callback: function(result) {
            // result will be true if button was click, while it will be false if users close the dialog directly.
            if(result && callback){
				callback();
			}
        }
    });
}
function showLargeHtmlWin(title,html,callback){
	BootstrapDialog.show({
        size: BootstrapDialog.SIZE_WIDE,
        title: title||'',
        message: html||'',
        type: BootstrapDialog.TYPE_DEFAULT, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        buttonLabel: '确定', // <-- Default value is 'OK',
        callback: function(result) {
            // result will be true if button was click, while it will be false if users close the dialog directly.
            if(result && callback){
				callback();
			}
        }
    });
}
function confirmWideHtmlWin(title,html,callback){
    return BootstrapDialog.confirm({
        size: BootstrapDialog.SIZE_WIDE,
        title: title||'',
        message: html||'',
        autoClose:false,
        type: BootstrapDialog.TYPE_DEFAULT, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        buttonLabel: '确定', // <-- Default value is 'OK',
        callback: function(result) {
            // result will be true if button was click, while it will be false if users close the dialog directly.
            if(result && callback){
                callback();
            }
        }
    });
}
function confirmLargeHtmlWin(title,html,callback){
    return BootstrapDialog.confirm({
        size: BootstrapDialog.SIZE_WIDE,
        title: title||'',
        message: html||'',
        autoClose:false,
        type: BootstrapDialog.TYPE_DEFAULT, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        buttonLabel: '确定', // <-- Default value is 'OK',
        callback: function(result) {
            // result will be true if button was click, while it will be false if users close the dialog directly.
            if(result && callback){
                callback();
            }
        }
    });
}
function showUrlWin(title,url,callback){
	BootstrapDialog.confirm({
        title: title||'',
        message: $('<div></div>').load(url),
        type: BootstrapDialog.TYPE_DEFAULT, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        buttonLabel: '确定', // <-- Default value is 'OK',
        callback: function(result) {
            // result will be true if button was click, while it will be false if users close the dialog directly.
            if(result && callback){
				callback();
			} 
        }
    });
}
function showLargeUrlWin(title,url,callback){
    return BootstrapDialog.confirm({
        size: BootstrapDialog.SIZE_WIDE,
        title: title||'',
        message: $('<div></div>').load(url),
        autoClose:false,
        type: BootstrapDialog.TYPE_DEFAULT, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        buttonLabel: '确定', // <-- Default value is 'OK',
        callback: function(result) {
            // result will be true if button was click, while it will be false if users close the dialog directly.
            if(result && callback){
                callback();
            }
        }
    });
}
function showHugeUrlWin(title,url,callback,loadCb,loadRequestParams){
    return BootstrapDialog.confirm({
        size: BootstrapDialog.SIZE_MAX_HUGE,
        title: title||'',
        message: $('<div></div>').load(url,loadRequestParams,function(response,status,xhr){
            if(loadCb && $.isFunction(loadCb)){
                loadCb(response,status,xhr);
            }
        }),
        autoClose:false,
        type: BootstrapDialog.TYPE_DEFAULT, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        buttonLabel: '确定', // <-- Default value is 'OK',
        callback: function(result) {
            // result will be true if button was click, while it will be false if users close the dialog directly.
            if(result && callback){
                callback();
            }
        }
    });
}

function confirmText(title,content,callback){
    BootstrapDialog.confirm({
        title: title||'',
        message: content||'',
        type: BootstrapDialog.TYPE_DEFAULT, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        btnOKLabel:'确定', // <-- Default value is 'OK',
        btnCancelLabel:'取消', // <-- Default value is 'OK',
        btnOKClass:'btn-success',
        btnCancelClass:'btn-danger',
        callback: function(result) {
            // result will be true if button was click, while it will be false if users close the dialog directly.
            if(result && callback){
                callback();
            }
        }
    });
}