$(document).ready(load);
// TODO change logic, when user it's on home; not reaload page, found the way to remove of DOM .bx-wrapper(.bxslider) and #content-find-product-home
// NOTE public access to functions on js/validations/validation
function load() {
	$('[data-toggle="tooltip"]').tooltip();
	validateLocalStorage(); // validate if sopport localStorage
	getSheetActive('home'); // set first sheet active
	showInformation('home'); // show information by ajax
	findElementByEnter();
	initModalConfirm();
	getFocusLogin();
	// validate if show form for show products
	if($('#frmProducts').length) {
		getProducts();
	}
	// prevent submit when user find product by enter on public
	$('#frmFindPublicProduct').submit(function(e) {
			return false;
	});

} // END function load

function addModalEffect(){
	//console.log(event);
	$("#modalPicture").modal("show");
}

function addModalEffectOf(){
	const hideButton = document.getElementById('boton-salir');
	//console.log(event);
	$("#modalPicture").modal("hide");
}

function addShoppingCar(productID, locations, unitario, caja, master) {
	if(typeof productID === 'undefined' ||
			typeof locations === 'undefined') return false;

	var quantity, title, price, sub, quantitySingle, quantityBox, quantityMaster;
	var bLocalStorage = false;
	title = $('#titulo' + productID).text();
	/*price = $('#sub' + productID).text();*/
	quantitySingle = $('#single' + productID).val();
	quantityBox = $('#box' + productID).val();
	quantityMaster = $('#master' + productID).val();

	if (quantitySingle > 0) {
		quantity = quantitySingle;
		var type = "Por Pieza";
		var piece = unitario;
		sub = currency($('#sub' + productID).text());
		price = currency(piece *sub);
	} else if (quantityBox > 0) {
		quantity = quantityBox;
		var type = "Caja";
		var piece = caja;
		sub = $('#sub' + productID).text();
		price = currency(piece *sub);
	} else if (quantityMaster > 0) {
		quantity = quantityMaster;
		var type = "Master";
		var piece = master;
		sub = $('#sub' + productID).text();
		price = currency(piece *sub);
	} else {
		showMessage('warning', 'Debe ingresar una cantidad del producto a cotizar');
		$('#addCar' + productID).focus();
	}

	// validate on which sheet the user it's viewing, and check structure of html to get data by id
	if(locations === 'listProducts') {
		/*quantity = $('#quantity' + productID).val();*/
		title = removeSpaceLabelProduct(title, 'Titulo:');
		price = removeSpaceLabelProduct(sub, 'Precio:$');
	} else if(locations === 'listShoppingCar') {
		quantity = $('#quanshop' + productID).val();
		price = removeSpaceLabelProduct(price, '$');
	} else {
		return false;
	}

	if(quantity.length) {
		bLocalStorage = checkLocalStorage();
		// create array to save on localStorage
		if(bLocalStorage === false) {
			createLocalStorage(productID, title, quantity, price, type, piece);
		} else {
			// update data on localStorage
			updateLocalStorage(productID, title, quantity, price, type, piece);
		}
	} else {
		showMessage('warning', 'Debe ingresar una cantidad del producto a cotizar');
		$('#quantity' + productID).focus();
	}
}

function calculateTotal(params) {

	var quantityInput = $('#' + params.quantityType +  params.productID).val();
	if(quantityInput > 0){
		var sacarsubtotal = (parseInt(quantityInput) * parseInt(params.quantity)) * parseFloat(params.price);
		var subtotal = currency(sacarsubtotal);
		$('#subtotal' + params.productID).html("<b class='lead'><strong>$ " + subtotal + "*</strong></b>");

		var sacariva = sacarsubtotal * .16;
		var iva = currency(sacariva);
		$('#impuesto' + params.productID).html("<b class='lead'><strong>$ " + iva + "</strong></b>");

		var sacartotal = sacariva + sacarsubtotal;
		var total = currency(sacartotal);
		$('#total' + params.productID).html("<b style='font-size:2em'> $ " + total + "MXN*</b>");

	} else if (quantityInput === 0){
		var sacarsubtotal = (parseInt(quantityInput) * parseInt(params.quantity)) * 1;
		var subtotal = currency(sacarsubtotal);
		$('#subtotal' + params.productID).html("<b class='lead'><strong>$ " + subtotal + "*</strong></b>");

		var sacariva = sacarsubtotal * .16;
		var iva = currency(sacariva);
		$('#impuesto' + params.productID).html("<b class='lead'><strong>$ " + iva + "</strong></b>");

		var sacartotal = sacariva + sacarsubtotal;
		var total = currency(sacartotal);
		$('#total' + params.productID).html("<b style='font-size:2em'> $ " + total + "MXN*</b>");

	}
	document.getElementById('addCar' + params.productID).focus();
}

