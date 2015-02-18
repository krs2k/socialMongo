function NotifyBox(notify)
{
	var notifyList = $('ul.notify');
	notifyList.hide();
	notifyList.menu();

	this.buildNotifyMenu = function(list, items){
		$.each(items, function(i)
		{
		var res = items[i].split("#");
	    var li = $('<li/>')
	        .addClass('ui-menu-item')
	        .attr('role', 'menuitem')
	        .appendTo(list);
	    var aaa = $('<a/>')
	        .addClass('ui-all')
	        .prop("href", "index.php?controller=users&task=show&id="+res[1])
	        .text(res[0])
	        .appendTo(li);
		});
	}
	$('#notify').click(function() {
		if (notifyList.is(':hidden')){
			notifyList.show();
		}
		else
			notifyList.hide();
		var offset =  $(this).offset(),
		h = $( this ).height();
	   	notifyList.offset({ top: offset.top+h+15, left: offset.left-160});
	});

	if (notify.length){
		$( "#notifyCount" ).html(notify.length)
		this.buildNotifyMenu(notifyList, notify);
	}else
		$( "#notifyCount" ).hide();
}