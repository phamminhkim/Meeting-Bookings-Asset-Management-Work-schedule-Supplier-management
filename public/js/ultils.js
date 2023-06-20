 
 function fixDigit(val){
  return val.toString().length === 1 ? "0" + val : val;
 }
 function getIconFile(ext){
    fileIcon = "";
    if (ext) {
        
    } else {
        
    }
    switch (ext) {
         case 'docx':
            fileIcon = "far fa-fw fa-file-word";
              break;
        case 'pdf':
       fileIcon = "far fa-fw fa-file-pdf";
             break;
        case 'mln':
        fileIcon = "far fa-fw fa-envelope";
             break;
        case 'png':
        case 'jpg':
        case 'jpeg':
        case 'gif':
        case 'bmp':
        case 'jfif':

        fileIcon = "far fa-fw fa-image";
             break;
        case 'xls':
        case 'xlsx':
        fileIcon = "fas fa-fw fa far fa-file-excel";
        break;
       
         default:
         fileIcon = "far fa-fw fas fa-file-alt";
         break;
        
    }
    return fileIcon;
}
function inputFormat(event){
     
     var extra = 0;
      // When user select text in the document, also abort.
    var selection = window.getSelection().toString();
    if (selection !== '') {
      return;
    }

    // When the arrow keys are pressed, abort.
    if ($.inArray(event.keyCode, [38, 40, 37, 39]) !== -1) {
      if (event.keyCode == 38) {
        extra = 1000;
      } else if (event.keyCode == 40) {
        extra = -1000;
      } else {
        return;
      }

    }

    var $this = $(this);
    // Get the value.
    var input = $this.val();
    var input = input.replace(/[\D\s\._\-]+/g, "");
    input = input ? parseInt(input, 10) : 0;
    input += extra;
    extra = 0;
    $this.val(function() {
      return (input === 0) ? "" : input.toLocaleString("en-US");
    });
}