$(document).ready(function () {
	$('#noti_Counter')
	    .css({ opacity: 0 })
	   	.css({ top: '-10px' })
	    .animate({ top: '-2px', opacity: 1 }, 500);

	$('#noti_Button').click(function () {
	    $('#notifications').fadeToggle('fast', 'linear', function () {
	        if ($('#notifications').is(':hidden')) {
	        }
	    });

	    $('#noti_Counter').fadeOut('slow');

	    return false;
	});
	$(document).click(function () {
	    $('#notifications').hide();

	    if ($('#noti_Counter').is(':hidden')) {
	    }
	});

	var url = 'search.php';

	$('#search-box').on('keyup', function(){
		var query = $(this).val();

		$.ajax({
			type: 'POST',
			url: url,
			data: {
				query: query
			},
			success: function(data){
				alert(data);

			}
		})
	});
});

$(document).ready(function () {
  $('.navmenu-1, .navmenu-2, .navmenu-3').on('click', 'li',function(){
    $('.navmenu-1 li.active, .navmenu-2 li.active, .navmenu-3 li.active').removeClass('active');
   	$(this).addClass('active');
  });
})

$(document).ready(function(){
  $(".button").click(function(){
    $(".modal").fadeIn();
    $("body").css("overflow", "hidden");
  });
  $(".cross").click(function(){
    $(".modal").fadeOut();
    $("body").css("overflow", "auto");
  });
});

$(function(){
  $('#tabs a ').click(function(){
      var page = this.hash.substr(1);
      $.get(page + ".php", function(gotHtml){
        $("#content").html(gotHtml);
    });
      hash = hash.replace(/^.*#/, '');
  });
});

