var inputText = ["Tell us how a simple chat on public transport made you smile, made you think, made your day, or ultimately changed your life...","Name", "City", "Email Address"];


$('textarea')
.click(function(){
	if($(this).val() == inputText[0]){
		$(this).val(''); 
	};
})
.blur(function(){
	if($(this).val() == ''){
		$(this).val(inputText[0])
	};
});
$('input.name')
.click(function(){
	if ($(this).val() == inputText[1]) {
		$(this).val('');
	};
})
.blur(function(){
	if (!$(this).val()) {
      	$(this).val(inputText[1]);
    };
});
$('input.city')
.click(function(){
	if ($(this).val() == inputText[2]) {
		$(this).val('');
	};
})
.blur(function(){
	if (!$(this).val()) {
      	$(this).val(inputText[2]);
    };
});

$('input.email')
.click(function(){
	if ($(this).val() == inputText[3]) {
		$(this).val('');
	};
})
.blur(function(){
	if (!$(this).val()) {
		$(this).val(inputText[3]);
	};
});

$('#submit').click(function(){
	$('form.ajax').submit();
});
$('form.ajax').on('submit', function(){
	$.ajax({
		url: $(this).attr('action'),
		type: $(this).attr('method'),
		data: $(this).serialize(),
		success: function(response){
			if(validate(response)){
				// have the controller build the html for the newly added comment
				// return it in the response. 
				responseArr = JSON.parse(response);
				responseHtml = responseArr[1];
				$('#form').html(responseHtml);
				scrollUp();
				moveComments();
			}else{
				//try stuff in here with an empty form
			};
			// get height of #form 
			// move left column down #form height
			// move bubble to final position
			// no need to redraw, view will handle. 
		}
	});
	return false;
});

function validate(response){
	responseArr = JSON.parse(response);
	success = responseArr[0];
	if (success) { return true;
	} else{
		for(i in responseArr){
			name = responseArr[i];
			switch(name){
				case 'text':
				handle = 'textarea';
				break;
				default:
				handle = "input." + name;
				break;
			};
		$(handle).addClass("error");
		}
	};
};

function moveComments(){
	heightOffset = $('#form').outerHeight();
	heightOffset += 80;//comment tail
	widthOffset = $('#form').outerWidth()/2;
	widthOffset += 20;

	topOffset = "+=" + widthOffset + "px";
	leftOffset = "+=" + heightOffset + "px";
	
	$('.left').each(function(){
		console.log("hit!");
		$(this).animate({top: leftOffset}, 400);
	});
	$("#form").animate({right: widthOffset}, 500);
	$('#form').animate({top: heightOffset}, 600)
	$('.speech').each(function(){
		//fix this.
		$(this).animate({top: "-=300px"}, 1000)
	});
};

function scrollUp(){
	var offset = $('#headline').offset();
	offset.left -= 20;
	offset.top -= 20;
	$('html, body').animate({
    scrollTop: offset.top,
    scrollLeft: offset.left
	});
};



