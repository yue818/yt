<?php
class Order_OrderController extends Ec_Controller_Action
{
    
    public function preDispatch()
    {
        $this->tplDirectory = "order/views/order/";
        $this->serviceClass = new Service_Orders();
    }

    public function listAction()
    {
        $this->forward('list', 'order-list', 'order');
    }
    
    /**
     * 订单详情
     * @throws Exception
     */
    public function detailAction(){

        try{
            $statusArr = Service_OrderProcess::getOrderStatus();
            
            $order_id = $this->getRequest()->getParam('order_id', '');
            if(empty($order_id)){
                throw new Exception(Ec::Lang('参数错误'));
            }
            $order = Service_CsdOrder::getByField($order_id, 'order_id');
            if(!$order){
                throw new Exception(Ec::Lang('订单不存在'));
            }
            if($order['customer_id']!=Service_User::getCustomerId()){
                throw new Exception(Ec::Lang('非法操作'));
            }
            $order['order_status'] = isset($statusArr[$order['order_status']])?$statusArr[$order['order_status']]['name']:$order['order_status'];
            // 历史数据 start
            $con = array(
                    'order_id' => $order_id
            );
            $invoice = Service_CsdInvoice::getByCondition($con,'*',0,0,'invoice_id asc');
            if(empty($invoice)&&$order['mail_cargo_type']!=3){
                throw new Exception(Ec::Lang('申报信息不存在'));
            }
            foreach($invoice as $k=>$v){
                $v['invoice_unitcharge'] = $v['invoice_quantity']?($v['invoice_totalcharge']/$v['invoice_quantity']):0;
                $invoice[$k] = $v;
            }
 

            $service = array();
            $services = Service_AtdExtraserviceKind::getAll();
            foreach ($services as $key => $val){
            	$group = Service_AtdExtraserviceKind::getByField($val['extra_service_kind'],'extra_service_kind','extra_service_group');
            	$groupName = Service_AtdExtraserviceKind::getByField($group['extra_service_group'],'extra_service_kind','extra_service_cnname');
            	$name = $group['extra_service_group'] ? $groupName['extra_service_cnname'].':':'';
				$str = ($group['extra_service_group'] == 'C0' ) ? substr($val['extra_service_cnname'], 2) : $val['extra_service_cnname'];
            	$service[$val['extra_service_kind']] = $name.$str;
            }
            
            $extservices = Service_CsdExtraservice::getByCondition($con); //echo "<pre>";print_r($extservices);die;
            $extservice = '';
            foreach ($extservices as $k => $v){
            	if ($v[extra_servicecode] == 'C2'){
            		$se = $service[$v[extra_servicecode]].$v[extra_servicevalue]."元每票; ";
            	}else{
            		$se = $service[$v[extra_servicecode]].'; ';
            	}
            	$extservice .= $se;
            }
            $extservice = trim($extservice,';');
            $shipperConsignee = Service_CsdShipperconsignee::getByField($order_id,'order_id');
            if(!$shipperConsignee){
                throw new Exception(Ec::Lang('收发件人信息不存在'));
            }
            // 历史数据 end
            $this->view->order = $order;
            $this->view->invoice = $invoice;
            $this->view->shipperConsignee = $shipperConsignee;
            $this->view->extservice = $extservice;
            $con = array(
                    'ref_id' => $order_id
            );
            $logArr = Service_OrderLog::getByCondition($con,'*',0,0,'log_id asc');
            foreach($logArr as $k=>$v){
                $v['user_name'] = Ec::Lang('系统');
                if($v['op_user_id']){
                    $u = Service_User::getByField($v['op_user_id'],'user_id');
                    $v['user_name'] = $u['user_name'];
                }
            
                $logArr[$k] = $v;
            }
            //         print_r($con);exit;
            $this->view->logArr = $logArr;
            echo Ec::renderTpl($this->tplDirectory . "order_detail.tpl", 'layout');
        }catch (Exception $e){
            header("Content-type: text/html; charset=utf-8");
            echo $e->getMessage();exit;
            $this->forward('deny','error','default');
        }
       
    }
    /**
     * 手工创建订单
     */
    public function createAction()
    {
        $order_id = $this->getRequest()->getParam('order_id', '');
        $cpy = $this->getRequest()->getParam('cpy', null);
        if($this->getRequest()->isPost()){
            $orderR = array();
            $params = $this->getRequest()->getParams();
            //订单头
            $order = $this->getParam('order',array());
            //收件人,发件人
            $consignee = $this->getParam('consignee',array());
            //申报信息
            $invoice = $this->getParam('invoice',array());
            //额外服务
            $extraservice = $this->getParam('extraservice',array());
            //预报？草稿？
            $status = $this->getParam('status','1');
            
            
            $orderArr = array(
                'product_code' => strtoupper($order['product_code']),
                'country_code' => strtoupper($order['country_code']),
                'refer_hawbcode' => strtoupper($order['refer_hawbcode']),
                'order_weight' => $order['order_weight'],
                'order_pieces' => $order['order_pieces'],
            	
            		'order_length'=>$order['order_length'],
            		'order_width'=>$order['order_width'],
            		'order_height'=>$order['order_height'],
            		
                'buyer_id' =>$order['buyer_id'],
                'order_id' => $order['order_id'],
                'order_create_code'=>'w',
                'customer_id'=>Service_User::getCustomerId(),
                'creater_id'=>Service_User::getUserId(),
                'modify_date'=>date('Y-m-d H:i:s'),
                'mail_cargo_type' => $order['mail_cargo_type'],
                'tms_id'=>Service_User::getTmsId(),
                'customer_channelid'=>Service_User::getChannelid(),
                'insurance_value' => trim($order['insurance_value1']),
            );
            $volumeArr=array(
            	'length'=>$order['order_length'],
            	'width'=>$order['order_width'],
            	'height'=>$order['order_height'],	
            		
            );

            /*$return = array(
                'ask' => 0,
                'message' => Ec::Lang('订单操作失败')
            );
             $return["message"] = $volumeArr['length'];
            die(Zend_Json::encode($return));*/

            $consigneeArr = array(
                'consignee_countrycode' => strtoupper($order['country_code']),
                'consignee_company' => $consignee['consignee_company'],
                'consignee_province' => $consignee['consignee_province'],
                'consignee_name' => $consignee['consignee_name'],
                'consignee_city' => $consignee['consignee_city'],
                'consignee_telephone' => $consignee['consignee_telephone'],
                'consignee_mobile' => $consignee['consignee_mobile'],
                'consignee_postcode' => $consignee['consignee_postcode'],
                'consignee_email' => $consignee['consignee_email'],
                'consignee_street' => $consignee['consignee_street'],
                'consignee_street2' => $consignee['consignee_street2'],
                'consignee_street3' => $consignee['consignee_street3'],
                'consignee_certificatetype' => $consignee['consignee_certificatetype'],
                'consignee_certificatecode' => $consignee['consignee_certificatecode'],
                'consignee_credentials_period' => $consignee['consignee_credentials_period'],
                'consignee_doorplate' => $consignee['consignee_doorplate'],
            );
            
            $consignee['shipper_account'] = ! empty($consignee['shipper_account']) ? $consignee['shipper_account'] : '';
            $shipperArr = Service_CsiShipperTrailerAddress::getByField($consignee['shipper_account'], 'shipper_account');            
            $invoiceArr = array();
            foreach($invoice as $column=>$v){
                foreach($v as $kk=>$vv){
                    if($kk==0)
                        continue;
                    $kk1=$kk-1;
                    $invoiceArr[$kk1][$column] = $vv;
                }
            }
            
            /*$return = array(
                'ask' => 0,
                'message' => Ec::Lang('订单操作失败')
            );
            $return["message"] = $invoiceArr[0]["sku"];
            die(Zend_Json::encode($return));*/

              
            // php hack 
            if(! empty($invoiceArr)){
                array_unshift($invoiceArr, array());
                unset($invoiceArr[0]);
            }
            
            //如果类型是文件，则可以允许海关物品无关联
           /*  if($order['mail_cargo_type']==3){
                $isNotNullOfInvoice=false;
                foreach ($invoiceArr as $v){
                    foreach ($v as $k=>$vv){
                        if(!empty($vv)&&$k!='unit_code'){
                            $isNotNullOfInvoice = true;
                            break;
                        } 
                    }
                }
                !$isNotNullOfInvoice&&$invoiceArr=array();
            } */
//             print_r($orderArr);
//             print_r($invoiceArr);
//             print_r($extraservice);
//             print_r($shipperArr);
//             print_r($consigneeArr);
//             exit;
//print_r($orderArr);die;
            $process = new Process_Order();
            $process->setVolume($volumeArr);
            $process->setOrder($orderArr);
            $process->setInvoice($invoiceArr);
            $process->setExtraservice($extraservice);       
            $process->setShipper($shipperArr);          
            $process->setConsignee($consigneeArr); 
//             $process
            $return = $process->createOrderTransaction($status);
            
//             print_r($params);exit;
            die(Zend_Json::encode($return));
        }
        
        if($order_id){
            try {
                $order = Service_CsdOrder::getByField($order_id, 'order_id');
                if(!$order){
                    throw new Exception(Ec::Lang('订单不存在或已删除'));
                }
                if($order['customer_id']!=Service_User::getCustomerId()){
                    throw new Exception(Ec::Lang('非法操作'));
                }
                // 历史数据 start
                $con = array(
                        'order_id' => $order_id
                );
                $invoice = Service_CsdInvoice::getByCondition($con,'*',0,0,'invoice_id asc');
                
                foreach($invoice as $k=>$v){
                    $v['invoice_unitcharge'] = $v['invoice_quantity']?($v['invoice_totalcharge']/$v['invoice_quantity']):0;
                    $v['invoice_weight'] = $v['invoice_weight']?($v['invoice_totalWeight']/$v['invoice_quantity']):0;
                    $invoice[$k] = $v;
                }
                
                $atd_extraservice_kind_arr = Common_DataCache::getAtdExtraserviceKindAll();
                $extservice = Service_CsdExtraservice::getByCondition($con);
                foreach($extservice as $v){
                	$extra_servicecode = $v['extra_servicecode'];
                	//保险费 C0
                	if($atd_extraservice_kind_arr[$extra_servicecode]['extra_service_group']=='C0'){
                		$order['insurance_value'] = $v['extra_servicevalue'];
                	}
                }
                $shipperConsignee = Service_CsdShipperconsignee::getByField($order_id,'order_id');
                // 历史数据 end
                if($cpy){
                    unset($order['order_id']);
                    if($order['order_status']!='E'){
                        unset($order['shipper_hawbcode']);
                        unset($order['refer_hawbcode']);
                        unset($order['server_hawbcode']);
                    }
                }//print_r($order);die;
                $this->view->order = $order;
                $this->view->invoice = $invoice;
                $this->view->shipperConsignee = $shipperConsignee;
                $this->view->extservice = $extservice;
            } catch (Exception $e) {
                header("Content-type: text/html; charset=utf-8");
                echo $e->getMessage();exit;                
            }
            
            
//             print_r($order);exit;
        }else{
			$op = $this->getParam ( 'op', '' );
			if ($op == 'fast-create-order') {
				$product_code = $this->getParam ( 'product_code', '' );
				$country_code = $this->getParam ( 'country_code', '' );
				$order = array (
						'product_code' => $product_code,
						'country_code' => $country_code 
				);
				$this->view->order = $order;
			}
		}

		$countrys = Service_IddCountry::getByCondition(null, '*', 0, 0, '');
        $this->view->country = $countrys;

        $this->view->productKind = Process_ProductRule::getProductKind();

        // print_r($productKind);
        $con = array('unit_status'=>'ON');
        $units = Service_AddDeclareunit::getByCondition($con);
        $this->view->units = $units;

        //证件类型
        $con = array();
        $certificates = Service_AtdCertificateType::getByCondition($con);
        $this->view->certificates = $certificates;  

        //邮政包裹申报种类表
        $con = array();
        $mailCargoTypes = Service_AtdMailCargoType::getByCondition($con);
        $this->view->mailCargoTypes = $mailCargoTypes; 
        //选取默认收件人
        $this->view->shipperCustom=$this->getShipper($order_id);
        
        $html =  Ec::renderTpl($this->tplDirectory . "order_create1.tpl",'system-layout-0506');
        $html = preg_replace('/>\s+</','><',$html);
        echo $html;
    }