function checkLocalStorage() {
	var existLocalStorage = false;
	var shoppingCar = localStorage.getItem('shoppingcar');
	if(shoppingCar != null) {
		existLocalStorage = true;
	}
	return existLocalStorage;
}

function clickProductCategoryBy(paramsSend) {
	$('#closemodal').click(function(){
		$('body').removeClass('modal-open');
		$('body').css({
			padding: 0,
			margin: 0
		});
		$('.modal-backdrop').remove();
	});
	if(typeof paramsSend === 'undefined') return false;
	event.preventDefault();
	var params = {
		clickProductCategoryBy: paramsSend.categoriaID,
		location: 'clickProductCategory',
		url: 'php/product/product.php',
		booleanResponse: false,
		divResultID: 'information',
		msgSuccess: 'Ok!',
		msgError: 'Error al intentar buscar producto'
	};

	$.ajax({
		beforeSend: function(){
			$('#espere').show();
		},
		success: function() {
			$('#espere').hide();
			generalFunctionToRequest(params);
		},
		error: function(err) {
			showMessage('error', 'Ha ocurrido un error, intentar nuevamente ó contactar al administrador');
		}
	});
}

function clickProductMarcaBy(paramSend) {
	$('#closemodal5').click(function(){
		$('body').removeClass('modal-open');
		$('body').css({
			padding: 0,
			margin: 0
		});
		$('.modal-backdrop').remove();
	});

	if(typeof paramSend === 'undefined') return false;
	event.preventDefault();
	var params = {
		clickProductMarcaBy: paramSend.marcaID,
		location: 'clickProductMarca',
		url: 'php/product/product.php',
		booleanResponse: false,
		divResultID: 'information',
		msgSuccess: 'Ok!',
		msgError: 'Error al intentar buscar producto'
	};
	$.ajax({
		beforeSend: function(){
			$('#espere').show();
		},
		success: function() {
			$('#espere').hide();
			generalFunctionToRequest(params);
		},
		error: function(err) {
			showMessage('error', 'Ha ocurrido un error, intentar nuevamente ó contactar al administrador');
		}
	});
}

function clickProductInterestBy(enviarParametros) {
	$('#closemodal1').click(function(){
		$('body').removeClass('modal-open');
		$('body').css({
			padding: 0,
			margin: 0
		});
		$('.modal-backdrop').remove();
	});
	$('#closemodal2').click(function(){
		$('body').removeClass('modal-open');
		$('body').css({
			padding: 0,
			margin: 0
		});
		$('.modal-backdrop').remove();
	});
	$('#closemodal2').click(function(){
		$('body').removeClass('modal-open');
		$('body').css({
			padding: 0,
			margin: 0
		});
		$('.modal-backdrop').remove();
	});
	$('#closemodal3').click(function(){
		$('body').removeClass('modal-open');
		$('body').css({
			padding: 0,
			margin: 0
		});
		$('.modal-backdrop').remove();
	});
	$('#closemodal4').click(function(){
		$('body').removeClass('modal-open');
		$('body').css({
			padding: 0,
			margin: 0
		});
		$('.modal-backdrop').remove();
	});
	if(typeof enviarParametros === 'undefined') return false;
	event.preventDefault();
	var params = {
		clickProductInterestBy: enviarParametros.productoID,
		location: 'clickProductInterest',
		url: 'php/product/product.php',
		booleanResponse: false,
		divResultID: 'information',
		msgSuccess: 'Ok!',
		msgError: 'Error al intentar buscar producto'
	};
	$.ajax({
		beforeSend: function(){
			$('#espere').show();
		},
		success: function() {
			$('#espere').hide();
			generalFunctionToRequest(params);
		},
		error: function(err) {
			showMessage('error', 'Ha ocurrido un error, intentar nuevamente ó contactar al administrador');
		}
	});
}

