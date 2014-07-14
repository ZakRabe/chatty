//*************************************************************************
// Enables sorting of grid-view table rows
//*************************************************************************
function setUpSortable(){
	$("tbody").sortable({
		handle: '.upDown',
		helper: 'original',
		opacity: 0.8,
		axis: 'y',
		revert: true,
		start: function(event, ui){ //create //start
			var headerRow = $('thead tr',$(this).parent()).first()
			var tds = $('td',$(ui.item))
			
			$('th',$(headerRow)).each(function(index){
				$(tds[index]).width($(this).width())
			});
		},
		update: function(event, ui){
			var item_id = $('a.upDown',ui.item).attr('href');
			var newOrder = $('a.upDown',$(this)).sortable('toArray');
			var orderIds = [];
			
			newOrder.each(function(index){
				orderIds.push($(newOrder[index]).attr('href'))
			});
			
			if ($('.grid-view',document.body).attr('rel')){
				$.ajax({
					url: $('.grid-view',document.body).attr('rel')+'order',
					dataType: 'json',
					data:{id: item_id, newOrder:orderIds},
					type: "POST",
					success: function(data) {
						if(data.success) {
							$.fn.yiiGridView.update('user-grid'); 
						}
					},
				});
			}
		}
	});
}

$(document).ready(function(){

	setUpSortable()

	//*************************************************************************
	// Disable clicking on up/down icon
	//*************************************************************************
	$('a.upDown').bind('click', function(e) {
		e.preventDefault();
	});
});
