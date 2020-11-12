$(document).on('ready', function(){
	var current_view = get_value_from_datalayer('current_view');
	var user_id = get_value_from_datalayer('userId');

	if (current_view == 'checkout' && eeMultiChanelVisitCheckoutStep) {
		eventDataLayerCheckoutNewStep(eeMultiChanelVisitCheckoutStep);
	} else if (current_view == 'purchase' && typeof measure_purchase != 'undefined' && eeMultiChanelFinishOrderStep) {
		eventDataLayerCheckoutNewStep(eeMultiChanelFinishOrderStep);
	} else if (current_view == 'product' && eeMultiChanelVisitProductPageStep) {
		eventDataLayerCheckoutNewStep(eeMultiChanelVisitProductPageStep);
	} else if (current_view == 'cart' && eeMultiChanelVisitCartPageStep) {
		eventDataLayerCheckoutNewStep(eeMultiChanelVisitCartPageStep);
	}
});

$(document).ajaxSuccess(function(event, xhr, settings, json) {
	if(settings.url == "index.php?route=checkout/cart/add" && !json.error) {
		//Measuring add to cart
		if(json['atc_id'])
			eventDataLayerAddToCart(json['atc_id'], json['atc_price'], json['atc_name'], json['atc_category'], json['atc_brand'], json['atc_quantity'], json['atc_variant']);
	} else if(settings.url == "index.php?route=account/wishlist/add" && !json.error) {
		//Measuring add to wishlist
		if(json['atw_id'])
			eventDataLayerAddToWishlist(json['atw_id'], json['atw_price'], json['atw_name'], json['atw_category'], json['atw_brand']);
	} else if(settings.url == "index.php?route=checkout/cart/remove" && !json.error) {
		//Measuring remove from cart
		if(json['atc_id'])
			eventDataLayerRemoveFromCart(json['atc_id'], json['atc_price'], json['atc_name'], json['atc_category'], json['atc_brand'], json['atc_quantity'], json['atc_variant']);
	}
});

$(document).on('click', 'a', function(event){
	var link = this.href.replace(/\&/g, '&amp;');

	if(typeof EePromotionsClick != 'undefined' && typeof EePromotionsClick[link] != 'undefined') {
        setPromotionClickDataLayer(EePromotionsClick[link]);
    } else if(typeof EeProductsClick != 'undefined'  && typeof EeProductsClick[link] != 'undefined') {
        setProductClickDataLayer(EeProductsClick[link]);
    }
});

function setProductClickDataLayer(json_data)
{
	var object = {};

	if(typeof json_data.id != 'undefined')
		object.id = json_data.id;

	if(typeof json_data.price != 'undefined')
		object.price = json_data.price;

	if(typeof json_data.name != 'undefined')
		object.name = json_data.name;

	if(typeof json_data.category != 'undefined')
		object.category = json_data.category;

	if(typeof json_data.brand != 'undefined')
		object.brand = json_data.brand;

	//Push product click in datalayer
	dataLayer.push({
		"event": "productClick",
		"productClicked": object
	});

	return true;
}

function setPromotionClickDataLayer(json_data)
{
	var object = {};

	if(typeof json_data.id != 'undefined')
		object.id = json_data.id;

	if(typeof json_data.name != 'undefined')
		object.name = json_data.name;

	if(typeof json_data.creative != 'undefined')
		object.creative = json_data.creative;

	if(typeof json_data.position != 'undefined')
		object.position = json_data.position;

	//Push product click in datalayer
	dataLayer.push({
		"event": "promoClick",
		"promotionClicked": object
	});

	return true;
}

function eventDataLayerAddToCart(product_id, price, name, category, brand, quantity, variant)
{
	var object = {};

	if(typeof product_id != 'undefined')
		object.id = product_id;

	if(typeof price != 'undefined')
		object.price = price;

	if(typeof name != 'undefined')
		object.name = name;

	if(typeof category != 'undefined')
		object.category = category;

	if(typeof brand != 'undefined')
		object.brand = brand;

	if(typeof quantity != 'undefined')
		object.quantity = quantity;
	else
		object.quantity = 1;

	if(typeof variant != '')
		object.variant = variant;

	if (eeMultiChanelAddToCartStep) {
		eventDataLayerCheckoutNewStep(eeMultiChanelAddToCartStep);
	}

	dataLayer.push({
		"event": "addToCart",
		"addToCart": object
	});
}

function eventDataLayerAddToWishlist(product_id, price, name, category, brand)
{
	var object = {};

	if(typeof product_id != 'undefined')
		object.id = product_id;

	if(typeof price != 'undefined')
		object.price = price;

	if(typeof name != 'undefined')
		object.name = name;

	if(typeof category != 'undefined')
		object.category = category;

	if(typeof brand != 'undefined')
		object.brand = brand;

	dataLayer.push({
		"event": "addToWishlist",
		"addToWishlist": object
	});
}

function eventDataLayerRemoveFromCart(product_id, price, name, category, brand, quantity, variant)
{
	var object = {};

	if(typeof product_id != 'undefined')
		object.id = product_id;

	if(typeof price != 'undefined')
		object.price = price;

	if(typeof name != 'undefined')
		object.name = name;

	if(typeof category != 'undefined')
		object.category = category;

	if(typeof brand != 'undefined')
		object.brand = brand;

	if(typeof quantity != 'undefined')
		object.quantity = quantity;
	else
		object.quantity = 1;

	if(typeof variant != '')
		object.variant = variant;

	dataLayer.push({
		"event": "removeFromCart",
		"removeFromCart": object
	});
}

function eventDataLayerCheckoutNewStep(step, option)
{
	var object = {};

	if(typeof step != 'undefined')
		object.step = step;

	if(typeof option != 'undefined')
		object.option = option;

	dataLayer.push({
		"event": "checkoutStep",
		"checkoutStep": object
	});
}

function removeFromCart_OC15(product_id_key, quantity)
{
	$.ajax({
		url: 'index.php?route=extension/module/google_all/get_product_datas',
		data: {product_id_key: product_id_key, quantity: quantity},
		type: "POST",
		dataType: 'json',
		beforeSend:function()
		{
		},
		success: function(data) {
			if(data)
				eventDataLayerRemoveFromCart(data.atc_id, data.atc_price, data.atc_name, data.atc_category, data.atc_brand, data.atc_quantity, data.atc_variant);
		},
		error: function(data) {
		},
	});
}

function abandoned_carts_put_events_to_inputs()
{
	$(document).on('blur', input_selector_firstname+', '+input_selector_lastname+', '+input_selector_email, function(){
		var email = $(input_selector_email).val();
		var firstname = $(input_selector_firstname).val();
		var lastname = $(input_selector_lastname).val();

		abandonedCartSubscribe(email, firstname, lastname);
	});
}

function abandonedCartSubscribe(email, firstname, lastname)
{
	if(typeof email == 'undefined')
		email = '';
	if(typeof firstname == 'undefined')
		firstname = '';
	if(typeof lastname == 'undefined')
		lastname = '';

	$.ajax({
		url: 'index.php?route=extension/module/google_all/abandoned_cart_insert',
		data: {email:email, firstname:firstname, lastname:lastname},
		type: "POST",
		dataType: 'json',
		beforeSend:function(){
		},
		success: function(data) {
		},
		error: function(data) {
		},
	});
}

function get_value_from_datalayer(value) {
	var value_found = '';
	dataLayer.forEach(function(val, key) {
		if(typeof val[value] != 'undefined') {
            value_found = val[value];
            return false;
        }
	});
	return value_found;
}