    /**
     * 手工创建DHL订单
     */
    public function createdhlAction()
    {
       $order_id = $this->getRequest()->getParam('order_id', '');
        $cpy = $this->getRequest()->getParam('cpy', null);
        if($this->getRequest()->isPost()){
            $orderR = array();
            $params = $this->getRequest()->getParams();
            //订单头
            $order = $this->getParam('order',array());
            //收件人,发件人
            $consignee = $this->getParam('consignee',array());
            //发件人
            $shipper = $this->getParam('shipper',array());
            //申报信息
            $invoice = $this->getParam('invoice',array());
            //额外服务
            $extraservice = $this->getParam('extraservice',array());
            //预报？草稿？
            $status = $this->getParam('status','1');
            
            $orderArr = array(
                'product_code' => strtoupper($order['product_code']),
                'country_code' => strtoupper($order['country_code']),
                'refer_hawbcode' => strtoupper($order['refer_hawbcode']),
                'order_weight' => $order['order_weight'],
                'order_pieces' => $order['order_pieces'],
                 
                'order_length'=>$order['order_length'],
                'order_width'=>$order['order_width'],
                'order_height'=>$order['order_height'],
                'buyer_id' =>$order['buyer_id'],
                'order_id' => $order['order_id'],
                'order_create_code'=>'w',
                'customer_id'=>Service_User::getCustomerId(),
                'creater_id'=>Service_User::getUserId(),
                'modify_date'=>date('Y-m-d H:i:s'),
                'mail_cargo_type' => $order['mail_cargo_type'],
                'tms_id'=>Service_User::getTmsId(),
                'customer_channelid'=>Service_User::getChannelid(),
                'insurance_value' => trim($order['insurance_value']),
                'insurance_value_gj' => $order['insurance_value_gj'],
        
            );
            $volumeArr=array(
                'length'=>$order['order_length'],
                'width'=>$order['order_width'],
                'height'=>$order['order_height'],
    
            );
            
            /*$return = array(
             'ask' => 0,
             'message' => Ec::Lang('订单操作失败')
            );
            $return["message"] = $volumeArr['length'];
            die(Zend_Json::encode($return));*/
    
            $consigneeArr = array(
                'consignee_countrycode' => strtoupper($order['country_code']),
                'consignee_company' => $consignee['consignee_company'],
                'consignee_province' => $consignee['consignee_province'],
                'consignee_name' => $consignee['consignee_name'],
                'consignee_city' => $consignee['consignee_city'],
                'consignee_telephone' => $consignee['consignee_telephone'],
                'consignee_mobile' => $consignee['consignee_mobile'],
                'consignee_postcode' => $consignee['consignee_postcode'],
                'consignee_email' => $consignee['consignee_email'],
                'consignee_street' => $consignee['consignee_street'],
                'consignee_street2' => $consignee['consignee_street2'],
                'consignee_street3' => $consignee['consignee_street3'],
                'consignee_certificatetype' => $consignee['consignee_certificatetype'],
                'consignee_certificatecode' => $consignee['consignee_certificatecode'],
                'consignee_credentials_period' => $consignee['consignee_credentials_period'],
                'consignee_doorplate' => $consignee['consignee_doorplate'],
            );
    
            $consignee['shipper_account'] = ! empty($consignee['shipper_account']) ? $consignee['shipper_account'] : '';
            //$shipperArr = Service_CsiShipperTrailerAddress::getByField($consignee['shipper_account'], 'shipper_account');
            $shipperArr = array(
                'shipper_name' => $shipper['shipper_name'],
                'shipper_company' => $shipper['shipper_company'],
                'shipper_countrycode' => $shipper['shipper_countrycode'],
                'shipper_province' => $shipper['shipper_province'],
                'shipper_city' => $shipper['shipper_city'],
                'shipper_street' => $shipper['shipper_street'],
                'shipper_postcode' => $shipper['shipper_postcode'],
                'shipper_areacode' => $shipper['shipper_areacode'],
                'shipper_telephone' => $shipper['shipper_telephone'],
                'shipper_mobile' => $shipper['shipper_mobile'],
                'shipper_email' => $shipper['shipper_email'],
                'shipper_certificatecode' => $shipper['shipper_certificatecode'],
                'shipper_certificatetype' => $shipper['shipper_certificatetype'],
                'shipper_fax' => $shipper['shipper_fax'],
                'shipper_mallaccount' => $shipper['shipper_mallaccount']
            );
            
            $invoiceArr = array();
            foreach($invoice as $column=>$v){
                foreach($v as $kk=>$vv){
                $invoiceArr[$kk][$column] = $vv;
                }
            }
               //去掉都为空的海关信息
            foreach ($invoiceArr as $k=>$v){
                $flag = false;
                foreach ($v as $vv){
                    if(!empty($vv)){
                        $flag=true;
                        break;
                    }
                }
                if(!$flag){
                    unset($invoiceArr[$k]);
                }
            }
            foreach ($invoiceArr as $column=>$vc){
                if(!$vc['invoice_enname']){
                    $vc['invoice_enname'] = $invoice['invoice_enname'][0];
                    $vc['invoice_cnname'] = $invoice['invoice_cnname'][0];
                    $vc['invoice_shippertax'] = $invoice['invoice_shippertax'][0];
                    $vc['invoice_consigneetax'] = $invoice['invoice_consigneetax'][0];
                    $vc['invoice_totalcharge_all'] = $invoice['invoice_totalcharge_all'][0];
                    $vc['hs_code'] = $invoice['hs_code'][0];
                    $invoiceArr[$column]=$vc;
                }
            }
           //var_dump($invoiceArr);die;
           /*  var_dump($invoice);
            foreach($invoice as $column=>$v){
                foreach($v as $kk=>$vv){
                    if(empty($vv)){
                        unset($invoiceArr[$kk]);
                        continue;
                    }
                    $invoiceArr[$kk][$column] = $vv;
                    
                }
            }
            foreach ($invoiceArr as $column=>&$v){
                if(!$v['invoice_enname']){
                    $v['invoice_enname'] = $invoice['invoice_enname'][0];
                    $v['invoice_cnname'] = $invoice['invoice_cnname'][0];
                    $v['invoice_shippertax'] = $invoice['invoice_shippertax'][0];
                    $v['invoice_consigneetax'] = $invoice['invoice_consigneetax'][0];
                    $v['invoice_totalcharge_all'] = $invoice['invoice_totalcharge_all'][0];
                    $v['sku'] = $invoice['sku'][0];
                }
            } */
            // php hack
            if(! empty($invoiceArr)){
                array_unshift($invoiceArr, array());
                unset($invoiceArr[0]);
            }
            
           /*  //如果类型是文件，则可以允许海关物品无关联
            if($order['mail_cargo_type']==3){
                $isNotNullOfInvoice=false;
                foreach ($invoiceArr as $v){
                    foreach ($v as $k=>$vv){
                        if(!empty($vv)&&$k!='unit_code'){
                            $isNotNullOfInvoice = true;
                            break;
                        } 
                    }
                }
                !$isNotNullOfInvoice&&$invoiceArr=array();
            } */
            
            //echo 1111;die;
            $process = new Process_OrderDhl();
            $process->setVolume($volumeArr);
            $process->setOrder($orderArr);
            $process->setInvoice($invoiceArr);
            $process->setExtraservice($extraservice);
            $process->setShipper($shipperArr);
            $process->setConsignee($consigneeArr);
            //             $process
            $return = $process->createOrderTransaction($status);
    
            //             print_r($params);exit;
            die(Zend_Json::encode($return));
        }
    
        if($order_id){
            try {
                $order = Service_CsdOrder::getByField($order_id, 'order_id');
                if(!$order){
                    throw new Exception(Ec::Lang('订单不存在或已删除'));
                }
                if($order['customer_id']!=Service_User::getCustomerId()){
                    throw new Exception(Ec::Lang('非法操作'));
                }
                // 历史数据 start
                $con = array(
                    'order_id' => $order_id
                );
                $invoice = Service_CsdInvoice::getByCondition($con,'*',0,0,'invoice_id asc');
    
                foreach($invoice as $k=>$v){
                    $v['invoice_unitcharge'] = $v['invoice_quantity']?($v['invoice_totalcharge']/$v['invoice_quantity']):0;
                    $v['invoice_weight'] = $v['invoice_weight']?($v['invoice_totalWeight']/$v['invoice_quantity']):0;
                    $invoice[$k] = $v;
                }
    
                $atd_extraservice_kind_arr = Common_DataCache::getAtdExtraserviceKindAll();
                $extservice = Service_CsdExtraservice::getByCondition($con);
                foreach($extservice as $v){
                    $extra_servicecode = $v['extra_servicecode'];
                    //保险费 C0
                    if($atd_extraservice_kind_arr[$extra_servicecode]['extra_service_group']=='C0'){
                        $order['insurance_value'] = $v['extra_servicevalue'];
                    }
                }
                $shipperConsignee = Service_CsdShipperconsignee::getByField($order_id,'order_id');
                // 历史数据 end
                if($cpy){
                    unset($order['order_id']);
                    if($order['order_status']!='E'){
                        unset($order['shipper_hawbcode']);
                        unset($order['refer_hawbcode']);
                        unset($order['server_hawbcode']);
                    }
                }//print_r($order);die;
                $this->view->order = $order;
                $this->view->invoice = $invoice;
                $this->view->shipperConsignee = $shipperConsignee;
                $this->view->extservice = $extservice;
            } catch (Exception $e) {
                header("Content-type: text/html; charset=utf-8");
                echo $e->getMessage();exit;
            }
        }else{
            $op = $this->getParam ( 'op', '' );
            if ($op == 'fast-create-order') {
                $product_code = $this->getParam ( 'product_code', '' );
                $country_code = $this->getParam ( 'country_code', '' );
                $order = array (
                    'product_code' => $product_code,
                    'country_code' => $country_code
                );
                $this->view->order = $order;
            }
        }
        
        $countrys = Process_ProductRule::arrivalCountry('G_DHL');
        //var_dump($countrys);
        //$countrys = Service_IddCountry::getByCondition(null, '*', 0, 0, '');
        $this->view->country = $countrys;
    
        $this->view->productKind = Process_ProductRule::getProductKind();
        $con = array('unit_status'=>'ON');
        $units = Service_AddDeclareunit::getByCondition($con);
        $this->view->units = $units;
    
        //证件类型
        $con = array();
        $certificates = Service_AtdCertificateType::getByCondition($con);
        $this->view->certificates = $certificates;
    
        //邮政包裹申报种类表
        $con = array();
        $mailCargoTypes = Service_AtdMailCargoType::getByCondition($con);
        $this->view->mailCargoTypes = $mailCargoTypes;
        //选取默认收件人
        $this->view->shipperCustom=$this->getShipper($order_id,1);
        //var_dump($this->getShipper($order_id,1));
        $html =  Ec::renderTpl($this->tplDirectory . "order_create_dhl.tpl", 'system-layout-0506');
        $html = preg_replace('/>\s+</','><',$html);
        echo $html;
    }
    
