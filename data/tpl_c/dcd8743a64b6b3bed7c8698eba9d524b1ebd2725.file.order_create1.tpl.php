<?php /* Smarty version Smarty-3.1.13, created on 2016-05-12 13:09:23
         compiled from "D:\yt1\application\modules\order\views\order\order_create1.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4664572c044fe3bc70-33279268%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dcd8743a64b6b3bed7c8698eba9d524b1ebd2725' => 
    array (
      0 => 'D:\\yt1\\application\\modules\\order\\views\\order\\order_create1.tpl',
      1 => 1463029658,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4664572c044fe3bc70-33279268',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_572c044fe76601_16025544',
  'variables' => 
  array (
    'order' => 0,
    'productKind' => 0,
    'c' => 0,
    'country' => 0,
    'mailCargoTypes' => 0,
    's' => 0,
    'shipperConsignee' => 0,
    'certificates' => 0,
    'units' => 0,
    'i' => 0,
    'invoice' => 0,
    'shipperCustom' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_572c044fe76601_16025544')) {function content_572c044fe76601_16025544($_smarty_tpl) {?><?php if (!is_callable('smarty_block_t')) include 'D:\\yt1\\libs\\Smarty\\plugins\\block.t.php';
if (!is_callable('smarty_block_ec')) include 'D:\\yt1\\libs\\Smarty\\plugins\\block.ec.php';
?><style>
.select1{
width: 100px;
}
.info .Validform_wrong {
	background: url("/images/error.png") no-repeat scroll left center
		rgba(0, 0, 0, 0);
	color: red;
	padding-left: 20px;
	white-space: nowrap;
}

.Validform_checktip {
	color: #999;
	font-size: 12px;
	height: 20px;
	line-height: 20px;
	margin-left: 8px;
	overflow: hidden;
}


.Validform_checktip {
	margin-left: 0;
}

.info {
	border: 1px solid #ccc;
	padding: 2px 20px 2px 5px;
	color: #666;
	position: absolute;
	margin-top: -32px;
	margin-left: 10px;
	display: none;
	line-height: 20px;
	background-color: #fff;
	float: left;
}

.dec {
	bottom: -8px;
	display: block;
	height: 8px;
	overflow: hidden;
	position: absolute;
	left: 10px;
	width: 17px;
}

.dec s {
	font-family: simsun;
	font-size: 16px;
	height: 19px;
	left: 0;
	line-height: 21px;
	position: absolute;
	text-decoration: none;
	top: -9px;
	width: 17px;
}

.dec .dec1 {
	color: #ccc;
}

.dec .dec2 {
	color: #fff;
	top: -10px;
}

</style>
<script type="text/javascript">
function successTip(tip, order) {
	if(order.order_status=='D'){
		$('<div title="操作提示 (Esc)" id="success-tip"><p align="">' + tip + '</p></div>').dialog({
	        autoOpen: true,
	        closeOnEscape:false,
	        width: 600,
	        maxHeight: 400,
	        modal: true,
	        show: "slide",
	        buttons: [
	            {
	                text: '录入下一条订单',
	                click: function () {
	                    $(this).dialog("close");
	                    $('#order_id').val('');
	                    $('#shipper_hawbcode').val('');
	                    setTimeout(function(){
	                    	$('#shipper_hawbcode').focus()
	                    },100);
	                	$('.orderSubmitVerifyBtn').show();
	                	$('.orderSubmitDraftBtn').show();
	                }
	            },
	            {
	                text: '查看订单',
	                click: function () {
	                    $(this).dialog("close");
	                    leftMenu('order-list','订单管理','/order/order-list/list?shipper_hawbcode='+order.shipper_hawbcode);
	                }
	            },
	            {
	                text: '编辑订单',
	                click: function () {
	                    $(this).dialog("close");
	                    $('#order_id').val(order.order_id);
	                    if(order.order_status!='D'){
	                    	$('.orderSubmitVerifyBtn').hide();
	                    	$('.orderSubmitDraftBtn').hide();
	                    	alertTip('订单不是草稿不允许编辑');
	                    }else{
		                	$('.orderSubmitVerifyBtn').show();
		                	$('.orderSubmitDraftBtn').show();
	                    }
	                }
	            }
	            
	        ],
	        close: function () {
	            $(this).remove();
	        },
	        open:function(){
	        	$('.ui-dialog-titlebar-close',$(this).parent()).remove();
	        }
	    });
	}else{
		$('<div title="操作提示 (Esc)" id="success-tip"><p align="">' + tip + '</p></div>').dialog({
	        autoOpen: true,
	        closeOnEscape:false,
	        width: 600,
	        maxHeight: 400,
	        modal: true,
	        show: "slide",
	        buttons: [
	            {
	                text: '录入下一条订单',
	                click: function () {
	                    $(this).dialog("close");
	                    $('#order_id').val('');
	                    $('#shipper_hawbcode').val('');
	                    setTimeout(function(){
	                    	$('#shipper_hawbcode').focus()
	                    },100);
	                	$('.orderSubmitVerifyBtn').show();
	                	$('.orderSubmitDraftBtn').show();
	                }
	            },
	            {
	                text: '查看订单',
	                click: function () {
	                    $(this).dialog("close");
	                    leftMenu('order-list','订单管理','/order/order-list/list?shipper_hawbcode='+order.shipper_hawbcode);
	                }
	            }
	            
	        ],
	        close: function () {
	            $(this).remove();
	        },
	        open:function(){
	        	$('.ui-dialog-titlebar-close',$(this).parent()).remove();
	        }
	    });
	}
    
}

function formSubmit(status){
	$("#orderForm :input").each(function(){
		var val = $(this).val();
		val = $.trim(val)
		$(this).val(val);
	});
	var param = $("#orderForm").serialize();
	param+='&status='+status;
	 loadStart();
	$.ajax({
		type: "POST",
		url: "/order/order/create",
		data: param,
		dataType:'json',
		success: function(json){
			 loadEnd('');
			var html = json.message;
			if(json.ask){
				html+="<br/>系统单号:"+json.order.shipper_hawbcode;
                $('#shipper_hawbcode').val(json.order.shipper_hawbcode);                
				successTip(html,json.order);
			}else{
				 if(json.err){
					 html+="<ul style='padding:0 25px;list-style-type:decimal;'>";
					 $.each(json.err,function(k,v){
						 html+="<li>"+v+"</li>";
					 })
					 html+="</ul>";
				 }
				alertTip(html);
			}
		}
	});
}
function check_extra_service(){
	$('#insurance_value_div').hide();
	$('#insurance_value_div #insurance_value').attr('disabled',true);
	
	$('.extra_service:checked').each(function(){
		// 投保金额
		var group = $(this).attr('group');
		var type = $(this).val();
		if(group=='C0' && type == 'C2'){
			$('#insurance_value_div').show();
			$('#insurance_value_div #insurance_value').attr('disabled',false);
		}
	})
}
$(function() {
    $(".datepicker").datepicker({ dateFormat: "yy-mm-dd"});
	$('.addInvoiceBtn').live('click',function(){
		var clone = $('#products .table-module-b1').eq(0).clone();
		$(':input',clone).val('');
		$('.unit_code',clone).val('PCE');
		$('.total',clone).html('0');
		$('#products').append(clone);
	});
	

	$('.delInvoiceBtn').live('click',function(){
		if($('.delInvoiceBtn').size()<=1){			
			alertTip('最后一个申报信息不可删除');
			return;
		}
		var this_ = $(this);
		jConfirm('你确定该操作吗？', '删除申报信息', function(r) {
    		if(r){
    			this_.parent().parent().remove();
    		}
    	});
       
	});

	$('#country_code').live('change',function(){
		var product_code = $('#product_code').val();
		var country_code = $('#country_code').val();
		var order_id = $('#order_id').val();
		if($.trim(order_id)==''){
			order_id='';
		}
		$('#product_extraservice_wrap').html('');
		if($.trim(product_code)==''){
			return;
		}
		if($.trim(country_code)==''){
			return;
		}
		var tip_id='dddddd';
		// loadStart(tip_id);
		// 附加服务
		$.ajax({
			type: "POST",
			url: "/order/product-rule/optional-serve-type",
			data: {'product_code':product_code,'country_code':country_code,'order_id':order_id},
			dataType:'json',
			success: function(json){
				var html = json.message;
				// loadEnd(html,tip_id);
				if(json.ask){
					var product_extraservice_wrap = '';
					var i = 0;
					$.each(json.data,function(k,v){
						
						// 不同分组的额外服务换行显示
						if(i++ != 0) {
							product_extraservice_wrap+="<br/>"
						}
						
						// 当长度大于0说明为多选项附加服务
						if(v.length > 1) {
							// 根据额外服务KEY，获取数据
							var group = json.group[k];
							product_extraservice_wrap+="<label title='"+group.extra_service_cnname+"' for='xx'><input type='checkbox' class='group_checkbox' group='"+k+"' id='"+ group.extra_service_kind +"'/><label for='"+ group.extra_service_kind +"'>"+group.extra_service_cnname +"</label>";
							$.each(v, function(kk,vv) {
								var checked = vv.checked?'checked':'';
								product_extraservice_wrap+="<input type='radio' class='extra_service' group='"+k+"' id='"+ vv.extra_service_kind +"' disabled='disabled' value='"+vv.extra_service_kind+"' name='extraservice[" + k +"]' "+checked+"/><label for='"+ vv.extra_service_kind +"'>"+vv.extra_service_cnname+"</label>&nbsp;";
							});
							
							if(k == 'C0') {
								product_extraservice_wrap+="<span id='insurance_value_div' style='display:none;'>投保金额：<input type='text' group='"+k+"' class='input_text insurance_value' value='' name='order[insurance_value1]' id='insurance_value' disabled style='width:30px;'/> USD&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:showInsurancExplan()'>投保须知</a></span>";
							}
							product_extraservice_wrap+="</label>";
						} else {
							v = v[0];
							var checked = v.checked?'checked':'';
							product_extraservice_wrap+="<label title='"+v.extra_service_cnname+"'><input type='checkbox' class='extra_service' group='"+k+"' value='"+v.extra_service_kind+"' name='extraservice[" + k +"]' "+checked+"/>"+v.extra_service_cnname+"</label>";
						}
						
					})
					
					$('#product_extraservice_wrap').html(product_extraservice_wrap);

					check_extra_service();
				}
			}
		});		
	});
	
	$('.group_checkbox').live('click', function(obj){
		if($(this).is(':checked')) {
			$(this).parents('label').find("input[type='radio']")
				.attr("disabled",false)
				.attr("checked", false);
			
			$(this).find("input[type='text']")
				.attr("disabled",false);
		} else {
			$(this).parents('label')
				.find("input[type='radio']")
				.attr("disabled",true)
				.attr("checked", false);
			
			$(this).find("input[type='text']")
				.attr("disabled",true)
				.val("");
			
			$('#insurance_value_div').hide();
			$('#insurance_value_div #insurance_value').attr('disabled',true);
			$('#insurance_value_div #insurance_value').val('');
		}
	});

	$('#country_code').live('change',function(){
		var product_code = $('#product_code').val();
		var country_code = $('#country_code').val();
		var order_id = $('#order_id').val();
		if($.trim(order_id)==''){
			order_id='';
		}
		$('#product_extraservice_wrap').html('');
		if($.trim(product_code)==''){
			return;
		}
		if($.trim(country_code)==''){
			return;
		}
		var tip_id='eeee';
		// loadStart(tip_id);
		// 必填项
		$.ajax({
			type: "POST",
			url: "/order/product-rule/web-required",
			data: {'product_code':product_code,'country_code':country_code,'order_id':order_id},
			dataType:'json',
			success: function(json){
				var html = json.message;
				// loadEnd(html,tip_id);
				if(json.ask){
					 $('.msg').text('');
					 $(':input').removeClass('web_require');
					 $.each(json.data,function(k,v){
						 $('.'+v+' ~ .msg').text('*');
						 $('.'+v).addClass('web_require');
					 });
				}else{
					 
				}

			}
		});		
	});
	$('#product_code').live('change',function(){
		var product_code = $(this).val();
		var options = "<option value=''  class='ALL'>-请选择-</option>";
		$('#country_code').html(options);
		if($.trim(product_code)==''){
			return;
		}
		var tip_id='bbbbbbb';
		// loadStart(tip_id);
		// 获取支持国家
		$.ajax({
			type: "POST",
			url: "/order/product-rule/get-country",
			data: {'product_code':product_code},
			dataType:'json',
			success: function(json){
				// loadEnd('',tip_id);
				var html = json.message;
				if(json.ask){
					 var default_v = $('#country_code').attr('default');
					 options = '';
					 if(json.data.length>1){
						 options = "<option value=''  class='ALL'>-请选择-</option>";						 
					 }
					 // alert(json.data.length);
					 $.each(json.data,function(k,v){
						 options+="<option value='"+v.country_code+"' class='"+v.country_code+"'>"+v.country_code+" ["+v.country_cnname+"  "+v.country_enname+"]</option>";
					 });
					 $('#country_code').html(options);
					 
					 setTimeout( function(){
						 $('#country_code').chosen('destroy');
						 $('#country_code').chosen({search_contains:true});
					 },20);
					 setTimeout(function(){
						 $('#country_code').val(default_v);
						 $('#country_code').change();
					 },10)
					 
				}else{
					 
				}

			}
		});		
	});

	$('.invoice_unitcharge,.quantity').live('keyup',function(){
		var tr = $(this).parent().parent();
		var invoice_quantity = $('.quantity',tr).val();
		var invoice_unitcharge = $('.invoice_unitcharge',tr).val();
		var quantity=parseFloat(invoice_unitcharge)*parseFloat(invoice_quantity);
		var totalQuantity=isNaN(quantity)?0:quantity;
		$('.totalcharge',tr).html(totalQuantity);
		// alert($(this).val());
	});
	
    // 新增重量=============
	
	$('.weight,.quantity').live('keyup',function(){
		
		var tr = $(this).parent().parent();
		var invoice_quantity = $('.quantity',tr).val();
		var invoice_weight = $('.weight',tr).val();
		var weight=parseFloat(invoice_weight)*parseFloat(invoice_quantity);
	    var totalWeight=isNaN(weight)?0:weight;
		$('.totalWeight',tr).html(totalWeight);
		// alert($(this).val());
	});
	

	$(".orderSubmitBtn").click(function(){
		var status = $(this).attr('status');// D,P
		var tip = '';
		var title = $(this).val()+"?";
		
		// 保险必须填价值
		if($("input[name='extraservice[C0]']:checked").size() > 0 && $('#insurance_value_div #insurance_value').val() == ''&& $("#C2:checked").size() > 0) {
			$('#insurance_value_div #insurance_value').focus();
			alert("投保金额不能为空!");
			return;
		}
		
		/*if(($("#C0").attr("checked")==true)  && ($("#C1:checked").size() <= 0 || $("#C3:checked").size() <= 0)){
			$('#insurance_value_div #insurance_value').focus();
			alert("请选择保险类型!");
			return;
		}*/
		//敏感货物
		if($("#M1:checked").size() > 0 && $("input[name='extraservice[M1]']:checked").size() <= 0) {
			alert("请选择敏感货物类型");
			return;
		}
		
		var shipper_hawbcode = $('#shipper_hawbcode').val();