function createLocalStorage(productID, title, quantity, price, type, piece) {
	if(typeof productID === 'undefined' ||
			typeof title === 'undefined' ||
			typeof quantity === 'undefined' ||
			typeof price === 'undefined' ||
			typeof type === 'undefined' ||
			typeof piece === 'undefined') return false;
	var arrayShopping = [];
	var item = {
		product: productID,
		title: title,
		quantity: quantity,
		price: price,
		type: type,
		piece: piece
	};
	arrayShopping.push(item);
	var saveArray = JSON.stringify(arrayShopping);
	localStorage.setItem('shoppingcar', saveArray);
	showMessage('success', 'Se ha agregado el producto a la compra');
}

function currency(value, decimals, separators) {
		decimals = decimals >= 0 ? parseInt(decimals, 0) : 2;
		separators = separators || [',', "'", '.'];
		var number = (parseFloat(value) || 0).toFixed(decimals);
		if (number.length <= (4 + decimals))
				return number.replace('.', separators[separators.length - 1]);
		var parts = number.split(/[-.]/);
		value = parts[parts.length > 1 ? parts.length - 2 : 0];
		var result = value.substr(value.length - 3, 3) + (parts.length > 1 ?
				separators[separators.length - 1] + parts[parts.length - 1] : '');
		var start = value.length - 6;
		var idx = 0;
		while (start > -3) {
				result = (start > 0 ? value.substr(start, 3) : value.substr(0, 3 + start))
						+ separators[idx] + result;
				idx = (++idx) % 2;
				start -= 3;
		}
		return (parts.length == 3 ? '-' : '') + result;
}

function deleteLocalStorage() {
	localStorage.removeItem('shoppingcar');
	showMessage('success', 'El carrito de compra se ha borrado');
	showInformation('product'); // refresh information
}

function deleteElementShoppingCar(element) {
	if(typeof element === 'undefined') return false;

		element = parseInt(element);
		var arrayShopping = localStorage.getItem('shoppingcar');
		var saveArray;
		arrayShopping = JSON.parse(arrayShopping);
		_.remove(arrayShopping, {
			product: element
		});
		saveArray = JSON.stringify(arrayShopping);
		// assign false variable, because on shopping.php validate boolean variable
		if(saveArray.length == 2) {
			saveArray = 'false';
		}
		localStorage.setItem('shoppingcar', saveArray);
		showInformation('shopping'); // refresh information
}

