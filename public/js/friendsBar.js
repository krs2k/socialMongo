function FriendsBar(friends, friendRequests){
	$( "#friendsSearch" ).autocomplete({
      	minLength: 0,
		search: function(event, ui) {
			$( ".friendsList" ).html("")
		},
      	source: function( request, response ) {
	        var term  = new RegExp("^" + $.ui.autocomplete.escapeRegex(request.term), "i");
	        var resArray  = [];
	        response($.map( friends, function(friend) {
	        	if (term.test(friend.name) || term.test(friend.lastName) ) 
	        		return({label: 'a', value: friend.name, user: friend})
	        }))
        },
        open: function( event, ui ) {
	        $(".ui-autocomplete").hide();
	    }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
    	var user = item.user
    	var photoUrl = "public/images/nophoto.png";
    	if (user._id.$id)
    		user._id = user._id.$id;
    	if(user.profilePhoto)
			photoUrl = 'tmp/images/'+user._id+'/'+user.profilePhoto;

	  	return $( "<li>" )
      	.append('<img src="'+photoUrl+'">')
        .append('<p>'+user.name + " " + user.lastName +'</p>' )
        .appendTo( ".friendsList" )
        .prop('user', user)
		.click(function(){
			window.location.replace("index.php?id="+user._id)
		})
    };
  	$( "#friendsSearch" ).autocomplete( "search", "" );
}