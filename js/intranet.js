$(document).ready(load);

// NOTE public access to functions on js/validations/validation
function load() {
  $('[data-toggle="tooltip"]').tooltip();
  findProductByEnter();
  showInformation('home'); // the home page depend of kind user, it's validate on php
}

function calculatePiezas(params) {
  //console.log(params);
  var quantitySelect = document.getElementById("quantity"+ params.productoID).value;
  var price = document.getElementById("price" + params.productoID).textContent;
  //console.log('Valor de quantitySelect es de: ' + quantitySelect, "El precio es: " + price);
  if(quantitySelect > 0){
    var subtotal = parseFloat(price) * parseInt(quantitySelect);
    //console.log("El precio es: " + price,"El precio final es: " + subtotal)
    $('#actualizarQuantity' + params.productoID).html("<input type='number' class='form-control text-center' style='vertical-align:middle; font-weight:bold;' id='quantity" + params.productoID + "' min=1 value='" + quantitySelect + "'/>");
    saveQuantityShoppinCarPartner(params.productoID);
    $('#actualizarPrice' + params.productoID).html("<b style='display:none' id='price" + params.productoID + "'>" + price + "</b><b>" + new Intl.NumberFormat("en-IN", {style: "currency", currency: "MXN"}).format(subtotal) + "<b></td>");
  } else if (quantitySelect === 0){
    $('#actualizarQuantity' + params.productoID).html("<input type='number' class='form-control text-center' style='vertical-align:middle; font-weight:bold;' id='quantity" + params.productoID + "' min=1 value='" + quantitySelect + "'/>");
  }
}

function deleteProductShoppinCarPartner(productID) {
  if(typeof productID === 'undefined') return false;

  var url = '../php/shopping/shopping.php';
  var dataSend = {
    data: 'empty',
    location: 'deleteproductshoppingcarpartner',
    productID: parseInt(productID)
  };

  var carritoNav = parseInt(document.getElementById('carritoNav').innerHTML);

  var aumentarProducto = carritoNav - 1;

  //console.log(aumentarProducto);

  $('#carritoNav').html(aumentarProducto);

  alert('Se elimino correctamente el producto a su lista de pedidos.');

  $.ajax({
    type: 'post',
    data: dataSend,
    url: url,
    beforeSend: function(){
      $('#procesando').show();
    },
    complete: function(){
      $('#procesando').hide();
    },
    success: function(data) {
      showInformation('shopping');
      showMessage('success', 'Se ha Eliminado el producto seleccionado del carrito');
    },
    error: function(err) {
      showMessage('error', 'Ha ocurrido un error, intentar nuevamente ó contactar al administrador');
    }
  });
}

// delete all elements
function deleteShoppinCarPartner() {
  var url = '../php/shopping/shopping.php';
  var dataSend = {
    data: 'empty',
    location: 'deleteshoppingcarpartner'
  };

  var aumentarProducto = 0;

  //console.log(aumentarProducto);

  $('#carritoNav').html(aumentarProducto);

  alert('Se elimino toda la lista correctamente.');

  $.ajax({
    type: 'post',
    data: dataSend,
    url: url,
    beforeSend: function(){
      $('#procesando').show();
    },
    complete: function(){
      $('#procesando').hide();
    },
    success: function(data) {
      showInformation('shopping');
    },
    error: function(err) {
      showMessage('error', 'Ha ocurrido un error, intentar nuevamente ó contactar al administrador');
    }
  });
}

// NOTE first, catch enter and get id to identify request, then call function findElementByGeneral and send ID
function findProductByEnter() {
  $(document).keypress(function (e) {
      if (e.which == 13) {
          var element = $('input:focus').attr('id');
          if(element != undefined) {
            element = element.trim();
            if(element == 'findProductBy') {
              // call function that find product by code
              findElementByGeneral('findProductBy');
            }
            else if(element == 'findCrudProductBy') {
              findElementByGeneral('findCrudProductBy');
            }
            else if(element == 'findCrudUserBy') {
              findElementByGeneral('findCrudUserBy');
            }
          }
      }
  });
}

