<?php

class Report_check_lib
{
    function __construct()
    {
        $this->CI = &get_instance();
    }

    private $balance_sheets_negative = ['1159', '1126', '1122', '1124', '1302', '1631', '1641', '1542',
      '1422', '1552', '1553', '1511', '1512', '1411', '1432','1442', '1443', '1137',
      '1433', '1452', '1453', '1462', '1463', '1492', '1493','3120', '3450', '3600'
    ];
    private $balance_sheets_check_list = [
      [
        'total' => ['1100'],
        'sum' => ['1111', '1112', '1113', '1114', '1151', '1154', '1158', '1161', '1157', '1159',
                  '1125', '1126', '1121', '1122', '1123', '1124', '1129', '1130', '1140', '1190'],
      ],
      [
        'total' => ['1130'],
        'sum' => ['1131', '1132', '1133', '1134', '1135', '1136', '1137', '1138'],
      ],
      [
        'total' => ['1140'],
        'sum' => ['1141', '1142', '1143', '1144', '1145', '1149'],
      ],
      [
        'total' => ['1190'],
        'sum' => ['1191', '1192', '1193', '1199'],
      ],
      [
        'total' => ['1200'],
        'sum' => ['1300', '1302', '1612', '1615', '1621', '1622', '1618', '1630', '1631', '1640', '1641', '1400',
                  '1541', '1542', '1421', '1422', '1551', '1552', '1553', '1510','1511', '1512', '1900'],
      ],
      [
        'total' => ['1400'],
        'sum' => ['1410', '1411', '1431', '1432', '1433', '1441', '1442', '1443', '1451', '1452', '1453', '1461', '1462', '1463', '1470', '1491', '1492', '1493'],
      ],
      [
        'total' => ['1900'],
        'sum' => ['1901', '1902', '1903', '1904'],
      ],
      [
        'total' => ['1000'],
        'sum' => ['1100', '1200'],
      ],
      [
        'total' => ['2100'],
        'sum' => ['2110', '2140', '2150', '2170', '2180', '2126', '2120', '2120', '2121', '2130', '2136', '2190'],
      ],
      [
        'total' => ['2110'],
        'sum' => ['2111', '2112', '2113', '2119'],
      ],
      [
        'total' => ['2130'],
        'sum' => ['2131', '2132', '2133', '2134', '2135'],
      ],
      [
        'total' => ['2136'],
        'sum' => ['2137', '2138'],
      ],
      [
        'total' => ['2190'],
        'sum' => ['2191', '2192', '2193', '2195', '2196'],
      ],
      [
        'total' => ['2200'],
        'sum' => ['2210', '2220', '2230', '2240', '2260', '2270', '2280', '2281', '2290', '2900'],
      ],
      [
        'total' => ['2900'],
        'sum' => ['2910', '2940', '2951', '2970', '2999'],
      ],
      [
        'total' => ['2000'],
        'sum' => ['2100', '2200'],
      ],
      [
        'total' => ['3100'],
        'sum' => ['3110', '3130', '3120'],
      ],
      [
        'total' => ['3410'],
        'sum' => ['3411', '3412'],
      ],
      [
        'total' => ['3420'],
        'sum' => ['3421', '3422'],
      ],
      [
        'total' => ['3430'],
        'sum' => ['3431', '3432', '3434', '3435'],
      ],
      [
        'total' => ['3400'],
        'sum' => ['3410', '3420', '3430', '3440', '3450'],
      ],
      [
        'total' => ['3500'],
        'sum' => ['3502', '3503', '3504', '3505', '3506', '3507'],
      ],
      [
        'total' => ['3000'],
        'sum' => ['3100', '3300', '3400', '3500', '3600'],
      ],
      [
        'total' => ['9000'],
        'sum' => ['2000', '3000'],
      ],
      [
        'total' =>['1000'],
        'sum' => ['9000'],
      ]
    ];

    private $income_statements_negative = ['02', '03', '05', '08', '45', '93', '99', '101',
      '57', '129', '55', '125', '112', '119', '95', '113', '62', '63', '58'
    ];

