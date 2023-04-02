let search='';
let forward='';
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
    select:function(dogadaj,ui){
        switch (ui.item.type) {
            case 'user':
                forward='user/profile/';
                window.location.href=url + forward + item.id;
                break;
            case 'post':
                forward='post/index/';
                window.location.href=url + forward + item.id;
                break;
            default:
                break;
        }
    }
}).autocomplete( 'instance' )._renderItem = function( ul, item ) {
    return $( '<li>' )
      .append( '<div class="pt-4 m-auto border border-1 border-primary">' + '<p class="text-decoration-none text-break text-center">' + item.type+ ': ' + item.text + '</p><div>')
      .appendTo( ul );
  };