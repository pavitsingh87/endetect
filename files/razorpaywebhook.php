<?php
include("connection.php");
$webhookread_query = $conn->query("select * from U_webhookread where id='1'");
$webhookread_num = $transaction_query->num_rows;
if($webhookread_query->num_rows>0)
{
	$webhookRow = $webhookread_query->fetch_assoc();
}
$webhookread = $conn->query("select * from U_razorpaywebhooks where id>".$webhookRow["webhookid"]);
if($webhookread->num_rows>0)
{
	// last webhookid read
	while($webhookrow = $webhookread->fetch_assoc())
	{
		//print_r($webhookrow);
		$re  = json_decode($webhookrow["datasource"],true);
		//print_r($re);
		if($re["event"]!="")
		{
			// insert record for subscription records in U_transaction

			$subscriptionid 		= $re["payload"]["subscription"]["entity"]["id"];
			$status 				= $re["payload"]["subscription"]["entity"]["status"];
			$paymentcurrency		= $re["payload"]["payment"]["entity"]["currency"];
			$paymentstatus 			= $re["payload"]["payment"]["entity"]["status"];
			$order_id 				= $re["payload"]["payment"]["entity"]["order_id"];
			$email_id 				= $re["payload"]["payment"]["entity"]["email"];
			$create_at 				= $re["create_at"];
			$payment_id = $re["payload"]["payment"]["entity"]["id"];
			$transaction_query = $conn->query("select * from U_transaction where subscriberid='".$subscriptionid."' AND order_status='1' limit 1");
			$transaction_num = $transaction_query->num_rows;
			if($transaction_query->num_rows>0)
			{
				$transactionRow = $transaction_query->fetch_assoc();
			}
			if($transaction_num>0)
			{
				if($status=="completed")
				{
						// update U_transaction with U_transactionlist
						// update U_license with extended expiry date
						// search subscription id last 
						$transactionid = $conn->query("insert into U_transaction set 
						owner_id = '".$transactionRow["owner_id"]."',
						package_id = '".$transactionRow["package_id"]."',
						total_licenses='".$transactionRow["total_licenses"]."',
						package_type='".$transactionRow["package_type"]."',
						package_base_price='".$transactionRow["package_base_price"]."',
						total_base_price='".$transactionRow["total_base_price"]."',
						total_amount='".$transactionRow["total_amount"]."',
						promo_code='".$transactionRow["promo_code"]."',
						promo_discount='".$transactionRow["promo_discount"]."',
						total_after_discount='".$transactionRow["total_after_discount"]."',
						igst_percentage='".$transactionRow["igst_percentage"]."',
						igst_value = '".$transactionRow["igst_value"]."',
						cgst_percentage='".$transactionRow["cgst_percentage"]."',
						cgst_value='".$transactionRow["cgst_value"]."',
						sgst_percentage='".$transactionRow["sgst_percentage"]."',
						sgst_value='".$transactionRow["sgst_value"]."',
						total_amount_after_gst='".$transactionRow["total_amount_after_gst"]."',
						total_amount_received='".$transactionRow["total_amount_received"]."',
						ipaddress='".$transactionRow["ipaddress"]."',
						billing_name='".$transactionRow["billing_name"]."',
						billing_address='".$transactionRow["billing_address"]."',
						billing_city='".$transactionRow["billing_city"]."',
						billing_state='".$transactionRow["billing_state"]."',
						billing_zip='".$transactionRow["billing_zip"]."',
						billing_country='".$transactionRow["billing_country"]."',
						billing_email='".$transactionRow["billing_email"]."',
						billing_mobile='".$transactionRow["billing_mobile"]."',
						state_code='".$transactionRow["state_code"]."',
						gst_code='".$transactionRow["gst_code"]."',
						payment_date='".$transactionRow["payment_date"]."',
						created_date='".$transactionRow["created_date"]."',
						paymenttype='".$transactionRow["paymenttype"]."',
						lickey='".$transactionRow["lickey"]."',
						transaction_details='".$transactionRow["transaction_details"]."',
						adjustments='".$transactionRow["adjustments"]."',
						adjustmentstype='".$transactionRow["adjustmentstype"]."',
						duedate='".$transactionRow["duedate"]."',
						transactionref='".$transactionRow["transactionref"]."',
						transfer_type='".$transactionRow["transfer_type"]."',
						tcreatedby='RazorpayWebHook',
						order_id='".$order_id."',
						payment_id='".$payment_id."',
						subscriberid='".$subscriptionid."',
						order_status='1',
						paymenturl='".$transactionRow["paymenturl"]."'
						");	
						$transactionid = $conn->insert_id;


						if($transactionRow["lickey"]!="")
						{
								// if lickey is suspended activate the license key and add expiry date from last expiry date
								// check package
								$packageQuery = $conn->query("select * from package where id='".$transactionRow["package_id"]."'");	
								if($packageQuery->num_rows>0)
								{
									$packageRow = $packageQuery->fetch_assoc();
								}
								$licenseQuery = $conn->query("select * from U_license where lickey='".$transactionRow["lickey"]."'");	
								if($licenseQuery->num_rows>0)
								{
									$licenseRow = $licenseQuery->fetch_assoc();
								}
								if($packageRow["packagedurationtype"]=="year")
								{
									// 1 year to package
									$monthstoadd = $packageRow["packageduration"] * 12;

								}
								else if($packageRow["packagedurationtype"]=="month")
								{
									$monthstoadd = $packageRow["packageduration"];
								}
								

								$exp_date = $licenseRow["licexp_date"];echo "<br>";
								$newexp_date = Date("Y-m-d H:i:s", strtotime("+".$monthstoadd." month", strtotime($exp_date)));
								
								$conn->query("update U_license set suspendedlic='0',licexp_date='".$newexp_date."', transaction_id='".$transactionid."'  where lickey='".$transactionRow["lickey"]."'");
								$update_old_transaction = $conn->query("update U_transaction set subscriberid='', paymenturl='', lickey='' where transaction_id='".$transactionRow["transaction_id"]."'");

						}

				}
				else if($status=="cancelled" || $status=="halted")
				{
					// only add into U_transaction
					// if status is halted then expiry date is current date
					$transactionid = $conn->query("insert into U_transaction set 
						owner_id = '".$transactionRow["owner_id"]."',
						package_id = '".$transactionRow["package_id"]."',
						total_licenses='".$transactionRow["total_licenses"]."',
						package_type='".$transactionRow["package_type"]."',
						package_base_price='".$transactionRow["package_base_price"]."',
						total_base_price='".$transactionRow["total_base_price"]."',
						total_amount='".$transactionRow["total_amount"]."',
						promo_code='".$transactionRow["promo_code"]."',
						promo_discount='".$transactionRow["promo_discount"]."',
						total_after_discount='".$transactionRow["total_after_discount"]."',
						igst_percentage='".$transactionRow["igst_percentage"]."',
						igst_value = '".$transactionRow["igst_value"]."',
						cgst_percentage='".$transactionRow["cgst_percentage"]."',
						cgst_value='".$transactionRow["cgst_value"]."',
						sgst_percentage='".$transactionRow["sgst_percentage"]."',
						sgst_value='".$transactionRow["sgst_value"]."',
						total_amount_after_gst='".$transactionRow["total_amount_after_gst"]."',
						total_amount_received='".$transactionRow["total_amount_received"]."',
						ipaddress='".$transactionRow["ipaddress"]."',
						billing_name='".$transactionRow["billing_name"]."',
						billing_address='".$transactionRow["billing_address"]."',
						billing_city='".$transactionRow["billing_city"]."',
						billing_state='".$transactionRow["billing_state"]."',
						billing_zip='".$transactionRow["billing_zip"]."',
						billing_country='".$transactionRow["billing_country"]."',
						billing_email='".$transactionRow["billing_email"]."',
						billing_mobile='".$transactionRow["billing_mobile"]."',
						state_code='".$transactionRow["state_code"]."',
						gst_code='".$transactionRow["gst_code"]."',
						payment_date='".$transactionRow["payment_date"]."',
						created_date='".$transactionRow["created_date"]."',
						paymenttype='".$transactionRow["paymenttype"]."',
						lickey='".$transactionRow["lickey"]."',
						transaction_details='".$transactionRow["transaction_details"]."',
						adjustments='".$transactionRow["adjustments"]."',
						adjustmentstype='".$transactionRow["adjustmentstype"]."',
						duedate='".$transactionRow["duedate"]."',
						transactionref='".$transactionRow["transactionref"]."',
						transfer_type='".$transactionRow["transfer_type"]."',
						tcreatedby='RazorpayWebHook',
						order_id='',
						payment_id='',
						subscriberid='".$subscriptionid."',
						order_status='0',
						paymenturl='".$transactionRow["paymenturl"]."'
						");
					if($status=="halted" && $transactionRow["lickey"]!="")
					{
						// License Suspended
						$conn->query("update U_license set suspendedlic='1' where lickey='".$transactionRow["lickey"]."'");
					}
				}
			}	
		}
		$conn->query("update U_webhookread set webhookid='".$webhookrow["id"]."' where id=1");
	}
}

/*
( [entity] => event [account_id] => acc_CZlUkNXcoktW7I [event] => subscription.completed [contains] => Array ( [0] => subscription [1] => payment ) [payload] => Array ( [subscription] => Array ( [entity] => Array ( [id] => sub_D0x7zVLDoHWsVF [entity] => subscription [plan_id] => plan_D0wq8TRla4qsWK [customer_id] => cust_Czl0bKTP9XHrsI [status] => completed [current_start] => 1565289000 [current_end] => 1565893800 [ended_at] => 1565289000 [quantity] => 1 [notes] => Array ( ) [charge_at] => [start_at] => 1564724774 [end_at] => 1565289000 [auth_attempts] => 0 [total_count] => 2 [paid_count] => 2 [customer_notify] => 1 [created_at] => 1564724754 [expire_by] => [short_url] => [has_scheduled_changes] => [change_scheduled_at] => [remaining_count] => 0 ) ) [payment] => Array ( [entity] => Array ( [id] => pay_D3YtFElPyLPjXq [entity] => payment [amount] => 100 [currency] => INR [status] => captured [order_id] => order_D3YtEuhGsapPQT [invoice_id] => inv_D3YtEsl4c5ocqN [international] => [method] => card [amount_refunded] => 0 [amount_transferred] => 0 [refund_status] => [captured] => 1 [description] => Recurring Payment via Subscription [card_id] => card_D3YtFa1yLxylTb [card] => Array ( [id] => card_D3YtFa1yLxylTb [entity] => card [name] => Pavit [last4] => 0008 [network] => Visa [type] => credit [issuer] => ICIC [international] => [emi] => 1 [expiry_month] => 3 [expiry_year] => 2023 ) [bank] => [wallet] => [vpa] => [email] => pavit.cellgell@gmail.com [contact] => +919999558552 [customer_id] => cust_Czl0bKTP9XHrsI [token_id] => [notes] => Array ( ) [fee] => 3 [tax] => 0 [error_code] => [error_description] => [created_at] => 1565294418 ) ) ) [created_at] => 1565294446 ) 

*/
?>