// if($.trim(shipper_hawbcode)==''){
// tip+='客户单号未填写,系统将自动分配一个单号,';
// }
		tip+='你确定该操作吗？'; 
		
		jConfirm(tip, title, function(r) {
			if(r){
				formSubmit(status);
			}
		});			
	});
	
	// $('.input_text').val('1');
	$('.extra_service').live('click',function(){
		check_extra_service();
	})
});
$(function(){
	$('select').each(function(){
		$(this).val($(this).attr('default'));
	});
	// $('.msg').html('*');
});

function getTipTpl(){
	return $('<div class="info" style=""><span class="Validform_checktip Validform_wrong"></span><span class="dec"><s class="dec1">◆</s><s class="dec2">◆</s></span></div>');
}
	$('#shipper_hawbcode').blur(function(){
		var order_id = $('#order_id').val();
		var refrence_no = $(this).val();
		var this_ = $(this);
		$.ajax({
			type: "POST",
			url: "/order/order/check-refrence-no",
			data: {'order_id':order_id,'refrence_no':refrence_no},
			dataType:'json',
			success: function(json){
				// loadEnd('',tip_id);
				var html = json.message;
				if(!json.ask){
					this_.siblings('.msg').text(html);
				}else{
					this_.siblings('.msg').text(html);
				}
			}
		});	
	});

	$(function(){
		// alert(0);
		$('#product_code').chosen({width:'300px',search_contains:true});
		$('#country_code').chosen({search_contains:true});
	});

	
	// 新增==============

	// 体积错误提示
		function vol_tip(obj,reg,msg){	
			var reg = reg;	
			var val = $(obj).val();
			var msg =msg;
			
			if(val==''){
				return;
			}
			var tip = getTipTpl();
			if($(obj).prev('.info').size()==0){
				$(obj).prev().after(tip);
			}
			
			var tip = $(obj).prev('.info');
			if(!reg.test(val)){
				$('.Validform_checktip',tip).text(msg);	
				var left = $(obj).position().left;
				var top = $(obj).position().top;
				tip.css({'left':(left-12),'top':(top-3)}).show();		
			}else{
				tip.hide();
			}
			
	}


	// 通用错误提示
		function err_tip(obj,reg,msg){
			
			var reg = reg; 
			var val = $(obj).val();
			if(val==''){
				return;
			}
			var tip = getTipTpl();
			if($(obj).siblings('.info').size()==0){
				$(obj).parent().prepend(tip);
			}else{
				
			}
			var tip = $(obj).siblings('.info');
			//console.log(val);
			//console.log(reg.test(val));
			if(!reg.test(val)){
				$('.Validform_checktip',tip).text(msg);	
				tip.show();			
			}else{
				tip.hide();
			}
			
		
	}