    /**
     * 订单导入
     */
    public function importAction()
    {
        $this->forward('import-user-template');
        
//         if($this->getRequest()->isPost()){
//             set_time_limit(0);
//             ini_set('memory_limit', '1024M');
//             $return = array(
//                 'ask' => 0,
//                 'message' => 'Request Method Err'
//             );
            
//             $file = $_FILES['fileToUpload'];
//             // print_r($file);exit;
//             $process = new Process_OrderUpload();
//             if($file && $file['tmp_name'] && $file['size'] > 0 && empty($file['error'])){
                
//                 $result = $process->importTransaction($file);
//             }else{
//                 $param = $this->getParam('fileData', array());
//                 // print_r($param);exit;
//                 $result = $process->submitBatchTransaction($param);
//             }
//             // $result['ask'] = 0;
//             $this->view->result = $result;
//             // print_r($result);//exit;
//         }
        
//         echo Ec::renderTpl($this->tplDirectory . "order_import.tpl", 'layout-upload');
    }
 

    /**
     * 订单导入
     */
    public function importUserTemplateAction()
    {
        $this->view->productKind = Process_ProductRule::getProductKind();
        if($this->getRequest()->isPost()){
            set_time_limit(0);
            ini_set('memory_limit', '1024M');
            $return = array(
                'ask' => 0,
                'message' => 'Request Method Err'
            );
            $param = $this->getRequest()->getParams();
            //var_dump($param);
                // 默认发件人
            $shipper_account = $this->getParam('shipper_account', 0);
//             print_r($shipper_account);exit;
            //国家映射
            //$country_map = $this->getParam('country_map', array());
            $ansych = $this->getParam('ansych', '');
//                 print_r($country_map);exit;
            $file = $_FILES['fileToUpload'];
            //print_r($file);
            $process = new Process_OrderUploadUserTemplate();
            //设置默认发件人
            if($shipper_account)
                $process->setDefaultShipperAccount($shipper_account);
            //$process->setCountryMap($country_map);
            
            if($file && $file['tmp_name'] && $file['size'] > 0 && empty($file['error'])) {
            	// 异步提交模式
            	if($ansych) {
            		$result = $process->importByAsynchTransaction($file);
            	} else {
            		// 及时提交并返回结果
                	$result = $process->importTransaction($file);
            	}
            }else{
                $param = $this->getParam('fileData', array());
                //print_r($param);exit;
                //$process->setCountryMap($countryMapArr);
                $result = $process->submitBatchTransaction($param);
            }
            
            $result['ansych'] = $ansych;
            // $result['ask'] = 0;
            $this->view->result = $result;
            $this->view->result_str = print_r($result, true);
            
            $notExistCountryArr = $process->getNotExistCountryArr();
            if(! empty($notExistCountryArr)){
                $this->view->notExistCountryArr = $notExistCountryArr;
                $this->view->countrys = Common_DataCache::getCountry();
            }
            
            echo $this->view->render($this->tplDirectory . "order_import_file_content.tpl");
            exit();
            // print_r($result);//exit;
        }
       
        $sql = "select report_filename,report_file_path from csd_customer_report where customer_id=0 or customer_id='' or customer_id is null  order by report_id";
        $baseTemplate = Common_Common::fetchAll($sql);
        $this->view->baseTemplate = $baseTemplate;
        // print_r(Common_DataCache::getCountry());exit;
        echo Ec::renderTpl($this->tplDirectory . "order_import.tpl", 'layout-upload');
    }
    /**
     * 订单审核
     */
    public function verifyAction()
    {
        if($this->getRequest()->isPost()){
            set_time_limit(0);
            $param = $this->_request->getParams();
            $orderIdArr = $this->_request->getParam('order_id', array());
            $op = $this->_request->getParam('op', '');
            $process = new Process_Order();
            $return = $process->verifyOrderBatchTransaction($orderIdArr, $op);
            // print_r($return);exit;
            die(json_encode($return));
        }
    }
    