// add param location and call generalFunctionToRequest to process request
function findElementByGeneral(inputID) {
  console.log("Aqui entramos")
  if(typeof inputID === 'undefined') return false;

  var findElementBy = $('#' + inputID);
  if(findElementBy.val().length > 0) {
    // user can search product by: code, key and title (description of product)
    var params = {
      findElementBy: findElementBy.val()
    };
    switch(inputID) {
      case 'findProductBy':
        params.location =  'find-toAdd-product-to-shoppingcart-partner';
        break;
      case 'findCrudProductBy':
        params.location = 'find-productBy-crud';
        break;
      case 'findCrudUserBy':
        params.location = 'find-userBy-crud';
        break;
      default:
        return false;
    }
    generalFunctionToRequest(params);
  } else {
    showMessage('error', 'Escriba un código, clave ó descripción');
    findElementBy.focus();
  }
}

// NOTE if after response need call another funciton. Use this function and call another function, construct and send params.
// @param elementValueID, input abput: product, user, order, etc...
// @param destination: is the place where call
function getDataAfterResponse(elementValueID, location) {
  if(typeof elementValueID === 'undefined' ||
      typeof location === 'undefined') return false;

  var params = {};
  switch(location) {
    case 'getProduct-to-update-crud':
      params = {
        productID: elementValueID,
        location: location,
        url: '../php/product/product.php',
        booleanResponse: false,
        divResultID: 'result-find-productBy-crud',
        msgSuccess: 'Ok!',
        msgError: 'Error al intentar editar producto'
      };
      generalFunctionToRequest(params);
      break;
    case 'getUser-to-update-crud':
      params = {
        userID: elementValueID,
        location: location,
        url: '../php/user/user.php',
        booleanResponse: false,
        divResultID: 'result-find-userBy-crud',
        msgSuccess: 'Ok!',
        msgError: 'Error al intentar editar usuario'
      };
      generalFunctionToRequest(params);
      break;
    default:
      return false;
  }
}

function getFocus(elementID) {
  if(typeof elementID === 'undefined') return false;
  $('#' + elementID).focus();
}

// NOTE receive array with name of propertie ID and i get value and push to array and return
// @params params: array with ID's that i need process (save or update)
function getInputsValue(params) {
  if(typeof params === 'undefined') return false;

  var result = {};
  for (var key in params) {
    if (params.hasOwnProperty(key)) {
      // console.log(key + " -> " + params[key]);
      result[params[key]] = $('#' + params[key]).val();
    }
  }
  return result;
}