function enableDisableRadiobuttons(params) {
	if(typeof params === 'undefined') return false;

	if(params.quantityType === 'single') {
		// disable all elements. without main element
		$('#single' +params.productID).prop('disabled', false);
		$('#box' +params.productID).prop('disabled', true);
		$('#master' +params.productID).prop('disabled', true);
		// Focus
		document.getElementById('single' + params.productID).focus();
		// clear inputs
		$('#box' +params.productID).val('');
		$('#master' +params.productID).val('');
		// Colocar la cantidad minima
		//$('#single' +params.productID).val(parseInt(params.quantity));
		$('#single' +params.productID).val("1");
		// Colocar las cantidades en 1 pza para sacar las matematicas de reinicio
		var sacarsubtotal = (1 * parseInt(params.quantity)) * parseFloat(params.price);
		var subtotal = currency(sacarsubtotal);
		$('#subtotal' + params.productID).html("<b class='lead'><strong>$ " + subtotal + "*</strong></b>");

		var sacariva = sacarsubtotal * .16;
		var iva = currency(sacariva);
		$('#impuesto' + params.productID).html("<b class='lead'><strong>$ " + iva + "</strong></b>");

		var sacartotal = sacariva + sacarsubtotal;
		var total = currency(sacartotal);
		$('#total' + params.productID).html("<b style='font-size:2em'> $ " + total + "MXN*</b>");

	} else if (params.quantityType === 'box') {
		$('#single' +params.productID).prop('disabled', true);
		$('#box' +params.productID).prop('disabled', false);
		$('#master' +params.productID).prop('disabled', true);
		// Focus
		document.getElementById('box' + params.productID).focus();
		// clear inputs
		$('#single' +params.productID).val('');
		$('#master' +params.productID).val('');
		// Colocar la cantidad minima
		//$('#box' +params.productID).val(parseInt(params.quantity));
		$('#box' +params.productID).val("1");
		// Colocar las cantidades en 1 pza para sacar las matematicas de reinicio
		var sacarsubtotal = (1 * parseInt(params.quantity)) * parseFloat(params.price);
		var subtotal = currency(sacarsubtotal);
		$('#subtotal' + params.productID).html("<b class='lead'><strong>$ " + subtotal + "*</strong></b>");

		var sacariva = sacarsubtotal * .16;
		var iva = currency(sacariva);
		$('#impuesto' + params.productID).html("<b class='lead'><strong>$ " + iva + "</strong></b>");

		var sacartotal = sacariva + sacarsubtotal;
		var total = currency(sacartotal);
		$('#total' + params.productID).html("<b style='font-size:2em'> $ " + total + "MXN*</b>");
	} else if (params.quantityType === 'master') {
		$('#single' +params.productID).prop('disabled', true);
		$('#box' +params.productID).prop('disabled', true);
		$('#master' +params.productID).prop('disabled', false);
		// Focus
		document.getElementById('master' + params.productID).focus();
		// clear inputs
		$('#single' +params.productID).val('');
		$('#box' +params.productID).val('');
		// Colocar la cantidad minima
		//$('#master' +params.productID).val(parseInt(params.quantity));
		$('#master' +params.productID).val("1");
		// Colocar las cantidades en 1 pza para sacar las matematicas de reinicio
		var sacarsubtotal = (1 * parseInt(params.quantity)) * parseFloat(params.price);
		var subtotal = currency(sacarsubtotal);
		$('#subtotal' + params.productID).html("<b class='lead'><strong>$ " + subtotal + "*</strong></b>");

		var sacariva = sacarsubtotal * .16;
		var iva = currency(sacariva);
		$('#impuesto' + params.productID).html("<b class='lead'><strong>$ " + iva + "</strong></b>");

		var sacartotal = sacariva + sacarsubtotal;
		var total = currency(sacartotal);
		$('#total' + params.productID).html("<b style='font-size:2em'> $ " + total + "MXN*</b>");
	}
}

// find quantity and update if exists
function findElement(array, item) {
	if(typeof array === 'undefined' ||
			typeof item === 'undefined') return false;

	var bExistElement = false;
	_.map(array, function(element) {
		if(element.product == item.product) {
			element.quantity = parseInt(element.quantity) + parseInt(item.quantity);
			bExistElement = true;
			showMessage('info', 'Se ha actualizado la cantidad del producto');
		}
	});
	if(!bExistElement) {
		array.push(item);
		showMessage('success', 'Se ha agregado el producto a la compra');
	}
	return array;
}

// do something when user press enter key
function findElementByEnter() {
	var enviarParams = {};
	$(document).keypress(function (e) {
			if (e.which == 13) {
					var element = $('input:focus').attr('id');
					// console.log('enter key is pressed', element);
					if(element != undefined) {
						element = element.trim();
						// NOTE first validate id for add product to shopping cart, because i remove the ID (i mean: quantity5, quanshop5).
						// And both options have the same length then validate others inputs; ELSE
						var validateInput = element.substring(0,8);
						if(validateInput == 'quantity') {
								element = element.replace('quantity', '');
								element = parseInt(element);
								addShoppingCar(element, 'listProducts');
						}  else if(validateInput == 'quanshop') {
								element = element.replace('quanshop', '');
								element = parseInt(element);
								addShoppingCar(element, 'listShoppingCar');
								showInformation('shopping');
						} else {
							// NOTE find by other kind of input, order, user, etc
							if(element == 'findPublicProductBy') {
								var params = {
									findPublicProductBy: $('#findPublicProductBy').val(),
									location: 'findPublicProduct',
									url: 'php/product/product.php',
									booleanResponse: false,
									divResultID: 'information',
									msgSuccess: 'Ok!',
									msgError: 'Error al intentar buscar producto'
								};
								//console.log(params);
								enviarParams = generalFunctionToRequest(params);

								/*$.ajax({
									beforeSend: function(){
										$('#espere').show();
									},
									success: function() {
										$('#espere').hide();
										generalFunctionToRequest(params);
									},
									error: function(err) {
										showMessage('error', 'Ha ocurrido un error, intentar nuevamente ó contactar al administrador');
									}
								});*/
							} else if(element == 'txtPublicOrderCode') {
								var params = {
									jQueryID: 'txtPublicOrderCode',
									location: 'getpublicorders',
									url: 'php/order/order.php',
									booleanResponse: false,
									divResultID: 'resultPublicOrderCode'
								};
								enviarParams = generalFunctionToRequest(params);

								/*$.ajax({
									beforeSend: function(){
										$('#espere').show();
									},
									success: function() {
										$('#espere').hide();
										generalFunctionToRequest(params);
									},
									error: function(err) {
										showMessage('error', 'Ha ocurrido un error, intentar nuevamente ó contactar al administrador');
									}
								});*/
							}
						}
					}
			}
	});

	$.ajax({
		beforeSend: function(){
			$('#espere').show();
		},
		success: function() {
			$('#espere').hide();
			generalFunctionToRequest(enviarParams);
		},
		error: function(err) {
			showMessage('error', 'Ha ocurrido un error, intentar nuevamente ó contactar al administrador');
		}
	});
}