    /**
     * 获取发件人信息方法
     */
    protected function getShipper($order_id,$getDefault=false){
        $con = array(
            'customer_id' => Service_User::getCustomerId()
        );
        
        $con['customer_channelid'] = Service_User::getChannelid();
        $submiters = Service_CsiShipperTrailerAddress::getByCondition($con);
        if(empty($submiters))
            return array();
        if($order_id){
            $shipperConsignee = Service_CsdShipperconsignee::getByField($order_id,'order_id');
            if($shipperConsignee){
                foreach($submiters as $k=>$v){
                    if($shipperConsignee['shipper_account']==$v['shipper_account']){
                        $v['is_default'] = 1;
                    }else{
                        $v['is_default'] = 0;
                    }
                    $submiters[$k] = $v;
                }
            }
        }
        //只有一条发件信息，默认选中
        if(count($submiters)==1){
            $submiters[0]['is_default'] = 1;
        }
        
        if($getDefault){
            if(count($submiters)==1){
                $submiters = $submiters[0];
            }else{
                $default_key = -1;
                foreach ($submiters as $k=>$v){
                    if($v['is_default']==1){
                        $default_key = $k;
                        break;
                    }
                }
                $submiters=$default_key>=0?$submiters[$default_key]:$submiters[0];
            }
        }
        
        return $submiters;
    }
    
    
    /**
     * 获取发件人信息 
     */
    public function getSubmiterAction(){
        $order_id = $this->getParam('order_id', '');
        $type     = $this->getParam('type', 0);
        $con = array(
            'customer_id' => Service_User::getCustomerId()
        );

        $con['customer_channelid'] = Service_User::getChannelid();
        $submiters = Service_CsiShipperTrailerAddress::getByCondition($con);        
        if($order_id){
            $shipperConsignee = Service_CsdShipperconsignee::getByField($order_id,'order_id');
            if($shipperConsignee){
                foreach($submiters as $k=>$v){
                    if($shipperConsignee['shipper_account']==$v['shipper_account']){
                        $v['is_default'] = 1;                        
                    }else{
                        $v['is_default'] = 0;
                    }
                    $submiters[$k] = $v;
                }
            }
        }
        //只有一条发件信息，默认选中
        if(count($submiters)==1){
            $submiters[0]['is_default'] = 1;
        }
        $this->view->submiters = $submiters; 
        if(!$type)
            echo $this->view->render($this->tplDirectory . "order_submiter.tpl");
        else 
            exit(json_encode($submiters));
    }
    
