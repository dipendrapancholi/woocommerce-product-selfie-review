jQuery(document).ready(function($){

	$('.close').click(function(){

		$("#sidebar_modal_right").hide();

	});

	$('.attachmentLink').click(function(){

		var comment = $(this).attr('data-content');

		var rating 	= $(this).attr('data-rating');

		var date 	= $(this).attr('data-date');

		var author 	= $(this).attr('title');

		var imgsrc  = $(this).find('img').attr('src');

		if( rating == 3){
			rating_label = 'Very Poor';
			rating_width = '0%';
		}

		/*var rating_label = '';
		var rating_width = '';*/

		if( rating == 1){
			rating_label = 'Very Poor';
			rating_width = '20%';
		} else if(rating == 2){
			rating_label = 'Not that bad';
			rating_width = '40%%';
		}else if(rating == 3){
			rating_label = 'Average';
			rating_width = '60%';
		}else if(rating == 4){
			rating_label = 'Good';
			rating_width = '80%';
		}else{
			rating_label = 'Perfect';
			rating_width = '100%';
		}

		$(".date.LTgray").text(date);

		$(".selfieDescription").html(comment);

		$("._reviewUserName").text(author);

		$(".reviewer-imgName").text(author.charAt(0));
		
		$(".selfie-headLine").html(rating_label);

		$(".filled-stars").width(rating_width);

		$(".pdpSelfieImg").attr("src", imgsrc);
		
		$("#sidebar_modal_right").show();

	});

});