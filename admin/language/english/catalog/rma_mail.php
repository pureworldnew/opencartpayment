<?php
/**
 * Webkul Software.
 * @category  Webkul
 * @author    Webkul
 * @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
/*
This language file also contain some data which will be used to send more information data to admin / customer and that's why because we can not write that data directly because that will change condition to condition so we used sort codes.
i explaining few here like
- {config_logo} - will be your Store logo
- {config_name} - will be your Store Name
- {customer_name} - will be your Customer Name related to this RETURN
- {order_id} - will be your Order Id for this RETURN
- {link} - will be <a> link for your site according to condition
- {rma_id} - will be your RETURN Id for this RETURN
- {admin_message} - will be Admin Message which will send to customer
[NIk]
*/

//admin send message to customer
$_['message_to_customer_subject']    	  = 'Regarding Your Return at Gempacked';
$_['message_to_customer_message']  =
'{config_logo}

We have changed the status for your Return (#{rma_id}) to - %s .

Hello {customer_name},

{customer_message}

Thanks,
{config_name}';

//customer changed status
$_['label_to_customer_subject']    	  =  'Shipping label Regarding your Return';
$_['label_to_customer_message']  =
'{config_logo}
Hello {customer_name},

We have sent you a shipping label for Return #{rma_id}, which you can use for your return.
<a  href="{link}">here</a>

Thanks,
{config_name}';

//RETURN Generate
$_['generate_admin_subject']    	  = $_['generate_customer_subject']    	  = 'Return has been Generated For Order ID';
$_['generate_admin_message']  =
'{config_logo}
Hello {config_owner},

Customer {customer_name} has generated a Return for Order #{order_id}.
Reply ASAP.

Thanks,
{config_name}';

$_['generate_customer_message']  =
'{config_logo}
Hello {customer_name},

We have received your return request for Order #{order_id}.
We will reply within 1-2 business days with instructions on how to proceed.
You can check your return details <a  href="{link}">here</a>.

Thanks,
{config_name}';

//customer send message to admin
$_['message_to_admin_subject']    	  = 'Customer Sent Message regarding Return';
$_['message_to_admin_message']  =
'{config_logo}
Hello {config_owner},

Customer {customer_name} sent message regarding Return Id - #{rma_id}.
{customer_message}

Thanks,
{config_name}';

//customer changed status
$_['status_to_admin_subject']    	  = $_['status_to_customer_subject']    	  = 'Return Status has been Changed';
$_['status_to_admin_message']  =
'{config_logo}
Hello {config_owner},

Customer {customer_name} changed Return #{rma_id} status to following - %s.

Thanks,
{config_name}';

$_['status_to_customer_message']  =
'{config_logo}
Hello {customer_name},

Your Return #{rma_id} status has been successfully changed to following - %s.

Thanks,
{config_name}';