    /**
     * 支持的运输方式
     */
    public function getProductAction(){
        $productKind = Process_ProductRule::getProductKind();
        echo Zend_Json::encode($productKind);
    }
    /**
     * 导出订单
     */
    public function exportAction(){
        $order_id_arr = $this->getParam('orderId', array());
        $process = new Process_OrderUpload();
        $process->baseExportProcess($order_id_arr);
    }

    /**
     * 订单打印
     */
    public function printAction()
    {
        $this->tplDirectory = "order/views/template/";
        try{
            set_time_limit(0);
            $order_id_arr = $this->getParam('orderId', array());
            $type = $this->getParam('type', 'label');
            if(empty($order_id_arr) || !is_array($order_id_arr)){
                throw new Exception(Ec::Lang('没有需要打印的订单'));
            }
            $result = array();
            foreach($order_id_arr as $order_id){
                $rs = Service_CsdOrderProcess::getOrderInfo($order_id);
                $data = $rs['data'];
                $order = $data['order'];
                if($order['customer_id']!=Service_User::getCustomerId()){
                    continue;
                }

                //如果跟踪号没有获取 则不能打印
                $getTrackingCode = Service_CsdOrder::getByField($order_id, 'order_id');
                if(empty($getTrackingCode['server_hawbcode'])){
                    continue;
                }

                //========================================
                $updateRow = array();
                $updateRow['print_date'] = date('Y-m-d H:i:s');                 
                Service_CsdOrder::update($updateRow, $order_id, 'order_id');                
                // 日志
                $logRow = array(
                        'ref_id' => $order_id,
                        'log_content' => Ec::Lang('订单打印')
                );
                Service_OrderLog::add($logRow);
                //======================================== 
                //验证回邮地址
                $result[] = $rs;
            }
            if(empty($result)){
                throw new Exception('没有需要打印的订单');
            }
            $this->view->result = $result;

            $this->view->customer_name = '协议客户名称xxxxxx';
            $this->view->return_address = '退件单位名称xxxxxxx';
            $this->view->label_desc = '标签说明标签说明标签说明标签说明标签说明标签说明标签说明标签说明标签说明标签说明标签说明标签说明标签说明标签说明';
            
//             echo $this->view->render($this->tplDirectory . "B4.tpl");
            echo $this->view->render($this->tplDirectory . "A3.tpl");
//             echo $this->view->render($this->tplDirectory . "Common.tpl");
        }catch(Exception $e){
            header("Content-type: text/html; charset=utf-8");
            echo $e->getMessage();
            exit();
        }
    }
    