function generalFunctionToRequest(params) {
  if(typeof params === 'undefined' ||
      params === null ||
      typeof params.location === 'undefined') return false;

  //console.log("Hola:" + params.location, params);
  var dataSend = {};
  switch(params.location) {
    case 'addProduct-to-shoppingcart-partner':
      // send empty value because that`s the logic to control workflow switch
      dataSend.data = 'empty';
      dataSend.location = params.location;
      dataSend.productID = params.productoID;

      var carritoNav = parseInt(document.getElementById('carritoNav').innerHTML);

      var aumentarProducto = carritoNav + 1;

      //console.log(aumentarProducto);

      $('#carritoNav').html(aumentarProducto);

      alert('Se agrego correctamente el producto a su lista de pedidos.');
      break;
    case 'find-productBy-crud':
      dataSend.data = 'empty';
      dataSend.location = params.location;
      params.url = '../php/product/product.php';
      dataSend.findProductBy = params.findElementBy;
      params.booleanResponse = false;
      params.divResultID = 'result-find-productBy-crud';
      break;
    case 'find-userBy-crud':
      dataSend.data = 'empty';
      dataSend.location = params.location;
      params.url = '../php/user/user.php';
      dataSend.findUserBy = params.findElementBy;
      params.booleanResponse = false;
      params.divResultID = 'result-find-userBy-crud';
      break;
    case 'find-toAdd-product-to-shoppingcart-partner':
      dataSend.data = 'empty';
      dataSend.location = params.location;
      params.url = '../php/shopping/shopping.php';
      dataSend.findProductBy = params.findElementBy;
      params.booleanResponse = false;
      params.divResultID = 'resultFindProductBy';

      // TODO after callback, call function showInformation('shopping');
      break;
    case 'getProduct-to-update-crud':
      dataSend.data = 'empty';
      dataSend.location = params.location;
      dataSend.productID = params.productID;
      break;
    case 'getUser-to-update-crud':
      dataSend.data = 'empty';
      dataSend.location = params.location;
      dataSend.userID = params.userID;
      break;
    case 'newProduct-crud':
    case 'newUser-crud':
      dataSend.data = 'empty';
      dataSend.location = params.location;
      break;
    case 'saveAllQuantity-shoppingCartPartner':
      dataSend.data = 'empty';
      dataSend.location = params.location;
      params.url = '../php/shopping/shopping.php';
      params.booleanResponse = false;
      params.divResultID = 'content-shoppingCar-partner';
      dataSend.arrayContentUpdateQuantities = params.arrayContentUpdateQuantities;
      break;
    case 'saveNewProduct-crud':
      var requiredInputs = ["clave",
                            "codigo",
                            "titulo",
                            "publico",
                            "constructor",
                            "socio"];
      var bValidateInputs = validateInputs(requiredInputs);
      if(bValidateInputs) {
        var getDataForm = getInputsValue(params.dataForm);
        dataSend.data = 'empty';
        dataSend.location = params.location;
        dataSend.dataForm = getDataForm;
      } else {
        params = {};
        showMessage('error', 'No deben haber campos vacíos');
      }
      break;
    case 'saveNewUser-crud':
      var requiredInputs = ["username",
                            "password",
                            "rol",
                            "nombreCompleto",
                            "clave"];
      var bValidateInputs = validateInputs(requiredInputs);
      if(bValidateInputs) {
        var getDataForm = getInputsValue(params.dataForm);
        dataSend.data = 'empty';
        dataSend.location = params.location;
        dataSend.dataForm = getDataForm;
      } else {
        params = {};
        showMessage('error', 'No deben haber campos vacíos');
      }
      break;
    case 'saveorderpartner':
      dataSend.data = 'empty';
      dataSend.location = params.location;
      alert('Pedido procesado correctamente, se le hará llegar a su email un correo con la información de su pedido. Gracias por usar Escritorio Virtual FMO.');
      break;
    case 'setProduct-to-update-crud':
      var requiredInputs = ["clave",
                            "codigo",
                            "titulo",
                            "publico",
                            "constructor",
                            "socio"];
      var bValidateInputs = validateInputs(requiredInputs);
      if(bValidateInputs) {
        var getDataForm = getInputsValue(params.dataForm);
        dataSend.data = 'empty';
        dataSend.location = params.location;
        dataSend.dataForm = getDataForm;
        dataSend.dataDB = params.dataDB;
        dataSend.elementID = params.elementID;
      } else {
        params = {};
        showMessage('error', 'No deben haber campos vacíos');
      }
      break;
    case 'setUser-to-update-crud':
      var requiredInputs = ["username",
                            "password",
                            "rol",
                            "nombreCompleto",
                            "clave"];
      var bValidateInputs = validateInputs(requiredInputs);
      if(bValidateInputs) {
        var getDataForm = getInputsValue(params.dataForm);
        dataSend.data = 'empty';
        dataSend.location = params.location;
        dataSend.dataForm = getDataForm;
        dataSend.dataDB = params.dataDB;
        dataSend.elementID = params.elementID;
      } else {
        params = {};
        showMessage('error', 'No deben haber campos vacíos');
      }
      break;
    case 'shopping-orders-record':
      dataSend.data = 'empty';
      dataSend.location = 'getorders';
      params.url = '../php/order/order.php';
      params.booleanResponse = false;
      params.divResultID = 'content-orders-record';
      dataSend.section = 'shopping-cart-partner';
      break;
    case 'showModalProductRegisteredUser':
      dataSend.data = 'empty';
      dataSend.location = params.location;
      dataSend.section = params.section;
      dataSend.productID = params.productID;
      break;
    case 'updatequantityexisting':
      dataSend.data = 'empty';
      dataSend.location = params.location;
      dataSend.existingQuantity = $('#' + params.jQueryID).val();
      dataSend.orderID = params.orderID;
      dataSend.productID = params.productID;
      dataSend.numPrecio = params.numPrecio;
      //console.log("Orden ID: ", dataSend.orderID, "Producto ID: ", dataSend.productID, "Cantidad: ", dataSend.existingQuantity, "Tipo de Precio: ", dataSend.numPrecio);
      break;
    case 'updateorderstatus':
      var currentStatus = $('#' + params.jQueryID).val();
      dataSend.data = 'empty';
      dataSend.location = params.location;
      dataSend.orderStatus = currentStatus;
      dataSend.orderID = params.orderID;
      //console.log(event);
      break;
    default:
      return false;
  }

  // TODO refactor function to not send data
  $.ajax({
    type: 'post',
    data: dataSend,
    url: params.url,
    beforeSend: function(){
      $('#espere').show();
    },
    complete: function(){
      $('#espere').hide();
    },
    success: function(data) {
      // TODO in file php, return false if there is error and catch here with
      // function that validate true or false, and show message
      if(params.booleanResponse) {
        switch(params.location) {
          case 'addProduct-to-shoppingcart-partner':
            $('#' +  params.divResultID).html(data);
            showMessageResponse(params);
            break;
          case 'saveorderpartner':
            showMessageResponse(params);
            showInformation('shopping');
            //console.log(params);
            break;
          case 'setProduct-to-update-crud':
            if(data === 'success-query') {
              params['responseOfRequest'] = true;
              showMessageResponse(params);
              getDataAfterResponse(params.elementID, 'getProduct-to-update-crud');
            } else if(data === 'error-query') {
              params['responseOfRequest'] = false;
              showMessageResponse(params);
            } else if(data === 'nothing-to-update') {
              showMessage('warning', 'Ningun dato para actualizar');
            }
            break;
          case 'saveNewProduct-crud':
            //console.log('value data: ', data);
            if(data === 'error-query') {
              params['msgError'] = 'Error al guardar datos de producto';
              params['responseOfRequest'] = false;
              showMessageResponse(params);
            } else {
              params['responseOfRequest'] = true;
              showMessageResponse(params);
              showInformation('product');
            }
            break;
          case 'saveNewUser-crud':
            if(data == 'already exist') {
              showMessage('warning', 'Usuario ya existe');
            } else if(data == 'success') {
              showMessage('success', 'Usuario guardado correctamente');
            }
            showInformation('user');
            break;
          case 'setUser-to-update-crud':
            showMessageResponse(params);
            getDataAfterResponse(params.elementID, 'getUser-to-update-crud');
            break;
          case 'updatequantityexisting':
            showMessageResponse(params);
            showDetailOrder(dataSend.orderID, dataSend.numPrecio);
            break;
          case 'updateorderstatus':
            showMessageResponse(params);
            showInformation('order');
            break;
        }
      } else if(params.booleanResponse == false) {
        // update div id = params.divResultID and show data
        switch (params.location) {
          case 'find-productBy-crud':
            if(data === 'error-query') {
              params['msgError'] = 'Error al intentar conseguir información de producto';
              params['responseOfRequest'] = false;
              showMessageResponse(params);
            } else {
              $('#' +  params.divResultID).html(data);
            }
            break;
          case 'getProduct-to-update-crud':
            if(data === 'error-query') {
              params['msgError'] = 'Error al intentar conseguir información de producto';
              params['responseOfRequest'] = false;
              showMessageResponse(params);
            } else {
              $('#' +  params.divResultID).html(data);
              getFocus('titulo');
            }
            break;
          case 'newProduct-crud':
            $('#' +  params.divResultID).html(data);
            getFocus('titulo');
            break;
          case 'getUser-to-update-crud':
          case 'newUser-crud':
            $('#' +  params.divResultID).html(data);
            getFocus('username');
            $('#clave').keyup(validateMaxLength);
            break;
          case 'shopping-orders-record':
            $('#' +  params.divResultID).html(data);
            break;
          case 'showModalProductRegisteredUser':
            $('#' + params.divResultID).html(data);
            // NOTE invoke modal, the same id for all modal; if user wnat to change information;
            // add to json section and change general name for modal
            $('#modal-product-description').modal('show'); // toggle, show and hide
            break;
          default:
            $('#' +  params.divResultID).html(data);
        }
      }
    },
    error: function(err) {
      showMessage('error', 'Ha ocurrido un error, intentar nuevamente ó contactar al administrador');
    }
  });
}