//校验规则		

function rule_check_length(data,minlen,maxlen){
	var datalen	= data.length;
	if(minlen>0){
		return datalen>=minlen;
	}
	if(maxlen>0){
		return datalen<=maxlen;
	}
	return true;
}
function rule_check_zm(data){
	return /^[A-Za-z]+$/.test(data);
}
function rule_check_hanzi(data){
	return /[\u4e00-\u9fa5]+/.test(data);
}
function rule_check_num(data){
	return /^[0-9]+$/.test(data);
}		

//公司
$('.checkchar').live('keyup',function(){
	err_tip(this,/^[a-zA-Z0-9\s]{1,36}$/,'不允许出现非英文允许英文数字混合,长度最多36字符');
})
//收件人
$('.checkchar1').live('keyup',function(){
	err_tip(this,/^[a-zA-Z\s]{1,36}$/,'不允许出现非英文，长度最多36字符');
})
//城市 
$('.checkchar3').live('keyup',function(){
	err_tip(this,/^[a-zA-Z\s]+$/,'不允许出现非英文');
})

//地址
$('.checkchar2').live('keyup',function(){
	err_tip(this,/^[\w\W]{0,36}$/,'长度最多36字符');
})

// 体积
$('.order_volume').live('keyup',function(){
	vol_tip(this,/^\d+(\.\d)?$/,'须为数字,且小数最多为1位');
})