function generalFunctionToRequest(params) {
	//console.log(params);
	if(typeof params === 'undefined') return false;

	event.preventDefault();

	var dataSend = {};
	switch(params.location) {
		case 'confirm-status-order':
			dataSend.data = 'empty';
			dataSend.location = params.location;
			dataSend.orderID = params.orderID;
			dataSend.status = params.status;
			break;
		case 'clickProductCategory':
			dataSend.data = 'empty';
			dataSend.location = params.location;
			// validate if object don't have property (request from button), then get value by id. If exist, value from Enter input findPublicProductBy
			if (!params.hasOwnProperty('clickProductCategoryBy')) {
				params['clickProductCategoryBy'] = $('#clickProductCategoryBy').val();
			}
			dataSend.clickProductCategoryBy = params.clickProductCategoryBy;
			break;
		case 'clickProductCategorySearch':
			dataSend.data = 'empty';
			dataSend.location = params.location;
			// validate if object don't have property (request from button), then get value by id. If exist, value from Enter input findPublicProductBy
			if (!params.hasOwnProperty('clickProductCategorySearchBy')) {
				params['clickProductCategorySearchBy'] = $('#clickProductCategorySearchBy').val();
			}
			dataSend.clickProductCategorySearchBy = params.clickProductCategorySearchBy;
			break;
		case 'clickProductMarca':
			dataSend.data = 'empty';
			dataSend.location = params.location;
			// validate if object don't have property (request from button), then get value by id. If exist, value from Enter input findPublicProductBy
			if (!params.hasOwnProperty('clickProductMarcaBy')) {
				params['clickProductMarcaBy'] = $('#clickProductMarcaBy').val();
			}
			dataSend.clickProductMarcaBy = params.clickProductMarcaBy;
			break;
		case 'clickProductInterest':
			dataSend.data = 'empty';
			dataSend.location = params.location;
			// validate if object don't have property (request from button), then get value by id. If exist, value from Enter input findPublicProductBy
			if (!params.hasOwnProperty('clickProductInterestBy')) {
				params['clickProductInterestBy'] = $('#clickProductInterestBy').val();
			}
			dataSend.clickProductInterestBy = params.clickProductInterestBy;
			break;
		case 'findPublicProduct':
			dataSend.data = 'empty';
			dataSend.location = params.location;
			// validate if object don't have property (request from button), then get value by id. If exist, value from Enter input findPublicProductBy
			if (!params.hasOwnProperty('findPublicProductBy')) {
				params['findPublicProductBy'] = $('#findPublicProductBy').val();
			}
			dataSend.findPublicProductBy = params.findPublicProductBy;
			break;
		case 'getpublicorders':
			var orderCode = $('#' + params.jQueryID).val();
			dataSend.data = 'empty';
			dataSend.location = params.location;
			dataSend.orderCode = orderCode;
			dataSend.section = 'orders-record-public';
			//console.log(orderCode);
			break;
		case 'showdetailorder':
			dataSend.data = 'empty';
			dataSend.location = params.location;
			dataSend.userLocation = 'mayorista'; // to identity kind of user
			dataSend.pedidoID = params.pedidoID;
			break;
		case 'showModalFindPublicProduct':
		case 'showModalPublicProduct':
			dataSend.data = 'empty';
			dataSend.location = params.location;
			dataSend.section = params.section;
			dataSend.productID = params.productID;
			dataSend.alta = params.alta;
			break;
		case 'show-modal-payment-method':
			dataSend.data = 'empty';
			dataSend.location = params.location;
			dataSend.section = params.section;
			dataSend.orderID = params.orderID;
			break;
		default:
			return false;
	}

	$.ajax({
		type: 'post',
		data: dataSend,
		url: params.url,
		beforeSend: function(){
			$('#espere').show();
		},
		success: function(data) {
			$('#espere').hide();
			// TODO in file php, return false if exist an error and catch here with
			// function that validate true or false, and show message
			if(params.booleanResponse == false) {
				switch(params.location) {
					case 'findPublicProduct':
						if(data === 'error-query') {
							showMessage('error', 'Error al intentar conseguir información de producto');
						} else {
							$('.backstretch').remove();
							$('#' + params.divResultID).html(data);
						}
						break;
					case 'showModalFindPublicProduct':
					case 'showModalPublicProduct':
						$('#' + params.divResultID).html(data);
						// NOTE invoke modal, the same id for all modal; if user wnat to change information;
						// add to json section and change general name for modal
						$('#modal-product-description').modal('show'); // toggle, show and hide
						break;
					case 'show-modal-payment-method':
						$('#' + params.divResultID).html(data);
						$('#modal-payment-method').modal('show'); // toggle, show and hide
						break;
					default:
						$('#' + params.divResultID).html(data);
				}
			} else if(params.booleanResponse == true) {
				switch (params.location) {
					case 'confirm-status-order':
						if(data === 'error-query') {
							showMessage('error', 'Error al intentar actualizar status de la pedido');
						} else {
							var type = '', msg = '';
							if (params.status == 'confirmado') {
								type = 'success';
								msg = 'Se ha confirmado correctamente su pedido';
							} else if(params.status == 'cancelado') {
								type = 'warning';
								msg = 'Se ha cancelado correctamente su pedido';
							}
							showMessage(type, msg);
							showInformation('publicorder');
						}
						break;
				}
			}
		},
		error: function(err) {
			showMessage('error', 'Ha ocurrido un error, intentar nuevamente ó contactar al administrador');
		}
	});
}

