<?php
class WC_Paysepro_Gateway extends WC_Payment_Gateway {
	public function __construct() {
    	$this->id			      	= 'paysepro';
		$this->icon 		    	= apply_filters('woocommerce_paysepro_icon',plugins_url() . "/" . plugin_basename(dirname(__FILE__)) . '/assets/paysepro.png');
		$this->has_fields 		= false; // 
		$this->method_title   = __( 'Paysepro', "paysepro_wc" );
		$this->init_form_fields();
		$this->init_settings();
	    $this->title 		  			= apply_filters( 'woopaysepro_title', __( 'Paysepro','paysepro_wc') );
		$this->description    			= apply_filters( 'woopaysepro_description', __( 'Paysepro offers you worldwide coverage with a complete payment solution.','paysepro_wc' ) );
	    $this->serviceID      			= $this->get_option('serviceID') ;
	    $this->moduleID       			= $this->get_option('moduleID') ;
		$this->store       	  			= $this->get_option('store') ;
		$this->supportEmail     		= $this->get_option('supportEmail') ;
		$this->productDescription       = $this->get_option('productDescription') ;
		$this->typeID      				= $this->get_option('typeID') ;
	    $this->colorID      			= $this->get_option('colorID') ;
	    $this->lastStatus      			= $this->get_option('lastStatus') ;
	    add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
	    add_action( 'woocommerce_receipt_paysepro', array( $this, 'receipt_page' ) );
		add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array( &$this, 'paysepro_ipn_response') );
	    add_action ('woocommerce_thankyou',array($this,'order_received'),1); 
	}
  //////////////////////////////////////////////////////////////////////  
  // 
 	function paysepro_ipn_response()
	{
		global $woocommerce; // VAR GLOB
		$get_filtered = filter_input_array(INPUT_GET);
		$order_id = $get_filtered['ucode'];  				 //receive order ID from paysepro
		$order = new WC_Order( $order_id );
		$service_id = $this->serviceID;  	 				 //var id service
		$module_id = $this->moduleID;  		 				 //var id module
		$store = $this->store;  		 			 		 //var store name
		$support_email = $this->supportEmail;  		 		 //var support email
		$product_description = $this->productDecription;  	 //var product description	
		$type_id = $this->typeID;			//var paymentbox type
		$color_id = $this->colorID;			//var button color
		$lastStatus = $this->lastStatus;	//var button color
		$status = $order->get_status();		//var status  order
		$result = $get_filtered["result"];
		if(!in_array($_SERVER['REMOTE_ADDR'],array('51.195.200.178')))
		{
			exit;
		}
     ///
     if ($status!="cancelled") // != cancelled
     {
        if( ($get_filtered['price'] == $order->get_total() and strtolower($get_filtered['currency']) == strtolower(get_woocommerce_currency()) ) and $get_filtered['uid'] == $service_id and $get_filtered['mid'] == $module_id)
		{
			if($result=="ok")
			{
				$order->update_status('processing');
				if($lastStatus=="completed"||$lastStatus==1) $order->update_status('completed');
				$order->reduce_order_stock();
				$woocommerce->cart->empty_cart();
			} else {
				$order->update_status('processing');
				$order->update_status('cancelled');
			}
		}
	} 
} 
  //////////////////////////////////////////////////////////////////////                                                                     
  function init_form_fields() {
     // 
     $this->form_fields = array(
    	'enabled' => array(
    		'title' => __( 'Enable/Disable', 'paysepro_wc' ),
    		'type' => 'checkbox',
    		'label' => __( 'Enable Paysepro payments', 'paysepro_wc' ),
    		'default' => 'yes'
    	),
    	'serviceID' => array(
    		'title' => __( 'API UID', 'paysepro_wc' ),
    		'type' => 'text',
    		'description' => __( 'This is the ID of your Paysepro account.', 'paysepro_wc' ),
    		'default' => 0,
    		'desc_tip'      => true,
    	),
    	'moduleID' => array(
    		'title' => __( 'API MID', 'paysepro_wc' ),
    		'type' => 'text',
    		'description' => __( 'This is the ID of your Paysepro module.', 'paysepro_wc' ),
    		'default' => 0,
    		'desc_tip'      => true,
		),
    	'colorID' => array(
    		'title' => __( 'Button Color', 'paysepro_wc' ),
    		'type' => 'select',
    		'description' => __( 'This is the color of the button.', 'paysepro_wc' ),
    		'default' => 1,
    		'desc_tip'      => true,
			'options' => Array(
				1=>__( 'white', 'paysepro_wc' ),
				2=>__( 'green', 'paysepro_wc' ),
				3=>__( 'black', 'paysepro_wc' ),
			),
    	),
    	'typeID' => array(
    		'title' => __( 'Show local price', 'paysepro_wc' ),
    		'type' => 'select',
    		'description' => __( 'Show the price in local currency.', 'paysepro_wc' ),
    		'default' => 1,
    		'desc_tip'      => true,
			'options' => Array(
				0=>__( 'no', 'paysepro_wc' ),
				1=>__( 'yes', 'paysepro_wc' ),
			),
    	),

    	'supportEmail' => array(
    		'title' => __( 'Support Email', 'paysepro_wc' ),
    		'type' => 'text',
    		'description' => __( 'This is the support email.', 'paysepro_wc' ),
    		'default' => '',
    		'desc_tip'      => true,
    	),		
    	'store' => array(
    		'title' => __( 'Store Name', 'paysepro_wc' ),
    		'type' => 'text',
    		'description' => __( 'This is the name of your store.', 'paysepro_wc' ),
    		'default' => '',
    		'desc_tip'      => true,
    	),		
    	'productDescription' => array(
    		'title' => __( 'Product Description', 'paysepro_wc' ),
    		'type' => 'textarea',
    		'description' => __( 'This is the description of the product.', 'paysepro_wc' ),
    		'default' => '',
    		'desc_tip'      => true,
		),

    	'lastStatus' => array(
    		'title' => __( 'Status after payment', 'paysepro_wc' ),
    		'type' => 'select',
    		'description' => __( 'Select the last status the order will get after payment received.', 'paysepro_wc' ),
    		'default' => 'processing',
			'desc_tip'      => true,
			'options' => Array(
				0=>__( 'processing', 'paysepro_wc' ),
				1=>__( 'completed', 'paysepro_wc' ),
			),
    	),

    );
  }
  //////////////////////////////////////////////////////////////////////   
  public function admin_options() {
    // 
?>
		<h3><?php _e( 'Paysepro', 'paysepro_wc' ); ?></h3> 	
		<table class="form-table">
<?php $this->generate_settings_html(); ?>
			<tr valign="top">
				<th scope="row" class="titledesc"><?php _e('Payments notification URL (IPN)','paysepro_wc');?></th>
				<td class="forminp"><b><?php _e(home_url( '/' ),'paysepro_wc'); ?>?wc-api=WC_Paysepro_Gateway</b><br></td>
			</tr>
		</table>
<?php
	 }
 
  //////////////////////////////////////////////////////////////////////   
   function receipt_page($order) {
    echo '<p>'.__( 'Click the Paysepro button to proceed with your purchase', "paysepro_wc" ).'</p>';
    echo $this->generate_paysepro_form($order); 
	}
  //////////////////////////////////////////////////////////////////////////////