// 电话
$('.order_phone').live('keyup',function(){
    //err_tip(this,/^\(\d+\)\d+-\d+$|^\d+\s\d+$/,'格式为(xxx)xxx-xxx 或xxx空格xxxxx');
	err_tip(this,/^(\d){4,25}$/,'格式为4-25位纯数字');
})

// 申报价值
   $('.invoice_unitcharge').live('keyup',function(){
	var reg = /^\d+(\.\d{1,2})?$/;
	err_tip(this,reg,'须为数字,且小数最多为2位');		
})

// 重量
$('.weight').live('keyup',function(){
	var reg = /(^0\.[5-9]$)|(^[1-9]\d{0,5}(\.\d)?$)/;
	err_tip(this,reg,'须为数字,且小数最多为1位,范围为0.5-999999.9');	
})

// 数量
$('.quantity').live('keyup',function(){
	var reg = /^[1-9][0-9]?$/;
	err_tip(this,reg,'须为正整数，范围为1-99');
})
	
	// ==============结束



</script>

<div class="location">
    		<p><a href="javascript:void(0)">在线订单</a> > 
    		<a href="javascript:void(0)" onclick="leftMenu('order-list','订单管理','/order/order-list/list?quick=39')">订单管理</a>
    		 > ESB录单</p>
    	</div>
    	<ul class="tabnav">
    		<li class="on"><a href="javascript:void(0)">ESB录单</a></li>
    		<li><a href="javascript:void(0)" onclick="leftMenu('order-list','订单管理','/order/order-list/list?quick=39')">订单管理</a></li>
    		<!--<li><a href="">上传记录</a></li>-->
    	</ul>
    	<div class="layerbody">
    		<div class="layTit">
    			<h3>基本信息</h3>
    		</div>
    		<form method="POST" action="" onsubmit="return false;" id="orderForm">
    		<table class="layTable" border="0" cellpadding="0" cellspacing="0">
    			<tr>
    				<td><p><i>*</i> 运输方式 : </p><select class='input_select product_code'
							name='order[product_code]'
							default='<?php if (isset($_smarty_tpl->tpl_vars['order']->value)){?><?php echo $_smarty_tpl->tpl_vars['order']->value['product_code'];?>
<?php }?>'
							id='product_code'>
								<option value='' class='ALL'><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
-select-<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</option>
								<?php  $_smarty_tpl->tpl_vars['c'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['c']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['productKind']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['c']->key => $_smarty_tpl->tpl_vars['c']->value){
$_smarty_tpl->tpl_vars['c']->_loop = true;
?>
								<option value='<?php echo $_smarty_tpl->tpl_vars['c']->value['product_code'];?>
'><?php echo $_smarty_tpl->tpl_vars['c']->value['product_code'];?>

									[<?php echo $_smarty_tpl->tpl_vars['c']->value['product_cnname'];?>
 <?php echo $_smarty_tpl->tpl_vars['c']->value['product_enname'];?>
]</option>
								<?php } ?>
						</select></td>
    				<td><p><i>*</i> 收件人国家 : </p><select class='input_select country_code'
							name='order[country_code]'
							default='<?php if (isset($_smarty_tpl->tpl_vars['order']->value)){?><?php echo $_smarty_tpl->tpl_vars['order']->value['country_code'];?>
<?php }?>'
							id='country_code' style='width: 400px; max-width: 400px;'>
								<option value='' class='ALL'><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
-select-<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</option>
								<!-- 
								<?php  $_smarty_tpl->tpl_vars['c'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['c']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['country']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['c']->key => $_smarty_tpl->tpl_vars['c']->value){
$_smarty_tpl->tpl_vars['c']->_loop = true;
?>
								<option value='<?php echo $_smarty_tpl->tpl_vars['c']->value['country_code'];?>
' country_id='<?php echo $_smarty_tpl->tpl_vars['c']->value['country_id'];?>
' class='<?php echo $_smarty_tpl->tpl_vars['c']->value['country_code'];?>
'><?php echo $_smarty_tpl->tpl_vars['c']->value['country_code'];?>
 [<?php echo $_smarty_tpl->tpl_vars['c']->value['country_name'];?>
  <?php echo $_smarty_tpl->tpl_vars['c']->value['country_name_en'];?>
]</option>
								<?php } ?>
								 -->
						</select></td>
    			</tr>
    			<tr>
    				<td><p>客户单号 : </p><input type="text" value='<?php if (isset($_smarty_tpl->tpl_vars['order']->value)){?><?php echo $_smarty_tpl->tpl_vars['order']->value['refer_hawbcode'];?>
<?php }?>'
							name='order[refer_hawbcode]' id='refer_hawbcode' /></td>
    				<td><p>货物重量 : </p><input type="text" class="weight" value='<?php if (isset($_smarty_tpl->tpl_vars['order']->value)){?><?php echo $_smarty_tpl->tpl_vars['order']->value['order_weight'];?>
<?php }?>'
							name='order[order_weight]' id='order_weight' /></td>
    			</tr>
    			<tr>
    				<td><p>外包装件数 : </p><input type="text" class="quantity" value='<?php if (isset($_smarty_tpl->tpl_vars['order']->value)){?><?php echo $_smarty_tpl->tpl_vars['order']->value['order_pieces'];?>
