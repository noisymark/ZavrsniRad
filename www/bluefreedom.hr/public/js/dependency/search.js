$( '#search' ).autocomplete({
    source: function(req,res){
       $.ajax({
           url: url + 'post/ajaxSearch/' + req.term,
           success:function(odgovor){
            res(odgovor);
            console.log(odgovor);
        }
       }); 
    },
    minLength: 2,
    select:function(dogadaj,ui){
        console.log(ui.item);
    }
}).autocomplete( 'instance' )._renderItem = function( ul, item ) {
    return $( '<li>' )
      .append( '<div onClick="location=\'' + url + 'post/index/' + item.sifraobjave + '\';" class="pt-4 m-auto border border-1 border-primary">' + '<p class="text-decoration-none text-break text-center">POST: ' + item.naslovobjave + '<br>FROM: '+ item.punoimekorisnika + '</p><div>')
      .appendTo( ul );
  };