function getFocusLogin() {
	$('#login-modal').on('shown.bs.modal', function () {
		$('#user').focus();
	});
}

function getFocusConfirmOrder() {
	$('#confirm-order').on('shown.bs.modal', function () {
		$('#emailOrder').focus();
	});
}

// set class active, depending on wich page the user is
function getSheetActive(path) {
	if(typeof path === 'undefined') return false;

	// get param from url
	// var path = window.location.search;
	switch(path) {
		case'products':
			$(".navbar-nav a:contains('Productos')").parent().addClass('active');
			break;
		case'shopping':
			$(".navbar-nav a:contains('Compra')").parent().addClass('active');
			break;
		case'login':
			$(".navbar-nav a:contains('Login')").parent().addClass('active');
			break;
	}
}

function getProducts() {
	var frm = $('frmProducts');
	if(frm.hasOwnProperty('serialize')) {
		var dataForm = frm.serialize();
		$.ajax({
			type: 'post',
			data: {data: 'getproducts'},
			url: 'php/products/products.php',
			beforeSend: function(){
				$('#espere').show();
			},
			success: function(data) {
				$('#espere').hide();
				if(data === 'error-query') {
					showMessage('error', 'Error al intentar conseguir información de producto');
				} else {
					frm.html(data);
				}
			},
			error: function(err) {
				showMessage('error', 'Ha ocurrido un error, intentar nuevamente ó contactar al administrador');
			}
		});
	} else {
		return false;
	}
}

