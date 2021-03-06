jQuery(function ($) {

	$(document).ready(function(){
		$("#customer_list").trigger('click');
		document_action();
		$('.order-date').datepicker({dateFormat: 'yy-mm-dd'});
	});
	$('.delivery-datepicker').datepicker({dateFormat: 'yy-mm-dd'});
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
				customer_action();
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {

			}
		});
	});

	$("#customer_select").on("change", function(e){

		if (e.target.value == 0) return;

		$("#user_login").val('');
		$("#user_pass").val('');

		$(".customer-content").hide();
		var add_type = e.target.value;
		var add_body;
		switch (add_type) {
			case 'customer':
				add_body = ".customer-add";
				break;
			default:
				add_body = ".group-add";
		}

		if (add_type != "customer"){
			var data = {
				action: "get_customer_content",
				add_type: add_type
			};
			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: data,
				success: function(response){
					$(add_body).html(response);
					$(add_body).show();
					group_action(add_type);
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {

				}
			});
		} else {
			$(add_body).show();
		}
	});

    $('.multi-switch').multiSwitch({
		functionOnChange: function ($e) {
        	$("#user_status").val($($e).attr('value'));
    	}
    });

// Personal Information Page

	$(".customer-button").on("click", function(e){
		$(".customer-content").hide();
		$(".customer-body").show();
		$("#customer_select").val(0);
	});

	$("#shipping_chk_box").on("change", function(){
		if(this.checked) {
			$("#shipping_check").val(1);

			$("#shipping_address_1").val($("#billing_address_1").val());
			$("#shipping_city").val($("#billing_city").val());
			$("#shipping_state").val($("#billing_state").val());
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
	
	$("input[name=customer_type]").on("change", function(e){
		if (e.target.value == "Retailer") {
			$("#payment_method").val(1);
			$('#payment_method').prop('disabled', 'disabled');
		} else {
			$('#payment_method').prop('disabled', false);
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

// Documents Page

	$("#doc_btn").on("click", function(e){
		$(".popup_background").show();
		$("#popup_form").show();
		$("#file_path").val(null);

	});
	$("#doc_cancel_btn").on("click", function(e){
		$(".popup_background").hide();
		$("#popup_form").hide();		
	});
	$("#doc_save_btn").on("click", function(e){
		if($("#file_path").val() == '' || $("#file_path").val() == null){
			e.preventDefault();
			return false;
		}
	});
	$("#search_btn").on("click", function(e){
		var customer_id = $("#customer_id").val();
		var search_key = $("#search_box").val();
		var data = {
			action: "get_document_body",
			customer_id: customer_id,
			search_key: search_key
		};
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			success: function(response){
				$("#doc_body").html(response);
				document_action();
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {

			}
		});			
	});

	$("#search_box").keyup(function(event) {
	    if (event.keyCode === 13) {
	        $("#search_btn").trigger('click');
	    }
	});

	document_action = function() {
		$(".doc-action-icons").on("click", function(e){
			var data = {
				action: "process_document_action",
				selected_id: e.target.id
			};
			var message = e.target.title == "Delete" ? "" : "Successfully send!";
			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: data,
				success: function(response){
					if (message)
						alert(message);
					window.location.reload();
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {

				}
			});	
		});
	}

// Payment Terms Add Page
	group_action = function(add_type) {
		$("#customer_cancel_btn").on("click", function(e){
			$(".customer-content").hide();
			$(".customer-body").show();
			$("#customer_select").val(0);
		});
		$("#customer_save_btn").on("click", function(e){
			switch (add_type) {
				case 'group':
					if (!$("#group_name").val()){
						alert('Type Group Name');
						$("#group_name").focus();
						return;	
					}
					break;
				case 'price':
					if (!$("#price_name").val()){
						alert('Type Price Name');
						$("#price_name").focus();
						return;	
					}
					break;					
			}
			e.preventDefault();
			var form_data = $("#customer_content_data").serialize();
			var data = {
				action: "save_customer_content_data",
				customer_content_type: add_type,
				form_data: form_data
			};
			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: data,
				success: function(response){
					$("#"+add_type+"_list").trigger("click");
					$("#customer_cancel_btn").trigger("click");
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {

				}
			});	
		});
		
		if ($("input[name=cut_off_time]:checked").val() == 1) {
			$("#cut_time_table").show();
		}
		$(".cut-off-time").on("click", function(e){
			if (e.target.value == 1) {
				$("#cut_time_table").show();
			} else {
				$("#cut_time_table").hide();
			}
		});

		if ($('input[name=price_rule]:checked').val() == 0) {
		 	$("#product_list").show();
		}
		if ($("#number_round").is(":checked")){
			$("#number_round").val(1);
		}

		$(".price-rule").on("change", function(e){
			if (e.target.value == 1) {
				$("#product_list").hide();
			} else {
				$("#product_list").show();
			}
		});

		$("#number_round").on("change", function(e){
			if (this.checked){
				$("#number_round").val(1);
			}else {
				$("#number_round").val(0);
			}
		});		
	}
// Customer Content Update Page
	customer_action = function() {
		$(".customer-content-edit").on("click", function(e){
			var add_type = this.getAttribute("data-type");
			var row_id = this.getAttribute("data-row-id");
			var data = {
				action: "get_customer_content",
				add_type: add_type,
				row_id: row_id
			};
			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: data,
				success: function(response){
					$(".group-add").html(response);
					$(".customer-body").hide();
					$(".group-add").show();
					group_action(add_type);
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {

				}
			});
		});
	}
// Transaction Page
	$("#order_search_btn").on("click", function(e){
		var customer_id = $("#order_customer_id").val();
		var start_date = $("#order_start_date").val();
		var end_date = $("#order_end_date").val();
		var order_search_box = $("#order_search").val();

		if (start_date > end_date) {
			alert("Invalid Date Range");
			$("#order_start_date").focus();
			return false;
		}
		var data = {
			action: "get_transaction_data",
			customer_id: customer_id,
			start_date: start_date,
			end_date: end_date,
			order_search_box: order_search_box
		};		
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			success: function(response){
				$("#transaction_body").html(response);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {

			}
		});
	});
});

function select_file(files) {
	if(jQuery(files[0]).attr("name")){
		jQuery("#file_path").val(files[0].name);
	}
}