    private $income_statements_check_list = [
      [
        'total' => ['04'],
        'sum' => ['01', '02', '03'],
      ],
      [
        'total' => ['06'],
        'sum' => ['04', '05'],
      ],
      [
        'total' => ['07'],
        'sum' => ['06', '04'],
      ],
      [
        'total' => ['08'],
        'sum' => ['10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '23', '24',
                  '25', '26', '27', '28', '29', '30', '31', '32'],
      ],
      [
        'total' => ['09'],
        'sum' => ['08','04'],
      ],
      [
        'total' => ['33'],
        'sum' => ['06','08'],
      ],
      [
        'total' => ['104'],
        'sum' => ['33','04'],
      ],
      [
        'total' => ['06'],
        'sum' => ['04','05'],
      ],
      [
        'total' => ['34'],
        'sum' => ['35', '36', '38', '39', '40', '41', '43', '44'],
      ],
      [
        'total' => ['45'],
        'sum' => ['46','47','48','49','51','52'],
      ],
      [
        'total' => ['53'],
        'sum' => ['33','34','35'],
      ],
      [
        'total' => ['54'],
        'sum' => ['53','04','34'],
      ],
      [
        'total' => ['59'],
        'sum' => ['53','93','99','101','57','129','58','55','125','126','132'],
      ],
    ];

    private $business_tax_returns_negative = ['17','18','19','40','41','42','43'
    ];

    private $business_tax_returns_check_list = [
      [
        'total' => ['21'],
        'sum' => ['1','5','9','13','17'],
      ],
      [
        'total' => ['22'],
        'sum' => ['2','6','10','14','18'],
      ],
      [
        'total' => ['23'],
        'sum' => ['3','7','11','15','19'],
      ],
      [
        'total' => ['25'],
        'sum' => ['21','23'],
      ],
      [
        'total' => ['44'],
        'sum' => ['28','32','36','78','40'],
      ],
      [
        'total' =>['45'],
        'sum' =>['29','33','37','79','41'],
      ],
      [
        'total' => ['46'],
        'sum' => ['30','34','38','80','42'],
      ],
      [
        'total' => ['47'],
        'sum' => ['31','35','39','81','43'],
      ],
      [
        'total' => ['101'],
        'sum' => ['22'],
      ],
      [
        'total' => ['107'],
        'sum' => ['45','47'],
      ],
      [
        'total' => ['110'],
        'sum' => ['108','107'],
      ],
      [
        'total' => ['111'],
        'sum' => ['101','110'],
      ],
      [
        'total' => ['112'],
        'sum' => ['101','110'],
      ],
      [
        'total' => ['113'],
        'sum' => ['23','47'],
      ],
      [
        'total' => ['114'],
        'sum' => ['111','112'],
      ],
      [
        'total' => ['115'],
        'sum' =>['112','114'],
      ],
    ];
    private $balance_sheets_list =[];
    private $income_statements_list =[];
    private $business_tax_returns_list =[];

    public function balance_sheet_check($data,$sum,$sum_list){
      $list_false = 0;
      $all = array_merge($sum_list,$sum);
      foreach($all as $v){
        if(! isset($this->balance_sheets_list[$v]) || $this->balance_sheets_list[$v]['value'] == ''){
          $list_false = 1;
          if(! isset($this->balance_sheets_list[$v])){
            $this->balance_sheets_list[$v]['value'] = '';
          }
        }
      }

      if($list_false != 1){
        $total = 0;
        foreach($sum_list as $v){
          if(!isset($data[$v]['value'])){
            $value = 0;
          }else{
            if(is_numeric($data[$v]['value'])){
              $value = $data[$v]['value'];
            }else{
              $value = 0;
            }
          }
          if(in_array($v,$this->balance_sheets_negative)){
            $total -= $value;
          }else{
            $total += $value;
          }
        }
        if($total != $data[$sum[0]]['value']){
          foreach($all as $v){
            $this->balance_sheets_list[$v]['check'] = 'false';
          }
        }else{
          foreach($all as $v){
            $this->balance_sheets_list[$v]['check'] = 'true';
          }
        }
      }else{
        foreach($all as $v){
          $this->balance_sheets_list[$v]['check'] = 'false';
        }
      }
    }