<?php }else{ ?>1<?php }?>'
							name='order[order_pieces]' id='order_pieces' /></td>
    				<td><p>包裹申报种类: </p>
    				
    				<select
							default="<?php if (isset($_smarty_tpl->tpl_vars['order']->value)){?><?php echo $_smarty_tpl->tpl_vars['order']->value['mail_cargo_type'];?>
<?php }?>"
							name="order[mail_cargo_type]"
							class="input_select mail_cargo_type" id='mail_cargo_type'>
								<option value='' class='ALL'><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
-select-<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</option>
								<?php  $_smarty_tpl->tpl_vars['s'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['s']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['mailCargoTypes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['s']->key => $_smarty_tpl->tpl_vars['s']->value){
$_smarty_tpl->tpl_vars['s']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['s']->key;
?>
								<option value="<?php echo $_smarty_tpl->tpl_vars['s']->value['mail_cargo_code'];?>
"><?php echo $_smarty_tpl->tpl_vars['s']->value['mail_cargo_enname'];?>
(<?php echo $_smarty_tpl->tpl_vars['s']->value['mail_cargo_cnname'];?>
)</option>
								<?php } ?>
						</select></td>
    			</tr>
    			<tr>
    				<td colspan="2">
    				<p>体积 : </p>
    				<div class="goodvolume">
    				<p><input type="text" class="order_volume"  onfocus="if (value =='长'){value =''}" onblur="if (value ==''){value='长'}" name='order[order_length]' id='order_length'  
							value='<?php if (isset($_smarty_tpl->tpl_vars['order']->value)){?><?php echo $_smarty_tpl->tpl_vars['order']->value['length'];?>
<?php }else{ ?>长<?php }?>' /> CM</p>
    				<p><input class="order_volume" type="text"  onfocus="if (value =='宽'){value =''}" onblur="if (value ==''){value='宽'}" name='order[order_width]' id='order_width'
							value='<?php if (isset($_smarty_tpl->tpl_vars['order']->value)){?><?php echo $_smarty_tpl->tpl_vars['order']->value['width'];?>
<?php }else{ ?>宽<?php }?>' /> CM</p>
    				<p><input type="text" class="order_volume"  onfocus="if (value =='高'){value =''}" onblur="if (value ==''){value='高'}" name='order[order_height]'id='order_height'
							value='<?php if (isset($_smarty_tpl->tpl_vars['order']->value)){?><?php echo $_smarty_tpl->tpl_vars['order']->value['height'];?>
<?php }else{ ?>高<?php }?>' /> CM</p>
    				</div>
    				</td>
    			</tr>

    		</table>
    	</div>

    	<div class="layerbody">
    		<div class="layTit">
    			<h3>收件人信息</h3>
    		</div>
    		<table class="layTable" border="0" cellpadding="0" cellspacing="0">
    			<tr>
    				<td><p>公司名 : </p><input class="checkchar" type="text" value="<?php if (isset($_smarty_tpl->tpl_vars['shipperConsignee']->value)){?><?php echo $_smarty_tpl->tpl_vars['shipperConsignee']->value['consignee_company'];?>
<?php }?>"
							name='consignee[consignee_company]' id='consignee_company' /></td>
    				<td><p><i>*</i> 收件人 : </p><input type="text" class="checkchar3" value="<?php if (isset($_smarty_tpl->tpl_vars['shipperConsignee']->value)){?><?php echo $_smarty_tpl->tpl_vars['shipperConsignee']->value['consignee_name'];?>
<?php }?>"
							name='consignee[consignee_name]' id='consignee_name' /></td>
    			</tr>
    			<tr>
    				<td><p>收件人省/州 : </p><input class="checkchar1" type="text" placeholder='如果国家没有州应不填写' value="<?php if (isset($_smarty_tpl->tpl_vars['shipperConsignee']->value)){?><?php echo $_smarty_tpl->tpl_vars['shipperConsignee']->value['consignee_province'];?>
<?php }?>"
							name='consignee[consignee_province]' id='consignee_province' /></td>
    				<td><p>收件人电话 : </p><input type="text" value='<?php if (isset($_smarty_tpl->tpl_vars['shipperConsignee']->value)){?><?php echo $_smarty_tpl->tpl_vars['shipperConsignee']->value['consignee_telephone'];?>
<?php }?>'
							name='consignee[consignee_telephone]' id=consignee_telephone /></td>
    			</tr>
    			<tr>
    				<td><p>收件人城市 : </p><input type="text" class="checkchar1"  value="<?php if (isset($_smarty_tpl->tpl_vars['shipperConsignee']->value)){?><?php echo $_smarty_tpl->tpl_vars['shipperConsignee']->value['consignee_city'];?>
<?php }?>"
							name='consignee[consignee_city]' id='consignee_city' /></td>
    				<td><p>收件人手机 : </p><input class="order_phone" type="text" value='<?php if (isset($_smarty_tpl->tpl_vars['shipperConsignee']->value)){?><?php echo $_smarty_tpl->tpl_vars['shipperConsignee']->value['consignee_mobile'];?>
<?php }?>'
							name='consignee[consignee_mobile]' id='consignee_mobile' /></td>
    			</tr>
    			<tr>
    				<td><p>收件人邮箱 : </p><input type="text"  value='<?php if (isset($_smarty_tpl->tpl_vars['shipperConsignee']->value)){?><?php echo $_smarty_tpl->tpl_vars['shipperConsignee']->value['consignee_email'];?>
<?php }?>'
							name='consignee[consignee_email]' id='consignee_email' /></td>
    				<td><p>邮编 : </p><input type="text" value='<?php if (isset($_smarty_tpl->tpl_vars['shipperConsignee']->value)){?><?php echo $_smarty_tpl->tpl_vars['shipperConsignee']->value['consignee_postcode'];?>
<?php }?>'
							name='consignee[consignee_postcode]' id='consignee_postcode' /></td>
    			</tr>
    			<tr>
    				<td><p>地址1 : </p><input type="text" class="checkchar2" value="<?php if (isset($_smarty_tpl->tpl_vars['shipperConsignee']->value)){?><?php echo $_smarty_tpl->tpl_vars['shipperConsignee']->value['consignee_street'];?>
