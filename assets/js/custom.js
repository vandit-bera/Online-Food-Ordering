jQuery('#frmRegister').on('submit',function(e){
	jQuery('.error_field').html('');
	jQuery('#register_submit').attr('disabled',true);
	jQuery('#form_msg').html('Please wait...');
	jQuery.ajax({
		url:FRONT_SITE_PATH+'login_register_submit',
		type:'post',
		data:jQuery('#frmRegister').serialize(),
		success:function(result){
			jQuery('#form_msg').html('');
			jQuery('#register_submit').attr('disabled',false);
			var data=jQuery.parseJSON(result);
			if(data.status=='error'){
				jQuery('#'+data.field).html(data.msg);
			}
			if(data.status=='success'){
				jQuery('#'+data.field).html(data.msg);
				jQuery('#frmRegister')[0].reset();
			}
		}
		
	});
	e.preventDefault();
});	


jQuery('#frmLogin').on('submit',function(e){
	jQuery('.error_field').html('');
	jQuery('#login_submit').attr('disabled',true);
	jQuery('#form_login_msg').html('Please wait...');
	jQuery.ajax({
		url:FRONT_SITE_PATH+'login_register_submit',
		type:'post',
		data:jQuery('#frmLogin').serialize(),
		success:function(result){
			jQuery('#form_login_msg').html('');
			jQuery('#login_submit').attr('disabled',false);
			var data=jQuery.parseJSON(result);
			if(data.status=='error'){
				jQuery('#form_login_msg').html(data.msg);
			}else{
				var is_checkout=jQuery('#is_checkout').val();
				if(is_checkout=='yes'){
					window.location.href='checkout';
				}else if(data.status=='success'){
					//jQuery('#form_login_msg').html(data.msg);
					window.location.href='shop';
				}
			}
		}
		
	});
	e.preventDefault();
});	


jQuery('#frmForgotPassword').on('submit',function(e){
	jQuery('#forgot_submit').attr('disabled',true);
	jQuery('#form_forgot_msg').html('Please wait...');
	jQuery.ajax({
		url:FRONT_SITE_PATH+'login_register_submit',
		type:'post',
		data:jQuery('#frmForgotPassword').serialize(),
		success:function(result){
			jQuery('#form_forgot_msg').html('');
			jQuery('#forgot_submit').attr('disabled',false);
			var data=jQuery.parseJSON(result);
			if(data.status=='error'){
				jQuery('#form_forgot_msg').html(data.msg);
			}
			if(data.status=='success'){
				jQuery('#form_forgot_msg').html(data.msg);
				//window.location.href='shop.php';
			}
		}
		
	});
	e.preventDefault();
});	


function set_checkbox(id){
	var cat_dish=jQuery('#cat_dish').val();
	var check=cat_dish.search(":"+id);
	if(check!='-1'){
		cat_dish=cat_dish.replace(":"+id,'');
	}else{
		cat_dish=cat_dish+":"+id;	
	}
	jQuery('#cat_dish').val(cat_dish);
	jQuery('#frmCatDish')[0].submit();
}

function setFoodType(type){
	jQuery('#type').val(type);
	jQuery('#frmCatDish')[0].submit();
}

function setSearch(){
	jQuery('#search_str').val(jQuery('#search').val());
	jQuery('#frmCatDish')[0].submit();
}

function add_to_cart(id,type){
	var qty=jQuery('#qty'+id).val();
	var attr=jQuery('input[name="radio_'+id+'"]:checked').val();
	var is_attr_checked='';
	if(typeof attr=== 'undefined'){
		is_attr_checked='no';
	}
	if(qty>0 && is_attr_checked!='no'){
		jQuery.ajax({
			url:FRONT_SITE_PATH+'manage_cart',
			type:'post',
			data:'qty='+qty+'&attt='+attr+'&type='+type,
			success:function(result){
				var data=jQuery.parseJSON(result);
				swal("Congratulation!", "Dish added successfully", "success");
				jQuery('#shop_added_msg_'+attr).html('(Added -'+qty+')');
				jQuery('#totalCartDish').html(data.totalCartDish);
				jQuery('#totalPrice').html(data.totalPrice+' Rs');
				var tp1=data.totalPrice;
				if(data.totalCartDish==1){
					var tp=qty*data.price;
					var html='<div class="shopping-cart-content"><ul id="cart_ul"><li class="single-shopping-cart" id="attr_'+attr+'"><div class="shopping-cart-img"><a href="javascript:void(0)"><img alt="" src="'+SITE_DISH_IMAGE+data.image+'"></a></div><div class="shopping-cart-title"><h4><a href="javascript:void(0)">'+data.dish+'</a></h4><h6>Qty: '+qty+'</h6><span>'+tp+' Rs</span></div><div class="shopping-cart-delete"><a href="javascript:void(0)" onclick=delete_cart("'+attr+'")><i class="ion ion-close"></i></a></div></li></ul><h4>Total : <span class="shop-total" id="shopTotal">'+tp+' Rs</span></h4><div class="shopping-cart-btn"><a href="cart">view cart</a><a href="checkout">checkout</a></div></div>';	
					jQuery('.header-cart').append(html);
				}else{
					var tp=qty*data.price;
					jQuery('#attr_'+attr).remove();
					var html='<li class="single-shopping-cart" id="attr_'+attr+'"><div class="shopping-cart-img"><a href="#"><img alt="" src="'+SITE_DISH_IMAGE+data.image+'"></a></div><div class="shopping-cart-title"><h4><a href="javascript:void(0)">'+data.dish+'</a></h4><h6>Qty: '+qty+'</h6><span>'+tp+' Rs</span></div><div class="shopping-cart-delete"><a href="javascript:void(0)" onclick=delete_cart("'+attr+'")><i class="ion ion-close"></i></a></div></li>';
					jQuery('#cart_ul').append(html);
					jQuery('#shopTotal').html(tp1+ 'Rs');
				}
				
			}
		});
	}else{
		swal("Error", "Please select qty and dish item", "error");
	}
}