function initModalConfirm() {
	var url = '//cdnjs.cloudflare.com/ajax/libs/bootstrap-confirmation/1.0.5/bootstrap-confirmation.min.js';
	$.getScript( url, function() {
		// do something
	});
}

// NOTE function general for pagination:
// @param divResultID : Place where display information
// @param url: File to be process request
// @param location: Option to access work flow and send to class.file.php
// @param section: In one file, there are many request; it's to identify where is that request
// @param page and limit: currentPage and limit, becase increment or decrement depend where is the user.
function pagination(params) {
	if(typeof params === 'undefined' ||
			typeof params.location === 'undefined') return false;

	var dataSend = {
		data: 'empty',
		location: params.location,
		currentPage: params.page,
		limitPages: params.limit
	};

	// load data in div #information
	$.ajax({
		type: 'post',
		data: dataSend,
		url: params.url,
		beforeSend: function(){
			$('#espere').show();
		},
		success: function(data) {
			$('#espere').hide();
			$('#' + params.divResultID).html(data);
		},
		error: function(err) {
			//console.log('error', 'Ha ocurrido un error, intentar nuevamente ó contactar al administrador');
		}
	});
}

function processOrder(order) {
	/*if(typeof evt === 'undefined' || typeof evt != 'function') return false;*/
	/*evt.preventDefault();*/
	// Send data to order and process, save db
	var email = $('#emailOrder').val();
	var nombre = $('#nombre').val();
	var direccion = $('#direccion').val();
	var postal = $('#postal').val();
	var rfc = $('#rfc').val();
	var celular = $('#celular').val();

	// validate that user put email
	if(email.length > 0) {
		var validate = validateEmail(email);
		// check if email it's correct
		if(validate) {
			$('#confirm-order').modal('hide');
			var dataSend = {
				data: order,
				email: email,
				nombre: nombre,
				direccion: direccion,
				postal: postal,
				rfc: rfc,
				celular: celular,
				location: 'saveorder'
			}
			//console.log(dataSend);
			$.ajax({
				type: 'post',
				data: dataSend,
				url: 'php/order/order.php',
				beforeSend: function(){
					$('#espere').show();
				},
				success: function(data) {
					$('#espere').hide();
					// NOTE remove this class for bug, when user press scape or something like that, the screen has stop(opacity) and can move
					removeClassModal();
					deleteLocalStorage();
					showMessage('success', 'Se ha realizado el pedido correctamente');
					/*showInformation('product');*/ // redirect to show status order by code
				},
				error: function(err) {
					showMessage('error', 'Ha ocurrido un error, intentar nuevamente ó contactar al administrador');
				}
			});
		} else {
			showMessage('error', 'Ingresar correo electrónico válido');
		}
	} else {
		showMessage('error', 'Correo electrónico es requerido para enviar folio de pedido');
		$('#emailOrder').focus();
	}
}

function removeClassModal() {
	$('body').removeClass('modal-open');
	$('div').remove('.modal-backdrop');
}

function removeSpaceLabelProduct(string, removeString) {
	if(typeof string === 'undefined' ||
			typeof removeString === 'undefined') return false;

	string = string.replace(/\s+/, "");
	string = string.trim();
	string = string.replace(removeString, '');
	return string;
}

function saveAllQuantityShoppingCartPublic(evt) {
	if(typeof evt === 'undefined' || typeof evt != 'object') return false;

	evt.preventDefault();
	var value = null;
	var element = null;
	var saveArray = null;
	var arrayShopping = localStorage.getItem('shoppingcar');
	arrayShopping = JSON.parse(arrayShopping);

	// NOTE i can search by attribute, like this: data-example and access to property input
	var arrayIDS = $('#containerShoppingCar input[id]')         // find spans with ID attribute
	.map(function() { return this.id; }) // convert to set of IDs
	.get(); // convert to instance of Array (optional)

	_.map(arrayIDS, function(id, key) {
		value = $('#' + id).val();
		if(value != '') {
			id = id.replace('quanshop', '');
			id = parseInt(id);
			element = _.find(arrayShopping, {product: id});
			element.quantity = parseInt(element.quantity) + parseInt(value);
			element.quantity = element.quantity + '';
		}
	});

	saveArray = JSON.stringify(arrayShopping);
	localStorage.setItem('shoppingcar', saveArray);
	showInformation('shopping');
}