<?php }?>"
							name='consignee[consignee_street]' id='consignee_street' /></td>
    				<td><p><i>*</i> 收件人门牌号 : </p><input type="text" value='<?php if (isset($_smarty_tpl->tpl_vars['shipperConsignee']->value)){?><?php echo $_smarty_tpl->tpl_vars['shipperConsignee']->value['consignee_doorplate'];?>
<?php }?>'
							name='consignee[consignee_doorplate]' id='consignee_doorplate' /></td>
    			</tr>
    			<tr>
    				<td><p>地址2 : </p><input type="text" class="checkchar2" value="<?php if (isset($_smarty_tpl->tpl_vars['shipperConsignee']->value)){?><?php echo $_smarty_tpl->tpl_vars['shipperConsignee']->value['consignee_street2'];?>
<?php }?>"
							name='consignee[consignee_street2]' id='consignee_street2' /></td>
    				<td><p>地址3 : </p><input type="text" class="checkchar2" value="<?php if (isset($_smarty_tpl->tpl_vars['shipperConsignee']->value)){?><?php echo $_smarty_tpl->tpl_vars['shipperConsignee']->value['consignee_street3'];?>
<?php }?>"
							name='consignee[consignee_street3]' id='consignee_street3' /></td>
    			</tr>
    			<tr>
    				<td colspan="2">
    				<p>证件类型 : </p>
    				<div class="papers">
	    				<div class="labelss">
	    				<select
							name="consignee[consignee_certificatetype]"
							class="input_select consignee_certificatetype"
							id='consignee_certificatetype'
							default='<?php if (isset($_smarty_tpl->tpl_vars['shipperConsignee']->value)){?><?php echo $_smarty_tpl->tpl_vars['shipperConsignee']->value['consignee_certificatetype'];?>
<?php }?>'>
								<?php  $_smarty_tpl->tpl_vars['s'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['s']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['certificates']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['s']->key => $_smarty_tpl->tpl_vars['s']->value){
$_smarty_tpl->tpl_vars['s']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['s']->key;
?>
								<option value="<?php echo $_smarty_tpl->tpl_vars['s']->value['certificate_type'];?>
"><?php echo $_smarty_tpl->tpl_vars['s']->value['certificate_type_enname'];?>
(<?php echo $_smarty_tpl->tpl_vars['s']->value['certificate_type_cnname'];?>
)</option>
								<?php } ?>
						</select>
	    				</div>
    					<div class="labelss"><p>号码 : </p><input type="text" value='<?php if (isset($_smarty_tpl->tpl_vars['shipperConsignee']->value)){?><?php echo $_smarty_tpl->tpl_vars['shipperConsignee']->value['consignee_certificatecode'];?>
<?php }?>'
							name='consignee[consignee_certificatecode]'
							id='consignee_certificatecode' /></div>
	    				<div class="Validity labelss">
							<div class="col-md-4">
								<p>有效期 : </p><!--<input type="text" id="calendar1" value="2014" class="form-control">-->
								<input type='text'
							class='datepicker'
							value='<?php if (isset($_smarty_tpl->tpl_vars['shipperConsignee']->value)){?><?php echo $_smarty_tpl->tpl_vars['shipperConsignee']->value['consignee_credentials_period'];?>
<?php }?>'
							name='consignee[consignee_credentials_period]'
							id='consignee_credentials_period' />
							</div>
						</div>
    				</div>
    				</td>
    			</tr>

    		</table>
    	</div>
    	<div class="layerbody">
    		<div class="layTit">
    			<h3>海关申报信息 <i>*</i></h3>
    		</div>
    		<table class="layTable returntable" id="tab11" style="display: none;" border="0" cellpadding="0" cellspacing="0">
    		<tbody>
    			<tr>
    				<td><i>*</i> <input type="text" name='invoice[invoice_enname][]'/></td>
    				<td><i>*</i> <input type="text" name='invoice[invoice_cnname][]'/></td>
    				<td><i>*</i> <input type="text" class="quantity" name='invoice[invoice_quantity][]'/></td>
    				<td>
    					<select default="PCE" name="invoice[unit_code][]"
							> <?php  $_smarty_tpl->tpl_vars['s'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['s']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['units']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['s']->key => $_smarty_tpl->tpl_vars['s']->value){
$_smarty_tpl->tpl_vars['s']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['s']->key;
?>
								<option value="<?php echo $_smarty_tpl->tpl_vars['s']->value['unit_code'];?>
"><?php echo $_smarty_tpl->tpl_vars['s']->value['unit_enname'];?>
(<?php echo $_smarty_tpl->tpl_vars['s']->value['unit_cnname'];?>
)</option>
								<?php } ?>
						</select>
    				</td>
    				<td><i>*</i> <input type="text" class="invoice_unitcharge" name='invoice[invoice_unitcharge][]'></td>
    				<td class="totalcharge">0</td>
    				<td><i>*</i> <input type="text" class="weight" name='invoice[invoice_weight][]'></td>
    				<td class='totalWeight'>0</td>
    				<td><input type="text" name='invoice[sku][]' value='<?php echo $_smarty_tpl->tpl_vars['i']->value['sku'];?>