    public function income_statement_check($data,$sum,$sum_list){
      $list_false_left = 0;
      $list_false_right = 0;
      $all = array_merge($sum_list,$sum);
      foreach($all as $v){
        if(! isset($this->income_statements_list[$v]) || $this->income_statements_list[$v]['left']['value'] == ''){
          $list_false_left = 1;
          if(! isset($this->income_statements_list[$v])){
            $this->income_statements_list[$v]['left']['value'] == '';
          }
        }

        if(! isset($this->income_statements_list[$v]) || $this->income_statements_list[$v]['right']['value'] == ''){
          $list_false_right = 1;
          if(! isset($this->income_statements_list[$v])){
            $this->income_statements_list[$v]['right']['value'] == '';
          }
        }
      }
      //left
      if($list_false_left != 1){
        if($sum[0]=='07' || $sum[0]=='09' || $sum[0]=='104' || $sum[0]=='54'){
          if($sum[0]=='07'){
            if(is_numeric($data['06']['left']['value']) && is_numeric($data['04']['left']['value']) && $data['04']['left']['value'] != '0'){
              $left_percent = $data['06']['left']['value']*100/$data['04']['left']['value'];
              $left_percent = substr(sprintf("%.4f", $left_percent),0,-2);
            }else{
              $left_percent = '0';
            }
            if($left_percent != $data['07']['left']['value']){
              $this->income_statements_list['07']['left']['check'] = 'false';
            }else{
              $this->income_statements_list['07']['left']['check'] = 'true';
            }
          }
          if($sum[0]=='09'){
            if(is_numeric($data['08']['left']['value']) && is_numeric($data['04']['left']['value']) && $data['04']['left']['value'] != '0'){
              $left_percent = $data['08']['left']['value']*100/$data['04']['left']['value'];
              $left_percent = substr(sprintf("%.4f", $left_percent),0,-2);
            }else{
              $left_percent = '0';
            }
            if($left_percent != $data['09']['left']['value']){
              $this->income_statements_list['09']['left']['check'] = 'false';
            }else{
              $this->income_statements_list['09']['left']['check'] = 'true';
            }
          }
          if($sum[0]=='104'){
            if(is_numeric($data['33']['left']['value']) && is_numeric($data['04']['left']['value']) && $data['04']['left']['value'] != '0'){
              $left_percent = $data['33']['left']['value']*100/$data['04']['left']['value'];
              $left_percent = substr(sprintf("%.4f", $left_percent),0,-2);
            }else{
              $left_percent = '0';
            }
            if($left_percent != $data['104']['left']['value']){
              $this->income_statements_list['104']['left']['check'] = 'false';
            }else{
              $this->income_statements_list['104']['left']['check'] = 'true';
            }
          }
          if($sum[0]=='54'){
            if(is_numeric($data['53']['left']['value']) && is_numeric($data['04']['left']['value']) && is_numeric($data['34']['left']['value']) && $data['04']['left']['value'] + $data['34']['left']['value'] > '0'){
              $left_percent = $data['53']['left']['value']*100/($data['04']['left']['value']+$data['34']['left']['value']);
              $left_percent = substr(sprintf("%.4f", $left_percent),0,-2);
            }else{
              $left_percent = '0';
            }
            if($left_percent != $data['54']['left']['value']){
              $this->income_statements_list['54']['left']['check'] = 'false';
            }else{
              $this->income_statements_list['54']['left']['check'] = 'true';
            }
          }
        }else{

          $total_left = 0;
          foreach($sum_list as $v){
            if(!isset($data[$v]['left']['value'])){
              $value = 0;
            }else{
              if(is_numeric($data[$v]['left']['value'])){
                $value = $data[$v]['left']['value'];
              }else{
                $value = 0;
              }
            }
            if(in_array($v,$this->income_statements_negative) && ($sum[0] != '45' || $sum[0] != '34' || $sum[0] != '08') ){
              $total_left -= $value;
            }else{
              $total_left += $value;
            }
          }

          if($total_left != $data[$sum[0]]['left']['value']){
            foreach($all as $v){
              $this->income_statements_list[$v]['left']['check'] = 'false';
            }
          }else{
            foreach($all as $v){
              $this->income_statements_list[$v]['left']['check'] = 'true';
            }
          }

        }

      }else{
        foreach($all as $v){
          $this->income_statements_list[$v]['left']['check'] = 'false';
        }
      }
      //right
      if($list_false_right != 1){
        if($sum[0]=='07' || $sum[0]=='09' || $sum[0]=='104' || $sum[0]=='54'){
          if($sum[0]=='07'){
            if(is_numeric($data['06']['right']['value']) && is_numeric($data['04']['right']['value']) && $data['04']['right']['value'] != '0'){
              $right_percent = $data['06']['right']['value']*100/$data['04']['right']['value'];
              $right_percent = substr(sprintf("%.4f", $right_percent),0,-2);
            }else{
              $right_percent = '0';
            }
            if($right_percent != $data['07']['right']['value']){
              $this->income_statements_list['07']['right']['check'] = 'false';
            }else{
              $this->income_statements_list['07']['right']['check'] = 'true';
            }
          }
          if($sum[0]=='09'){
            if(is_numeric($data['08']['right']['value']) && is_numeric($data['04']['right']['value']) && $data['04']['right']['value'] != '0'){
              $right_percent = $data['08']['right']['value']*100/$data['04']['right']['value'];
              $right_percent = substr(sprintf("%.4f", $right_percent),0,-2);
            }else{
              $right_percent = '0';
            }
            if($right_percent != $data['09']['right']['value']){
              $this->income_statements_list['09']['right']['check'] = 'false';
            }else{
              $this->income_statements_list['09']['right']['check'] = 'true';
            }
          }
          if($sum[0]=='104'){
            if(is_numeric($data['33']['right']['value']) && is_numeric($data['04']['right']['value']) && $data['04']['right']['value'] != '0'){
              $right_percent = $data['33']['right']['value']*100/$data['04']['right']['value'];
              $right_percent = substr(sprintf("%.4f", $right_percent),0,-2);
            }else{
              $right_percent = '0';
            }
            if($left_percent != $data['104']['right']['value']){
              $this->income_statements_list['104']['right']['check'] = 'false';
            }else{
              $this->income_statements_list['104']['right']['check'] = 'true';
            }
          }
          if($sum[0]=='54'){
            if(is_numeric($data['53']['right']['value']) && is_numeric($data['04']['right']['value']) && is_numeric($data['34']['right']['value']) && $data['04']['right']['value'] + $data['34']['right']['value'] > '0'){
              $right_percent = $data['53']['right']['value']*100/($data['04']['right']['value']+$data['34']['right']['value']);
              $right_percent = substr(sprintf("%.4f", $right_percent),0,-2);
            }else{
              $right_percent = '0';
            }
            if($right_percent != $data['54']['right']['value']){
              $this->income_statements_list['54']['right']['check'] = 'false';
            }else{
              $this->income_statements_list['54']['right']['check'] = 'true';
            }
          }
        }else{

          $total_right = 0;
          foreach($sum_list as $v){
            if(!isset($data[$v]['right']['value'])){
              $value = 0;
            }else{
              if(is_numeric($data[$v]['right']['value'])){
                $value = $data[$v]['right']['value'];
              }else{
                $value = 0;
              }
            }
            if(in_array($v,$this->income_statements_negative) && ($sum[0] != '45' || $sum[0] != '34' || $sum[0] != '08')){
              $total_right -= $value;
            }else{
              $total_right += $value;
            }
          }

          if($total_right != $data[$sum[0]]['right']['value']){
            foreach($all as $v){
              $this->income_statements_list[$v]['right']['check'] = 'false';
            }
          }else{
            foreach($all as $v){
              $this->income_statements_list[$v]['right']['check'] = 'true';
            }
          }

        }

      }else{
        foreach($all as $v){
          $this->income_statements_list[$v]['right']['check'] = 'false';
        }
      }

    }

