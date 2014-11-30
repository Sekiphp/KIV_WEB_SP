	/**
	 * Vraci hodnotu parametru z GETU (URL)
	 */	 	
	function getUrlParameter(sParam){
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for(var i = 0; i < sURLVariables.length; i++){
      var sParameterName = sURLVariables[i].split('=');
      if(sParameterName[0] == sParam){
        return sParameterName[1];
      }
    }
	}   
  
	//<![CDATA[ 
  $(window).load(function(){ 
		
		/**
		 * Vyskakovaci okno v detailu tiketu
		 */		 		  
    $("#kontakt_dozor").click(function() {
      var url = "php/json_kontakt.php?data=" + getUrlParameter('idn');
      
      BootstrapDialog.show({
        title: 'Kontaktní údaje na dozor', 
        message: $('<div></div>').load(url)
      });  
    });
		
		/**
		 * Naseptavac zamestnancu
		 */		 		                         
    $("#zodpovedny").autocomplete({
      source: function( request, response ) {
        $.ajax({
          url: "php/json_zakym.php?data=" + $("#zodpovedny").val(),
          dataType: "json",
          data: {
            q: request.term
          },
          success: function( data ) {
            response( data );
          }                   
        });
      },
      select: function (event, ui) {
        event.preventDefault();      
        $("#zodpovedny").val(ui.item.label);
      }, 
      minLength: 3
    }); 
			
		/**
		 * Naseptavac dat z predeslych navstev
		 */		 			
    $("#jmeno").autocomplete({
      source: function( request, response ) {
        $.ajax({
          url: "php/json_navsteva.php?data=" + $("#jmeno").val(),
          dataType: "json",
          data: {
            q: request.term
          },
          success: function( data ) {
            response( data );
          }                   
        });
      },
      select: function (event, ui) {
        event.preventDefault();      
        $("#jmeno").val(ui.item.pretty);
        $("#nazev_firmy").val(ui.item.firma);
        $("#spz").val(ui.item.spz);
        $("#cop").val(ui.item.cop);
      }, 
      minLength: 3
    }); 

		/**
		 * Custmizovany autocomplete pro 3 inputy pro subnavstevniky 
		 */		 		
    $(".jmenosm").keydown(function() {
  		var cislo = ($(this).attr('id')).split("_");
  		
	    $(".jmenosm").autocomplete({
	      source: function( request, response ) {
	        $.ajax({
	          url: "php/json_navsteva.php?data=" + $("#name_" + cislo[1]).val(),
	          dataType: "json",
	          data: {
	            q: request.term
	          },
	          success: function( data ) {
	            response( data );
	          }                   
	        });
	      },
	      select: function (event, ui) {
	        event.preventDefault();      
	        $("#name_" + cislo[1]).val(ui.item.pretty);
	        $("#cop_" + cislo[1]).val(ui.item.cop);
	      }, 
	      minLength: 3
	    }); 		
		});
  });  
	//]]> 