// structure data send to file php to be processed for each location
function showInformation(location) {
	var url = null;
	var dataSend = {};
	//console.log(location);
	// encodeURIComponent
	switch(location) {
		case 'home':
			url = 'php/home.php';
			break;
		case 'product':
			url = 'php/product/product.php';
			dataSend.data = 'empty';
			dataSend.location = 'getproducts';
			break;
		case 'publicorder':
			url = 'php/order/order.php';
			dataSend.data = 'empty';
			dataSend.location = 'publicordercode';
			break;
		case 'order':
			url = 'php/order/order.php';
			dataSend.data = 'empty';
			dataSend.location = 'getorders';
			break;
		case 'shopping':
			url = 'php/shopping/shopping.php';
			var shoppingCar = localStorage.getItem('shoppingcar');
			if(shoppingCar != null) {
				dataSend.data = shoppingCar;
				dataSend.location = 'getshoppingpublic';
			} else {
				dataSend.data = false;
				dataSend.location = 'getshoppingpublic';
			}
			break;
		default:
			url = 'php/home.php'; // always send to home if location doesn't exist

	}
	// load data in div #information
	$.ajax({
		type: 'post',
		data: dataSend,
		url: url,
		beforeSend: function(){
			$('#espere').show();
		},
		success: function(data) {
			$('#espere').hide();
			// NOTE at this point remove from the DOM elements, i like this choice
			// otherwise i need to call for each options location (remove div, removeClass, etc)
			if(location != 'home') {
				$('.backstretch').remove();
			} if(location != 'publicorder') {
				$('#information').removeClass('background-img-pedidos');
			} if(location != 'shopping') {
				$('#information').removeClass('background-img-carrito');
			}

			if(location == 'home') {
				// I need to call trigger again because if i inject code (same in inspector chrome)
				// it doesn´t work, the best way it's call like the firts time.
				
				$('#information').empty();
			} else if(location == 'shopping') {
				$('#information').html(data);
				var shoppingCar = localStorage.getItem('shoppingcar');
				if(shoppingCar === null) {
					$( '#information').addClass('background-img-carrito');
				}
				// NOTE after refresh div, load div for modal email; to set focus. On load
				// it doesn't work because doesn't exist on DOM.
				getFocusConfirmOrder();
			} else if(location == 'publicorder') {
				$('#information').html(data);
				$('#txtPublicOrderCode').focus();
				// set background-image
				$('#information').addClass('background-img-pedidos');
			} else if(location == 'product') {
				if(data === 'error-query') {
					showMessage('error', 'Error al intentar conseguir información de producto');
				} else {
					$('#information').html(data);
				}
			} else {
				$('#information').html(data);
			}
		},
		error: function(err) {
			showMessage('error', 'Ha ocurrido un error, intentar nuevamente ó contactar al administrador');
		}
	});
}

function updateLocalStorage(productID, title, quantity, price, type, piece) {
	if(typeof productID === 'undefined' ||
			typeof title === 'undefined' ||
			typeof quantity === 'undefined' ||
			typeof price === 'undefined' ||
			typeof type === 'undefined' ||
			typeof piece === 'undefined') return false;
	var arrayShopping = localStorage.getItem('shoppingcar');
	var saveArray = null;
	var item = {
		product: productID,
		title: title,
		quantity: quantity,
		price: price,
		type: type,
		piece: piece
	};
	arrayShopping = JSON.parse(arrayShopping);
	if(arrayShopping == false) {
		arrayShopping = '[]';
		arrayShopping = JSON.parse(arrayShopping);
	}
	// before update localStorage, find element; if exist update quantity; if doesn't exist push on array
	arrayShopping = findElement(arrayShopping, item);
	saveArray = JSON.stringify(arrayShopping);
	localStorage.setItem('shoppingcar', saveArray);
	//console.log(arrayShopping);
}

function validateEmail(email) {
	if(typeof email === 'undefined') return false;

	var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}

// localStorage.setItem('nombre', 'pepe');
// var nombre = localStorage.getItem('nombre');
// localStorage.removeItem('nombre');

function validateLocalStorage() {
	if (!window.localStorage) {
		alert('Tu Browser no soporta localStorage!');
	}
}