    /**
     * 获取申报信息
     */
    public function getInvoiceAction() {
    	if($this->getRequest()->isPost()){
    		
    		$orderIdArr = $this->_request->getParam('order_id', array());
    		$condition = array();
    		$condition['order_id_in'] = $orderIdArr;
    		$condition['customer_id'] = Service_User::getCustomerId();
    		
    		$fields = array('csd_order.order_id', 
		    				'shipper_hawbcode',
		    				'country_code',
		    				'csd_invoice.invoice_cnname',
		    				'csd_invoice.invoice_enname',
		    				'csd_invoice.unit_code',
		    				'csd_invoice.invoice_quantity',
		    				'csd_invoice.invoice_totalcharge',
		    				'csd_invoice.hs_code',
		    				'csd_invoice.invoice_note',
		    				'csd_invoice.invoice_url');
    		
    		// 获取订单信息
    		$invoice = Service_CsdOrder::getByConditionJoinInvoice($condition, $fields);
    		foreach($invoice as $k=>$v){
    			$v['invoice_unitcharge'] = $v['invoice_quantity']?($v['invoice_totalcharge']/$v['invoice_quantity']):0;
    			$invoice[$k] = $v;
    		}
    		
    		$return['state'] = 1;
    		$return['data'] = $invoice;
    		
    		$con = array('unit_status'=>'ON');
    		$units = Service_AddDeclareunit::getByCondition($con);
    		$return['units'] = $units;
    		// print_r($return);exit;
    		die(json_encode($return));
    	}
    }
    