// show preaload information about file before upload
function listenerCsvFile() {
    if(typeof $('#csvFile')[0] === 'undefined' ||
        typeof $('#csvFile')[0].files[0] === 'undefined') return false;

    var file = $('#csvFile')[0].files[0];
    var fileName = file.name;
    var extension = fileName.substring(fileName.lastIndexOf('.') + 1);
    var fileSize = file.size;
    showMessageUploadFile('<span>Archivo para subir: ' + fileName + ', peso total: ' + fileSize + ' bytes.</span>');
}

// NOTE for this code work, need all information inside form, and proccess on file php; get variables by function $_POST AND $_FILE
function loadFile(params) {
  if(typeof params === 'undefined' ||
      params === null ||
      typeof params.location === 'undefined') return false;

  var file = null;
  var formData = null;
  var validation = true;

  if(params.location == 'uploadCsv') {
    formData = new FormData($('#frmUpdateTables')[0]);
    file = $('#csvFile')[0].files[0];
    var selectTable = $('#tableUploadCsv').val();
    if(selectTable ==  'empty') {
      validation = false;
      showMessage('warning', 'Seleccione una tabla para actualizarla');
    }
  } else if(params.location == 'uploadPdf') {
    formData = new FormData($('#frmUploadCatalogo')[0]);
    file = $('#pdfFile')[0].files[0];
  } else if(params.location == 'uploadJpg') {
    formData = new FormData($('#frmUploadSlider')[0]);
    file = $('#jpgFile')[0].files[0];
  } else {
    return false;
  }

  if(file != null && validation == true) {
    var fileName = file.name;
    var extension = fileName.substring(fileName.lastIndexOf('.') + 1);

    if(extension == params.extension) {

       $.ajax({
          // url: "../php/general/general.php?data=empty&location=uploadCsv",
          url: params.url,
          type: 'post',
          // form data
          data: formData,
          // params necessary to upload file by ajax
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function() {
              message = $("<span>Subiendo el archivo, por favor espere...</span>");
              showMessageUploadFile(message)
          },
          success: function(response) {
              //console.log(response);
              if(response == 'upload') {
                message = $("<span>"+params.msgSuccess+"</span>");
                showMessageUploadFile(message);
              } else if (response == 'error') {
                message = $("<span>"+params.msgError+"</span>");
                showMessageUploadFile(message);
              }
              // at this point call function that insert on database
          },
          error: function() {
              message = $("<span>Ha ocurrido un error al subir archivo.</span>");
              showMessageUploadFile(message);
          }
      });
    } else {
      showMessage('warning', 'Extensión no valida, sólo archivos ' + params.extension);
    }
  } else {
    showMessage('error', 'Favor de seleccionar archivo');
  }
}

