var url = document.URL.split('public')[0];

$( "#search" ).autocomplete({
    source: function( request, response ) {
        $.ajax({
            dataType: "json",
            type : 'Get',
            url: 'index.php?controller=find&task=autocomplete',
            data:{
                term: request.term,
            },
            success: function(data) {
                response( $.map( data, function(user) {
                    if (!user.profilePhoto) 
                       user.profilePhoto = 'public/images/nophoto.png';
                   else
                        user.profilePhoto= "tmp/images/"+user._id.$id+"/"+ user.profilePhoto;
                    return {       
                        label: user.name+" "+user.lastName,
                        id: user._id.$id,
                        photoUrl: user.profilePhoto
                    };
                }));
            },
        });
    },
    select: function( event, ui ) {
        window.location.replace("index.php?id="+ui.item.id)
    }
}).autocomplete( "instance" )._renderItem = function( ul, item ) {
    return $( "<li></li>" )
        .data( "item.autocomplete", item )
        .append("<img class='findPhotoBox' src='"+ item.photoUrl+ "' height='25' width='25'/><span class='findNameBox'>" + item.label+ "</span>" )
        .appendTo( ul );
};;