function generate_paysepro_form($orderID)
{
	// Paysepro form 
	global $woocommerce;
    $order = new WC_Order($orderID);
  	$paysepro_args       = $this->prepare_args($order);

    foreach ($paysepro_args as $key => $value) { 
    	$paysepro_args_array[] = '<input type="hidden" name="'.esc_attr( $key ).'" value="'.esc_attr( $value ).'" />';
	}
	$price=$paysepro_args["psp_price"];
	$extra=$orderID;
	$uid=$paysepro_args["psp_serviceid"];
	$mid=$paysepro_args["psp_moduleid"];
	$store=urlencode($paysepro_args["psp_store"]);
	$supportEmail=urlencode($paysepro_args["psp_support_email"]);
	$productDescription=urlencode($paysepro_args["psp_product_description"]);
	$type=($paysepro_args["psp_typeid"]=="yes"||$paysepro_args["psp_typeid"]==1)?"on":"off";
	$color=$paysepro_args["psp_colorid"];
	$color=$color==1?"white":($color==2?"green":"black");

	$customerName=$order->get_billing_first_name()." ".$order->get_billing_last_name();
	$customerEmail=$order->get_billing_email();

	$base_url="https://checkout.paysepro.com";
	
	$url=$base_url."?uid=$uid&mid=$mid&price=$price&ucode=$extra&name=$store&contact=$supportEmail&description=$productDescription&localprice=$type&cname=$customerName&cemail=$customerEmail";
	$r='<a href="'.$url.'" title="Pagar" target="_blank"><img src="'.$base_url.'/image.php?uid='.$uid.'&mid='.$mid.'&color=green" alt="pay" /></a>';

	return($r);
    }
    
  ////////////////////////////////////////////////////////////////////////////// 
  function prepare_args( $order ) {
    // Prepare Paysepro form parameters
		global $woocommerce;
		$orderID = $order->id;  // Assign order number               
    $shopOrderInfo = get_bloginfo('name').' | Order #'.$orderID; // Order information to be shown at the payment screen   // 
    add_query_arg( 'wc-api', 'WC_Paysepro_Gateway', home_url( '/' ) );
		$args = array (
				'psp_serviceid'				=> $this->serviceID,         	// Paysepro ID
				'psp_moduleid'				=> $this->moduleID,         	// Module ID			
				'psp_store'					=> $this->store,         		// Store Name
				'psp_support_email'			=> $this->supportEmail,         // Support Email
				'psp_product_description'	=> $this->productDescription,   // Product Description						
				'psp_typeid'				=> $this->typeID,         		// Payment Box Type
				'psp_colorid'				=> $this->colorID,         		// Button Color
				'psp_currency'				=> get_woocommerce_currency(),  // Currency
				'psp_name'					=> $shopOrderInfo,    			//   
        		'psp_custom'     			=> $orderID,       				// 
        		'psp_last_status'    		=> $this->lastStatus,  			// 
				'psp_price'					=> $order->get_total(), 		// 
    			'psp_return_url'			=> apply_filters( 'paysepro_param_urlOK', $this->get_return_url( $order )), // Success URL
				'psp_cancel_url'			=> apply_filters( 'paysepro_param_urlKO', $order->get_checkout_payment_url()) // Cancel URL            
		);		
		return $args;		
	} 
  ////////////////////////////////////////////////////////////////////////////// 
	function process_payment( $order_id ) {
		global $woocommerce; 
		$order = new WC_Order( $order_id );

		$order->reduce_order_stock();
		$woocommerce->cart->empty_cart();

		return array(
			'result' 	=> 'success',
			'redirect'	=> $order->get_checkout_payment_url( true )
		);
	} 
  ////////////////////////////////////////////////////////////////////////////// 
      function order_received($order_id){          
        $order = new WC_Order( $order_id );
        switch($order->get_status()){
          case 'completed':{
               wc_print_notice( __( 'Your order has been completed.', 'paysepro_wc' ), 'success' );
               break;
          }             
          case 'cancelled':{
               wc_print_notice( __( 'Your order cannot be completed.', 'paysepro_wc' ), 'error' );
               break;         
          } 
         default:{
              wc_print_notice( __( 'Your request is being processed, will be completed once it is confirmed by the local payment provider.', 'paysepro_wc' ), 'notice' );
          
          }
        }
   
    }
  ////////////////////////////////////////////////////////////////////////////// 

 }  
?>
