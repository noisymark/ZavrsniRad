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