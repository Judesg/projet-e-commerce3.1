$(document).ready(function(){
	
$('#listCouleur').on('change', function()
{

 		if (this.value == 'all')
 		{
 			$('.classdata').show();
 		}
 		else
 		{
 			var elems = $('.classdata[data-color="'+this.value+'"]');
 			 			$('.classdata').not(elems).hide();
 			elems.show();
 		}
 });


// /* ajax*/
// 		var color = $( "#listCouleur option:selected" ).val();
// 		var url = $('.side-bar-produit').data('url');

// 		$.ajax({

// 			type:"POST",
// 			url: "http://localhost/symfony/projet-e-commerce3/web/app_dev.php/returnProductsByCategory",
// 			data: {color: color},

// 			success: function(data2) {
				
// 			},

// 			error: function(error, data, test) {

// 			}

// 		});

// 	});*/*/*/

$('#listMaterial').on('change', function()
	{
		if (this.value == 'all')
		{
			$('.clasdata').show();
		}
		else
		{
			var elems = $('.classdata[data-material="'+this.value+'"]');
			$('.classdata').not(elems).hide();
			elems.show();
		}
	});










$('.stat').on('click', function() {
	  var $stats = $('.stat:checked');
	  var $items = $('.content-produit-list article');

	  $items.show();
	  if ($stats.length == 0)
	    return;

	  var $vstats = $.map($stats, function(o) {return $(o).data('id'); });
	  
	  $stats.each(function() 
	  {
	  
	    var $stat = $(this);
	    $items.filter(function() {
	        return $vstats.indexOf($(this).data($stat.data('type'))) < 0;
	    }).hide();
	  })
	});




});