<?

	/* You can specify whether you want the postage amount hidden. */
	$xml_HidePostageAmount = 'True'; // Boolean, True or False, Default: False.

	/* You can use this field to request that a reference number of other memo be printed on to the label. */
	$xml_PrintedMemo = 'OCXMLSDC'; // Custom print memo that appears at the bottom of shipping labels.
	
	/* SHIPMENT NOTIFICATION VARIABLES **************************************************************************************************** */
	
	/* If this is set to “true”, then a Shipment notification email will be sent to all recipients (unless overridden for specific recipients). */
	$xml_ShipmentNotification = 'True'; // Boolean, True or False.
	
	/* This is an attribute of the ShipmentNotification element. If this is set to “TRUE”, then if your Company’s name has been set in the My Account section, it will be used in the Subject line of the email (“Acme Inc. has sent you a package”). If this is set to “FALSE” or not set at all, then the user name will be used in the subject line of the email (“John Smith has sent you a package”) */
	$xml_Shipment_Notification_companyInSubject = 'True'; // Boolean, True or False.
	
	/* This is an attribute of the ShipmentNotification element. If this is set to “true” then the email will come from your company name (From: Acme Inc). If it is set to “false” or not set, then the email will come from the user’s name (From: John Smith). */
	$xml_Shipment_Notification_fromCompany = 'True'; //Boolean, True or False.
	
	/* CUSTOMS FORM VARIABLES **************************************************************************************************** */
	
	/*
	Set this value to “true” to acknowledge acceptance of the USPS Privacy Act Statement and also the Content Restrictions and Prohibitions for the country you are sending the mailpiece to.
	If this is set to “false,” the label will not be printed.
	*/
	$xml_customs_UserAcknowledged = 'True'; // Boolean, True or False, Required.

?>