// NOTE function general for pagination:
// @param divResultID : Place where display information
// @param url: File to be process request
// @param location: Option to access work flow and send to class.file.php
// @param section: In one file, there are many request; it's to identify where is that request
// @param page and limit: currentPage and limit, becase increment or decrement depend where is the user.
function pagination(params) {
  if(typeof params === 'undefined' ||
      params === null ||
      typeof params.location === 'undefined' ||
      typeof params.section === 'undefined' ||
      typeof params.page === 'undefined' ||
      typeof params.limit === 'undefined') return false;

  //console.log(params);
  var dataSend = {
    data: 'empty',
    location: params.location,
    section: params.section,
    currentPage: params.page,
    limitPages: params.limit
  };

  // load data in div #information
  $.ajax({
    type: 'post',
    data: dataSend,
    url: params.url,
    success: function(data) {
      $('#' + params.divResultID).html(data);
    },
    error: function(err) {
      console.log('error', 'Ha ocurrido un error, intentar nuevamente ó contactar al administrador');
    }
  });
}

function saveAllQuantityShoppinCarPartner() {
  /*if(typeof evt === 'undefined' || typeof evt != 'function') return false;*/

  /*evt.preventDefault();*/
  var params = {};
  var arrayContentUpdateQuantities = [];
  var element = {};
  var value = '';
  // NOTE i can search by attribute, like this: data-example and access to property input
  var arrayIDS = $('#content-shoppingCar-partner input[id]')         // find spans with ID attribute
  .map(function() { return this.id; }) // convert to set of IDs
  .get(); // convert to instance of Array (optional)

  _.map(arrayIDS, function(id, key) {
    value = $('#' + id).val();
    id = id.replace('quantity', '');
    element['productID'] = id;
    element['quantity'] = parseInt(value);
    arrayContentUpdateQuantities.push(element);
    element = {};
  });


  // NOTE send array(id and quantity) to php to update $_SESSION
  params['location'] = 'saveAllQuantity-shoppingCartPartner';
  params['arrayContentUpdateQuantities'] = arrayContentUpdateQuantities;
  generalFunctionToRequest(params);
}

