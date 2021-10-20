function fetchReport(type, id, check=0, certification_id='', result) {
    data = {'report' : null}
    $.ajax({
        type: "GET",
        url: "/admin/Ocr/report?type=" + type + "&id=" + id +"&check=" + check + "&certification=" + certification_id,
        success: function (response) {
            if (!response || response.status.code != 200) {
                result(data);
                return;
            }
            if(response.status.message != ''){
              data.message = response.status.message;
            }
            if(response.response.data_type){
              result(response.response);
              return;
            }
            data.report = response.response;
            if(type == 'income_statement'){
              result(data.report.income_statement_logs.items[0].income_statement);
            }else if(type == 'business_tax_return_report') {
              result(data.report.business_tax_return_logs.items[0].business_tax_return);
            }else if(type == 'balance_sheet'){
              result(data.report.balance_sheet_logs.items[0].balance_sheet);
            }else if(type == 'insurance_table'){
              result(data.report.insurance_table_logs.items[0].insurance_table);
            }else if(type == 'amendment_of_register'){
              result(data.report.amendment_of_register_logs.items[0].amendment_of_register);
            }else if(type == 'credit_investigation'){
              result(data.report.credit_investigation_logs.items[0].credit_investigation);
            }else{
              result(data);
            }
        },
        error: function(error) {
            result(data);
        }
    });
}

function toCurrency(num,is_thousandths){
  if(!is_thousandths){
    var parts = num.toString().split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    return parts.join('.');
  }
  return num;
}

function fillReport(data,type,location,input_location=''){
  Object.keys(data).forEach(function(key) {
    if(location == 'report'){
      if(key && key != 'table_list'){
        if(input_location){
          htmlKey = "#" + input_location + key;
        }else{
          htmlKey = "#" + key;
        }
        if(type == 'insurance_table'){
          if(key != 'insuredList'){
            value = data[key];
          }else{
            value ='';
          }
        }else{
          value = data[key];
        }
        $(htmlKey).val(value);
      }
    }
    if(type == 'income_statement' && location == 'table'){
      // htmlKeyNumber = Number(data[key]['key']);
      if(key){
		  htmlKey = "#" + key;
          value = data[key]['left'];
    	  // console.log(htmlKey);
    	  // console.log(value);
          $(htmlKey).val(value);
          htmlKey = "#" + key + '_AddAdjust';
          value = data[key]['right'];
          $(htmlKey).val(value);
	  }
    }

    if(type == 'balance_sheet' && location == 'table_st'){
      htmlKey = "#" + key + "_ST";
      value = data[key];
      $(htmlKey).val(value);
    }

    if(type == 'balance_sheet' && location == 'table_t'){
      htmlKey = "#" + key + "_T";
      value = data[key];
      $(htmlKey).val(value);
    }

  })
}

function edit_click(){
  $("input").prop('disabled', function(i, v) { return !v; });
  if ($(".btn").length) {
    $(".btn").prop('hidden', function(i, v) { return !v; });
  }
}

function save_click(ocr_type, id, certification_id){
  var table_data = {};
  $('.save-this').each(function () {
    data_id = this.id;
    data_input = $("#" + data_id).val();
    table_data[data_id] = data_input;
  });

  table_data['table_list'] = {};
  $('.save-table').each(function (index,item) {
    table_data['table_list'][this.id] = {};
    var table_index = this.id;
    var num = 0;
    table_data['table_list'][table_index] = {};
    if(ocr_type != 'insurance_table_person'){
      if(ocr_type != 'credit_investigation'){
        $(this).find('tr').each(function (index,item) {
          table_data['table_list'][table_index][num]={};
          $(this).find('td').each(function (index,item) {
            $(this).find('input').not('input[type="checkbox"]').each(function(){
              input_id = this.id;
              table_data['table_list'][table_index][num][input_id] = $(this).val();
            })
          });
          num +=1;
        });
      }else{
        $(this).find('.credit-tr').each(function (index,item) {
          table_data['table_list'][table_index][num]={};
          $(this).find('.credit-td').each(function (index,item) {
            $(this).find('input').not('input[type="checkbox"]').each(function(){
              input_id = this.id;
              table_data['table_list'][table_index][num][input_id] = $(this).val();
            })
          });
          num +=1;
        });
      }
    }else{
      $(this).find('div').each(function (index,item) {
        table_data['table_list'][table_index][num]={};
        $(this).find('div').each(function (index,item) {
          $(this).find('input').not('input[type="checkbox"]').each(function(){
            input_id = this.id.replace(/_[0-9]*$/g, '');
            table_data['table_list'][table_index][num][input_id] = $(this).val();
          })
        });
        num +=1;
      });
    }

  });

  $.ajax({
      type: "POST",
      url: "/admin/Ocr/save",
      data:{
        'table_data' : table_data,
        'ocr_type' : ocr_type,
        'certification_id' : certification_id,
        'id' : id
      },
      success: function (response) {
        alert(response);
      },
      error: function(error) {
          alert(error);
      }
  });
}

function send_click(ocr_type, id, certification_id){

  var table_data = {};
  $('.result-this').each(function () {
    data_id = this.id;
    data_input = $("#" + data_id).val();
    table_data[data_id] = data_input;
  });

  $.ajax({
      type: "POST",
      url: "/admin/Ocr/send",
      data: {
        'ocr_type' : ocr_type,
        'certification_id' : certification_id,
        'id' : id,
        'table_data' : table_data,
      },
      success: function (response) {
        alert(response);
      },
      error: function(error) {
          alert(error);
      }
  });
}

// add table list
function addTableList(element,html){
  $(element).append(html);
}
// delete table list
function deleteTableList(element){
  $( element).last().remove();
}
