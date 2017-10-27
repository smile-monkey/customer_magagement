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
				show_customer_edit();
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
	});

	// $(".customer-body").hide();
	// $(".customer-add").show();
	// $(".group-add").hide();

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
				// window.location.reload();
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {

			}
		});
	});

	function show_customer_edit() {
		$(".customer-table .edit").on("click",function(e){
			e.preventDefault();
			$(".customer-edit").show();
			$(".customer-body").hide();

			var customer_id = $(this).attr("data-cusotmer_id");
			var data = {
				action: "show_customer_edit",
				customer_id: customer_id
			};
			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: data,
				success: function(response){
					$(".customer-edit").html(response);
					show_customer_nav(customer_id);
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {

				}
			});			
		});

		$(".customer-table .view").on("click",function(e){
			// e.preventDefault();
			console.log($(this).attr("data-cusotmer_id"));
		});
	}

	function show_customer_nav(customer_id) {
		$(".customer_edit_nav .nav-tab").on("click", function(e){
			$(".customer_edit_nav .nav-tab").removeClass("nav-tab-active");
			$(this).addClass("nav-tab-active");
			var data = {
				action: "show_customer_nav",
				customer_id: customer_id,
				nav_id: e.target.id
			};
			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: data,
				success: function(response){
					$(".customer-edit-body").html(response);
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {

				}
			});			
		});

		$("#customer_edit_select").on("change", function(e){
			if (e.target.value == "view") {
				$(".customer-edit").hide();
				$(".customer-body").show();				
			}
		});
	}
});