function delete_cart(id,is_type){
	jQuery.ajax({
		url:FRONT_SITE_PATH+'manage_cart',
		type:'post',
		data:'attt='+id+'&type=delete',
		success:function(result){
			if(is_type=='load'){
				window.location.href=window.location.href;
			}else{
				var data=jQuery.parseJSON(result);
				//swal("Congratulation!", "Dish added successfully", "success");
				jQuery('#totalCartDish').html(data.totalCartDish);
				jQuery('#shop_added_msg_'+id).html('');
				
				if(data.totalCartDish==0){
					jQuery('.shopping-cart-content').remove();
					jQuery('#totalPrice').html('');
				}else{
					var tp1=data.totalPrice;
					jQuery('#shopTotal').html(tp1+ 'Rs');
					jQuery('#attr_'+id).remove();
					jQuery('#totalPrice').html(data.totalPrice+' Rs');
				}
			}
			
		}
	});
}


jQuery('#frmProfile').on('submit',function(e){
	jQuery('#profile_submit').attr('disabled',true);
	jQuery('#form_msg').html('Please wait...');
	jQuery.ajax({
		url:FRONT_SITE_PATH+'update_profile',
		type:'post',
		data:jQuery('#frmProfile').serialize(),
		success:function(result){
			jQuery('#form_msg').html('');
			jQuery('#profile_submit').attr('disabled',false);
			var data=jQuery.parseJSON(result);
			if(data.status=='success'){
				jQuery('#user_top_name').html(jQuery('#uname').val());
				swal("Success Message", data.msg, "success");
			}
		}
	});
	e.preventDefault();
});	

jQuery('#frmPassword').on('submit',function(e){
	jQuery('#password_submit').attr('disabled',true);
	jQuery('#password_form_msg').html('Please wait...');
	jQuery.ajax({
		url:FRONT_SITE_PATH+'update_profile',
		type:'post',
		data:jQuery('#frmPassword').serialize(),
		success:function(result){
			jQuery('#password_form_msg').html('');
			jQuery('#password_submit').attr('disabled',false);
			var data=jQuery.parseJSON(result);
			if(data.status=='success'){
				swal("Success Message", data.msg, "success");
			}
			if(data.status=='error'){
				swal("Error Message", data.msg, "error");
			}
		}
	});
	e.preventDefault();
});	

function apply_coupon(){
	var coupon_code=jQuery('#coupon_code').val();
	if(coupon_code==''){
		jQuery('#coupon_code_msg').html('Please enter coupon code');
	}else{
		jQuery.ajax({
			url:FRONT_SITE_PATH+'apply_coupon',
			type:'post',
			data:'coupon_code='+coupon_code,
			success:function(result){
				var data=jQuery.parseJSON(result);
				if(data.status=='success'){
					swal("Success Message", data.msg, "success");
					jQuery('.shopping-cart-total').show();
					jQuery('.coupon_code_str').html(coupon_code);
					jQuery('.final_price').html(data.coupon_code_apply+' Rs');
				}
				if(data.status=='error'){
					swal("Error Message", data.msg, "error");
				}
			}
		})
	}
}

function updaterating(id,oid){
	var rate=jQuery('#rate'+id).val();
	var rate_str=jQuery('#rate'+id+' option:selected').text();
	
	if(rate==''){
		//jQuery('#coupon_code_msg').html('Please enter coupon code');
	}else{
		jQuery.ajax({
			url:FRONT_SITE_PATH+'updaterating',
			type:'post',
			data:'id='+id+'&rate='+rate+'&oid='+oid,
			success:function(result){
				jQuery('#rating'+id).html("<div class='set_rating'>"+rate_str+"</div>");
			}
		})
	}
}