function saveQuantityShoppinCarPartner(productID) {
  if(typeof productID === 'undefined') return false;
  //console.log(productID)
  var url = '../php/shopping/shopping.php';
  var dataSend = {
    data: 'empty',
    location: 'savequantityshoppingcarpartner',
    productID: productID,
    quantity: $('#quantity' + productID).val()
  };

  $.ajax({
    type: 'post',
    data: dataSend,
    url: url,
    beforeSend: function(){
      $('#procesando').show();
    },
    complete: function(){
      $('#procesando').hide();
    },
    success: function(data) {
      showMessage('success', 'Se ha guardado la cantidad del producto');
      showInformation('shopping');
    },
    error: function(err) {
      showMessage('error', 'Ha ocurrido un error, intentar nuevamente ó contactar al administrador');
    }
  });
}

function showMorosidad(perid, tiempoMor) {
  if(typeof perid === 'undefined') return false;
  //console.log(perid, tiempoMor);
  var url = '../php/report/report.php';
  var dataSend = {};
  dataSend.data = 'empty';
  dataSend.location = 'showdetailMor';
  dataSend.perid = perid;
  dataSend.tiempoMor = tiempoMor;
  dataSend.userLocation = 'reports-partner'; // to identity kind of user
  // load data in div #information
  $.ajax({
    type: 'post',
    data: dataSend,
    url: url,
    beforeSend: function(){
      $('#procesando').show();
    },
    complete: function(){
      $('#procesando').hide();
    },
    success: function(data) {
      $('#page-wrapper').html(data);
    },
    error: function(err) {
      showMessage('error', 'Ha ocurrido un error, intentar nuevamente ó contactar al administrador');
    }
  });
}

function showDetail(pedidoID) {
  if(typeof pedidoID === 'undefined') return false;
  //console.log(pedidoID);
  var url = '../php/order/order.php';
  var dataSend = {};
  dataSend.data = 'empty';
  dataSend.location = 'showdetail';
  dataSend.pedidoID = pedidoID;
  dataSend.userLocation = 'registrado'; // to identity kind of user
  // load data in div #information
  $.ajax({
    type: 'post',
    data: dataSend,
    url: url,
    beforeSend: function(){
      $('#procesando').show();
    },
    complete: function(){
      $('#procesando').hide();
    },
    success: function(data) {
      $('#page-wrapper').html(data);
    },
    error: function(err) {
      showMessage('error', 'Ha ocurrido un error, intentar nuevamente ó contactar al administrador');
    }
  });
}

function showDetailOrder(pedidoID, numPrecio) {
  if(typeof pedidoID === 'undefined') return false;
  //console.log("Hola ", pedidoID, numPrecio);
  var url = '../php/order/order.php';
  var dataSend = {};
  dataSend.data = 'empty';
  dataSend.location = 'showdetailorder';
  dataSend.pedidoID = pedidoID;
  dataSend.numPrecio = numPrecio;
  dataSend.userLocation = 'registrado'; // to identity kind of user
  // load data in div #information
  $.ajax({
    type: 'post',
    data: dataSend,
    url: url,
    beforeSend: function(){
      $('#procesando').show();
    },
    complete: function(){
      $('#procesando').hide();
    },
    success: function(data) {
      $('#page-wrapper').html(data);
    },
    error: function(err) {
      showMessage('error', 'Ha ocurrido un error, intentar nuevamente ó contactar al administrador');
    }
  });
}