    public function business_tax_return_check($data,$sum,$sum_list){
      $list_false = 0;
      $all = array_merge($sum_list,$sum);
      foreach($all as $v){
        if(! isset($this->business_tax_returns_list[$v]) ){
          $list_false = 1;
          $this->business_tax_returns_list[$v]['check'] = 'false';
          $this->business_tax_returns_list[$v]['value'] = '';
        }
        if( ! isset($data[$v]) ){
          $list_false = 1;
          $data[$v]['check'] = 'false';
          $data[$v]['value'] = '';
        }
      }

      if($list_false != 1){
        $total = 0;
        if($sum[0] == 101 || $sum[0] == 107 || $sum[0] == 110 || $sum[0] == 111 || $sum[0] == 112 || $sum[0] == 113 || $sum[0] == 114 || $sum[0] == 115){

          if($sum[0] == 101){
            if($data['22']['value'] != $data['101']['value']){
              $this->business_tax_returns_list['101']['check'] = 'false';
              $this->business_tax_returns_list['22']['check'] = 'false';
            }else{
              $this->business_tax_returns_list['101']['check'] = 'true';
              $this->business_tax_returns_list['22']['check'] = 'true';
            }
          }

          if($sum[0] == 107){
            $check_sum = is_numeric($data['45']['value']) ? $data['45']['value']:0 + is_numeric($data['49']['value'])? $data['49']['value']:0;
            if($check_sum != $data['107']['value']){
              foreach($all as $v){
                $this->business_tax_returns_list[$v]['check'] = 'false';
              }
            }else{
              foreach($all as $v){
                $this->business_tax_returns_list[$v]['check'] = 'true';
              }
            }
          }

          if($sum[0] == 110){
            $check_sum = is_numeric($data['107']['value']) ? $data['107']['value']:0 + is_numeric($data['108']['value']) ? $data['108']['value']:0;
            if($check_sum != $data['110']['value']){
              foreach($all as $v){
                $this->business_tax_returns_list[$v]['check'] = 'false';
              }
            }else{
              foreach($all as $v){
                $this->business_tax_returns_list[$v]['check'] = 'true';
              }
            }
          }

          if($sum[0] == 111){
            $check_sum = is_numeric($data['101']['value']) ? $data['101']['value']:0 - is_numeric($data['110']['value']) ? $data['110']['value']:0;
            if($check_sum<0){
              $check_sum = 0;
            }
            if($check_sum != $data['111']['value']){
              foreach($all as $v){
                $this->business_tax_returns_list[$v]['check'] = 'false';
              }
            }else{
              foreach($all as $v){
                $this->business_tax_returns_list[$v]['check'] = 'true';
              }
            }
          }

          if($sum[0] == 112){
            $check_sum = is_numeric($data['110']['value']) ? $data['110']['value']:0 - is_numeric($data['101']['value']) ? $data['101']['value']:0;
            if($check_sum<0){
              $check_sum = 0;
            }
            if($check_sum != $data['112']['value']){
              foreach($all as $v){
                $this->business_tax_returns_list[$v]['check'] = 'false';
              }
            }else{
              foreach($all as $v){
                $this->business_tax_returns_list[$v]['check'] = 'true';
              }
            }
          }

          if($sum[0] == 113){
            $check_sum = is_numeric($data['23']['value']) ? $data['23']['value']:0 *5/100 + is_numeric($data['47']['value']) ? $data['47']['value']:0;
            if($check_sum != $data['113']['value']){
              foreach($all as $v){
                $this->business_tax_returns_list[$v]['check'] = 'false';
              }
            }else{
              foreach($all as $v){
                $this->business_tax_returns_list[$v]['check'] = 'true';
              }
            }
          }

          if($sum[0] == 114){
            if($data['112']['value']>$data['113']['value']){
              $check_sum = $data['112']['value'];
            }else{
              $check_sum = $data['113']['value'];
            }
            if($check_sum != $data['114']['value']){
              foreach($all as $v){
                $this->business_tax_returns_list[$v]['check'] = 'false';
              }
            }else{
              foreach($all as $v){
                $this->business_tax_returns_list[$v]['check'] = 'true';
              }
            }
          }

          if($sum[0] == 115){
            $check_sum = is_numeric($data['112']['value']) ? $data['112']['value']:0 - is_numeric($data['114']['value']) ? $data['114']['value']:0;
            if($check_sum<0){
              $check_sum = 0;
            }
            if($check_sum != $data['115']['value']){
              foreach($all as $v){
                $this->business_tax_returns_list[$v]['check'] = 'false';
              }
            }else{
              foreach($all as $v){
                $this->business_tax_returns_list[$v]['check'] = 'true';
              }
            }
          }

        }else{
          foreach($sum_list as $v){
            if(!isset($data[$v]['value'])){
              $value = 0;
            }else{
              if(is_numeric($data[$v]['value'])){
                $value = $data[$v]['value'];
              }else{
                $value = 0;
              }
            }
            if(in_array($v,$this->business_tax_returns_negative)){
              $total -= $value;
            }else{
              $total += $value;
            }
          }
          if($total != $data[$sum[0]]['value']){
            foreach($all as $v){
              $this->business_tax_returns_list[$v]['check'] = 'false';
            }
          }else{
            foreach($all as $v){
              $this->business_tax_returns_list[$v]['check'] = 'true';
            }
          }
        }
      }else{
        foreach($all as $v){
          $this->business_tax_returns_list[$v]['check'] = 'false';
        }
      }

    }
    //refactor balance sheet response
    public function balance_sheet($data){
      $refactor_list = [];

      foreach($data->table->assetsItems->subTotal as $k=>$v){
        $refactor_list[$k]['value'] = isset($v)? $v:'';
        $refactor_list[$k]['table'] = 'assetsItems';
        $refactor_list[$k]['location'] = 'subTotal';
        $refactor_list[$k]['check'] = isset($v)? '':'false';
      }
      foreach($data->table->assetsItems->total as $k=>$v){
        $refactor_list[$k]['value'] = isset($v)? $v:'';
        $refactor_list[$k]['table'] = 'assetsItems';
        $refactor_list[$k]['location'] = 'total';
        $refactor_list[$k]['check'] = isset($v)? '':'false';
      }
      foreach($data->table->liabilitiesItems->subTotal as $k=>$v){
        $refactor_list[$k]['value'] = isset($v)? $v:'';
        $refactor_list[$k]['table'] = 'liabilitiesItems';
        $refactor_list[$k]['location'] = 'subTotal';
        $refactor_list[$k]['check'] = isset($v)? '':'false';
      }
      foreach($data->table->liabilitiesItems->total as $k=>$v){
        $refactor_list[$k]['value'] = isset($v)? $v:'';
        $refactor_list[$k]['table'] = 'liabilitiesItems';
        $refactor_list[$k]['location'] = 'total';
        $refactor_list[$k]['check'] = isset($v)? '':'false';
      }
      $this->balance_sheets_list = $refactor_list;
      foreach($this->balance_sheets_check_list as $v){
        $this->balance_sheet_check($refactor_list,$v['total'],$v['sum']);
      }

      $data->table = $this->balance_sheets_list;
      return $data;
    }

