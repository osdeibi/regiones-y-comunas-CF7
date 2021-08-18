jQuery(function($){
	$('select.departamento').val($(".departamento option:first").val());
	$("select.ciudad").prop("disabled", true);
	$('select.ciudad').val($(".ciudad option:first").val());
	$("select.departamento").change(function(){
		$("select.ciudad").addClass('loading');
		$("select.ciudad").prop("disabled", true);
		if($("select.ciudad").length>0)
		{
			var sid=$(this).children("option:selected").attr('data-id');
			$("select.ciudad").html('<option value="0">---</option>');
			jQuery.ajax({
				url : dycdc_ajax.ajax_url,
				type : 'post',
				dataType : "json",
				data : {action: "dycdc_get_ciudades",nonce_ajax : dycdc_ajax.nonce,sid:sid},
				success : function( response ) {
					for(i=0;i<response.length;i++)
					{
						var ct_id=response[i]['id'];
						var ct_name=response[i]['name'];
						var opt="<option value='"+ct_name+"' data-id='"+ct_id+"'>"+ct_name+"</option>";
						$("select.ciudad").append(opt);
					}
					$("select.ciudad").prop("disabled", false);
					$("select.ciudad").removeClass('loading');
				}
			});
		}
	});
});
