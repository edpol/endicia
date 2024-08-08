let fileObj;

function upload_file(e) {
    e.preventDefault();
    fileObj = e.dataTransfer.files[0];
    ajax_file_upload(fileObj);
}

function file_explorer() {
    document.getElementById('selectfile').click();
    document.getElementById('selectfile').onchange = function() {
        fileObj = document.getElementById('selectfile').files[0];
        ajax_file_upload(fileObj);
    };
}

function ajax_file_upload(fileObj) {
console.log(fileObj);
    if(fileObj !== undefined) {
        let form_data = new FormData();
        form_data.append('file', fileObj);
        $.ajax({
            type: 'POST',
            url: 'ajax.php',
            contentType: false,
            processData: false,
            data: form_data,
            success:function(response) {
//                alert(response);
                $('#selectfile').val('');
                location.href = "uploadMOM.php"
            }
        });
    }
}

/*
 *    setup listeners on class blue, pink and admin
 */
function buttonSetup(button) {

    function makeItHappenDown(x,buttonDown) {
        return function(){
            x.className=buttonDown;
        }
    }
    function makeItHappenUp(x,buttonUp) {
        return function(){
            x.className=buttonUp;
        }
    }

    if (document.getElementsByClassName(button+"_up")) {
        let a = document.getElementsByClassName(button+"_up");
        let x;
        for (let i = 0; i < a.length; ++i) {
            x = a[i];
            x.addEventListener("mousedown", makeItHappenDown(x,button+"_down"), false);
            x.addEventListener("mouseup", makeItHappenUp(x,button+"_up"), false);
        }
    }
}

function disableButton() {
    document.getElementById("myBtn").disabled = true;
//    document.getElementById("myForm").submit();
    return true;
}

window.onload = function () {
    buttonSetup("blue");
}