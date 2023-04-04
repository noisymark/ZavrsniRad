$('#searchpostofusers').autocomplete({
    source: function(req,res){
       $.ajax({
           url: url + 'user/ajaxSearch/' + id + '?search=' + req.term,
           success:function(odgovor){
            res(odgovor);
            console.log(odgovor);
        }
       }); 
    },
    minLength: 2
}).autocomplete( 'instance' )._renderItem = function( ul, item ) {
    return $( '<li>' )
      .append( '<div onClick="location=\'' + url + 'post/index/' + item.postid + '\';" class="pt-4 m-auto border border-1 border-primary">' + '<p class="text-decoration-none text-break text-center">POST: ' + item.posttitle + '<br>FROM: '+ item.postauthor + '</p><div>')
      .appendTo( ul );
  };


  var userId;

  $(".profilePhoto").click(function(){
    var myModal = new bootstrap.Modal(document.getElementById("profilePhotoModal"), {});
      userId=$(this).attr("id").split("_")[1];
        $("#image").attr("src",$(this).attr("src"));
        myModal.show();
        definirajCropper();
  
        return false;
    });
  
    $("#savePhoto").click(function(){
      var opcije = { "width": 350, "height": 350 };
      var result = $image.cropper("getCroppedCanvas", opcije, opcije);
      //console.log(result.toDataURL());
  
      //ako želimo jpg https://github.com/fengyuanchen/cropperjs
      var Modal = bootstrap.Modal.getInstance(profilePhotoModal);
      $.ajax({
          type: "POST",
          url:  url + "/user/saveImage/",
          data: "id=" + userId + "&profilePhoto=" + result.toDataURL(),
          success: function(vratioServer){
            if(!vratioServer.error){
              $("#user_"+userId).attr("src",result.toDataURL());
              Modal.hide();
            }else{
              alert(vratioServer.description);
            }
          }
        });
  
  
      return false;
    });
  
  

  var $image;

  function definirajCropper(){


    var URL = window.URL || window.webkitURL;
    $image = $('#image');
    var options = {aspectRatio: 1 / 1 };

    // Cropper
    $image.on({}).cropper(options);

    var uploadedImageURL;


    // Import image
    var $inputImage = $('#inputImage');

    if (URL) {
      $inputImage.change(function () {
        var files = this.files;
        var file;

        if (!$image.data('cropper')) {
          return;
        }

        if (files && files.length) {
          file = files[0];

          if (/^image\/\w+$/.test(file.type)) {


            if (uploadedImageURL) {
              URL.revokeObjectURL(uploadedImageURL);
            }

            uploadedImageURL = URL.createObjectURL(file);
            $image.cropper('destroy').attr('src', uploadedImageURL).cropper(options);
            $inputImage.val('');
          } else {
            window.alert('Datoteka nije u formatu slike');
          }
        }
      });
    } else {
      $inputImage.prop('disabled', true).parent().addClass('disabled');
    }

    }
