// constants for managing caches
var CACHE_ORDER = 'pos_order_';
var CACHE_RETURN = 'pos_return_';
var CACHE_CURRENT_ORDER = 'pos_local_current_order';
var CACHE_CURRENT_RETURN = 'pos_local_current_return';
var CACHE_SHIPPING_METHOD = 'pos_local_shipping_method';
var CACHE_ADDRESS = 'pos_local_address_';
var CACHE_DEFAULT_CUSTOMER = 'pos_default_customer';
var CACHE_RETURN = 'pos_local_return_';
var CACHE_AUTOCOMP_PRODUCT = 'pos_autocomplete_product';
var CACHE_MAX_ORDER_ID = 'pos_local_max_order_id';
var CACHE_MAX_RETURN_ID = 'pos_local_max_return_id';
var CACHE_MAX_PRODUCT_ID = 'pos_local_max_product_id';
var CACHE_MAX_CUSTOMER_ID = 'pos_local_max_customer_id';
var CACHE_MAX_ADDRESS_ID = 'pos_local_max_address_id';
var CACHE_EMPTY_ORDER = 'pos_empty_order';
var CACHE_EMPTY_RETURN = 'pos_empty_return';
var CACHE_CASH_IN_OUT = 'pos_local_cash_in_out';
var CACHE_PRINT = 'pos_print_';
var SESSION_POS_DISCOUNT = 'pos_local_order_discount';

var local_order_product_id = -1;
var local_return_product_id = -1;
console.log = function() {}


// use local db to store cached products and customers info for now as the size of products might be bigger than 5M
var pos_db = new PouchDB('pos', {adapter: 'websql', auto_compaction: true, size: 50});
if (!pos_db.adapter) { // websql not supported by this browser
	pos_db = new PouchDB('pos', {auto_compaction: true});
};
// pos_db.destroy();

/*
function createDesignDoc(name, mapFunction) {
	var ddoc = {
		_id: '_design/' + name,
		views: {}
	};
	ddoc.views[name] = { map: mapFunction.toString() };
	return ddoc;
};

// create views for queries

pos_db.get('_design/product_query', function(err, doc){
	console.log(err);
	console.log(doc);
	if (doc || err.status == 404) {
		pos_db.remove(doc, function(err, response) {
			var product_query = createDesignDoc('product_query', function(doc) {
				if (doc._id.substring(0, 'product_'.length) == 'product_') {
					emit([doc.product_id, doc.name], 1);
				}
			});
			pos_db.put(product_query, function(err, result) {
				// test query
			});
		});
	}
});


pos_db.query('product_query', {startkey: [nil, ''], endkey: ['28', {}]}, function(err1, doc1) {
	console.log(err1);
	console.log(doc1);
});
*/

var gql = new GQL(pos_db);
/*
//PouchDB('pos', function (err, db) {
	gql.gql({select: "*", where: "name contains 'touch'"}, function(err1, response) {
		console.log(err1);
		console.log(response);
	});
//});
*/

function saveDoc(key, value, overwrite) {
	// check if the value is already in the database, assuming value is a json object
	pos_db.get(key, function(err, doc) {
		if (err) {
			// error occured
			console.log(err);
			if (err.status = 404) {
				pos_db.put(value, key);
			}
		} else {
			if (doc) {
				// should update the doc as we find it
				for (var index in value) {
					doc[index] = value[index];
				}
				if (overwrite) {
					for (var index in doc) {
						if (index != '_id' && index != '_rev') {
							if (typeof value[index] == 'undefined') {
								delete doc[index];
							}
						}
					}
				}
				pos_db.put(doc);
			} else {
				pos_db.put(value, key);
			}
		}
	});
};

function backendSaveProduct(product, overwrite) {
	if (product) {
		// check if the product is already in the database
		var key = 'product_' + product['product_id'];
		saveDoc(key, product, overwrite);
	}
};

function backendSaveProducts(products) {
	if (products) {
		for (var index in products) {
			backendSaveProduct(products[index], true);
		}
	}
};

function backendGetProduct(product_id, callback) {
	if (product_id) {
		var key = 'product_' + product_id;
		pos_db.get(key, function(err, doc) {
			if (err) {
				console.log(err);
			} else {
				if (doc) {
					var json = {'product_id': doc['product_id'], 'option_data': doc['option']};
					callback(json);
				}
			}
		});
	} else {
		console.log('no product id');
	}
};