    // 编辑申报信息
    public function editInvoiceAction() {
    	if($this->getRequest()->isPost()){
    
    		$invoice = $this->_request->getParam('invoice', array());
    		$invoiceArr = array();
    		// 按订单转换数组
    		foreach($invoice as $order => $row) {
    			// 转换单个订单的多条申报数据
	    		foreach($row as $column => $v){
	    			foreach($v as $kk=>$vv){
	    				$invoiceArr[$order][$kk][$column] = $vv;
	    			}
	    		}
    		}
    		
//     		print_r($invoiceArr);

    		$process = new Process_Order();
    		$return = $process->editInvoiceTransaction($invoiceArr);
    
    		die(json_encode($return));
    	}
    }
    
    public function checkRefrenceNoAction(){
		$return = array (
				'ask' => 1,
				'message' => '' 
		);
		$order_id = $this->getParam ( 'order_id', '' );
		$refrence_no = $this->getParam ( 'refrence_no', '' );
		if (!empty ( $refrence_no )) { 
			$con = array (
					'shipper_hawbcode' => $refrence_no 
			);
			$shipper_hawbcode_arr = Service_CsdOrder::getByCondition ( $con );
			 
			foreach ( $shipper_hawbcode_arr as $k=>$v ) {
				if ($order_id == $v ['order_id']) {
					unset($shipper_hawbcode_arr[$k]);
				}
			}			
			if (!empty($shipper_hawbcode_arr)) {
				$return['ask'] = 0;
				$return ['message'] = "客户单号系统已经存在";
			}			 
		}
		
		die ( Zend_Json::encode ( $return ) );
	}
	