'></td>
    				<td><input type="text" name='invoice[hs_code][]'></td>
    				<td><input type="text" name='invoice[invoice_note][]'/></td>
    				<td><input type="text" name='invoice[invoice_url][]'/></td>
    				<td><a href="javascript:;" class="delete" onClick="deltr(this)">删除</a></td>
    			</tr>
    		</tbody>
    		</table>
    		<table class="layTable returntable" id="dynamicTable" border="0" cellpadding="0" cellspacing="0">
    		<thead>
    			<tr>
    				<th>英文品名</th>
    				<th>中文品名</th>
    				<th>数量</th>
    				<th>单位</th>
    				<th>单价($)</th>
    				<th>总价</th>
    				<th>重量(kg)</th>
    				<th>总重</th>
    				<th>SKU</th>
    				<th>海关协制编号</th>
    				<th>配货信息</th>
    				<th>销售地址</th>
    				<th>操作</th>
    			</tr>
    		</thead>
    		<tbody>
    			<?php if ($_smarty_tpl->tpl_vars['invoice']->value){?> <?php  $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['i']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['invoice']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['i']->key => $_smarty_tpl->tpl_vars['i']->value){
$_smarty_tpl->tpl_vars['i']->_loop = true;
?>
    			<tr class="table-module-b1">
						<td><input type="text"
							name='invoice[invoice_enname][]' value='<?php echo $_smarty_tpl->tpl_vars['i']->value['invoice_enname'];?>
'> </td>
						<td><input type='text' 
							name='invoice[invoice_cnname][]' value='<?php echo $_smarty_tpl->tpl_vars['i']->value['invoice_cnname'];?>
'></td>
						<td><input type='text' class="quantity" 
							name='invoice[invoice_quantity][]' value='<?php echo $_smarty_tpl->tpl_vars['i']->value['invoice_quantity'];?>
'> </td>
						<td><select default="<?php if ($_smarty_tpl->tpl_vars['i']->value['unit_code']){?><?php echo $_smarty_tpl->tpl_vars['i']->value['unit_code'];?>
<?php }else{ ?>PCE<?php }?>" name="invoice[unit_code][]" >
								<?php  $_smarty_tpl->tpl_vars['s'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['s']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['units']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['s']->key => $_smarty_tpl->tpl_vars['s']->value){
$_smarty_tpl->tpl_vars['s']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['s']->key;
?>
								<option value="<?php echo $_smarty_tpl->tpl_vars['s']->value['unit_code'];?>
"><?php echo $_smarty_tpl->tpl_vars['s']->value['unit_enname'];?>
(<?php echo $_smarty_tpl->tpl_vars['s']->value['unit_cnname'];?>
)</option>
								<?php } ?>
							</select></td>
						<td><input type='text'  class="invoice_unitcharge"
							name='invoice[invoice_unitcharge][]'
							value='<?php echo $_smarty_tpl->tpl_vars['i']->value['invoice_unitcharge'];?>
'></td>
						<td class="totalcharge"><?php echo $_smarty_tpl->tpl_vars['i']->value['invoice_totalcharge'];?>
</td>
							
							<!--  -->
							<td><input type='text' class="weight"
							name='invoice[invoice_weight][]' value='<?php echo $_smarty_tpl->tpl_vars['i']->value['invoice_weight'];?>
'>
						    </td>
						    <td class='totalWeight'><?php echo $_smarty_tpl->tpl_vars['i']->value['invoice_totalWeight'];?>

						    </td>
							<!--  -->
							
						<td><input type='text' 
							name='invoice[sku][]' value='<?php echo $_smarty_tpl->tpl_vars['i']->value['sku'];?>
'> 
						</td>
						<td><input type='text' 
							name='invoice[hs_code][]' value='<?php echo $_smarty_tpl->tpl_vars['i']->value['hs_code'];?>
'> </td>
						<td><input type='text' 
							name='invoice[invoice_note][]' value='<?php echo $_smarty_tpl->tpl_vars['i']->value['invoice_note'];?>
'> </td>
						<td><input type='text' 
							name='invoice[invoice_url][]' value='<?php echo $_smarty_tpl->tpl_vars['i']->value['invoice_url'];?>
'> </td>
						<td><a href='javascript:;' class='delInvoiceBtn'><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
delete<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</a>
						</td>
					</tr>
    			
    			<?php } ?> <?php }else{ ?>
	    			<tr>
	    				<td><i>*</i> <input type="text" name='invoice[invoice_enname][]'/></td>
	    				<td><i>*</i> <input type="text" name='invoice[invoice_cnname][]'/></td>
	    				<td><i>*</i> <input type="text" class="quantity" name='invoice[invoice_quantity][]'/></td>
	    				<td>
	    					<select default="PCE" name="invoice[unit_code][]"
								> <?php  $_smarty_tpl->tpl_vars['s'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['s']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['units']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['s']->key => $_smarty_tpl->tpl_vars['s']->value){
$_smarty_tpl->tpl_vars['s']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['s']->key;
?>
									<option value="<?php echo $_smarty_tpl->tpl_vars['s']->value['unit_code'];?>
"><?php echo $_smarty_tpl->tpl_vars['s']->value['unit_enname'];?>
(<?php echo $_smarty_tpl->tpl_vars['s']->value['unit_cnname'];?>
)</option>
									<?php } ?>
							</select>
	    				</td>
	    				<td><i>*</i> <input type="text" class="invoice_unitcharge" name='invoice[invoice_unitcharge][]'></td>
	    				<td class="totalcharge">0</td>
	    				<td><i>*</i> <input type="text" class="weight" name='invoice[invoice_weight][]'></td>
	    				<td class='totalWeight'>0</td>
	    				<td><input type="text" name='invoice[sku][]' value='<?php echo $_smarty_tpl->tpl_vars['i']->value['sku'];?>
'></td>
	    				<td><input type="text" name='invoice[hs_code][]'></td>
	    				<td><input type="text" name='invoice[invoice_note][]'/></td>
	    				<td><input type="text" name='invoice[invoice_url][]'/></td>
	    				<td><a href="javascript:;" class="delete" onClick="deltr(this)">删除</a></td>
	    			</tr>
    			
    			<?php }?>
    		
    			
    		</tbody>
    		</table>
    		<div class="addmore">
    			<a href="javascript:;" id="btn_addtr">点击新增</a>
    		</div>
    	</div>

    	<div class="layerbody">
    		<div class="layTit">
    			<h3>发件人信息 <i>*</i> <!--<a href="">刷新</a>--></h3>
    		</div>
    		<div class="TextList">
    			<?php  $_smarty_tpl->tpl_vars['s'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['s']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['shipperCustom']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['s']->key => $_smarty_tpl->tpl_vars['s']->value){
$_smarty_tpl->tpl_vars['s']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['s']->key;
?>
				<p>	
					<label><span class="inputsel"><input type="radio" name='consignee[shipper_account]'  value='<?php echo $_smarty_tpl->tpl_vars['s']->value['shipper_account'];?>
'<?php if ($_smarty_tpl->tpl_vars['s']->value['is_default']){?>checked<?php }?>/>
					</span> <span><?php echo $_smarty_tpl->tpl_vars['s']->value['shipper_company'];?>
</span>
					<span><?php echo $_smarty_tpl->tpl_vars['s']->value['shipper_countrycode'];?>
</span>
					<span><?php echo $_smarty_tpl->tpl_vars['s']->value['shipper_province'];?>
</span>
					<span><?php echo $_smarty_tpl->tpl_vars['s']->value['shipper_city'];?>
</span>
					<span><?php echo $_smarty_tpl->tpl_vars['s']->value['shipper_street'];?>
</span>
					<span><?php echo $_smarty_tpl->tpl_vars['s']->value['shipper_name'];?>
</span>
					<span><?php echo $_smarty_tpl->tpl_vars['s']->value['shipper_telephone'];?>
</span>
					
					<span><a href="javascript:;" class="open_btn" shipper_account="<?php echo $_smarty_tpl->tpl_vars['s']->value['shipper_account'];?>
">修改信息</a></span></label>
			   </p>
		<?php } ?>
    			<div class="addmore addlast">
    				<a href="javascript:;" class="open_btn">点击新增</a>
    			</div>
    			


    		</div>
    	</div>
