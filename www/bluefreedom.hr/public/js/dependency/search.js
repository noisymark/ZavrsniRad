let search='';
$( '#search' ).autocomplete({
    source: function(req,res){
        search=req.term;
       $.ajax({
           url: url + 'post/ajaxSearch/' + req.term,
           success:function(odgovor){
            res(odgovor);
            //console.log(odgovor);
        }
       }); 
    },
    minLength: 2,
}).autocomplete( 'instance' )._renderItem = function( ul, item ) {
    switch (item.type) {
        case 'user':
            var forward='user/profile/';
            break;
        case 'post':
            var forward='post/index/';
            break;
        default:
            break;
    }
    return $( '<li>' )
      .append( '<div onClick="window.location.href=\''+ url + forward + item.id +'\'") class="pt-4 m-auto border border-1 border-primary">' + '<p class="text-decoration-none text-break text-center">' + item.type+ ': ' + item.text + '</p><div>')
      .appendTo( ul );
  };