	/**
	 * 导入发票数据
	 */
	public function importInvoiceAction() {
		if($this->getRequest()->isPost()) {
		
			set_time_limit(0);
			ini_set('memory_limit', '1024M');
			$return = array(
					'ask' => 0,
					'message' => 'Request Method Err'
			);
		
			$file = $_FILES['FileName'];
			$obj = new Process_OrderUpload();
			$return = $obj->importInvoiceTransaction($file);
		
			die(Zend_Json::encode($return));
		}
	}
	
	/**
	 * 导入重量数据
	 */
	public function importWeightAction() {
		if($this->getRequest()->isPost()) {
		
			set_time_limit(0);
			ini_set('memory_limit', '1024M');
			$return = array(
					'ask' => 0,
					'message' => 'Request Method Err'
			);
		
			$file = $_FILES['WeightFileName'];
			$obj = new Process_OrderUpload();
			$return = $obj->importWeight($file);
		
// 			print_r($return); die;
			die(Zend_Json::encode($return));
		}
	}

	/**
	 * 获取导入批次数据
	 */
	public function getImportBatchAction() {
		
		$batch = Service_CsdCustomerImportBatch::getByCondition(array('customer_id' => Service_User::getCustomerId()), '*', 10, 1, 'ccib_id desc');
		$this->view->batch = $batch;
		
		if($this->getRequest()->isPost()) {
				
			$return = array(
					'ask' => 0,
					'message' => ''
			);
				
			$return['data'] = $batch;
			die(Zend_Json::encode($return));
		}
		
		echo Ec::renderTpl($this->tplDirectory . "order_import_batch.tpl", 'layout');
	}
	
	/**
	 * 获取导入批次数据
	 */
	public function getImportBatchDetailAction() {
		if($this->getRequest()->isPost()) {
			
			$return = array(
					'ask' => 0,
					'message' => ''
			);
			
			$ccib_id = $this->getParam('id', '');
			$batch_detail = Service_CsdCustomerImportBatchDetail::getByCondition(array('ccib_id' => $ccib_id));
			$return['data'] = $batch_detail;
			
			die(Zend_Json::encode($return));
		}
	}
}