    //refactor income statement response
    public function income_statement($data){
      $refactor_list = [];

      foreach($data->netIncomeTable as $k=>$v){
        $refactor_list[$v->key]['left']['value'] = isset($v->value->left)? $v->value->left:'';
        $refactor_list[$v->key]['left']['check'] = isset($v->value->left)? '':'false';
        $refactor_list[$v->key]['right']['value'] = isset($v->value->right)? $v->value->right:'';
        $refactor_list[$v->key]['right']['check'] = isset($v->value->right)? '':'false';
      }
      $this->income_statements_list = $refactor_list;
      foreach($this->income_statements_check_list as $v){
        $this->income_statement_check($refactor_list,$v['total'],$v['sum']);
      }

      $data->netIncomeTable = $this->income_statements_list;
      return $data;
    }

    //refactor business tax return response
    public function business_tax_return($data){
      $refactor_list = [];
      foreach($data->output_info as $k=>$v){
        $refactor_list[$k]['value'] = isset($v)? $v:'';
        $refactor_list[$k]['check'] = isset($v)? '':'false';
      }
      foreach($data->input_info as $k=>$v){
        $refactor_list[$k]['value'] = isset($v)? $v:'';
        $refactor_list[$k]['check'] = isset($v)? '':'false';
      }
      foreach($data->tax_calculation as $k=>$v){
        $refactor_list[$k]['value'] = isset($v)? $v:'';
        $refactor_list[$k]['check'] = isset($v)? '':'false';
      }

      $this->business_tax_returns_list = $refactor_list;
      foreach($this->business_tax_returns_check_list as $v){
        $this->business_tax_return_check($refactor_list,$v['total'],$v['sum']);
      }

      $data->table = $this->business_tax_returns_list;
      return $data;
    }

    public function report_check($type='', $data=[]){
      if(!$type || !$data){
        return ['status'=>'fail', 'data'=>$data, 'msg'=>'傳送參數不正確' ];
      }
      if($type=='business_tax_return_report'){
        $type = 'business_tax_return';
      }
      if (is_callable(array($this,$type))) {
        $data->{"{$type}_logs"}->items[0]->$type = $this->$type($data->{"{$type}_logs"}->items[0]->$type);
      }else{
        return ['status'=>'fail', 'data'=>$data, 'msg'=>'該檢查方案尚未實作' ];
      }
      return ['status'=>'success', 'data'=>$data, 'msg'=>'' ];
    }

}