function backendGetProducts(data, callback) {
	var searchLimit = 8;
	var whereClause = [];
	if (data && data['filter_name'] && data['filter_scopes'] && data['filter_scopes'].length > 0) {
		var escaped_name = data['filter_name'].replace(/'/g, "\\'");
		whereClause.push('(name contains \'' + escaped_name + '\')');
		
		for (var i in data['filter_scopes']) {
			var filter_scope = data['filter_scopes'][i];
			if (filter_scope == 'model') {
				whereClause.push('(model contains \'' + escaped_name + '\')');
			} else if (filter_scope == 'manufacturer') {
				whereClause.push('(manufacturer contains \'' + escaped_name + '\')');
			} else if (filter_scope == 'upc') {
				whereClause.push('(upc contains \'' + escaped_name + '\')');
			} else if (filter_scope == 'sku') {
				whereClause.push('(sku contains \'' + escaped_name + '\')');
			} else if (filter_scope == 'ean') {
				whereClause.push('(ean contains \'' + escaped_name + '\')');
			} else if (filter_scope == 'mpn') {
				whereClause.push('(mpn contains \'' + escaped_name + '\')');
			}
		}
	}
	if (whereClause.length > 0) {
		// use GQL only when a filtering is required
		whereClause = whereClause.join(' or ');
		whereClause = '(_id contains \'product_\') and (' + whereClause + ')';
		if (data['quick_sale'] && parseInt(data['quick_sale']) > 0) {
			whereClause += ' and (quick_sale = \'2\')';
		}
		console.log(whereClause);
		gql.gql({select: "*", where: whereClause}, {limit: searchLimit}, function(err, response) {
			if (err) {
				console.log(err);
			} else {
				console.log(response);
				// check how many products match the filtering
				var json = {};
				if (response.rows && response.rows.length > 0) {
					json = response.rows;
				}
				callback(json);
			}
		});
	}
};

function backendGetProductByAttribute(attr, value, callback) {
	// Get the product by sku / upc / mpn
	var searchLimit = 1;
	if (attr && value) {
		// use GQL only when a filtering is required
		var whereClause = '(_id contains \'product_\') and (' + attr + ' = \'' + value.replace(/'/g, "\\'") + '\')';
		console.log(whereClause);
		gql.gql({select: "*", where: whereClause}, {limit: searchLimit}, function(err, response) {
			if (err) {
				console.log(err);
			} else {
				console.log(response);
				// check how many products match the filtering
				var json = {};
				if (response.rows && response.rows.length > 0) {
					json = response.rows[0];
				}
				callback(json);
			}
		});
	}
};

function backendSaveCategory(category_id, data) {
	// save the retrieved category and product info into the local database
	if (data) {
		var json = $.extend(true, {}, data);	// clone the object as we are going to modify the product info in the object
		// set the id for the category
		var id = 'category_' + category_id;
		
		if (json['browse_items'] && json['browse_items'].length > 0) {
			for (var i = 0; i < json['browse_items'].length; i++) {
				var item = json['browse_items'][i];
				if (item['type'] == 'P') {
					// when item type is product, remove product details from the json object to avoid data inconsistency
					// cannot save the product here because the save product calls are async, if call the function here, the object saved will only contain the type and product id
					for (var index in item) {
						if (index !== 'product_id' && index !== 'type') {
							delete json['browse_items'][i][index];
						}
					}
				}
			}
		}
		// save to database, and need to check if the category details is already there
		saveDoc(id, json);
		
		// also save the product in data into the database,
		if (data['browse_items'] && data['browse_items'].length > 0) {
			for (var i = 0; i < data['browse_items'].length; i++) {
				var item = data['browse_items'][i];
				if (item['type'] == 'P') {
					// save the product
					backendSaveProduct(item, true);
				}
			}
		}
	}
};

function backendGetCategory(category_id, callback) {
	// Get all subcategories and products for the category
	var json = {};
	pos_db.get('category_' + category_id, function(err, doc) {
		if (doc) {
			json = doc;
			if (json['browse_items']) {
				var keys = [];
				for (var i = 0; i < json['browse_items'].length; i++) {
					if (json['browse_items'][i]['type'] == 'P') {
						keys.push('product_' + json['browse_items'][i]['product_id']);
					}
				}
				pos_db.allDocs({include_docs: true, keys: keys}, function(err, docs) {
					if (err) {
						console.log(err);
					} else {
						for (var i = 0; i < json['browse_items'].length; i++) {
							if (json['browse_items'][i]['type'] == 'P') {
								for (var index = 0; index < docs.rows.length; index++) {
									if (docs.rows[index].id == 'product_' + json['browse_items'][i]['product_id']) {
										json['browse_items'][i] = docs.rows[index].doc;
									}
								}
							}
						}
					}
					callback(json);
				});
			} else if (json['path']) {
				callback(json);
			} else {
				openAlert(text_local_product_not_found);
			}
		} else {
			openAlert(text_local_product_not_found);
		}
	});
};

function isMatchedReturn(data, ret) {
	if (data) {
		if (data['filter_pos_return_id'] && parseInt(ret['filter_pos_return_id']) != parseInt(data['filter_pos_return_id'])) {
			return false;
		}
		
		if (data['filter_return_customer'] && (ret['firstname'] + ' ' + ret['lastname']).toLowerCase().indexOf(data['filter_return_customer'].toLowerCase()) < 0) {
			return false;
		}

		if (data['filter_return_status_id'] && parseInt(ret['return_status_id']) != parseInt(data['filter_return_status_id'])) {
			return false;
		}
			
		if (typeof data['filter_return_total'] != 'undefined' && posParseFloat(ret['total']) != parseFloat(data['filter_return_total'])) {
			return false;
		}

		if (data['filter_return_date_added'] && ret['return_date_added'].indexOf(data['filter_return_date_added']) < 0) {
			return false;
		}
		
		if (data['filter_return_date_modified'] && ret['return_date_modified'].indexOf(data['filter_return_date_modified']) < 0) {
			return false;
		}
	}
	
	return true;
};

function isMatchedOrder(data, order) {
	if (data['quote_status_id'] && parseInt(data['quote_status_id']) != 0) {
		return false;
	}
	
	if (data['filter_table_id'] && parseInt(order['order_table_id']) != parseInt(data['filter_table_id'])) {
		return false;
	}
	
	if (data['filter_order_id'] && parseInt(order['order_id']) != parseInt(data['filter_order_id'])) {
		return false;
	}
	
	if (data['filter_customer'] && (order['firstname'] + ' ' + order['lastname']).toLowerCase().indexOf(data['filter_customer'].toLowerCase()) < 0) {
		return false;
	}

	if (text_work_mode == '2') {
		if (data['filter_quote_status_id'] && parseInt(order['quote_status_id']) != parseInt(data['filter_quote_status_id'])) {
			return false;
		}
	} else {
		if (data['filter_order_status_id'] && parseInt(order['order_status_id']) != parseInt(data['filter_order_status_id'])) {
			return false;
		}
	}
		
	if (typeof data['filter_total'] != 'undefined' && posParseFloat(order['total']) != parseFloat(data['filter_total'])) {
		return false;
	}

	if (data['filter_date_added'] && order['date_added'].indexOf(data['filter_date_added']) < 0) {
		return false;
	}
	
	if (data['filter_date_modified'] && order['date_modified'].indexOf(data['filter_date_modified']) < 0) {
		return false;
	}
	
	return true;
};

function backendGetOrderList(data, callback) {
	var limit = 8;
	var start = 0;

	if (data['page']) {
		start = (parseInt(data['page']) - 1) * limit;
	}

	var total_orders = 0;
	var results = [];
	for (var i = 0; i < localStorage.length; i++) {
		var key = localStorage.key(i);
		if (key.substring(0, CACHE_ORDER.length) == CACHE_ORDER) {
			var order = JSON.parse(localStorage.getItem(key));
			if (isMatchedOrder(data, order)) {
				if (total_orders >= start && total_orders < start + limit) {
					results.push(order);
				}
				total_orders ++;
			}
		}
	}

	var json = {};
	json['orders'] = [];
	for (var i = 0; i < results.length; i++) {
		var result = results[i];
		var table_name = '';
		if (result['order_table_id']) {
			if (typeof tables != 'undefined') {
				for (var j in tables) {
					if (result['order_table_id'] == tables[j]['table_id']) {
						table_name = tables[j]['name'];
						break;
					}
				}
			}
		}
		var status = '';
		for (var j = 0; j < order_statuses.length; j++) {
			if (parseInt(order_statuses[j]['order_status_id']) == parseInt(result['order_status_id'])) {
				status = order_statuses[j]['name'];
			}
		}
		json['orders'].push({
			'order_id'      : result['order_id'],
			'table_name'    : table_name,
			'customer'      : result['customer'],
			'status'        : status,
			'status_id'     : result['order_status_id'],
			'user_id'		: result['user_id'],
			'email'			: result['email'],
			'total'         : formatMoney(result['total']),
			'date_added'    : parseInt(parseDate(result['date_added']) / 1000),
			'date_modified' : parseInt(parseDate(result['date_modified']) / 1000)
		});
	}
	
	var page = (data['page'] && !isNaN(parseInt(data['page']))) ? parseInt(data['page']) : 1;
	json['pagination'] = getPagination(total_orders, page, limit, 'selectOrderPage');
	
	callback(json);
};

function backendGetReturnList(data, callback) {
	var limit = 8;
	var start = 0;

	if (data && data['page']) {
		start = (parseInt(data['page']) - 1) * limit;
	}

	var total_returns = 0;
	var results = [];
	for (var i = 0; i < localStorage.length; i++) {
		var key = localStorage.key(i);
		if (key.substring(0, CACHE_RETURN.length) == CACHE_RETURN) {
			var ret = JSON.parse(localStorage.getItem(key));
			if (isMatchedReturn(data, ret)) {
				if (total_returns >= start && total_returns < start + limit) {
					results.push(ret);
				}
				total_returns ++;
			}
		}
	}

	var json = {}
	json['returns'] = [];
	for (var i = 0; i < results.length; i++) {
		var result = results[i];
		json['returns'].push({
			'pos_return_id' : result['pos_return_id'],
			'customer'      : result['customer'],
			'status'        : result['return_status'],
			'status_id'     : result['return_status_id'],
			'total'         : formatMoney(result['return_total']),
			'date_added'    : parseInt(parseDate(result['return_date_added']) / 1000),
			'date_modified' : parseInt(parseDate(result['return_date_modified']) / 1000)
		});
	}
	
	var page = (data && data['page'] && !isNaN(parseInt(data['page']))) ? parseInt(data['page']) : 1;
	json['pagination'] = getPagination(total_returns, page, limit, 'selectReturnPage');
	
	callback(json);
};

function backendSelectOrder(order_id, callback) {
	if (text_work_mode == '0' || text_work_mode == '2') {
		// order or quote
		// reset the order_product_id seq no
		local_order_product_id = -1;
		// get order info from the local storage, and move it from the order storage to current order to prevent it to be synced to server while it's being processed
		var orderString = localStorage.getItem(CACHE_ORDER + order_id);
		if (orderString) {
			order = JSON.parse(orderString);
			callback(order);
			// switch the selected order and the current in the local storage
			localStorage.removeItem(CACHE_ORDER + order_id);
			moveCurrentOrder();
			console.log('switch the selected order to current order');
			localStorage.setItem(CACHE_CURRENT_ORDER, orderString);
		} else {
			if (parseInt(order_id) > 0) {
				// local order id is not a positive integer
				openAlert(text_order_not_in_local);
			} else {
				// generate an id for local new order
				moveCurrentOrder();
				var data = getNewOrderId();
				var empty_order_info = localStorage.getItem(CACHE_EMPTY_ORDER);
				if (empty_order_info) {
					empty_order_info = JSON.parse(empty_order_info);
					empty_order_info['order_id'] = data['order_id'];
					empty_order_info['order_id_text'] = data['order_id_text'];
					if (text_work_mode == '2') {
						empty_order_info['quote_status_id'] = (typeof empty_quote_status_id == 'undefined') ? 1 : empty_quote_status_id;
						empty_order_info['quote_status'] = (typeof empty_quote_status_name == 'undefined') ? 1 : empty_quote_status_name;
					}
					callback(empty_order_info);
					// save the order info to the current order
					localStorage.setItem(CACHE_CURRENT_ORDER, JSON.stringify(empty_order_info));
				} else {
					openAlert(text_local_order_not_initialized);
				}
			}
		}
	} else if (text_work_mode == '1') {
		// select order for return
		var emptyReturn = localStorage.getItem(CACHE_EMPTY_RETURN);
		if (emptyReturn) {
			var json = JSON.parse(emptyReturn);
			
			// A new return should be created at this stage, check if the current return should be saved
			var cur_return = localStorage.getItem(CACHE_CURRENT_RETURN);
			if (cur_return) {
				cur_return = JSON.parse(cur_return);
				if (cur_return['return_products'] && cur_return['return_products'].length > 0) {
					// not an empty return, save it
					localStorage.setItem(CACHE_RETURN + cur_return['pos_return_id'], JSON.stringify(cur_return));
				}
			}
			// create a new return
			var data = getNewReturnId();
			json['order_id'] = order_id;
			json['pos_return_id'] = data['pos_return_id'];
			json['text_pos_return_id'] = data['text_pos_return_id'];
			var cur_time = formatDate(new Date());
			json['return_date_added'] = cur_time;
			json['return_date_modified'] = cur_time;
			localStorage.setItem(CACHE_CURRENT_RETURN, JSON.stringify(json));
			
			var orderString = localStorage.getItem(CACHE_ORDER + order_id);
			if (orderString) {
				order = JSON.parse(orderString);
				
				json['text_return_for_order'] = text_return_for_order.replace('%s', order_id);
				json['browseItems'] = [];
				for (var i = 0; i < order['products'].length; i++) {
					var result = order['products'][i];
					json['browseItems'].push({'type' : 'P',
										'name' : result['name'],
										'order_id' : order_id,
										'date_ordered' : order['date_added'],
										'order_product_id' : result['order_product_id'],
										'product_id' : result['product_id'],
										'image' : result['image'],
										'price_text' : result['price_text'],
										'stock' : result['quantity'],
										'hasOptions' : '0',	// options are all selected as they are order products, no need to reselect from the ui
										// add for (update) Weight based price begin
										'weight_price' : result['weight_price'],
										'weight_name' : result['weight_name'],
										'weight' : result['weight'],
										// add for (update) Weight based price end
										'has_sn' : '0', // sn is fixed as they are order products, no need to reselect from the ui
										'sn' : result['sn'] ? result['sn'] : '',
										'price' : result['price'],
										'tax' : result['tax'],
										'tax_class_id' : result['tax_class_id'],
										'shipping' : result['shipping'],
										'points' : 0,
										'model' : result['model'],
										'option' : result['option'],
										'quantity' : result['quantity'],
										'id' : result['product_id']});
				}
			}
			callback(json);
		} else {
			openAlert(text_local_order_not_initialized);
		}
	}
};

function populateReturnData(ret, callback) {
	var orderString = localStorage.getItem(CACHE_ORDER + ret['order_id']);
	if (orderString) {
		var order = JSON.parse(orderString);
		
		ret['text_return_for_order'] = text_return_for_order.replace('%s', ret['order_id']);
		ret['browseItems'] = [];
		for (var i = 0; i < order['products'].length; i++) {
			var result = order['products'][i];
			ret['browseItems'].push({'type' : 'P',
								'name' : result['name'],
								'order_id' : ret['order_id'],
								'date_ordered' : order['date_added'],
								'order_product_id' : result['order_product_id'],
								'product_id' : result['product_id'],
								'image' : result['image'],
								'price_text' : result['price_text'],
								'stock' : result['quantity'],
								'hasOptions' : '0',	// options are all selected as they are order products, no need to reselect from the ui
								// add for (update) Weight based price begin
								'weight_price' : result['weight_price'],
								'weight_name' : result['weight_name'],
								'weight' : result['weight'],
								// add for (update) Weight based price end
								'has_sn' : '0', // sn is fixed as they are order products, no need to reselect from the ui
								'sn' : result['sn'] ? result['sn'] : '',
								'price' : result['price'],
								'tax' : result['tax'],
								'tax_class_id' : result['tax_class_id'],
								'shipping' : result['shipping'],
								'points' : 0,
								'model' : result['model'],
								'option' : result['option'],
								'quantity' : result['quantity'],
								'id' : result['product_id']});
		}
	}
	console.log(ret);
	callback(ret);
};

function backendSelectReturn(pos_return_id, callback) {
	// reset the return_id seq no for the "pos" return
	local_return_product_id = -1;
	// get return info from the local storage, and move it from the return storage to current return to prevent it to be synced to server while it's being processed
	var returnString = localStorage.getItem(CACHE_RETURN + pos_return_id);
	if (returnString) {
		var ret = JSON.parse(returnString);
		if (parseInt(ret['customer_id']) > 0) {
			backendGetCustomer(ret['customer_id'], function(customer) {
				for (var key in customer) {
					ret[key] = customer[key];
				}
				populateReturnData(ret, callback);
			});
		} else {
			populateReturnData(ret, callback);
		}
		// switch the selected return and the current in the local storage
		localStorage.removeItem(CACHE_RETURN + pos_return_id);
		var currReturnString = localStorage.getItem(CACHE_CURRENT_RETURN);
		if (currReturnString) {
			currReturn = JSON.parse(currReturnString);
			if (currReturn['return_products'] && currReturn['return_products'].length > 0) {
				// not an empty return, save it
				localStorage.setItem(CACHE_RETURN + currReturn['pos_return_id'], currReturnString);
			}
		}
		console.log('switch the selected return to current return');
		localStorage.setItem(CACHE_CURRENT_RETURN, returnString);
	} else {
		if (parseInt(pos_return_id) > 0) {
			// local return id is not a positive integer
			openAlert(text_return_not_in_local);
		} else {
			// generate an id for local new return
			var currReturnString = localStorage.getItem(CACHE_CURRENT_RETURN);
			if (currReturnString) {
				currReturn = JSON.parse(currReturnString);
				if (currReturn['return_products'] && currReturn['return_products'].length > 0) {
					// not an empty return, save it
					localStorage.setItem(CACHE_RETURN + currReturn['pos_return_id'], currReturnString);
				}
			}
			var data = getNewReturnId();
			var emptyReturn = localStorage.getItem(CACHE_EMPTY_RETURN);
			if (emptyReturn) {
				emptyReturn = JSON.parse(emptyReturn);
				emptyReturn['pos_return_id'] = data['pos_return_id'];
				emptyReturn['text_pos_return_id'] = data['text_pos_return_id'];
				emptyReturn['order_id'] = 0;
				var cur_time = formatDate(new Date());
				emptyReturn['return_date_added'] = cur_time;
				emptyReturn['return_date_modified'] = cur_time;
				callback(emptyReturn);
				// save the return info to the current return
				localStorage.setItem(CACHE_CURRENT_RETURN, JSON.stringify(emptyReturn));
			} else {
				openAlert(text_local_return_not_initialized);
			}
		}
	}
};

function getNewOrderId() {
	var maxId = localStorage.getItem(CACHE_MAX_ORDER_ID);
	if (!maxId) {
		maxId = '1';
	}
	// check if the current order id has real order in local repository
	var emptyOrder = true;
	var order = localStorage.getItem(CACHE_ORDER + '-' + maxId);
	if (order) {
		order = JSON.parse(order);
		if (order['products'] && order['products'].length > 0) {
			emptyOrder = false;
		}
	}
	
	if (!emptyOrder) {
		// set a new max id back to local storage
		localStorage.setItem(CACHE_MAX_ORDER_ID, ''+(parseInt(maxId)+1));
		maxId = '' + (parseInt(maxId)+1);
	}
	
	var data = {'order_id': 0-parseInt(maxId)};
	var len = maxId.length;
	for (var i = 0; i < 7 - len; i++) {
		maxId = '0' + maxId;
	}
	data['order_id_text'] = 'L' + maxId;
	
	return data;
};

function getNewReturnId() {
	var maxId = localStorage.getItem(CACHE_MAX_RETURN_ID);
	if (!maxId) {
		maxId = '1';
	}
	// check if the current return id has real return in local repository
	var emptyReturn = true;
	var ret = localStorage.getItem(CACHE_RETURN + '-' + maxId);
	if (ret) {
		ret = JSON.parse(ret);
		if (ret['return_products'] && ret['return_products'].length > 0) {
			emptyReturn = false;
		}
	}
	
	if (!emptyReturn) {
		// set a new max id back to local storage
		localStorage.setItem(CACHE_MAX_RETURN_ID, ''+(parseInt(maxId)+1));
		maxId = '' + (parseInt(maxId)+1);
	}
	
	var data = {'pos_return_id': 0-parseInt(maxId)};
	var len = maxId.length;
	for (var i = 0; i < 7 - len; i++) {
		maxId = '0' + maxId;
	}
	data['text_pos_return_id'] = 'L' + maxId;
	
	return data;
};

function backendGetReturnDetails(return_id, callback) {
	var foundRet = false;
	var ret = localStorage.getItem(CACHE_CURRENT_RETURN);
	if (ret) {
		ret = JSON.parse(ret);
		if (ret['return_products']) {
			for (var i = 0; i < ret['return_products'].length; i++) {
				if (parseInt(ret['return_products'][i]['return_id']) == parseInt(return_id)) {
					var json = ret['return_products'][i];
					json['opened'] = (parseInt(json['opened']) > 0) ? text_opened : text_unopened;
					json['reason'] = '';
					if (typeof return_reasons != 'undefined') {
						for (var j = 0; j < return_reasons.length; j++) {
							if (parseInt(return_reasons[j]['return_reason_id']) == parseInt(json['return_reason_id'])) {
								json['reason'] = return_reasons[j]['name'];
								break;
							}
						}
					}
					json['return_time'] = parseInt(parseDate(json['date_added']) / 1000);
					json['options'] = '';
					if (json['weight_name']) {
						json['options'] += json['weight_name'] + ": " + json['weight'] + "\n";
					}
					if (json['sn']) {
						json['options'] += "SN: " + json['sn'] + "\n";
					}
					if (json['option']) {
						for (var j in json['option']) {
							json['options'] += json['option'][j]['name'] + ": " + json['option'][j]['value'] + "\n";
						}
					}
					callback(json);
					foundRet = true;
				}
			}
		}
	}
	if (!foundRet) {
		openAlert(text_no_return_details_found);
	}
};

function backendEditReturn(data, callback) {
	console.log(data);
	var json = {'success' : text_product_returned_successfully};
	if (data['new_total']) {
		// total price change, calculate the tax and price for the total
		var price = getPriceFromPriceWithTax(parseFloat(data['new_total'])/parseFloat(data['weight'])/parseInt(data['org_quantity']), data['tax_class_id'], data['customer_group_id']);
		json['tax_change'] = data['tax_change'] = parseFloat(data['new_total']) - parseInt(data['org_quantity']) * parseFloat(data['weight']) * (price + parseFloat(data['tax']));
		json['price_change'] = data['price_change'] = parseInt(data['org_quantity']) * parseInt(data['weight']) * (price - parseInt(data['price']));
		data['tax'] = parseFloat(data['tax']) + data['tax_change'] / parseFloat(data['weight']) / parseInt(data['org_quantity']);
		data['price'] = parseFloat(data['price']) + data['price_change'] / parseFloat(data['weight']) / parseInt(data['org_quantity']);
	}
	
	var ret = localStorage.getItem(CACHE_CURRENT_RETURN);
	var items_in_cart = 0;
	if (ret) {
		ret = JSON.parse(ret);
		console.log(ret);
		for (var i = 0; i < ret['return_products'].length; i++) {
			if ((parseInt(data['order_id']) && parseInt(ret['return_products'][i]['order_product_id']) == parseInt(data['order_product_id']))
				|| ((!data['order_id'] || parseInt(data['order_id']) == 0) && parseInt(ret['return_products'][i]['return_id']) == parseInt(data['return_id']))) {
				if (data['quantity']) {
					ret['return_products'][i]['quantity'] = data['quantity'];
				}
				if (data['price'] && data['tax']) {
					ret['return_products'][i]['price'] = data['price'];
					ret['return_products'][i]['tax'] = data['tax'];
				}
				ret['return_products'][i]['date_modifed'] = formatDate(new Date());
				break;
			}
		}
		if (data['action'] && data['action'] == 'delete') {
			for (var i = 0; i < ret['return_products'].length; i++) {
				if (parseInt(ret['return_products'][i]['return_id']) == parseInt(data['return_id'])) {
					ret['return_products'].splice(i, 1);
					break;
				}
			}
		}
		ret['return_sub_total'] = parseFloat(ret['return_sub_total']) + parseFloat(data['price_change']);
		ret['return_tax'] = parseFloat(ret['return_tax']) + parseFloat(data['tax_change']);
		ret['return_total'] = ret['return_sub_total'] + ret['return_tax'];
		ret['date_modifed'] = formatDate(new Date());
		// save the return info back to current return
		for (var i = 0; i < ret['totals'].length; i++) {
			if (ret['totals'][i]['code'] == 'tax') {
				ret['totals'][i]['value'] = ret['return_tax'];
			} else if (ret['totals'][i]['code'] == 'subtotal') {
				ret['totals'][i]['value'] = ret['return_sub_total'];
			} else if (ret['totals'][i]['code'] == 'total') {
				ret['totals'][i]['value'] = ret['return_total'];
			}
		}
		
		for (var i = 0; i < ret['return_products'].length; i++) {
			items_in_cart += parseInt(ret['return_products'][i]['quantity']);
		}
		localStorage.setItem(CACHE_CURRENT_RETURN, JSON.stringify(ret));
	}
	json['items_in_cart'] = items_in_cart;
	callback(json);
};

function getTax(price, tax_class_id, customer_group_id) {
	var tax = 0;
	var tax_rate_data = getRates(price, tax_class_id, customer_group_id);
	
	for ( var index in tax_rate_data) {
		tax += tax_rate_data[index]['amount'];
	}
	
	return tax;
};

function getRates(price, tax_class_id, customer_group_id, use_discount) {
	var tax_rate_data = {};
	var tax_rates = localStorage.getItem('tax_' + tax_class_id + '_' + customer_group_id);
	if (tax_rates) {
		tax_rates = JSON.parse(tax_rates);
		for (var index in tax_rates) {
			var tax_rate = tax_rates[index];
			var amount = 0;
			if (tax_rate_data[tax_rate['tax_rate_id']]) {
				amount = tax_rate_data[tax_rate['tax_rate_id']]['amount'];
			}
			
			if (tax_rate['type'] == 'F') {
				if (!use_discount) {
					// change for pos_discount, as the discount will not incur the fix amount tax
					amount += parseFloat(tax_rate['rate']);
				}
			} else if (tax_rate['type'] == 'P') {
				amount += (price / 100 * parseFloat(tax_rate['rate']));
			}
		
			tax_rate_data[tax_rate['tax_rate_id']] = {
				'tax_rate_id' : tax_rate['tax_rate_id'],
				'name'        : tax_rate['name'],
				'rate'        : tax_rate['rate'],
				'type'        : tax_rate['type'],
				'amount'      : amount
			};
		}
	}
	
	return tax_rate_data;
}

function getPriceFromPriceWithTax(price, tax_class_id, customer_group_id) {
	var cal_price = price;
	
	if (parseInt(config['config_tax']) == 1) {
		// the changed price is with tax according to the settings
		// get all tax rates
		var base = 100;
		var tax_rates = getRates(base, tax_class_id, customer_group_id);
		var rate_p = 0;
		for (var index in tax_rates) {
			var tax_rate = tax_rates[index];
			if (tax_rate['type'] == 'F') {
				// fixed amount rate
				cal_price -= tax_rate['rate'];
			} else if (tax_rate['type'] == 'P') {
				// percentage rate
				rate_p += tax_rate['rate'];
			}
		}
		cal_price = cal_price / (1+rate_p/100);
	}
	
	return cal_price;
}


function getNewProductId() {
	var maxId = localStorage.getItem(CACHE_MAX_PRODUCT_ID);
	if (!maxId) {
		maxId = '1';
	}
	localStorage.setItem(CACHE_MAX_PRODUCT_ID, (parseInt(maxId)+1));
	
	return 0-parseInt(maxId);
};

function backendCheckAndSaveOrder(data, callback) {
	if (data['action'] == 'insert_quick') {
		// save the product to local storage
		var price = (data['include_tax']) ? getPriceFromPriceWithTax(data['price'], data['tax_class_id'], customer_group_id) : data['price'];
		var tax = (data['include_tax']) ? data['price'] - price : 0;
		var tax_class_id = (data['include_tax']) ? data['tax_class_id'] : 0;
		var product_id = getNewProductId();
		var product = {'name': data['name'], 'type':'P', 'quick_sale':2, 'image': no_image_url, 'price_text':formatMoney(data['price']), 'hasOptions':'0', 'weight_price':'0', 'has_sn':'0', 'price':price, 'tax':tax, 'tax_class_id':tax_class_id, 'points':0, 'model':data['model'], 'parent_category_id':'-1', 'product_id':product_id, 'stock':1, 'description': '', 'manufacturer': '', 'upc': '', 'sku': '', 'ean': '', 'mpn': ''};
		backendSaveProduct(product, true);
		data['product'] = product;
		
		processCheckAndSaveOrder(data, callback);
	} else {
		backendGetProductDetails(data['product_id'], function(product) {
			console.log(product);
			data['product'] = product;
			processCheckAndSaveOrder(data, callback);
		});
	}
}

function processCheckAndSaveOrder(data, callback) {
	console.log(data);
	var action = data['action'];
	var is_empty_order = false;
	
	var json = {};
	var order = localStorage.getItem(CACHE_CURRENT_ORDER);
	if (order) {
		order = JSON.parse(order);
	} else {
		openAlert(text_local_order_not_initialized);
		callback(json);
	}
	
	if (action == 'insert' || action == 'insert_quick') {
		var open_bay_product_id = data['product_id'];
		// update order creation time if it's an empty order
		if (!order['products'] || order['products'].length == 0) {
			is_empty_order = true;
		}
		var tax = getTax(data['product']['price'], data['product']['tax_class_id'], data['customer_group_id']);
		
		if (!order['products']) {
			order['products'] = [];
		}
		var price = data['product']['price'];
		var price_text = formatMoney((parseInt(config['config_tax']) == 1) ? (parseFloat(data['product']['price']) + tax) : parseFloat(data['product']['price']));
		var total = data['quantity'] * data['weight'] * parseFloat(data['product']['price']);
		var total_text = formatMoney(data['quantity'] * data['weight'] * ((parseInt(config['config_tax']) == 1) ? (parseFloat(data['product']['price']) + tax) : parseFloat(data['product']['price'])));
		var order_product = {'product_id':data['product']['product_id'], 'name':data['product']['name'], 'model':data['product']['model'], 'image':data['product']['image'], 'quantity':data['quantity'], 'price':price, 'price_text':price_text, 'total':total, 'total_text':total_text, 'tax':tax, 'tax_class_id':data['product']['tax_class_id'], 'shipping':data['product']['shipping'], 'reward':data['product']['points'], 'weight':data['weight'], 'weight_price':data['product']['weight_price'], 'weight_name':data['product']['weight_name'], 'order_product_id':local_order_product_id};
		if (data['product_sn']) {
			order_product['product_sn'] = data['product_sn'];
		}
		json['order_product_id'] = local_order_product_id;
		local_order_product_id--;
		
		if (data['option']) {
			order_product['option'] = data['option'];
		}
		order['products'].push(order_product);
		
		json['price'] = data['product']['price'];
		json['text_price'] = price_text;
		json['text_total'] = total_text;
	} else if (action == 'modify_quantity') {
		if (order['products']) {
			for (var index in order['products']) {
				if (order['products'][index]['order_product_id'] == data['order_product_id']) {
					order['products'][index]['quantity'] = data['quantity_after'];
					order['products'][index]['total'] = ((parseInt(config['config_tax']) == 1) ? (parseFloat(data['product']['price']) + parseFloat(data['product']['tax'])) : parseFloat(data['product']['price'])) * data['quantity_after'] * parseFloat(order['products'][index]['weight']);
					order['products'][index]['total_text'] = formatMoney(order['products'][index]['total']);
					break;
				}
			}
		}
	} else if (action == 'modify_price') {
		var price_after = data['price_after'];
		if (parseInt(config['config_tax']) == 1) {
			price_after = getPriceFromPriceWithTax(price_after, data['product']['tax_class_id'], data['customer_group_id']);
		}
		if (order['products']) {
			for (var index in order['products']) {
				if (order['products'][index]['order_product_id'] == data['order_product_id']) {
					order['products'][index]['price'] = price_after;
					// regardless the price includes tax or not, the data['price_after'] is the final presenting price
					order['products'][index]['total'] = parseFloat(data['price_after']) * data['quantity'] * parseFloat(order['products'][index]['weight']);
					order['products'][index]['total_text'] = formatMoney(order['products'][index]['total']);
					break;
				}
			}
		}
		json['price'] = price_after;
	} else if (action == 'delete') {
		if (order['products']) {
			for (var index in order['products']) {
				if (order['products'][index]['order_product_id'] == data['order_product_id']) {
					order['products'].splice(index, 1);
					break;
				}
			}
		}
	}
	// save the current order info into local storage to be used by totals potentially
	console.log(order);
	localStorage.setItem(CACHE_CURRENT_ORDER, JSON.stringify(order));
	
	backendUpdateTotal(data, function(total_data) {
		json['order_total'] = total_data['order_total'];
		if (total_data['discount']) {
			json['discount'] = total_data['discount'];
		}
		if (total_data['extra_info']) {
			json['extra_info'] = total_data['extra_info'];
		}
		
		var cur_time = formatDate(new Date());
		order['date_modified'] = cur_time;
		order['totals'] = total_data['order_total'];
		order['total'] = total_data['total'];
		if (is_empty_order) {
			order['date_added'] = cur_time;
			if (data['work_mode'] == '0') {
				order['quote_status_id'] = '0';
				order['quote_id'] = '0';
			} else if (data['work_mode'] == '2') {
				order['quote_status_id'] = '1';
				order['quote_id'] = '0';
			}
			if (data['table_id']) {
				order['table_id'] = data['table_id'];
			}
		}
		// save the attributes to the order
		saveOrderInfo(order);
		
		if (action == 'insert_quick') {
			json['product_id'] = data['product']['product_id'];
		}
		json['success'] = text_order_success;
		// add for Quotation begin
		if (data['work_mode'] && data['work_mode'] == '2') {
			json['success'] = text_quote_sucess;
		}
		// add for Quotation end
		// add for Openbay integration begin
		if (action != 'modify_price') {
			// only if action is not modify_price
			json['enable_openbay'] = config['enable_openbay'];
		}
		// add for Openbay integration bend
		
		console.log(json);
		callback(json);
	});
};

function backendSyncTax() {
	// sync the given items, at least tax rate and product info
	var needSync = false;
	var pollInterval = 1; // 60 minutes
	var lastSync = localStorage.getItem('pos_tax_last_sync');
	if (lastSync) {
		if (lastSync < (+new Date() - pollInterval * 60 * 1000)) {
			needSync = true;
		}
	} else {
		needSync = true;
	}
	
	if (needSync && serverReachable) {
		$.ajax({
			url: 'index.php?route=module/pos/getTaxRatesAjax&token=' + token,
			type: 'post',
			dataType: 'json',
			localCache: false,
			success: function(json) {
				// overwrite the local storage with the updated tax rates
				for (var key in json) {
					localStorage.setItem(key, JSON.stringify(json[key]));
				}
				localStorage.setItem('pos_tax_last_sync', +new Date());
			}
		});
	}
};

function getPagination(total, page, limit, callback) {
	var num_links = 10;
	var num_pages = (total % limit == 0) ? total / limit : parseInt(total/limit) + 1;
	var text = text_pagination;
	
	var output = '<ul class="pos-pager">';
	if (page > 1) {
		output += '<li><a onclick="' + callback + '(1);" class="arrow first"></a></li><li><a onclick="' + callback + '(' + (page - 1) + ');" class="arrow prev"></a></li>';
	}

	if (num_pages > 1) {
		var start = 0, end = 0;
		if (num_pages <= num_links) {
			start = 1;
			end = num_pages;
		} else {
			start = page - parseInt(num_links / 2);
			end = page + parseInt(num_links / 2);
		
			if (start < 1) {
				end += start + 1;
				start = 1;
			}

			if (end > num_pages) {
				start -= (end - num_pages);
				end = num_pages;
			}
		}

		for (var i = start; i <= end; i++) {
			if (page == i) {
				output += '<li><a class="active">' + i + '</a></li>';
			} else {
				output += '<li><a onclick="' + callback + '(' + i + ');">' + i + '</a></li>';
			}	
		}
	}
	
	if (page < num_pages) {
		output += '<li><a onclick="' + callback + '(' + (page + 1) + ');" class="arrow next"></a></li><li><a onclick="' + callback + '(' + num_pages + ');" class="arrow last"></a></li>';
	}
	
	output += '</ul>';
	
	var values = [(total) ? ((page - 1) * limit) + 1 : 0, (((page - 1) * limit) > (total - limit)) ? total : (((page - 1) * limit) + limit), total, num_pages];
	text = constructPagingText(text, values);
	
	return '<span class="count">' + text + '</span>' + output;
};

function constructPagingText(text, values) {
	var index = text.indexOf('%d');
	var indexValue = 0;
	while (index >= 0) {
		var replace = '';
		if (values[indexValue]) replace = parseInt(values[indexValue]);
		text = text.substring(0,index) + replace + text.substring(index+2);
		
		indexValue++;
		index = text.indexOf('%d');
	}
	return text;
};

function orderSubTotal() {
	var subtotal = 0;
	var order = localStorage.getItem(CACHE_CURRENT_ORDER);
	if (order) {
		order = JSON.parse(order);
		if (order['products']) {
			for  (var i in order['products']) {
				if (order['products'][i]['discount'] && order['products'][i]['discount']['discounted_price']) {
					subtotal += parseFloat(order['products'][i]['discount']['discounted_price']) * parseInt(order['products'][i]['quantity']) * parseFloat(order['products'][i]['weight']);
				} else {
					subtotal += parseFloat(order['products'][i]['price']) * parseInt(order['products'][i]['quantity']) * parseFloat(order['products'][i]['weight']);
				}
			}
		}
	}
	
	return subtotal;
};

var PosTotal = function(sort_order, code) { this.sort_order = sort_order; this.code = code; };
PosTotal.prototype.getTotal = function(total_data, total, taxes) {};

var pos_discount = {};
var PosDiscountTotal = function() { PosTotal.call(this, config['pos_discount_sort_order'], 'pos_discount'); };
PosDiscountTotal.prototype = Object.create(PosTotal.prototype);
PosDiscountTotal.prototype.constructor = PosDiscountTotal;
PosDiscountTotal.prototype.getTotal = function(total_data, total, taxes) {
	if (!$.isEmptyObject(pos_discount)) {
		var amount = 0;
		if (pos_discount['code'] == 'pos_discount_fixed') {
			if ((0-pos_discount['value']) > total) {
				amount = 0-total;	
			} else {
				amount = pos_discount['value'];	
			}				
		} else if (pos_discount['code'] == 'pos_discount_percentage') {
			var percentage_text = pos_discount['title'];
			var index1 = percentage_text.indexOf('(');
			var index2 = percentage_text.indexOf(')');
			var percentage = 0;
			if (index1 >= 0 && index2 >= 0 && index2 > index1+2) {
				percentage = parseFloat(percentage_text.substring(index1+1, $index2));
			}

			var subtotal = orderSubTotal();
			amount = 0 - subtotal * percentage / 100;
		}
		
		total_data.push({
			'code'       : pos_discount['code'],
			'title'      : pos_discount['title'],
			'text'       : formatMoney(amount),
			'value'      : amount,
			'sort_order' : config['pos_discount_sort_order']
		});
		
		total += amount;
	}

	return {'total_data': total_data, 'total' : total, 'taxes' : taxes};
};

var PosRoundingTotal = function() { PosTotal.call(this, config['pos_rounding_sort_order'], 'pos_rounding'); };
PosRoundingTotal.prototype = Object.create(PosTotal.prototype);
PosRoundingTotal.prototype.constructor = PosRoundingTotal;
PosRoundingTotal.prototype.getTotal = function(total_data, total, taxes) {
	if (config['enable_rounding'] && config['config_rounding']) {
		var amount = toFixed((total * 10 - parseInt(total * 10)) / 10, 2);
		if (config['config_rounding'] == '5c') {
			if (amount < 0.03) {
				amount = -amount;
			} else if (amount < 0.05 || (amount > 0.05 && amount < 0.08)) {
				amount = 0.05 - amount;
			} else if (amount >= 0.08) {
				amount = 0.1 - amount;
			} else {
				amount = 0;
			}
		} else if (config['config_rounding'] == '10c') {
			amount = toFixed(amount, 1) - amount;
		} else if (config['config_rounding'] == '50c') {
			amount = toFixed(total - parseInt(total), 2);
			if (amount < 0.5) {
				amount = -amount;
			} else if (amount > 0.5) {
				amount = 0.5 - amount;
			}
		}
		
		total_data.push({
			'code'       : 'pos_rounding',
			'title'      : 'Rounding',
			'text'       : formatMoney(amount),
			'value'      : amount,
			'sort_order' : config['pos_rounding_sort_order']
		});
		
		total += amount;
	}
	
	return {'total_data': total_data, 'total' : total, 'taxes' : taxes};
};

var ShippingTotal = function() { PosTotal.call(this, config['shipping_sort_order'], 'shipping'); };
ShippingTotal.prototype = Object.create(PosTotal.prototype);
ShippingTotal.prototype.constructor = ShippingTotal;
ShippingTotal.prototype.getTotal = function(total_data, total, taxes) {
	var hasShipping = 0;
	var order = localStorage.getItem(CACHE_CURRENT_ORDER);
	if (order) {
		order = JSON.parse(order);
		if (order['products']) {
			for (var i in order['products']) {
				if (parseInt(order['products'][i]['shipping']) == 1) {
					hasShipping = 1;
					break;
				}
			}
		}
	}
	
	if (hasShipping) {
		var shipping_methods = localStorage.getItem(CACHE_SHIPPING_METHOD);
		
		var matched = false;
		if (shipping_methods) {
			shipping_methods = JSON.parse(shipping_methods);
			for (var i in shipping_methods['shipping_method']) {
				for (var j in shipping_methods['shipping_method'][i]['quote']) {
					var shipping_method_quote = shipping_methods['shipping_method'][i]['quote'][j];

					if (order['shipping_code'] == shipping_method_quote['code']) {
						matched = true;
						
						total_data.push({
							'code'       : 'shipping',
							'title'      : shipping_method_quote['title'],
							'value'      : shipping_method_quote['cost'],
							'sort_order' : config['shipping_sort_order']
						});
						
						if (shipping_method_quote['tax_class_id']) {
							var tax_rates = getRates(shipping_method_quote['cost'], shipping_method_quote['tax_class_id'], order['customer_group_id']);

							for (var i in tax_rates) {
								var tax_rate = tax_rates[i];
								if (!(taxes[tax_rate['tax_rate_id']])) {
									taxes[tax_rate['tax_rate_id']] = tax_rate['amount'];
								} else {
									taxes[tax_rate['tax_rate_id']] += tax_rate['amount'];
								}
							}
						}

						total += parseFloat(shipping_method_quote['cost']);
						
						break;
					}
				}
				
				if (matched) { break; }
			}
		}
		if (!matched) {
			total_data.push({
				'code'       : 'shipping',
				'title'      : 'In Store',
				'value'      : 0,
				'sort_order' : config['shipping_sort_order']
			});
		}
	}
	
	return {'total_data': total_data, 'total' : total, 'taxes' : taxes};
};

var SubTotalTotal = function() { PosTotal.call(this, config['sub_total_sort_order'], 'sub_total'); };
SubTotalTotal.prototype = Object.create(PosTotal.prototype);
SubTotalTotal.prototype.constructor = SubTotalTotal;
SubTotalTotal.prototype.getTotal = function(total_data, total, taxes) {
	var subtotal = orderSubTotal();
	total_data.push({
		'code'       : 'sub_total',
		'title'      : text_subtotal,
		'value'      : subtotal,
		'sort_order' : config['sub_total_sort_order']
	});

	total += subtotal;
	
	return {'total_data': total_data, 'total' : total, 'taxes' : taxes};
};

var TaxTotal = function() { PosTotal.call(this, config['tax_sort_order'], 'tax'); };
TaxTotal.prototype = Object.create(PosTotal.prototype);
TaxTotal.prototype.constructor = TaxTotal;
TaxTotal.prototype.getTotal = function(total_data, total, taxes) {
	var tax_rate_names = {};
	for (var i = 0; i < localStorage.length; i++) {
		if (localStorage.key(i).substring(0, 4) == 'tax_') {
			var value = localStorage.getItem(localStorage.key(i));
			value = JSON.parse(value);
			for (var tax_rate_id in value) {
				if (!tax_rate_names[tax_rate_id]) {
					tax_rate_names[tax_rate_id] = value[tax_rate_id]['name'];
				}
			}
		}
	}

	for (var key in taxes) {
		var value = taxes[key];
		if (value > 0) {
			total_data.push({
				'code'       : 'tax',
				'title'      : tax_rate_names[key] ? tax_rate_names[key] : '',
				'value'      : value,
				'sort_order' : config['tax_sort_order']
			});

			total += value;
		}
	}
	
	return {'total_data': total_data, 'total' : total, 'taxes' : taxes};
};

var TotalTotal = function() { PosTotal.call(this, config['total_sort_order'], 'total'); };
TotalTotal.prototype = Object.create(PosTotal.prototype);
TotalTotal.prototype.constructor = TotalTotal;
TotalTotal.prototype.getTotal = function(total_data, total, taxes) {
	total_data.push({
		'code'       : 'total',
		'title'      : column_order_total,
		'value'      : Math.max(0, total),
		'sort_order' : config['total_sort_order']
	});
	
	return {'total_data': total_data, 'total' : total, 'taxes' : taxes};
};

// var supportedTotals = [ new PosDiscountTotal(), new PosRoundingTotal(), new ShippingTotal(), new SubTotalTotal(), new TaxTotal(), new TotalTotal() ];
// do not use posDiscountTotal for now, as it will be calculated through different logic
var supportedTotals = [ new PosRoundingTotal(), new ShippingTotal(), new SubTotalTotal(), new TaxTotal(), new TotalTotal() ];

function backendUpdateTotal(data, callback) {
	// recalculate the total and return order totals
	var extra_info = {};
	var action = (data && data['action']) ? data['action'] : '';
	var order_products = {};
	order = localStorage.getItem(CACHE_CURRENT_ORDER);
	if (order) {
		order = JSON.parse(order);
		if (order['products']) {
			order_products = order['products'];
		}
	}
	
	if (action == 'insert') {
		// get total quantity of the products with the same product_id with the product added or modified
		var discount_quantity = 0;
		var order_products_affected = [];
		for (var key in order_products) {
			var order_product = order_products[key];
			if (order_product['product_id'] == data['product_id'] && !order_product['price_change']) {
				discount_quantity += order_product['quantity'];
				order_products_affected[order_product['order_product_id']] = {'quantity':order_product['quantity'], 'weight':order_product['weight']};
			}
		}
		var product = data['product'];

		// for insert, need to return the price in case the tax needs to be added to the price
		if (product && product['product_discounts'] && product['product_discounts'].length > 0) {
			var price = product['price'];
			var today = $.datepicker.formatDate('yy-mm-dd');
			var priority = -1;
			for (var i = 0; i < product['product_discounts'].length; i++) {
				var product_discount = product['product_discounts'][i];
				if (product_discount['quantity'] < discount_quantity && product_discount['customer_group_id'] == data['customer_group_id'] && (product_discount['date_start'] == '0000-00-00' || product_discount['date_start'] < today) && (product_discount['date_end'] = '0000-00-00' || product_discount['date_end'] > today)) {
					if (priority < 0 || priority > product_discount['priority']) {
						// first matched product discount, or higher priority discount found
						priority = product_discount['priority'];
						price = product_discount['price'];
					}
				}
			}
		
			var insert_tax = getTax(price, product['tax_class_id'], data['customer_group_id']);
			// update all the totals and price
			for (var add_order_product_id in order_products_affected) {
				var add_order_product_info = order_products_affected[add_order_product_id];
				var insert_total = parseFloat(price) * parseInt(add_order_product_info['quantity']) * parseFloat(add_order_product_info['weight']);
				extra_info[add_order_product_id] = {'price' : (parseInt(config['config_tax']) == 1) ? parseFloat(price) + insert_tax : price, 
					'total' : (parseInt(config['config_tax']) == 1) ? insert_total + insert_tax * parseInt(add_order_product_info['quantity']) * parseFloat(add_order_product_info['weight']): insert_total};
				// also need update the order_products to be added into the cart
				for (var key in order_products) {
					var order_product = order_products[key];
					if (order_product['order_product_id'] == add_order_product_id) {
						order_products[key]['price'] = price;
						order_products[key]['tax'] = insert_tax;
						order_products[key]['total'] = insert_total;
					}
				}
			}
		}
	} else if (action == 'modify_quantity') {
		// quantity change scenario is a little bit different
		// if before is qualified to have discount and after isn't, price will increase
		// if before isn't qualified to have discount and after is, price will decrease
		// for product price, only the after quantity is needed
		var modify_may_affect_other_order_products = false;
		for (var key in order_products) {
			var order_product = order_products[key];
			if (order_product['order_product_id'] == data['order_product_id'] && !order_product['price_change'] && order_product['quantity'] > 0) {
				modify_may_affect_other_order_products = true;
				break;
			}
		}
		if (modify_may_affect_other_order_products) {
			var curr_total_quantity = 0;
			var order_products_affected = [];
			for (var key in order_products) {
				var order_product = order_products[key];
				if (order_product['product_id'] == data['product_id'] && !order_product['price_change']) {
					curr_total_quantity += order_product['quantity'];
					order_products_affected[order_product['order_product_id']] = {'quantity':order_product['quantity'], 'weight':order_product['weight']};
				}
			}
			var prev_total_quantity = curr_total_quantity + parseInt(data['quantity_before']) - parseInt(data['quantity_after']);
			var discount_quantity = curr_total_quantity > prev_total_quantity ? curr_total_quantity : prev_total_quantity;
			
			var product = data['product'];
			if (product && product['product_discounts'] && product['product_discounts'].length > 0) {
				var today = $.datepicker.formatDate('yy-mm-dd');
				var priority = -1;
				var price = product['price'];
				for (var i = 0; i < product['product_discounts'].length; i++) {
					var product_discount = product['product_discounts'][i];
					if (product_discount['quantity'] < discount_quantity && product_discount['customer_group_id'] == data['customer_group_id'] && (product_discount['date_start'] == '0000-00-00' || product_discount['date_start'] < today) && (product_discount['date_end'] = '0000-00-00' || product_discount['date_end'] > today)) {
						if (priority < 0 || priority > product_discount['priority']) {
							// first matched product discount, or higher priority discount found
							priority = product_discount['priority'];
							price = product_discount['price'];
						}
					}
				}
				// change the product price as the price may be different from the original price as discount was used
				var changed_price = price;
				if (curr_total_quantity < prev_total_quantity) {
					changed_price = product['price'];
				}
				var modify_tax = getTax(changed_price, product['tax_class_id'], data['customer_group_id']);
				// update all the totals and price in order_product table
				for (var modify_order_product_id in order_products_affected) {
					var modify_order_product_info = order_products_affected[modify_order_product_id];
					var modify_total = changed_price * parseInt(modify_order_product_info['quantity']) * parseFloat(modify_order_product_info['weight']);
					extra_info[modify_order_product_id] = {'price' : (parseInt(config['config_tax']) == 1) ? changed_price + modify_tax : changed_price, 
						'total' : (parseInt(config['config_tax']) == 1) ? modify_total + modify_tax * parseInt(modify_order_product_info['quantity']) * parseFloat(modify_order_product_info['weight']): modify_total};
					// also need update the order_products to be added into the cart
					for (var key in order_products) {
						var order_product = order_products[key];
						if (order_product['order_product_id'] == modify_order_product_id) {
							order_products[key]['price'] = changed_price;
							order_products[key]['tax'] = modify_tax;
							order_products[key]['total'] = modify_total;
						}
					}
				}
			}
		}
	}
	
	// save the modified order products back to the order
	if (order) {
		order['products'] = order_products;
		console.log('write the saved products back to current order');
		localStorage.setItem(CACHE_CURRENT_ORDER, JSON.stringify(order));
	}
	backendCalcTotal(order_products, data, extra_info, callback);
}

function getTaxes(order_products, customer_group_id, use_order_discount) {
	var tax_data = {};
	
	// get subtotal for order level pos_discount
	var sub_total = 0;
	var discount_tax_class_id = 0;
	var compare_total = 0;	// used for comparing the total for each order product and pick the class id from the max total order product
	
	for (var i in order_products) {
		var order_product = order_products[i];
		if (order_product['tax_class_id']) {
			var tax_rates;
			if (order_product['discount']) {
				tax_rates = getRates(order_product['discount']['discounted_price'], order_product['tax_class_id'], customer_group_id);
				// logic for order level discount
				sub_total += parseFloat(order_product['discount']['discounted_price']) * order_product['quantity'] * order_product['weight'];
				if (order_product['discount']['discounted_total'] > compare_total && hasPercentage(tax_rates)) {
					discount_tax_class_id = order_product['tax_class_id'];
					compare_total = order_product['discount']['discounted_total'];
				}
			} else {
				tax_rates = getRates(order_product['price'], order_product['tax_class_id'], customer_group_id);
				// logic for order level discount
				sub_total += parseFloat(order_product['price']) * order_product['quantity'] * order_product['weight'];
				if (order_product['total'] > compare_total && hasPercentage(tax_rates)) {
					discount_tax_class_id = order_product['tax_class_id'];
					compare_total = order_product['total'];
				}
			}
			
			for (var j in tax_rates) {
				var tax_rate = tax_rates[j];
				if (!tax_data[tax_rate['tax_rate_id']]) {
					tax_data[tax_rate['tax_rate_id']] = tax_rate['amount'] * parseFloat(order_product['weight']) * parseInt(order_product['quantity']);
				} else {
					tax_data[tax_rate['tax_rate_id']] += tax_rate['amount'] * parseFloat(order_product['weight']) * parseInt(order_product['quantity']);
				}
			}
		}
	}
	
	if (use_order_discount) {
		// discount is enabled
		amount = calc_discount_amount(sub_total);
		var tax_rates = getRates(amount, discount_tax_class_id, customer_group_id, true);
		for (var j in tax_rates) {
			var tax_rate = tax_rates[j];
			if (!tax_data[tax_rate['tax_rate_id']]) {
				// shouldn't be there but leave the logic here for now ###
				tax_data[tax_rate['tax_rate_id']] = tax_rate['amount'];
			} else {
				tax_data[tax_rate['tax_rate_id']] += tax_rate['amount'];
			}
		}
	}
	
	return tax_data;
}

function calc_discount_amount(sub_total) {
	var amount = 0;
	if (!$.isEmptyObject(pos_discount)) {
		if (pos_discount['code'] == 'pos_discount_fixed') {
			if ((0-pos_discount['value']) > sub_total) {
				amount = 0 - sub_total;	
			} else {
				amount = pos_discount['value'];	
			}				
		} else if (pos_discount['code'] == 'pos_discount_percentage') {
			var percentage_text = pos_discount['title'];
			var index1 = percentage_text.indexOf('(');
			var index2 = percentage_text.indexOf(')');
			var percentage = 0;
			if (index1 >= 0 && index2 > index1+2) {
				percentage = parseFloat(percentage_text.substring(index1+1, index2));
			}

			amount = 0 - sub_total * percentage / 100;
		}
	}
	
	return amount;
}

function hasPercentage(tax_rates) {
	var has_percentage = false;
	for (var i in tax_rates) {
		if (tax_rates[i]['type'] == 'P') {
			has_percentage = true;
			break;
		}
	}
	return has_percentage;
}

function backendCalcTotal(order_products, data, extra_info, callback) {
	var order_total = [];					
	var total = 0;
	var order = JSON.parse(localStorage.getItem(CACHE_CURRENT_ORDER));
	
	supportedTotals.sort(function(a,b) {
		return (a.sort_order - b.sort_order);
	});
	
	var discount_total = false;
	if (config['pos_discount_status'] && parseInt(config['pos_discount_status']) > 0 && pos_discount['order_id'] && pos_discount['order_id'] == order_id) {
		discount_total = true;
	}
	var taxes = getTaxes(order_products, data['customer_group_id'], discount_total);
	
	for (var index in supportedTotals) {
		var aTotal = supportedTotals[index];
		var status = false;
		if (config[aTotal.code + '_status']) {
			status = parseInt(config[aTotal.code + '_status']);
		}
		
		if (status && !(aTotal.code == 'shipping' && order['shipping_code'] == 'instore.instore')) {
			var total_data = aTotal.getTotal(order_total, total, taxes);
			order_total = total_data['total_data'];
			total = total_data['total'];
			taxes = total_data['taxes'];
			if (aTotal.code == 'sub_total') {
				// right after subtotal, apply the discount
				if (discount_total) {
					// Not calling the total method, as it has no context of the current functions to get the tax from discount
					// even though discount is given before tax, the taxes variable already contains the full sub total
					var amount = calc_discount_amount(total);
					
					order_total.push({
						'code' : pos_discount['code'],
						'title' : pos_discount['title'],
						'text' : formatMoney(amount),
						'value' : amount,
						'sort_order' : config['pos_discount_status']
					});

					total += amount;
				}
			}
		}
	}
	// save totals
	saveOrderInfo({'totals':order_total, 'total': total});
	
	// add for product based discount begin
	var discount = {};
	if (data['action'] && (data['action'] == 'insert' || data['action'] == 'modify_quantity')) {
		for (var key in order_products) {
			order_product = order_products[key];
			if (data['order_product_id'] && data['order_product_id'] == order_product['order_product_id'] && order_product['discount'] && parseFloat(order_product['discount']['discount_value']) > 0) {
				var product_discount = order_product['discount'];
				var discount_value = parseFloat(product_discount['discount_value']);
				if (data['action'] == 'modify_quantity' && parseInt(product_discount['discount_type']) == 1) {
					discount_value = parseFloat(product_discount['discount_value']) * data['quantity_after'] / data['quantity_before'];
				}
				discount['discount_type'] = product_discount['discount_type'];
				discount['discount_value'] = discount_value;
				discount['total_text'] = (config['config_tax'] && parseInt(config['config_tax']) == 1) ? parseInt(order_product['quantity']) * (parseFloat(order_product['price']) + parseFloat(order_product['tax'])) * parseFloat(order_product['weight']) : order_product['total'];
				discount['discounted_total_text'] = (config['config_tax'] && parseInt(config['config_tax']) == 1) ? parseInt(order_product['quantity']) * (parseFloat(product_discount['discounted_price']) + parseFloat(product_discount['discounted_tax'])) : product_discount['discounted_total'];
			}
		}
	}
	// add for product based discount end
	callback({'order_total':order_total, 'total':total, 'discount':discount, 'extra_info':extra_info});
};

function backendDeleteOrder(data, callback) {
	// get order info from the local storage
	if (data) {
		data.each(function() {
			localStorage.removeItem(CACHE_ORDER + $(this).val());
		});
		backendGetOrderList({}, callback);
	}
};

function backendDeleteReturn(data, callback) {
	// get return info from the local storage
	if (data) {
		data.each(function() {
			localStorage.removeItem(CACHE_RETURN + $(this).val());
		});
		backendGetReturnList({}, callback);
	}
};

function backendSaveOrderStatus(data, callback) {
	// save order status to local storage
	saveOrderInfo(data);
	
	// return success
	var json = {'success':text_local_save_order_status_success};
	var complete_status_id = config['complete_status_id'] ? parseInt(config['complete_status_id']) : 5;
	if (data['order_status_id'] == complete_status_id) {
		json['p_complete'] = config['p_complete'] ? parseInt(config['p_complete']) : 0;
	}
	
	callback(json);
};

function backendSaveQuoteStatus(data, callback) {
	// save order status to local storage
	saveOrderInfo(data);
	
	// return success
	var json = {'success':text_local_save_quote_status_success};
	callback(json);
};

function backendConvertQuote2Order(data, callback) {
	// update the current quote
	saveOrderInfo(data);
	
	// copy the quote to an order and reassign the order id
	var cur_quote = JSON.parse(localStorage.getItem(CACHE_CURRENT_ORDER));
	cur_quote['order_status_id'] = 1;
	cur_quote['quote_status_id'] = 0;
	cur_quote['quote_id'] = cur_quote['order_id'];
	var orderIds = getNewOrderId();
	cur_quote['order_id'] = orderIds['order_id'];
	cur_quote['order_id_text'] = orderIds['order_id_text'];
	localStorage.setItem(CACHE_ORDER + orderIds['order_id'], JSON.stringify(cur_quote));
	callback({'order_id' : orderIds['order_id']});
};

function backendSaveReturnStatus(data, callback) {
	// save return status to local storage
	data['return_status'] = '';
	if (return_statuses) {
		for (var i = 0; i < return_statuses.length; i++) {
			if (parseInt(return_statuses[i]['return_status_id']) == parseInt(data['return_status_id'])) {
				data['return_status'] = return_statuses[i]['name'];
				break;
			}
		}
	}
	saveReturnInfo(data);
	
	// return success
	var json = {'success':text_local_save_return_status_success};
	var complete_status_id = config['return_complete_status_id'] ? parseInt(config['return_complete_status_id']) : 3;
	if (data['return_status_id'] == complete_status_id) {
		json['p_complete'] = config['p_complete'] ? parseInt(config['p_complete']) : 0;
		// once return is complete, save it to its dedicated local item
		moveCurrentReturn();
	}
	
	callback(json);
};

function moveCurrentOrder() {
	var currOrderString = localStorage.getItem(CACHE_CURRENT_ORDER);
	if (currOrderString) {
		currOrder = JSON.parse(currOrderString);
		if (currOrder['products'] && currOrder['products'].length > 0) {
			// not an empty order, save it
			localStorage.setItem(CACHE_ORDER + currOrder['order_id'], currOrderString);
			localStorage.removeItem(CACHE_CURRENT_ORDER);
		}
	}
};

function moveCurrentReturn() {
	var curReturnString = localStorage.getItem(CACHE_CURRENT_RETURN);
	if (curReturnString) {
		curReturn = JSON.parse(curReturnString);
		if (curReturn['return_products'] && curReturn['return_products'].length > 0) {
			localStorage.setItem(CACHE_RETURN + curReturn['pos_return_id'], curReturnString);
			localStorage.removeItem(CACHE_CURRENT_RETURN);
		}
	}
};

function backendSaveCustomerAddress(address) {
	if (address && address['address_id'] && typeof customer_id != 'undefined' && (parseInt(customer_id)) != 0) {
		// save customer details
		var key = 'customer_' + customer_id;
		pos_db.get(key, function(err, doc) {
			if (err) {
				// error occured
				console.log(err);
				if (err.status = 404) {
					var customer = {'customer_id':customer_id, 'addresses':[address]};
					pos_db.put(customer, key);
				}
			} else {
				if (doc) {
					// should update the doc as we find it
					var addressFound = false;
					if (doc['addresses']) {
						console.log(doc['addresses']);
						if (typeof doc['addresses'] == 'object') {
							doc['addresses'] = $.map(doc['addresses'], function(value, index) {
								return [value];
							});
						}
						for (var i = 0; i < doc['addresses'].length; i++) {
							if (parseInt(doc['addresses'][i]['address_id']) == address['address_id']) {
								// found the address
								for (var j in address) {
									doc['addresses'][i][j] = address[j];
								}
								addressFound = true;
								break;
							}
						}
						if (!addressFound) {
							doc['addresses'].push(address);
						}
					} else {
						doc['addresses'] = [address];
					}
					pos_db.put(doc, key);
				} else {
					var customer = {'customer_id':customer_id, 'addresses':[address]};
					pos_db.put(customer, key);
				}
			}
		});
	}
};

function backendGetCustomerAddress(address_id, callback) {
	if (address_id && typeof customer_id != 'undefined' && parseInt(customer_id) > 0) {
		var key = 'customer_' + customer_id;
		pos_db.get(key, function(err, doc) {
			if (err) {
				// error occured
				console.log(err);
			} else {
				if (doc && doc['addresses']) {
					for (var i = 0; i < doc['addresses'].length; i++) {
						if (parseInt(doc['addresses'][i]['address_id']) == address_id) {
							callback(doc['addresses'][i]);
							break;
						}
					}
				}
			}
		});
	}
};

function backendSaveCustomer(formData, callback) {
	var customer_data = getFormData(formData);
	var json = customer_data;
	if (customer_data['customer_id'] && parseInt(customer_data['customer_id']) != 0) {
		var save_customer_id = parseInt(customer_data['customer_id']);
		if (save_customer_id == -1) {
			// new customer, set the customer id to the max customer id
			var max_customer_id = localStorage.getItem(CACHE_MAX_CUSTOMER_ID);
			if (max_customer_id) {
				save_customer_id = 0 - parseInt(max_customer_id);
				localStorage.setItem(CACHE_MAX_CUSTOMER_ID, (parseInt(max_customer_id) + 1));
			} else {
				// important, the customer id starts with -2, as -1 has been used by the logic to indicate a "new" customer, regardless it's a local or server customer
				save_customer_id = -2;
				localStorage.setItem(CACHE_MAX_CUSTOMER_ID, 3);
			}
			customer_data['customer_id'] = save_customer_id;
			customer_data['date_added'] = formatDate(new Date());
		}
		
		json['hasAddress'] = 0;
		if (customer_data['customer_addresses']) {
			// assign unique address id for each address
			for (var i in customer_data['customer_addresses']) {
				var address = customer_data['customer_addresses'][i];
				if (parseInt(address['address_id']) < 0 && parseInt(address['address_id']) > -30) {
					// it's a new address id and has never been changed locally or cached from server
					var max_address_id = localStorage.getItem(CACHE_MAX_ADDRESS_ID);
					if (max_address_id) {
						localStorage.setItem(CACHE_MAX_ADDRESS_ID, (parseInt(max_address_id) + 1));
						max_address_id = 0 - parseInt(max_address_id);
					} else {
						// important, the address id starts with -31, to differentiate the new address id and locally saved address id
						// assume a single customer wouldn't have more than 30 addresses
						max_address_id = -31;
						localStorage.setItem(CACHE_MAX_ADDRESS_ID, 32);
					}
					customer_data['customer_addresses'][i]['address_id'] = max_address_id;
				}
			}
			
			json['hasAddress'] = 1;
			for (var i in customer_data['customer_addresses']) {
				var address = customer_data['customer_addresses'][i];
				if (address['default'] && parseInt(address['default']) > 0) {
					json['hasAddress'] = 2;
					json['customer_address_id'] = address['address_id'];
					customer_data['customer_address_id'] = address['address_id'];
					
					json['country_id'] = address['country_id'];
					json['zone_id'] = address['zone_id'];
					
					// set order shipping and payment address to this address
					if (text_work_mode == '0' || text_work_mode == '2') {
						var order_info = {'order_id': order_id, 'payment_firstname': address['firstname'], 'payment_lastname':address['lastname'], 'payment_company':address['company'], 'payment_address_1':address['address_1'], 'payment_address_2':address['address_2'], 'payment_city':address['city'], 'payment_postcode':address['postcode'], 'payment_country':address['country'], 'payment_country_id':address['country_id'], 'payment_zone':address['zone'], 'payment_zone_id':address['zone_id'], 'shipping_firstname':address['firstname'], 'shipping_lastname':address['lastname'],  'shipping_company':address['company'], 'shipping_address_1':address['address_1'], 'shipping_address_2':address['address_2'], 'shipping_city':address['city'], 'shipping_postcode':address['postcode'], 'shipping_country':address['country'], 'shipping_country_id':address['country_id'], 'shipping_zone':address['zone'], 'shipping_zone_id':address['zone_id']};
						saveOrderInfo(order_info);
					}
					break;
				}
			}
		}
		if (json['hasAddress'] < 2 && (text_work_mode == '0' || text_work_mode == '2')) {
			// no default address, set payment and shipping address to empty
			var order_info = {'order_id': order_id, 'payment_firstname': '', 'payment_lastname':'', 'payment_company':'', 'payment_address_1':'', 'payment_address_2':'', 'payment_city':'', 'payment_postcode':'', 'payment_country':'', 'payment_country_id':'', 'payment_zone':'', 'payment_zone_id':'', 'shipping_firstname':'', 'shipping_lastname':'',  'shipping_company':'', 'shipping_address_1':'', 'shipping_address_2':'', 'shipping_city':'', 'shipping_postcode':'', 'shipping_country':'', 'shipping_country_id':'', 'shipping_zone':'', 'shipping_zone_id':''};
			saveOrderInfo(order_info);
		}
		var customer_info = {'customer_addresses': {}};
		for (var customer_key in customer_data) {
			customer_info[customer_key] = customer_data[customer_key];
		}
		if (text_work_mode == '0' || text_work_mode == '2') {
			saveOrderInfo(customer_info);
		} else {
			saveReturnInfo(customer_info);
		}
		
		var tobe_saved = {'changed' : 1};
		for (var index in customer_data) {
			if (index != 'customer_id' && index != 'customer_group_id' && index.substring(0, 'customer_'.length) == 'customer_') {
				tobe_saved[index.substring('customer_'.length)] = customer_data[index];
			} else {
				tobe_saved[index] = customer_data[index];
			}
		}
		if (json['hasAddress'] == 0) {
			// address has been removed
			tobe_saved['addresses'] = {};
		}
		
		// save the customer to database
		saveDoc('customer_' + customer_data['customer_id'], tobe_saved);	// do not overwrite the added_date field
	}
	
	// update order or return info
	if (text_work_mode == '0' || text_work_mode == '2') {
		var order_info = {'order_id':order_id, 'customer_group_id':customer_data['customer_group_id'], 'customer_id':customer_data['customer_id'], 'firstname':customer_data['customer_firstname'], 'lastname':customer_data['customer_lastname'], 'customer':customer_data['customer_firstname'] + ' ' + customer_data['customer_lastname'], 'email':customer_data['customer_email'], 'telephone':customer_data['customer_telephone'], 'date_modified':formatDate(new Date())};
		saveOrderInfo(order_info);
		json['success'] = text_order_success;
	} else {
		var pos_return_info = {'pos_return_id':pos_return_id, 'customer_id':customer_data['customer_id'], 'firstname':customer_data['customer_firstname'], 'lastname':customer_data['customer_lastname'], 'customer':customer_data['customer_firstname'] + ' ' + customer_data['customer_lastname'], 'email':customer_data['customer_email'], 'telephone':customer_data['customer_telephone'], 'date_modified':formatDate(new Date())};
		saveReturnInfo(pos_return_info);
		json['success'] = text_return_success;
	}
	console.log(json);

	callback(json);
};

var customer_start_key;
function fetchCustomerListPage(page, callback, limit) {
	// fetch total number of customers first
	pos_db.allDocs({startkey: 'customer_', endkey: 'customer_\uffff'}, function (err, docs) {
		if (docs && docs['rows'].length > 0) {
			var total_rows = docs['rows'].length;
			
			if (!limit) limit = 8;
			var pageNum = page ? page : 1;
			var pageLimit = limit ? limit+1 : 11;
			var options = {limit: pageLimit};
	
			// then fetch the current page
			if (pageNum == 1) {
				options.startkey = 'customer_';
			} else {
				options.startkey = customer_start_key;
			}
			options.endkey = 'customer_\uffff';
			options.include_docs = true;
			pos_db.allDocs(options, function (err, response) {
				console.log('page: ' + pageNum + ', startkey: ' + options.startkey);
				console.log(response);
				if (response && response['rows'].length > 0) {
					customer_start_key = response['rows'][response['rows'].length - 1].doc._id;
					// populate the order list page
					var json = {'customers':[]};
					var item_in_array = (response.rows.length > limit) ? limit : response.rows.length;
					for (var i = 0; i < item_in_array; i++) {
						var customer = response.rows[i].doc;
						customer['name'] = customer['firstname'] + ' ' + customer['lastname'];
						json['customers'].push(customer);
					}
					json['pagination'] = getPagination(total_rows, page, limit, 'selectCustomerPage');
					callback(json);
				}
				if (err) {
					console.log(err);
					callback({});
				}
			});
		} else {
			callback({});
		}
	});
};

function backendGetCustomerList(data, callback) {
	var limit = 8;
	// get the customer info from the local repository
	var page = (data && data['page'] && parseInt(data['page']) > 0) ? parseInt(data['page']) : 1;
	var whereClause = ['(_id contains \'customer_\')'];
	if (data) {
		if (data['filter_customer_id']) {
			whereClause.push('(customer_id = \'' + data['filter_customer_id'] + '\')');
		}
		if (data['filter_name']) {
			whereClause.push('((firstname contains \'' + data['filter_name'].replace(/'/g, "\\'") + '\') or (lastname contains \'' + data['filter_name'].replace(/'/g, "\\'") + '\'))');
		}
		if (data['filter_email']) {
			whereClause.push('(email contains \'' + data['filter_email'].replace(/'/g, "\\'") + '\')');
		}
		if (data['filter_telephone']) {
			whereClause.push('(telephone contains \'' + data['filter_telephone'].replace(/'/g, "\\'") + '\')');
		}
		if (data['filter_date_added']) {
			whereClause.push('(date_added = \'' + data['filter_date_added'] + '\')');
		}
	}
	if (whereClause.length > 0) {
		// use GQL only when a filtering is required
		whereClause = whereClause.join(' and ');
		console.log(whereClause);
		gql.gql({select: "*", where: whereClause}, function(err, response) {
			if (err) {
				console.log(err);
			} else {
				console.log(response);
				// check how many customers match the filtering
				var json = {};
				if (response.rows && response.rows.length > 0) {
					var total_rows = response.rows.length;
					json['customers'] = [];
					var start = (page - 1) * limit;
					var rows_in_array = (total_rows > start + limit) ? start + limit : total_rows;
					for (var i = start; i < rows_in_array; i++) {
						var customer = response.rows[i];
						customer['name'] = customer['firstname'] + ' ' + customer['lastname'];
						json['customers'].push(customer);
					}
					json['pagination'] = getPagination(total_rows, page, limit, 'selectCustomerPage');
				} else {
					json['pagination'] = '';
				}
				callback(json);
			}
		});
	} else {
		// for the all-customer listing, use allDocs instead for performance reason
		fetchCustomerListPage(page, callback, limit);
	}
};

function backendGetCustomers(filter_name, callback) {
	var searchLimit = 8;
	if (filter_name) {
		// use GQL only when a filtering is required
		var whereClause = '(_id contains \'customer_\') and ((firstname contains \'' + filter_name.replace(/'/g, "\\'") + '\') or (lastname contains \'' + filter_name.replace(/'/g, "\\'") + '\'))';
		console.log(whereClause);
		gql.gql({select: "*", where: whereClause}, {limit: searchLimit}, function(err, response) {
			if (err) {
				console.log(err);
			} else {
				console.log(response);
				// check how many products match the filtering
				var json = {};
				if (response.rows && response.rows.length > 0) {
					json = response.rows;
					for (var i in json) {
						json[i]['name'] = json[i]['firstname'] + ' ' + json[i]['lastname'];
					}
				}
				callback(json);
			}
		});
	}
};

function backendSelectCustomer(customer) {
	// save the customer info passed from server end to local repository
	if (customer && customer['customer_id'] && parseInt(customer['customer_id']) > 0) {
		var normalized = {};
		if (customer['customer_addresses']) {
			normalized['addresses'] = {};
			for (var index in customer['customer_addresses']) {
				// save the zone info to the local storage and remove the zones before save it to the database
				var customer_address = customer['customer_addresses'][index];
				var address = {};
				for (var attr in customer_address) {
					if (attr == 'zones') {
						localStorage.setItem('zones_' + customer_address['country_id'], JSON.stringify(customer_address[attr]));
					} else {
						address[attr] = customer_address[attr];
					}
				}
				normalized['addresses'][customer_address['address_id']] = address;
			}
		}
		for (var index in customer) {
			if (index != 'customer_id' && index != 'customer_group_id' && index.substring(0, 'customer_'.length) == 'customer_') {
				if (index != 'customer_addresses') {
					normalized[index.substring('customer_'.length)] = customer[index];
				}
			} else {
				normalized[index] = customer[index];
			}
		}
		saveDoc('customer_' + customer['customer_id'], normalized, true);
	}
};

function backendGetCustomer(customer_id, callback) {
	var key = 'customer_' + customer_id;
	pos_db.get(key, function(err, doc) {
		if (err) {
			console.log(err);
			openAlert(text_customer_not_in_local);
		} else {
			var customer = {};
			for (var index in doc) {
				if (index != 'customer_id' && index != 'customer_group_id' && index != '_id' && index != '_rev') {
					customer['customer_' + index] = doc[index];
				} else {
					customer[index] = doc[index];
				}
			}
			if (customer['customer_addresses']) {
				for (var index in customer['customer_addresses']) {
					var zones = localStorage.getItem('zones_' + customer['customer_addresses'][index]['country_id']);
					if (zones) {
						customer['customer_addresses'][index]['zones'] = JSON.parse(zones);
					}
				}
			}
			console.log(customer);
			callback(customer);
		}
	});
};

function backendGetProductDetails(product_id, callback) {
	var key = 'product_' + product_id;
	pos_db.get(key, function(err, doc) {
		if (err) {
			console.log(err);
			openAlert(text_local_product_not_found);
		} else {
			doc['thumb'] = doc['image'];
			callback(doc);
		}
	});
};

function backendApplyDiscount(data, callback) {
	// save the discount info to order total
	var json = {};
	if (config['pos_discount_status'] && parseInt(config['pos_discount_status']) > 0) {
		pos_discount = {'order_id': data['order_id'], 'code': data['code'], 'title': data['title'], 'value': data['value']};
		backendUpdateTotal(data, function(total_data) {
			var cur_time = formatDate(new Date());
			// save the attributes to the order
			saveOrderInfo({'order_id':order_id, 'date_modified':cur_time, 'total':total_data['total'], 'totals':total_data['order_total']});
			
			json['totals'] = total_data['order_total'];
			json['success'] = text_order_success;
			callback(json);
		});
	} else {
		json['error'] = error_discount_not_installed;
		callback(json);
	}
};

function backendApplyProductDiscount(data, callback) {
	var json = {};
	
	var order = JSON.parse(localStorage.getItem(CACHE_CURRENT_ORDER));
	for (var index = 0; index < order['products'].length; i++) {
		var order_product = order['products'][index];
		if (order_product['order_product_id'] == data['order_product_id']) {
			console.log(order_product);
			var price = parseFloat(order_product['price']);
			var tax_class_id = parseInt(order_product['tax_class_id']);
			var discount_type = parseInt(data['discount_type']);
			var discount_value = parseFloat(data['discount_value']);
			var quantity = parseInt(data['quantity']);
			var weight = parseFloat(data['weight']);
			var config_tax = (config['config_tax'] && parseInt(config['config_tax']) > 0) ? 1 : 0;
			if (config_tax) {
				var before_discount_total_with_tax = (price + getTax(price, tax_class_id, data['customer_group_id'])) * quantity * weight;
				var after_discount_total_with_tax = before_discount_total_with_tax;
				if (discount_type == 1) {
					after_discount_total_with_tax = before_discount_total_with_tax - discount_value;
				} else if (discount_type == 2) {
					after_discount_total_with_tax = before_discount_total_with_tax * ( 1 - discount_value / 100);
				}
				after_discount_price_with_tax = after_discount_total_with_tax / quantity / weight;
				json['discounted_price'] = getPriceFromPriceWithTax(after_discount_price_with_tax, tax_class_id, data['customer_group_id']);
				json['discounted_tax'] = after_discount_price_with_tax - json['discounted_price'];
				json['discounted_total'] = json['discounted_price'] * quantity * weight;
			} else {
				if (discount_type == 1) {
					json['discounted_price'] = price - discount_value / quantity / weight;
				} else if (discount_type == 2) {
					json['discounted_price'] = price * ( 1 - discount_value / 100);
				}
				json['discounted_total'] = json['discounted_price'] * quantity * weight;
				json['discounted_tax'] = getTax($json['discounted_price'], tax_class_id, data['customer_group_id']);
			}
			// add product discount info into order product
			var discount = {'discount_type':discount_type, 'discount_value':discount_value, 'include_tax':config_tax, 'discounted_price':json['discounted_price'], 'discounted_total':json['discounted_total'], 'discounted_tax':json['discounted_tax']};
			console.log(discount);
			order['products'][index]['discount'] = discount;
			order['products'][index]['price_change'] = 1;
			// save it back to the order
			localStorage.setItem(CACHE_CURRENT_ORDER, JSON.stringify(order));
			
			// recalculate total
			backendCalcTotal(order['products'], data, {}, callback);

			break;
		}
	}
	
	if (!json['discounted_price']) {
		// if some other logical error
		callback(json);
	}
};

function backendGetPaymentsDetails(order_id, callback) {
	// when the payments details are required, the corresponding order should already be loaded into CACHE_CURRENT_ORDER or in its "named" local storage
	var order_loaded_properly = false;
	var order_payments = [];
	var order = localStorage.getItem(CACHE_CURRENT_ORDER);
	if (order) {
		order = JSON.parse(order);
		if (parseInt(order['order_id']) == order_id) {
			order_payments = order['order_payments'];
			order_loaded_properly = true;
		} else {
			order = localStorage.getItem(CACHE_ORDER + order_id);
			if (order) {
				order = JSON.parse(order);
				if (parseInt(order['order_id']) == order_id) {
					order_payments = order['order_payments'];
					order_loaded_properly = true;
				}
			}
		}
	}
	
	if (order_loaded_properly) {
		for (var i in order_payments) {
			order_payments[i]['payment_time'] = parseInt(parseDate(order_payments[i]['payment_time']) / 1000);
		}
		callback({'payments':order_payments});
	} else {
		openAlert(text_return_order_not_loaded);
	}
};

function backendSaveOrderPayment(data, callback) {
	// save the order payment details to the current order / return
	var json = {};
	if (data['order_id'] == 0) {
		// cash in / out payment
		json = {'success':text_cash_success};
		// save cash in / out locally
		data['payment_time'] = formatDate(new Date());
		var cash_in_out = localStorage.getItem(CACHE_CASH_IN_OUT);
		if (cash_in_out) {
			cash_in_out = JSON.parse(cash_in_out);
		} else {
			cash_in_out = [];
		}
		cash_in_out.push(data);
		localStorage.setItem(CACHE_CASH_IN_OUT, JSON.stringify(cash_in_out));
		callback(json);
	} else {
		if (text_work_mode == '0') {
			// for order only, quote will not have payment
			var order = localStorage.getItem(CACHE_CURRENT_ORDER);
			if (order) {
				var new_payment_id = -1;
				order = JSON.parse(order);
				if (parseInt(order['order_id']) == data['order_id']) {
					var order_payments = [];
					if (order['order_payments']) {
						order_payments = order['order_payments'];
						for (var i = 0; i < order_payments.length; i++) {
							if (parseInt(order_payments[i]['order_payment_id']) <= new_payment_id) {
								new_payment_id = parseInt(order_payments[i]['order_payment_id']) - 1;
							}
						}
					}
					// save the new payment to the order
					data['order_payment_id'] = new_payment_id;
					data['payment_time'] = formatDate(new Date());
					order_payments.push(data);
					order['order_payments'] = order_payments;
					localStorage.setItem(CACHE_CURRENT_ORDER, JSON.stringify(order));
					json = {'order_payment_id':new_payment_id, 'p_payment':config['p_payment']};
					callback(json);
				}
			}
			if (!json['order_payment_id']) {
				callback({'error':text_order_not_in_local});
			}
		} else if (text_work_mode == '1') {
			// for return
			var ret = localStorage.getItem(CACHE_CURRENT_RETURN);
			if (ret) {
				var new_payment_id = -1;
				ret = JSON.parse(ret);
				if (parseInt(ret['pos_return_id']) == data['pos_return_id']) {
					var return_payments = [];
					if (ret['return_payments']) {
						return_payments = ret['return_payments'];
						for (var i = 0; i < return_payments.length; i++) {
							if (parseInt(return_payments[i]['order_payment_id']) <= new_payment_id) {
								new_payment_id = parseInt(return_payments[i]['order_payment_id']) - 1;
							}
						}
					}
					// save the new payment to the order
					data['order_payment_id'] = new_payment_id;
					data['payment_time'] = formatDate(new Date());
					return_payments.push(data);
					ret['return_payments'] = return_payments;
					localStorage.setItem(CACHE_CURRENT_RETURN, JSON.stringify(ret));
					json = {'order_payment_id':new_payment_id, 'p_payment':config['p_payment']};
					callback(json);
				}
			}
			if (!json['order_payment_id']) {
				callback({'error':text_return_not_in_local});
			}
		}
	}
};

function backendRemoveOrderPayment(order_payment_id, callback) {
	if (text_work_mode == '0') {
		// it's order
		var order = localStorage.getItem(CACHE_CURRENT_ORDER);
		if (order) {
			order = JSON.parse(order);
			if (order['order_payments'] && order['order_payments'].length > 0) {
				for (var i = 0; i < order['order_payments'].length; i++) {
					if (parseInt(order['order_payments'][i]['order_payment_id']) == order_payment_id) {
						// remove the current order payment
						order['order_payments'].splice(i, 1);
						console.log(order);
						localStorage.setItem(CACHE_CURRENT_ORDER, JSON.stringify(order));
						break;
					}
				}
			}
		}
	} else if (text_work_mode == '1') {
		// it's return
		var ret = localStorage.getItem(CACHE_CURRENT_RETURN);
		if (ret) {
			ret = JSON.parse(ret);
			if (ret['return_payments'] && ret['return_payments'].length > 0) {
				for (var i = 0; i < ret['return_payments'].length; i++) {
					if (parseInt(ret['return_payments'][i]['order_payment_id']) == order_payment_id) {
						// remove the current return payment
						ret['return_payments'].splice(i, 1);
						console.log(ret);
						localStorage.setItem(CACHE_CURRENT_RETURN, JSON.stringify(ret));
						break;
					}
				}
			}
		}
	}
	callback({});
};

function backendSaveURL(type, html) {
	// save the rendered page into local repository
	var cache_key = CACHE_PRINT + type;
	if (type == 'receipt') {
		if (text_work_mode == '1') {
			// return receipt
			cache_key += '_return_receipt';
		} else if (config['gift_receipt_status_id'] && parseInt(order_status_id) == parseInt(config['gift_receipt_status_id'])) {
			cache_key += '_gift_receipt';
		}
	}
	localStorage.setItem(cache_key, base64_encode(html));
};

function backendPrintURL(type, data, cacheKey, callback) {
	// render the html page as per data
	if (type == 'receipt') {
		backendPrintReceipt(data, cacheKey, callback);
	} else if (type == 'invoice') {
		backendPrintInvoice(data, cacheKey, callback);
	} else if (type == 'cc_sign') {
		backendPrintCCReceipt(data, cacheKey, callback);
	}
};

function backendPrintReceipt(data, cacheKey, callback) {
	var receipt_user_info = (typeof user_info != 'undefined') ? user_info.replace('%s', user) : user;
	var barcode_for_product = config['barcode_for_product'] ? parseInt(config['barcode_for_product']) : 0;
	
	if (text_work_mode == '0') {
		if (!data['order_id']) { data['order_id'] = order_id; }
		var order = localStorage.getItem(CACHE_ORDER + data['order_id']);
		if (order) {
			order = JSON.parse(order);
		} else {
			curr_order = JSON.parse(localStorage.getItem(CACHE_CURRENT_ORDER));
			if (parseInt(curr_order['order_id']) == parseInt(data['order_id'])) {
				order = curr_order;
			}
		}
		
		if (order) {
			var html = '';
			if (config['gift_receipt_status_id'] && parseInt(order_status_id) == parseInt(config['gift_receipt_status_id'])) {
				html = base64_decode(localStorage.getItem(cacheKey + '_gift_receipt'));
			} else {
				html = base64_decode(localStorage.getItem(cacheKey));
			}
			
			$('#hidden_div').html(html);
			// update date and time
			var curDate = parseInt(new Date().getTime() / 1000);
			$('#receipt_date_td').text(date(date_format_short, curDate));
			$('#receipt_time_td').text(date(time_format, curDate));
			// update products info
			$('#receipt_products').empty();
			if (order['products'] && order['products'].length > 0) {
				var product_html = '';
				for (var i = 0; i < order['products'].length; i++) {
					var product = order['products'][i];
					product_html += '<tr><td align="right" valign="top">' + product['quantity'] + '</td>';
					product_html += '<td align="left" valign="top">' + product['name'] + '</td>';
					product_html += '<td align="right" valign="top">' + product['price_text'] + '</td>';
					var discount_data;
					if (product['discount'] && product['discount']['discount_type'] && parseInt(product['discount']['discount_type']) > 0) {
						discount_data = {'text_discount': product['discount']['discount_value'], 'total': product['discount']['discounted_total_text']};
						product_html += '<td align="right"><strike>' + product['total_text'] + '</strike></td>';
					} else {
						product_html += '<td align="right">' + product['total_text'] + '</td>';
					}
					product_html += '</tr>';
					if (product['option'] || parseInt(product['weight_price']) || product['sns']) {
						product_html += '<tr><td></td><td colspan="3" align="left">';
						if (product['option']) {
							for (var index in product['option']) {
								var option = product['option'][index];
								product_html += '&nbsp;<small> - ' + option['name']+ ': ' + option['value'] + '</small><br/>';
							}
						}
						if (parseInt(product['weight_price'])) {
							product_html += '&nbsp;<small> - ' + product['weight_name'] + ': ' + product['weight'] + '</small><br/>';
						}
						if (product['sns']) {
							for (var index in product['sns']) {
								var product_sn = product['sns'][index];
								product_html += '&nbsp;<small> - SN: ' + product_sn['sn'] + '</small><br/>';
							}
						}
						product_html += '</td></tr>';
					}
					if (discount_data) {
						product_html += '<tr><td colspan="3" align="right"><small>(' + text_discount + ': ' + discount_data['text_discount'] + ')</small></td>';
						product_html += '<td align="right">' + discount_data['total'] + '</td></tr>';
					}
					if (barcode_for_product) {
						product_html += '<tr><td colspan="4" align="center"><div id="barcode_13_total_' + i + '">' + product['ean'] + '</div></td></tr>';
					}
				}
				$('#receipt_products').html(product_html);
			}
			// update totals
			$('#receipt_totals').empty();
			if (order['totals'] && order['totals'].length > 0) {
				total_html = '';
				for (var i = 0; i < order['totals'].length; i++) {
					var total = order['totals'][i];
					total_html += '<tr><td colspan="3" align="right" width="80%"><b>' + total['title'] + ':</b></td>';
					total_html += '<td align="right" width="20%">' + formatMoney(total['value']) + '</td></tr>';
				}
				$('#receipt_totals').html(total_html);
			}
			// update payments
			$('#receipt_payments').empty();
			if (order['order_payments'] && order['order_payments'].length > 0) {
				var payment_html = '';
				for (var i = 0; i < order['order_payments'].length; i++) {
					var payment = order['order_payments'][i];
					payment_html += '<tr><td align="left" valign="top" style="border-bottom: 1px dashed #000000;">' + payment['payment_type'] + '</td>';
					payment_html += '<td align="right" valign="top" style="border-bottom: 1px dashed #000000;">' + formatMoney(payment['tendered_amount']) + '</td>';
					payment_html += '<td align="right" valign="top" style="border-bottom: 1px dashed #000000;">' + (payment['note'] ? payment['note'] : '&nbsp;') + '</td></tr>';
				}
				$('#receipt_payments').html(payment_html);
			}
			// update change?
			$('#receipt_change').empty();
			if (data['change']) {
				var change_html = '<tr><td align="left" valign="top" style="border-bottom: 1px dashed #000000;">' + text_change + '</td>';
				change_html += '<td align="right" valign="top" style="border-bottom: 1px dashed #000000;">' + formatMoney(data['change']) + '</td>';
				change_html += '<td align="right" valign="top" style="border-bottom: 1px dashed #000000;">&nbsp;</td></tr>';
				$('#receipt_change').html(change_html);
			}
			// update user info
			$('#receipt_user_info').text(receipt_user_info);
			$('#barcode_order').text(data['order_id']);
			
			// update gift receipt (the second part)
			if (config['gift_receipt_status_id'] && parseInt(order_status_id) == parseInt(config['gift_receipt_status_id'])) {
				// update date and time
				$('#receipt_date_td_1').text(date(date_format_short, curDate));
				$('#receipt_time_td_1').text(date(time_format, curDate));
				$('#receipt_gift_products').empty();
				// update products
				if (order['products'] && order['products'].length > 0) {
					var product_html = '';
					for (var i = 0; i < order['products'].length; i++) {
						var product = order['products'][i];
						product_html += '<tr><td align="right" valign="top">' + product['quantity'] + '</td>';
						product_html += '<td align="left" valign="top">' + product['name'];
						if (product['option']) {
							for (var index in product['option']) {
								var option = product['option'][index];
								product_html += '<br/>&nbsp;<small> - ' + option['name']+ ': ' + option['value'] + '</small>';
							}
						}
						if (product['sns']) {
							for (var index in product['sns']) {
								var product_sn = product['sns'][index];
								product_html += '<br/>&nbsp;<small> - SN: ' + product_sn['sn'] + '</small>';
							}
						}
						product_html += '</td></tr>';
					}
					$('#receipt_gift_products').html(product_html);
				}
				// update user info
				$('#receipt_user_info_1').text(receipt_user_info);
				$('#barcode_order_1').text(data['order_id']);
			}
			
			// call the print
			callback($('#hidden_div').html());
			$('#hidden_div').html('');
		} else {
			openAlert(text_order_not_in_local);
		}
	} else if (text_work_mode == '1') {
		if (!data['pos_return_id']) { data['pos_return_id'] = pos_return_id; }
		var posReturn = localStorage.getItem(CACHE_RETURN + data['pos_return_id']);
		if (posReturn) {
			posReturn = JSON.parse(posReturn);
		} else {
			curr_posReturn = JSON.parse(localStorage.getItem(CACHE_CURRENT_RETURN));
			if (parseInt(curr_posReturn['pos_return_id']) == parseInt(data['pos_return_id'])) {
				posReturn = curr_posReturn;
			}
		}
		
		if (posReturn) {
			var html = base64_decode(localStorage.getItem(cacheKey + '_return_receipt'));
			$('#hidden_div').html(html);
			// update date and time
			var curDate = parseInt(new Date().getTime() / 1000);
			$('#date_td').text(date(date_format_short, curDate));
			$('#time_td').text(date(time_format, curDate));
			// update products info
			$('#receipt_products').empty();
			if (posReturn['return_products'] && posReturn['return_products'].length > 0) {
				var product_html = '';
				for (var i = 0; i < posReturn['return_products'].length; i++) {
					var product = posReturn['return_products'][i];
					product_html += '<tr><td align="right" valign="top">' + product['quantity'] + '</td>';
					product_html += '<td align="left" valign="top">' + product['product'];
					if (product['option']) {
						for (var index in product['option']) {
							var option = product['option'][index];
							product_html += '<br/>&nbsp;<small> - ' + option['name']+ ': ' + option['value'] + '</small>';
						}
					}
					if (parseInt(product['weight_price']) > 0) {
						product_html += '&nbsp;<small> - ' + product['weight_name'] + ': ' + product['weight'] + '</small><br/>';
					}
					if (product['sns']) {
						for (var index in product['sns']) {
							var product_sn = product['sns'][index];
							product_html += '<br/>&nbsp;<small> - SN: ' + product_sn['sn'] + '</small>';
						}
					}
					product_html += '</td>';
					product_html += '<td align="right" valign="top">' + formatMoney(parseFloat(product['price']) + parseFloat(product['tax'])) + '</td>';
					product_html += '<td align="right">' + formatMoney((parseFloat(product['price']) + parseFloat(product['tax'])) * parseInt(product['quantity']) * parseInt(product['weight'])) + '&nbsp;CR</td></tr>';
				}
				$('#receipt_products').html(product_html);
			}
			// update totals
			$('#receipt_totals').empty();
			var total_data = [{'code':'subtotal', 'title':text_return_sub_total, 'value':posReturn['return_sub_total'], 'text':formatMoney(posReturn['return_sub_total']), 'sort_order':1},
							  {'code':'tax', 'title':text_return_tax, 'value':posReturn['return_tax'], 'text':formatMoney(posReturn['return_tax']), 'sort_order':2},
							  {'code':'total', 'title':text_return_total, 'value':parseFloat(posReturn['return_total']), 'text':formatMoney(parseFloat(posReturn['return_total'])), 'sort_order':3}];
			total_html = '';
			for (var i = 0; i < total_data.length; i++) {
				var total = total_data[i];
				total_html += '<tr><td colspan="3" align="right" width="80%"><b>' + total['title'] + ':</b></td>';
				total_html += '<td align="right" width="20%">' + total['text'] + '&nbsp;CR</td></tr>';
			}
			$('#receipt_totals').html(total_html);
			// update payments
			$('#receipt_payments').empty();
			if (posReturn['return_payments'] && posReturn['return_payments'].length > 0) {
				var payment_html = '';
				for (var i = 0; i < posReturn['return_payments'].length; i++) {
					var payment = posReturn['return_payments'][i];
					payment_html += '<tr><td align="left" valign="top" style="border-bottom: 1px dashed #000000;">' + payment['payment_type'] + '</td>';
					payment_html += '<td align="right" valign="top" style="border-bottom: 1px dashed #000000;">' + formatMoney(payment['tendered_amount']) + '&nbsp;CR</td>';
					payment_html += '<td align="right" valign="top" style="border-bottom: 1px dashed #000000;">' + (payment['note'] ? payment['note'] : '&nbsp;') + '</td></tr>';
				}
				$('#receipt_payments').html(payment_html);
			}
			// update change?
			$('#receipt_change').empty();
			if (data['change']) {
				var change_html = '<tr><td align="left" valign="top" style="border-bottom: 1px dashed #000000;">' + text_change + '</td>';
				change_html += '<td align="right" valign="top" style="border-bottom: 1px dashed #000000;">' + formatMoney(data['change']) + '</td>';
				change_html += '<td align="right" valign="top" style="border-bottom: 1px dashed #000000;">&nbsp;</td></tr>';
				$('#receipt_change').html(change_html);
			}
			// update user info
			$('#receipt_user_info').text(receipt_user_info);
			$('#barcode_order').text(data['pos_return_id']);
			
			// call the print
			callback($('#hidden_div').html());
			$('#hidden_div').html('');
		} else {
			openAlert(text_order_not_in_local);
		}
	}
};

function backendPrintInvoice(data, cacheKey, callback) {
	if (!data['order_id']) { data['order_id'] = order_id; }
	
	var order = localStorage.getItem(CACHE_ORDER + data['order_id']);
	if (order) {
		order = JSON.parse(order);
	} else {
		curr_order = JSON.parse(localStorage.getItem(CACHE_CURRENT_ORDER));
		if (parseInt(curr_order['order_id']) == parseInt(data['order_id'])) {
			order = curr_order;
		}
	}
		
	if (order) {
		var html = base64_decode(localStorage.getItem(cacheKey));
		
		$('#hidden_div').html(html);
		// update order id
		$('div[id^=invoice_order_id]').each(function() {
			$(this).text(data['order_id']);
		});
		// update date
		var curDate = date(date_format_short, parseInt(parseDate(order['date_added']) / 1000));
		$('#invoice_date_added').text(date(date_format_short, curDate));
		// update invoice no
		if (order['invoice_no']) {
			$('#invoice_invoice_no').html('<b>' + text_invoice_no + '</b> ' + order['invoice_no'] + '<br />');
		}
		// update payment and shipping method
		$('#invoice_payment_method').text(order['payment_method']);
		$('#invoice_shipping_method').text(order['shipping_method']);
		// update payment and shipping address
		var addressFormat = "{firstname} {lastname}\n{company}\n{address_1}\n{address_2}\n{city} {postcode}\n{zone}\n{country}";
		var addresses = {'payment':'', 'shipping':''};
		for (var index in addresses) {
			var replace = {'{firstname}' : order[index + '_firstname'],
						   '{lastname}'  : order[index + '_lastname'],
						   '{company}'   : order[index + '_company'],
						   '{address_1}' : order[index + '_address_1'],
						   '{address_2}' : order[index + '_address_2'],
						   '{city}'      : order[index + '_city'],
						   '{postcode}'  : order[index + '_postcode'],
						   '{zone}'      : order[index + '_zone'],
						   '{zone_code}' : order[index + '_zone_code'],
						   '{country}'   : order[index + '_country']
			};
			for (r_index in replace) {
				addresses[index] = addresses[index].replace(r_index, replace[r_index]);
			}
		}
		$('#invoice_payment_address').text(addresses['payment']);
		$('#invoice_shipping_address').text(addresses['shipping']);
		// update products
		$('#invoice_payment_address').text();
		$('#invoice_products').empty();
		if (order['products'] && order['products'].length > 0) {
			var product_html = '';
			for (var i = 0; i < order['products'].length; i++) {
				var product = order['products'][i];
				product_html += '<tr><td>' + product['name'];
				if (product['option']) {
					for (var index in product['option']) {
						var option = product['option'][index];
						product_html += '<br/>&nbsp;<small> - ' + option['name']+ ': ' + option['value'] + '</small>';
					}
				}
				product_html += '<td>' + product['model'] + '</td>';
				product_html += '<td>' + product['quantity'] + '</td>';
				product_html += '<td>' + product['price_text'] + '</td>';
				product_html += '<td>' + product['total_text'] + '</td>';
				product_html += '</tr>';
			}
			$('#invoice_products').html(product_html);
		}
		// update totals
		$('#invoice_totals').empty();
		if (order['totals'] && order['totals'].length > 0) {
			total_html = '';
			for (var i = 0; i < order['totals'].length; i++) {
				var total = order['totals'][i];
				total_html += '<tr><td class="text-right" colspan="4"><b>' + total['title'] + '</b></td>';
				total_html += '<td class="text-right">' + total['text'] + '</td></tr>';
			}
			$('#invoice_totals').html(total_html);
		}
		// update comment
		$('#invoice_comment').empty();
		if (order['comment']) {
			var comment_html = '<table class="table table-bordered">';
			comment_html += '<thead><tr><td><b>' + column_comment + '</b></td></tr></thead>';
			comment_html += '<tbody><tr><td>' + order['comment'] + '</td></tr></tbody>';
			comment_html += '</table>';
			$('#invoice_comment').html(comment_html);
		}
		
		// call the print
		callback($('#hidden_div').html());
		$('#hidden_div').html('');
	} else {
		openAlert(text_order_not_in_local);
	}
};

function backendPrintCCReceipt(data, cacheKey, callback) {
	// cannot process the credit card transaction when offline
};

function backendGetProductSNs(product_id, filter_sn, callback) {
	// search the product sn
	if (product_id && filter_sn) {
		pos_db.get('product_' + product_id, function(err, doc){
			if (err) {
				console.log(err);
			} else {
				var limit = 8;
				if (doc['product_sns'] && doc['product_sns'].length > 0) {
					var product_sns = [];
					for (var i = 0; i < doc['product_sns'].length; i++) {
						if (doc['product_sns'][i]['sn'].toLowerCase().indexOf(filter_sn.toLowerCase()) >= 0) {
							product_sns.push({'name':doc['product_sns'][i]['sn'], 'product_sn_id':doc['product_sns'][i]['product_sn_id']});
							if (product_sns.length == limit) {
								break;
							}
						}
					}
					callback(product_sns);
				}
			}
		});
	}
};

function saveOrderInfo(data) {
	if (data) {
		var cur_order = localStorage.getItem(CACHE_CURRENT_ORDER);
		if (cur_order) {
			cur_order = JSON.parse(cur_order);
		} else {
			cur_order = {};
		}
		for (var key in data) {
			cur_order[key] = data[key];
		}
		localStorage.setItem(CACHE_CURRENT_ORDER, JSON.stringify(cur_order));
	}
};

function saveReturnInfo(data) {
	if (data) {
		var cur_return = localStorage.getItem(CACHE_CURRENT_RETURN);
		if (cur_return) {
			cur_return = JSON.parse(cur_return);
		} else {
			cur_return = {};
		}
		for (var key in data) {
			cur_return[key] = data[key];
		}
		localStorage.setItem(CACHE_CURRENT_RETURN, JSON.stringify(cur_return));
	}
};

function backendSaveReturnAction(data, callback) {
	saveReturnInfo(data);
	data['affected'] = 1;
	callback(data);
};

function backendCheckReturn(data, callback) {
	// cannot access the return info for the given product
	openConfirm(text_cannot_check_return_when_offline, function() {
		callback({'quantity':0});
	});
};

function backendAddReturn(data, callback) {
	console.log(data);
	var ret = localStorage.getItem(CACHE_CURRENT_RETURN);
	if (ret) {
		ret = JSON.parse(ret);
		var json = {'success' : text_product_returned_successfully};
		data['return_id'] = json['return_id'] = local_return_product_id;
		data['date_added'] = data['date_modifed'] = formatDate(new Date());
		data['name'] = data['product'];
		data['price_text'] = formatMoney(parseFloat(data['price']) + parseFloat(data['tax']));
		data['total_text'] = formatMoney((parseFloat(data['price']) + parseFloat(data['tax'])) * parseInt(data['quantity']) * parseFloat(data['weight']));
		local_return_product_id--;

		if (ret['return_products']) {
			ret['return_products'].push(data);
		} else {
			ret['return_products'] = [data];
		}
		
		// update pos return total and tax
		ret['return_sub_total'] = parseFloat(ret['return_sub_total']) + parseFloat(data['price']) * parseInt(data['quantity']) * parseFloat(data['weight']);
		ret['return_tax'] = parseFloat(ret['return_tax']) + parseFloat(data['tax']) * parseInt(data['quantity']) * parseFloat(data['weight']);
		ret['return_total'] = ret['return_sub_total'] + ret['return_tax'];
		ret['return_date_modified'] = formatDate(new Date());
		for (var i = 0; i < ret['totals'].length; i++) {
			if (ret['totals'][i]['code'] == 'tax') {
				ret['totals'][i]['value'] = ret['return_tax'];
			} else if (ret['totals'][i]['code'] == 'subtotal') {
				ret['totals'][i]['value'] = ret['return_sub_total'];
			} else if (ret['totals'][i]['code'] == 'total') {
				ret['totals'][i]['value'] = ret['return_total'];
			}
		}
		console.log(ret);
		localStorage.setItem(CACHE_CURRENT_RETURN, JSON.stringify(ret));
		callback(json);
	} else {
		openAlert(text_local_return_not_initialized);
	}
};

var synced_returns = [];
var synced_orders = [];
function posuid() {
	var result = '';
	for(var i = 0; i < 32; i++) {
		result += Math.floor(Math.random()*16).toString(16).toUpperCase();
	}
	return result;
};

function syncData(fn, para, msg) {
	synced_returns = [];
	synced_orders = [];
	// before start to sync order, call start_sync_orders to initialize the session for syncing data, use an uid to differentiate this sync with other syncs that might happen at the same time
	var uid = posuid();
	var data = {'cash_in_out': []};
	var cash_in_out = localStorage.getItem(CACHE_CASH_IN_OUT);
	if (cash_in_out) {
		data['cash_in_out'] = JSON.parse(cash_in_out);
	}
	$.ajax({
		url: 'index.php?route=module/pos/sync_data_start&token=' + token + '&uid=' + uid,
		type: 'post',
		dataType: 'json',
		data: data,
		localCache: false,
		beforeSend: function() {
			if (fn) {
				openWaitDialog(msg);
			}
		},
		success: function(startJson) {
			// cash in and out has been synced, remove it from local storage
			localStorage.removeItem(CACHE_CASH_IN_OUT);
			// sync return first as it might refer to orders, and make sure all returns are synced before sync the orders
			console.log('ready to sync returns');
			var deferredsReturnSync = syncReturns(uid);
			$.when.apply(null, deferredsReturnSync).done(function() {
				// make sure the orders are synced completely before the customer and products are synced
				// as the customers and products are referred from the customer
				console.log('ready to sync orders');
				var deferreds = syncOrders(uid);

				$.when.apply(null, deferreds).done(function() {
					console.log('all orders were synced');
					$.ajax({
						url: 'index.php?route=module/pos/sync_data_end&token=' + token + '&uid=' + uid,
						type: 'post',
						dataType: 'json',
						data: {'returns' : synced_returns, 'orders' : synced_orders},
						localCache: false,
						complete: function() {
							if (fn) {
								closeWaitDialog();
							}
						},
						success: function(json) {
							// when the sync is done, call the functions if there is any
							if (fn) {
								fn(para);
							}
							
							// update customer ids
							saveSyncCustomerIds(0, json['customer_ids']);
							
							// update product ids
							saveSyncProductIds(0, json['product_ids']);
						}
					});
				});
			});
		}
	})
};

function saveSyncCustomerIds(index, customer_ids) {
	if (index < customer_ids.length) {
		var key = 'customer_' + customer_ids[index]['org_customer_id'];
		pos_db.get(key, function(err, doc) {
			if (doc) {
				doc['customer_id'] = customer_ids[index]['synced_customer_id'];
				doc['_id'] = 'customer_' + customer_ids[index]['synced_customer_id'];
				
				if (doc['addresses'] && customer_ids[index]['address_ids']) {
					for (var k in doc['addresses']) {
						for (var j = 0; j < customer_ids[index]['address_ids'].length; j++) {
							if (parseInt(doc['addresses'][k]['address_id']) == parseInt(customer_ids[index]['address_ids'][j]['org_address_id'])) {
								doc['addresses'][k]['address_id'] = customer_ids[index]['address_ids'][j]['synced_address_id'];
								if (doc['address_id'] && parseInt(doc['address_id']) == parseInt(customer_ids[index]['address_ids'][j]['org_address_id'])) {
									doc['address_id'] = customer_ids[index]['address_ids'][j]['synced_address_id'];
								}
								break;
							}
						}
					}
				}
				// remove the old customer, add the new one
				pos_db.remove(key, doc['_rev']);
				pos_db.put(doc);
				saveSyncCustomerIds(index+1, customer_ids);
			}
		});
	}
};

function saveSyncProductIds(index, product_ids) {
	if (index < product_ids.length) {
		var key = 'product_' + product_ids[index]['org_product_id'];
		pos_db.get(key, function(err, doc) {
			if (doc) {
				doc['product_id'] = product_ids[index]['synced_product_id'];
				doc['_id'] = 'product_' + product_ids[index]['synced_product_id'];
				// remove the old customer, add the new one
				pos_db.remove(key, doc['_rev']);
				pos_db.put(doc);
				saveSyncProductIds(index+1, product_ids);
			}
		});
	}
};

function syncReturns(uid) {
	var deferreds = [];
	
	var return_keys = [];
	for (var i = 0; i < localStorage.length; i++) {
		var key = localStorage.key(i);
		if (key.substring(0, CACHE_RETURN.length) == CACHE_RETURN) {
			return_keys.push(key);
		}
	}
	
	for (var i = 0; i < return_keys.length; i++) {
		var ret = JSON.parse(localStorage.getItem(return_keys[i]));
		ret['work_mode'] = text_work_mode;
		ret['user_id'] = user_id;
		console.log('syncing return ' + ret['pos_return_id']);
		deferreds.push(
			$.ajax({
				url: 'index.php?route=module/pos/sync_return&token=' + token + '&uid=' + uid,
				type: 'post',
				dataType: 'json',
				data: ret,
				localCache: false,
				success: function(json) {
					// remove this return from local storage once is synced successfully
					// note that this second loop (using return_keys) is used to avoid removeItem in the loop of localStorage keys
					console.log('removing key ' + json['org_pos_return_id'] + ' from local storage');
					localStorage.removeItem(CACHE_RETURN + json['org_pos_return_id']);
					synced_returns.push(json);
				}
			})
		);
	}
	
	return deferreds;
};

function syncOrders(uid) {
	var deferreds = [];
	
	var order_keys = [];
	for (var i = 0; i < localStorage.length; i++) {
		var key = localStorage.key(i);
		if (key.substring(0, CACHE_ORDER.length) == CACHE_ORDER) {
			order_keys.push(key);
		}
	}
	
	for (var i = 0; i < order_keys.length; i++) {
		var order = JSON.parse(localStorage.getItem(order_keys[i]));
		order['work_mode'] = text_work_mode;
		order['returns'] = synced_returns;
		console.log('syncing order ' + order['order_id']);
		console.log(order);
		deferreds.push(
			$.ajax({
				url: 'index.php?route=module/pos/sync_order&token=' + token + '&uid=' + uid,
				type: 'post',
				dataType: 'json',
				data: order,
				localCache: false,
				success: function(json) {
					// remove this order from local storage once is synced successfully
					// note that the second loop (using order_keys) is used to avoid removeItem in the loop of localStorage keys
					console.log('removing key ' + json['org_order_id'] + ' from local storage');
					localStorage.removeItem(CACHE_ORDER + json['org_order_id']);
					// update the customer id and product id if they are local previously
					synced_orders.push(json);
				}
			})
		);
	}
	
	return deferreds;
};

function backendFlushCurrentOrder() {
	// flush the current order in case it's offline while the order was processing
	// if the current order does not have any order product yet, create a new order as current order
	var empty_order = localStorage.getItem(CACHE_EMPTY_ORDER);
	if (empty_order) {
		empty_order = JSON.parse(empty_order);
		console.log($('#product tr').length);
		if ($('#product tr').length > 0) {
			// dump the current order into the current order, with the template from empty order info
			for (var key in empty_order) {
				console.log('adding ' + key + ' = ' + window[key]);
				if (window[key]) {
					empty_order[key] = window[key];
				} else {
					empty_order[key] = '';
				}
			}
			// add the products in case it's not in the products variable
			var formData = {};
			$('#product input').each(function() {
				formData[$(this).attr('name')] = $(this).val();
			});
			empty_order['products'] = [];
			var products = getFormData(formData);
			if (products['order_product']) {
				for (var index in products['order_product']) {
					var order_product_id = products['order_product'][index]['order_product_id'];
					var order_product_found = false;
					for (var i = 0; i < empty_order['products'].length; i++) {
						if (empty_order['products'][i]['order_product_id'] == order_product_id) {
							order_product_found = true;
							break;
						}
					}
					if (!order_product_found) {
						var product = products['order_product'][index];
						empty_order['products'].push(product);
					}
				}
			}
			
			if (parseInt(customer_id) > 0) {
				// save customer info into the order as well
				var customer_keys = ['customer_address_id', 'customer_addresses', 'customer_card_number', 'customer_firstname', 'customer_lastname', 'customer_email', 'customer_telephone', 'customer_fax', 'customer_password', 'customer_confirm', 'customer_newsletter', 'customer_status'];
				for (var i = 0; i < customer_keys.length; i++) {
					if (window[customer_keys[i]]) {
						empty_order[customer_keys[i]] = window[customer_keys[i]];
					}
				}
			}
		} else {
			// create a new order based on the empty order info
			var data = getNewOrderId();
			empty_order['order_id'] = data['order_id'];
			empty_order['order_id_text'] = data['order_id_text'];
		}
		// save the order info to the current order
		localStorage.setItem(CACHE_CURRENT_ORDER, JSON.stringify(empty_order));
		// update the products in the current order in case it does not have product details that are required when display them
		var i = 0;
		recur_call(i, empty_order['products']);
	}
};

function recur_call(index, products) {
	if (index == products.length) {
		// write back to the current order with the products only
		console.log('done, writing back to the current order');
		saveOrderInfo({'products': products});
	} else {
		var key = 'product_' + products[index]['product_id'];
		pos_db.get(key, function(err, doc) {
			if (err) {
				console.log(err);
			} else if (doc) {
				console.log('writing #' + index + ' product');
				doc['thumb'] = doc['image'];
				for (var attr in doc) {
					if (parseInt(doc['product_id']) < 0 || attr != 'description') {
						// description is not required for server cached product
						products[index][attr] = doc[attr];
					}
				}
				if (!products[index]['total']) {
					products[index]['total'] = parseFloat(products[index]['price']) * parseInt(products[index]['quantity']) * parseFloat(products[index]['weight']);
					products[index]['total_text'] = formatMoney(products[index]['total']);
				}
				recur_call(index+1, products);
			}
		});
	}
};

function backendFlushCurrentReturn() {
	// flush the current return in case it's offline while the return was processing
	// if the current return does not have any product yet, create a new return as current return
	var empty_return = localStorage.getItem(CACHE_EMPTY_RETURN);
	if (empty_return) {
		empty_return = JSON.parse(empty_return);
		if ($('#product tr').length > 0) {
			// dump the current return into the current return, with the template from empty return info
			for (var key in empty_return) {
				console.log('adding ' + key + ' = ' + window[key]);
				if (window[key]) {
					empty_return[key] = window[key];
				} else {
					empty_return[key] = '';
				}
			}
			// add the products in case it's not in the products variable
			var formData = {};
			$('#product input').each(function() {
				formData[$(this).attr('name')] = $(this).val();
			});
			empty_return['return_products'] = [];
			var products = getFormData(formData);
			if (products['order_product']) {
				for (var index in products['order_product']) {
					var order_product_id = products['order_product'][index]['order_product_id'];
					var order_product_found = false;
					for (var i = 0; i < empty_return['return_products'].length; i++) {
						if (empty_return['return_products'][i]['order_product_id'] == order_product_id) {
							order_product_found = true;
							break;
						}
					}
					if (!order_product_found) {
						empty_return['return_products'].push(products['order_product'][index]);
					}
				}
			}
		} else {
			// create a new order based on the empty order info
			var data = getNewReturnId();
			empty_return['pos_return_id'] = data['pos_return_id'];
			empty_return['text_pos_return_id'] = data['text_pos_return_id'];
			empty_return['order_id'] = 0;
			var cur_time = formatDate(new Date());
			empty_return['return_date_added'] = cur_time;
			empty_return['return_date_modified'] = cur_time;
		}
		// save the order info to the current order
		localStorage.setItem(CACHE_CURRENT_RETURN, JSON.stringify(empty_return));
	}
}

/* common functions begin */

function formatMoney(number) {
	number = number || 0;
	number = number * parseFloat((typeof currency_value == 'undefined') ? 1 : currency_value);
	var places = 2;
	number = toFixed(number, places);
	var negative = number < 0 ? "-" : "";
	i = parseInt(toFixed(number = Math.abs(+number || 0), places), 10) + "";
	j = (j = i.length) > 3 ? j % 3 : 0;
	return symbol_left + negative + (j ? i.substr(0, j) + text_thousand_point : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + text_thousand_point) + (places ? text_decimal_point + toFixed(Math.abs(number - i), places).slice(2) : "") + symbol_right;
};

function toFixed(num, fixed) {
	return (Math.round(parseFloat(num) * Math.pow(10, fixed)) / Math.pow(10, fixed)).toFixed(fixed);
};

function formatDate(date) {
	var hours = date.getHours();
	var minutes = date.getMinutes();
	var seconds = date.getMinutes();
	hours = ( hours < 10 ? "0" : "" ) + hours;
	minutes = ( minutes < 10 ? "0" : "" ) + minutes;
	seconds = ( seconds < 10 ? "0" : "" ) + seconds;

	var month = date.getMonth()+1;
	var day = date.getDate();
	month = ( month < 10 ? "0" : "" ) + month;
	day = ( day < 10 ? "0" : "" ) + day;
	
	return  date.getFullYear() + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;
};

function parseDate(dateString) {
	// assuming the date format is yyyy-MM-dd HH:mm:ss which is the string format from mysql database
	var parts = dateString.split(' ');
	var dateParts = parts[0].split('-');
	if (parts[1]) {
		var timeParts = parts[1].split(':');
		var date = new Date(dateParts[0], dateParts[1]-1, dateParts[2], timeParts[0], timeParts[1], timeParts[2]);
		return date.getTime();
	} else {
		var date = new Date(dateParts[0], dateParts[1]-1, dateParts[2]);
		return date.getTime();
	}
};

function getFormData(formElements) {
	var data = {};
	for (var index in formElements) {
		var top = data;
		var path = index;
		var val = formElements[index];
		var prev = '';
		while ((path.replace(/^(\[?\w+\]?)(.*)$/, function(_m, _part, _rest) {
			prev = path;
			_part = _part.replace(/[^A-Za-z_0-9]/g, '');
			if (!top[_part]) {
				if (/\w+/.test(_rest)) { 
					top[_part] = {}; 
					top = top[_part];
				} else {
					top[_part] = val; 
				}
			} else if (!/\w+/.test(_rest)) {
				top[_part] = val;
			} else {
				top = top[_part];
			}
			path = _rest;
		})) && (prev !== path));
	}
	
	return data;
};

function base64_encode(data) {
  var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
    ac = 0,
    enc = '',
    tmp_arr = [];

  if (!data) {
    return data;
  }

  do { // pack three octets into four hexets
    o1 = data.charCodeAt(i++);
    o2 = data.charCodeAt(i++);
    o3 = data.charCodeAt(i++);

    bits = o1 << 16 | o2 << 8 | o3;

    h1 = bits >> 18 & 0x3f;
    h2 = bits >> 12 & 0x3f;
    h3 = bits >> 6 & 0x3f;
    h4 = bits & 0x3f;

    // use hexets to index into b64, and append result to encoded string
    tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
  } while (i < data.length);

  enc = tmp_arr.join('');

  var r = data.length % 3;

  return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
};

function base64_decode(data) {
  var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
    ac = 0,
    dec = '',
    tmp_arr = [];

  if (!data) {
    return data;
  }

  data += '';

  do { // unpack four hexets into three octets using index points in b64
    h1 = b64.indexOf(data.charAt(i++));
    h2 = b64.indexOf(data.charAt(i++));
    h3 = b64.indexOf(data.charAt(i++));
    h4 = b64.indexOf(data.charAt(i++));

    bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;

    o1 = bits >> 16 & 0xff;
    o2 = bits >> 8 & 0xff;
    o3 = bits & 0xff;

    if (h3 == 64) {
      tmp_arr[ac++] = String.fromCharCode(o1);
    } else if (h4 == 64) {
      tmp_arr[ac++] = String.fromCharCode(o1, o2);
    } else {
      tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
    }
  } while (i < data.length);

  dec = tmp_arr.join('');

  return dec.replace(/\0+$/, '');
};

function date(format, timestamp) {
  //   example 1: date('H:m:s \\m \\i\\s \\m\\o\\n\\t\\h', 1062402400);
  //   returns 1: '09:09:40 m is month'
  //   example 2: date('F j, Y, g:i a', 1062462400);
  //   returns 2: 'September 2, 2003, 2:26 am'
  //   example 3: date('Y W o', 1062462400);
  //   returns 3: '2003 36 2003'
  //   example 4: x = date('Y m d', (new Date()).getTime()/1000);
  //   example 4: (x+'').length == 10 // 2009 01 09
  //   returns 4: true
  //   example 5: date('W', 1104534000);
  //   returns 5: '53'
  //   example 6: date('B t', 1104534000);
  //   returns 6: '999 31'
  //   example 7: date('W U', 1293750000.82); // 2010-12-31
  //   returns 7: '52 1293750000'
  //   example 8: date('W', 1293836400); // 2011-01-01
  //   returns 8: '52'
  //   example 9: date('W Y-m-d', 1293974054); // 2011-01-02
  //   returns 9: '52 2011-01-02'

  var that = this;
  var jsdate, f;
  // Keep this here (works, but for code commented-out below for file size reasons)
  // var tal= [];
  var txt_words = [
    'Sun', 'Mon', 'Tues', 'Wednes', 'Thurs', 'Fri', 'Satur',
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
  ];
  // trailing backslash -> (dropped)
  // a backslash followed by any character (including backslash) -> the character
  // empty string -> empty string
  var formatChr = /\\?(.?)/gi;
  var formatChrCb = function(t, s) {
    return f[t] ? f[t]() : s;
  };
  var _pad = function(n, c) {
    n = String(n);
    while (n.length < c) {
      n = '0' + n;
    }
    return n;
  };
  f = {
    // Day
    d: function() { // Day of month w/leading 0; 01..31
      return _pad(f.j(), 2);
    },
    D: function() { // Shorthand day name; Mon...Sun
      return f.l()
        .slice(0, 3);
    },
    j: function() { // Day of month; 1..31
      return jsdate.getDate();
    },
    l: function() { // Full day name; Monday...Sunday
      return txt_words[f.w()] + 'day';
    },
    N: function() { // ISO-8601 day of week; 1[Mon]..7[Sun]
      return f.w() || 7;
    },
    S: function() { // Ordinal suffix for day of month; st, nd, rd, th
      var j = f.j();
      var i = j % 10;
      if (i <= 3 && parseInt((j % 100) / 10, 10) == 1) {
        i = 0;
      }
      return ['st', 'nd', 'rd'][i - 1] || 'th';
    },
    w: function() { // Day of week; 0[Sun]..6[Sat]
      return jsdate.getDay();
    },
    z: function() { // Day of year; 0..365
      var a = new Date(f.Y(), f.n() - 1, f.j());
      var b = new Date(f.Y(), 0, 1);
      return Math.round((a - b) / 864e5);
    },

    // Week
    W: function() { // ISO-8601 week number
      var a = new Date(f.Y(), f.n() - 1, f.j() - f.N() + 3);
      var b = new Date(a.getFullYear(), 0, 4);
      return _pad(1 + Math.round((a - b) / 864e5 / 7), 2);
    },

    // Month
    F: function() { // Full month name; January...December
      return txt_words[6 + f.n()];
    },
    m: function() { // Month w/leading 0; 01...12
      return _pad(f.n(), 2);
    },
    M: function() { // Shorthand month name; Jan...Dec
      return f.F()
        .slice(0, 3);
    },
    n: function() { // Month; 1...12
      return jsdate.getMonth() + 1;
    },
    t: function() { // Days in month; 28...31
      return (new Date(f.Y(), f.n(), 0))
        .getDate();
    },

    // Year
    L: function() { // Is leap year?; 0 or 1
      var j = f.Y();
      return j % 4 === 0 & j % 100 !== 0 | j % 400 === 0;
    },
    o: function() { // ISO-8601 year
      var n = f.n();
      var W = f.W();
      var Y = f.Y();
      return Y + (n === 12 && W < 9 ? 1 : n === 1 && W > 9 ? -1 : 0);
    },
    Y: function() { // Full year; e.g. 1980...2010
      return jsdate.getFullYear();
    },
    y: function() { // Last two digits of year; 00...99
      return f.Y()
        .toString()
        .slice(-2);
    },

    // Time
    a: function() { // am or pm
      return jsdate.getHours() > 11 ? 'pm' : 'am';
    },
    A: function() { // AM or PM
      return f.a()
        .toUpperCase();
    },
    B: function() { // Swatch Internet time; 000..999
      var H = jsdate.getUTCHours() * 36e2;
      // Hours
      var i = jsdate.getUTCMinutes() * 60;
      // Minutes
      var s = jsdate.getUTCSeconds(); // Seconds
      return _pad(Math.floor((H + i + s + 36e2) / 86.4) % 1e3, 3);
    },
    g: function() { // 12-Hours; 1..12
      return f.G() % 12 || 12;
    },
    G: function() { // 24-Hours; 0..23
      return jsdate.getHours();
    },
    h: function() { // 12-Hours w/leading 0; 01..12
      return _pad(f.g(), 2);
    },
    H: function() { // 24-Hours w/leading 0; 00..23
      return _pad(f.G(), 2);
    },
    i: function() { // Minutes w/leading 0; 00..59
      return _pad(jsdate.getMinutes(), 2);
    },
    s: function() { // Seconds w/leading 0; 00..59
      return _pad(jsdate.getSeconds(), 2);
    },
    u: function() { // Microseconds; 000000-999000
      return _pad(jsdate.getMilliseconds() * 1000, 6);
    },

    // Timezone
    e: function() { // Timezone identifier; e.g. Atlantic/Azores, ...
      // The following works, but requires inclusion of the very large
      // timezone_abbreviations_list() function.
      /*              return that.date_default_timezone_get();
       */
      throw 'Not supported (see source code of date() for timezone on how to add support)';
    },
    I: function() { // DST observed?; 0 or 1
      // Compares Jan 1 minus Jan 1 UTC to Jul 1 minus Jul 1 UTC.
      // If they are not equal, then DST is observed.
      var a = new Date(f.Y(), 0);
      // Jan 1
      var c = Date.UTC(f.Y(), 0);
      // Jan 1 UTC
      var b = new Date(f.Y(), 6);
      // Jul 1
      var d = Date.UTC(f.Y(), 6); // Jul 1 UTC
      return ((a - c) !== (b - d)) ? 1 : 0;
    },
    O: function() { // Difference to GMT in hour format; e.g. +0200
      var tzo = jsdate.getTimezoneOffset();
      var a = Math.abs(tzo);
      return (tzo > 0 ? '-' : '+') + _pad(Math.floor(a / 60) * 100 + a % 60, 4);
    },
    P: function() { // Difference to GMT w/colon; e.g. +02:00
      var O = f.O();
      return (O.substr(0, 3) + ':' + O.substr(3, 2));
    },
    T: function() { // Timezone abbreviation; e.g. EST, MDT, ...
      // The following works, but requires inclusion of the very
      // large timezone_abbreviations_list() function.
      /*              var abbr, i, os, _default;
      if (!tal.length) {
        tal = that.timezone_abbreviations_list();
      }
      if (that.php_js && that.php_js.default_timezone) {
        _default = that.php_js.default_timezone;
        for (abbr in tal) {
          for (i = 0; i < tal[abbr].length; i++) {
            if (tal[abbr][i].timezone_id === _default) {
              return abbr.toUpperCase();
            }
          }
        }
      }
      for (abbr in tal) {
        for (i = 0; i < tal[abbr].length; i++) {
          os = -jsdate.getTimezoneOffset() * 60;
          if (tal[abbr][i].offset === os) {
            return abbr.toUpperCase();
          }
        }
      }
      */
      return 'UTC';
    },
    Z: function() { // Timezone offset in seconds (-43200...50400)
      return -jsdate.getTimezoneOffset() * 60;
    },

    // Full Date/Time
    c: function() { // ISO-8601 date.
      return 'Y-m-d\\TH:i:sP'.replace(formatChr, formatChrCb);
    },
    r: function() { // RFC 2822
      return 'D, d M Y H:i:s O'.replace(formatChr, formatChrCb);
    },
    U: function() { // Seconds since UNIX epoch
      return jsdate / 1000 | 0;
    }
  };
  this.date = function(format, timestamp) {
    that = this;
    jsdate = (timestamp === undefined ? new Date() : // Not provided
      (timestamp instanceof Date) ? new Date(timestamp) : // JS Date()
      new Date(timestamp * 1000) // UNIX timestamp (auto-convert to int)
    );
    return format.replace(formatChr, formatChrCb);
  };
  return this.date(format, timestamp);
};

function offsetDate(timestamp) {
	var offseted = new Date(timestamp*1000);
	if (timezone_offset != 'null') {
		offseted = new Date((timestamp + timezone_offset * 60) * 1000);
	}
	return date('Y-m-d H:i:s', offseted);
};

function posParseFloat(floatstring) {
	// to take care of different culture with the formatted currency string
	// convert to general thousand point (,) and decimal point (.)
	var fString = ''+floatstring;
	if (text_thousand_point != ',' || text_decimal_point != '.') {
		fString = fString.replace(text_thousand_point, '#tp#');
		fString = fString.replace(text_decimal_point, '.');
		fString = fString.replace('#tp#', ',');
	}
	
	return parseFloat(fString.replace(/[^0-9-.]/g, ''));
};

function occurrences(string, subString, allowOverlapping){

    string+=""; subString+="";
    if(subString.length<=0) return string.length+1;

    var n=0, pos=0;
    var step=(allowOverlapping)?(1):(subString.length);

    while(true){
        pos=string.indexOf(subString,pos);
        if(pos>=0){ n++; pos+=step; } else break;
    }
    return(n);
};

function openWaitDialog(msg) {
	if (msg) {
		$('#pos_wait_msg span').text(msg);
	} else {
		$('#pos_wait_msg span').text(text_wait);
	}
	$('#pos_wait_msg').show();
};

function closeWaitDialog() {
	$('#pos_wait_msg').hide();
};

function openAlert(msg) {
	$('#alert_dialog p').text(msg);
	$('#alert_dialog #alert_cancel').hide();
	$('#alert_dialog #alert_ok').unbind('click');
	$('#alert_dialog #alert_ok').click(function() {$("#alert_dialog").hide();});
	$('#alert_dialog #alert_ok').show();
	$('#alert_dialog').show();
};

function openConfirm(msg, fn) {
	$('#alert_dialog p').text(msg);
	$('#alert_dialog #alert_cancel').show();
	$('#alert_dialog #alert_ok').unbind('click');
	$('#alert_dialog #alert_ok').click(fn);
	$('#alert_dialog #alert_ok').show();
	$('#alert_dialog').show();
};