function showPersonal(perID) {
  if(typeof perID === 'undefined') return false;
  //console.log("El id es ", perID);
  var url = '../php/report/report.php';
  var dataSend = {};
  dataSend.data = 'empty';
  dataSend.location = 'getReporteVendedor';
  dataSend.perID = perID;
  dataSend.section = 'reports-partner';
  // load data in div #information
  $.ajax({
    type: 'post',
    data: dataSend,
    url: url,
    beforeSend: function(){
      $('#procesando').show();
    },
    complete: function(){
      $('#procesando').hide();
    },
    success: function(data) {
      $('#page-wrapper').html(data);
    },
    error: function(err) {
      showMessage('error', 'Ha ocurrido un error, intentar nuevamente ó contactar al administrador');
    }
  });
}

// TODO encodeURIComponent
// structure data send to file php to be processed for each location
function showInformation(location) {
  if(typeof location === 'undefined') return false;
  //console.log('entro y su locacion es: '+ location);
  var url = null;
  var dataSend = {};
  switch(location) {
    case 'dashboard':
      //TODO Aqui va el dashboard de la intranet
      url = '../php/shopping/shopping.php';
      dataSend.data = 'empty';
      dataSend.location = 'getDashBoard';
      break;
    case 'edocta':
      url = '../php/order/order.php';
      dataSend.data = 'empty';
      dataSend.location = 'edocta';
      dataSend.section = 'orders-record-partner';
      break;
    case 'facturas':
      url = '../php/order/order.php';
      dataSend.data = 'empty';
      dataSend.location = 'facturas';
      dataSend.section = 'orders-record-partner';
      break;
    case 'home':
      // TODO create file and process kind of user and redirect or show home page, depend kind of user has been logged
      url = '../php/general/general.php';
      dataSend.data = 'empty';
      dataSend.location = 'identify-home';
      break;
    case 'notcred':
      url = '../php/order/order.php';
      dataSend.data = 'empty';
      dataSend.location = 'notcred';
      dataSend.section = 'orders-record-partner';
      break;
    case 'order':
      url = '../php/order/order.php';
      dataSend.data = 'empty';
      dataSend.location = 'getorders';
      dataSend.section = 'orders-record-partner';
      break;
    case 'orderp':
      url = '../php/order/order.php';
      dataSend.data = 'empty';
      dataSend.location = 'getorders2';
      dataSend.section = 'orders-record-partner';
      //console.log(dataSend.data, dataSend.location, dataSend.section);
      break;
    case 'product':
      url = '../php/product/product.php';
      // send empty value because that`s the logic to control workflow switch
      dataSend.data = 'empty';
      dataSend.location = 'get-products-crud';
      break;
    case 'report':
      url = '../php/report/report.php';
      dataSend.data = 'empty';
      dataSend.location = 'getreport';
      dataSend.section = 'reports-partner';
      break;
    case 'dashBoardAdmin':
      url = '../php/report/report.php';
      dataSend.data = 'empty';
      dataSend.location = 'getDashBoardAdmin';
      dataSend.section = 'reports-partner';
      break;
    case 'shopping':
      url = '../php/shopping/shopping.php';
      dataSend.data = 'empty';
      dataSend.location = 'getshoppingpartner';
      break;
    case 'configuration':
      url = '../php/general/general.php';
      dataSend.data = 'empty';
      dataSend.location = 'get-inferface-to-update';
      break;
    case 'dashBoardSz':
      url = '../php/report/report.php';
      dataSend.data = 'empty';
      dataSend.location = 'getDashBoardSz';
      dataSend.section = 'reports-partner';
      break;
    case 'dashBoardDirIndex':
      url = '../php/report/report.php';
      dataSend.data = 'empty';
      dataSend.location = 'getDashBoardDirIndex';
      dataSend.section = 'reports-partner';
      break;
    case 'dashBoardDireccion':
      url = '../php/report/report.php';
      dataSend.data = 'empty';
      dataSend.location = 'getDashBoardDireccion';
      dataSend.section = 'reports-partner';
      break;
    case 'enlaceZona1':
      url = '../php/report/report.php';
      dataSend.data = 'empty';
      dataSend.location = 'getEnlaceZona1';
      dataSend.section = 'reports-partner';
      break;
    case 'enlaceZona2':
      url = '../php/report/report.php';
      dataSend.data = 'empty';
      dataSend.location = 'getEnlaceZona2';
      dataSend.section = 'reports-partner';
      break;
    case 'user':
      url = '../php/user/user.php';
      dataSend.data = 'empty';
      dataSend.location = 'get-users-crud';
      break;
    default:
      url = '../php/general/general.php'; // always send to home if location doesn't exist
      dataSend.data = 'empty';
      dataSend.location = 'identify-home';
  }
  // load data in div dynamically
  $.ajax({
    type: 'post',
    data: dataSend,
    url: url,
    beforeSend: function(){
      $('#procesando').show();
    },
    complete: function(){
      $('#procesando').hide();
    },
    success: function(data) {
      // to get data orders record on div, because it's the only when load need show data
      if(location == 'home') {
        showInformation(data);
      }
      else if(location == 'product') {
        $('#page-wrapper').html(data);
        getFocus('findCrudProductBy');
      }
      else if(location == 'shopping') {
        // NOTE first load script confirmation and then load response ajax, because use confirmation like this on php file (data-toggle="confirmation).confirmation({});
        var url = '//cdnjs.cloudflare.com/ajax/libs/bootstrap-confirmation/1.0.5/bootstrap-confirmation.min.js';
        $.getScript(url)
          .done(function(script, textStatus) {
            $('#page-wrapper').html(data);
            // after response, call another function to show information
            var params = {
              location: 'shopping-orders-record'
            };
            // load data to get process file getshoppingpartner, and update section(DIV ID) with other function
            generalFunctionToRequest(params);
          })
          .fail(function( jqxhr, settings, exception ) {
            console.log('Error: ' +  exception);
        });
      }
      else if(location == 'user') {
        $('#page-wrapper').html(data);
        getFocus('findCrudUserBy');
      }
      else if (location == 'report') {
        // NOTE before load div with id where load chart, load necessary libraries; then call method to make chart
        $.when(
            $.getScript( "//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js" ),
            $.getScript( "//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js" ),
            $.Deferred(function(deferred) {
                $(deferred.resolve);
            })
        ).done(function(){
            $('#page-wrapper').html(data); // first print div
            // NOTE obj its var print with php to javascript
            var result = [];
            for (var key in obj) {
              if (obj.hasOwnProperty(key)) {
                result.push(JSON.parse(obj[key]));
              }
            }

             Morris.Bar({
              element: 'myfirstchart',
              data: result,
              xkey: 'titulo',
              ykeys: ['constructor', 'socio'],
              labels: ['Series A', 'Series B']
            });
        });
      }
      else {
        // If request it's different to shopping
        $('#page-wrapper').html(data);
      }
    },
    error: function(err) {
      showMessage('error', 'Ha ocurrido un error, intentar nuevamente ó contactar al administrador');
    }
  });
}

// call the function that display message with librarie toastr, only receive boolean param
function showMessageResponse(params) {
  if(typeof params === 'undefined' ||
      params === null ||
      typeof params.responseOfRequest === 'undefined') return false;

  //console.log('showMessageResponse' ,params);
  if(params.responseOfRequest) {
    showMessage('success', params.msgSuccess);
  } else {
    showMessage('error', params.msgError);
  }
}

// show information on div, this happend when user will be upload a file
function showMessageUploadFile(message){
  if(typeof message === 'undefined') return false;

  $(".messagesUploadFile").html('').show();
  $(".messagesUploadFile").html(message);
}

function validateInputs(requiredInputs) {
  if(typeof requiredInputs === 'undefined') return false;

  var value;
  for(var position in requiredInputs) {
    value = $('#' + requiredInputs[position]).val();
    value = $.trim(value);
    if(value.length == 0) {
      // console.log(requiredInputs[position]);
      return false;
    }
  }
  return true;
}

function validateMaxLength() {
  if($(this).hasOwnProperty('val')) {
    var text = $(this).val();
    var maxlength = $(this).data('maxlength');

    if(maxlength > 0) {
      $(this).val(text.substr(0, maxlength));
    }
  } else {
    return false;
  }
}
