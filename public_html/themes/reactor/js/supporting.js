function generateOptionGroup(id, title){
	var html = ''
	var dropList = ''
	
	dropList += '<select class="Product_option_ids" rel="'+id+'" title="'+title+'" name="Product[option_ids_drop]">' //stick option group id in here somewhere
	dropList += '<option selected="selected" value=""></option>'
	
	$.ajax({
		url: '/admin/ProductOption/dropList',
		dataType: 'json',
		data:{id: id},
		type: "POST",
		success: function(data) {
			
			//console.log(data, data.dropList.length);
			
			if(data.status == 'success') {
				$(data.dropList).each(function(index, element) {
					dropList += '<option value="'+element.id+'" price="'+element.price+'" code="'+element.code+'">'+element.title+'</option>'
				});
			}
			dropList += '<option disabled="disabled" value="-">--------------------</option>'
			if(data.dropList.length > 0) dropList += '<option value="all">Add All Options</option>'
			dropList += '<option value="new">Add New Option</option>'
			dropList += '</select>'
			
			html += '<fieldset id="groupId_'+id+'">'
			html += '<input type="hidden" name="Product[optionGroup_ids][]" value="'+id+'">'
			html += '<legend>'+title+'</legend>'
			html += '<p><span class="option">Option</span><span class="price">Price</span><span class="code">Product Code Prefix/Sufix</span><span class="sale">On Sale</span></p>'
			html += '<div class="optionsWrapper"></div>'
			html += dropList
			html += '</fieldset>'
			
			$('#optionGroups').append(html)
			
		},
		
	});
	
	
	
}

function generateOption(groupId, id, title, price, code){
	var html = ''
	
	html += '<div id="groupOption_groupID_'+groupId+'_optionID_'+id+'">'
	
	html += '<input type="hidden" name="Product[options]['+groupId+']['+id+'][options_new]" value="1">'
	
	html += '<div class="optionColumn option">'
	html += '<input id="ytoption_status_'+id+'" type="hidden" name="Product[options]['+groupId+']['+id+'][options_status]" value="0">'
	html += '<input id="option_status_'+id+'" type="checkbox" name="Product[options]['+groupId+']['+id+'][options_status]" value="1" checked="checked">'
	html += '<label for="option_status_'+id+'">'+title+'</label>'
	html += '</div>'

	html += '<div class="optionColumn price">'
	html += '<input id="option_price_'+id+'" type="text" value="" name="Product[options]['+groupId+']['+id+'][options_price]" maxlength="20" size="10">'
	html += '</div>'
	
	html += '<div class="optionColumn code">'
	html += code
	html += '</div>'
	

	html += '<div class="optionColumn sale">'
	html += '<input id="ytoption_sale_'+id+'" type="hidden" name="Product[options]['+groupId+']['+id+'][options_sale]" value="0">'
	html += '<input id="option_sale_'+id+'" type="checkbox" name="Product[options]['+groupId+']['+id+'][options_sale]" value="1">'
	html += '</div>'
	
	html += '<div class="clear"></div>'
	html += '</div>'
	
	$('.optionsWrapper','#groupId_'+groupId).append(html)
	
	//console.log(groupId, id, title, price, code)
}


$(document).ready(function(){


	//*************************************************************************
	// Hides/shows correct options depending on state of Global Price checkbox
	//*************************************************************************
	$('#Product_global_price').bind('click', function(e) {
		var priceSaleDiv = $('.priceSale')
		var optionsDiv = $('.options')
		
		if(this.checked){
			priceSaleDiv.slideDown('slow');
			optionsDiv.slideUp(500)
		}else{
			priceSaleDiv.slideUp(500)
			optionsDiv.slideDown('slow');
		}

		//e.preventDefault();
	});
	
	/*if($('#Product_global_price').is(":checked") == false){
		$('.priceSale').hide();
	}else{
		$('.options').hide();
	}*/
	
	
	//*************************************************************************
	// Displays 'Add New Option Group' dialog if Add is selected in Option Group droplist 
	//*************************************************************************
	$('#Product_optionGroup_ids_drop').change(function() {
		
		switch($(this).val()){
			case 'new':
				$(this).val("")
				$("#ProductOptionGroup_title").val("")
				$("#AddNewOptionGroup").dialog("open");
			break
			
			case '':
				//do nothing
			break
			
			default:
				$selected = $('option:selected', this)
				//$("#selectBox").append('<option value="option5">option5</option>');

				generateOptionGroup($(this).val(), $selected.html())
			
				$selected.remove();
			break
		
		}

	});
	
	$("#Product_optionGroup_ids_drop option[value='-']").attr('disabled', 'disabled');

	
	
	//*************************************************************************
	// Displays 'Add New Option' dialog if Add is selected in Group droplist 
	//*************************************************************************
	$('.Product_option_ids').live("change", function() {

		switch($(this).val()){
			case 'new':
				$(this).val("")
			
				$('#ui-dialog-title-AddNewOption').html('Add New Option - '+$(this).attr('title'))
				$('#ProductOption_group_id').val($(this).attr('rel'))
				
				$("#ProductOption_title").val("")
				$("#ProductOption_global_price").val("")
				$("#ProductOption_global_code").val("")
				
				$("#AddNewOption").dialog("open");
			break
			
			case 'all':
				options = $('option[price]',this)
				groupId = $(this).attr('rel')
				options.each(function(index, element) {
					
					generateOption(groupId,$(element).val(), $(element).html(),$(element).attr('price'),$(element).attr('code'))
					$(element).remove();
				});
				$("option[value='all']", this).remove();
			break
			
			case '':
				//do nothing
			break
			
			default:
				$selected = $('option:selected', this)

			
				generateOption($(this).attr('rel'),$(this).val(), $selected.html(),$selected.attr('price'),$selected.attr('code'))
				
				$selected.remove();
				
				//remove Add All Options if none left in list...
				if($('option',this).length == 4){
					$("option[value='all']", this).remove();
				}
			break
		}

	});

	
});