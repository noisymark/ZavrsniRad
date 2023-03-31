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
        spremi(ui.item);
    }
}).autocomplete( 'instance' )._renderItem = function( ul, item ) {
    return $( '<li>' )
      .append( '<div> <img src="https://picsum.photos/30/30" />' + item.ime + ' ' + item.prezime + '<div>')
      .appendTo( ul );
  };



  function spremi(polaznik){
    $.ajax({
        url: url + 'osoba/dodajpolaznik?grupa=' + grupasifra + 
             '&polaznik=' + polaznik.sifra,
        success:function(odgovor){
           $('#podaci').append(
            '<tr>' + 
                '<td>' +
                    polaznik.ime + ' ' + polaznik.prezime +
                '</td>' + 
                '<td>' +
                    '<a class="brisiPolaznika" href="#" id="p_' + polaznik.sifra +  '">' +
                    ' <i class="fi-trash"></i>' +
                    '</a>' +
                '</td>' + 
            '</tr>'
           );
     }
    }); 
}