<input type="hidden" name='order[order_id]'
				value='<?php if (isset($_smarty_tpl->tpl_vars['order']->value)&&$_smarty_tpl->tpl_vars['order']->value['order_id']){?><?php echo $_smarty_tpl->tpl_vars['order']->value['order_id'];?>
<?php }?>'
				id='order_id'>
    	<div class="sendBtns">
    		<input class="btns1 orderSubmitBtn" status='P' type="submit" value="提交预报" />
    		<input type="submit" orderSubmitDraftBtn value="保存草稿" status='D'/>
    	</div>

		</form>
		<form id="submiterForm">
		<div class="WindowBox">
    				<div class="bgshaow closebtn"></div>
    				<div class="NewBody">
    					<div class="Title">
    						<h3><a href="javascript:;" class="closebtn"></a> 新增发件人</h3>
    					</div>
    					<div class="NewMain">
    						<form>
    						<ul>
    							<li>
    								<span><i>*</i> 公司名：</span>
    								<input type="text" name="E3" value="" class="">
    							</li>
    							<li>
    								<span><i>*</i> 发件人姓名：</span>
    								<input type="text" name="E2" value="" class="">
    							</li>
    							<li>
    								<span><i>*</i> 发件人国家：</span>
    								<?php $_smarty_tpl->smarty->_tag_stack[] = array('ec', array('name'=>'E4','default'=>'Y','search'=>'N','class'=>'select1')); $_block_repeat=true; echo smarty_block_ec(array('name'=>'E4','default'=>'Y','search'=>'N','class'=>'select1'), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
country<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_ec(array('name'=>'E4','default'=>'Y','search'=>'N','class'=>'select1'), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

    								<input type="text" name="E5" value="省/州" onfocus="if (value =='省/州'){value =''}" onblur="if (value ==''){value='省/州'}" class="input_text checkchar3">
    								<input type="text" name="E6" value="城市" onfocus="if (value =='城市'){value =''}" onblur="if (value ==''){value='城市'}" class="input_text checkchar3">
    							</li>
    							<li>
    								<span><i>*</i> 详细地址：</span>
    								<input type="text" name="E7" value="" class="">
    							</li>
    							<li>
    								<span><i>*</i> 邮编：</span>
    								<input type="text" name="E8" value="" class="">
    							</li>
    							<li>
    								<span><i>*</i> 联系电话：</span>
    								<input type="text" name="E10" value="" class="">
    							</li>
    							<li class="defult">
    								<label><input type="checkbox" value="1" name="E17" class=" "> 设为默认地址</label>
    							</li>
    						</ul>
    							<div class="sendBtns">
    							<input type="hidden" name="E0" value="" class="input_text">
    								<input type="submit" class="btns1" id="orderSubmitBtn" value="提交" />
    							</div>
    						</form>
    					</div>
    				</div>
    			</div>
		</form>
		<script>
		$(function(){
		$('#product_code').change();
		
		$('#country_code').change();
		
		});
		$(".datepicker").datepicker({ dateFormat: "yy-mm-dd"});
		//发件人js
		$('.open_btn').click(function(){
			//判断是否是新增或者修改
			var that = $(this);
			var text = $(this).text();
			if(text=='修改信息'){
				editShipperAccount(that.attr('shipper_account'));		
			}else{
				$('.NewMain input').each(function(index,element){
					var name = $(element).attr("name");
					if(name){
						switch(name){
							case 'E5':$(element).val('省/州');break;
							case 'E6':$(element).val('城市');break;
							case 'E17':$(element).attr('checked', false);break;
							default : $(element).val('');break;
						
						}
					}
				});
			}
			$('.WindowBox').fadeIn(300);
		});
		
		function editShipperAccount(shipper_account){	 
    $.ajax({
		   type: "POST",
		   url: '/order/submiter/get-by-json',
		   data: {'paramId':shipper_account},
		   async: true,
		   dataType: "json",
		   success: function(json){
			    if(json.state){
                    $.each(json.data,function(k,v){
                        v +='';
                        $("#submiterForm :input[name='"+k+"']").val(v); 			    	    
                    })
                    $("#submiterForm :checkbox[name='E17']").val('1');
                    if(json.data.E17==1||json.data.E17=='1'){
                    	$("#submiterForm :checkbox[name='E17']").attr('checked', true);
                    }else{
                    	$("#submiterForm :checkbox[name='E17']").attr('checked', false);
                    }                    
			    }else{
			       alertTip(json.message);
			    }
		   }
	});
}
		
		$("#orderSubmitBtn").click(function(){
		loadStart();
		var params = $("#submiterForm").serialize();
		$.ajax({
		    type: "post",
		    async: false,
		    dataType: "json",
		    url: '/order/submiter/edit',
		    data: params,
		    success: function (json) {
		    	loadEnd();
		    	switch (json.state) {
		            case 1:
			            
		            case 2:
			            parent.getSubmiter();
		                parent.alertTip(json.message);
		                parent.$('.dialogIframe').dialog('close');
		                break;
		            default:
		                var html = '';
		                if (json.errorMessage == null)return;
		                $.each(json.errorMessage, function (key, val) {
		                    html += '<span class="tip-error-message">' + val + '</span>';
		                });
		                alertTip(html);
		                break;
		        }
		    }
		});
	});
		
		$('.closebtn').click(function(event) {
		  $('.WindowBox').fadeOut(300);
		});
		
		</script>
		<?php }} ?>