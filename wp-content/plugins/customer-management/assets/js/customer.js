jQuery(function ($) {

	$(document).ready(function(){
		$("#customer_list").trigger('click');
	});

	$(".customer-list li").on("click",function(e){
		var obj = e.target;
		$( ".customer-list li" ).each(function() {
			obj == this ? $(this).addClass("list-selected") : $(this).removeClass("list-selected");
		});
		var data = {
			action: "show_list",
			list_id: obj.id
		};
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			success: function(response){
				$("#main_content").html(response);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {

			}
		});
	});

	$("#customer_select").on("change", function(e){
		if (e.target.value == 0) return;
		$("#user_login").val("");
		$("#user_pass").val("");
		if (e.target.value == 'Customer') {
			$(".customer-body").hide();
			$(".customer-add").show();
			$(".group-add").hide();
		} else {
			// $(".group-add").show();
			// $(".customer-add").hide();
		}
	});

    $('.multi-switch').multiSwitch({
		functionOnChange: function ($e) {
        	$("#user_status").val($($e).attr('value'));
    	}
    });

	$(".customer-button").on("click", function(e){
		$(".customer-body").show();
		$(".customer-add").hide();
		$(".group-add").hide();
		$("#customer_select").val(0);
	});

	$("#shipping_chk_box").on("change", function(){
		if(this.checked) {
			$("#shipping_check").val(1);

			$("#shipping_address_1").val($("#billing_address_1").val());
			$("#shipping_address_2").val($("#billing_address_2").val());
			$("#shipping_city").val($("#billing_city").val());
			$("#shipping_postcode").val($("#billing_postcode").val());
			$("#shipping_country").val($("#billing_country").val());
		} else {			
			$("#shipping_check").val(0);
		}
	});

	$("#save_new_btn").on("click", function(e){
		e.preventDefault();
		var form_data = $("#add_form").serialize();
		var data = {
			action: "save_customer_data",
			form_data: form_data
		};
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			success: function(response){
				window.location.reload();
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {

			}
		});
	});

	$("#customer_edit_select").on("change", function(e){
		if (e.target.value == "view") {
			window.location = ajax_object.adminurl;
		}
	});

	$(".customer-edit-button").on("click", function(e){
		e.preventDefault();
		var form_data = $(".customer-edit-data").serialize();
		var data = {
			action: "save_customer_edit_data",
			form_data: form_data
		};
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			success: function(response){
				window.location.reload();
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {

			}
		});		
	});

});