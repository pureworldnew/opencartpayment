<?php
class ControllerExport extends Controller {
    /**
     * Function to generate xml for order export to shipstation
     * 
     * @return xml
     */
    public function index() {
        //Include the model files
        $this->load->model('export');

        $data = array();
        //Get the start and end date to fetch the orders
        if (isset($this->request->get['start_date']) && $this->request->get['start_date']) {
            $start_date_time = explode(' ', $this->request->get['start_date']);
            $startdate = explode('/', $start_date_time[0]);
            $starttime = explode(':', $start_date_time[1]);
            $data['startdate'] = date('Y-m-d H:i:s', mktime($starttime[0], $starttime[1], 0, $startdate[0], $startdate[1], $startdate[2]));
        } else {
            echo $this->language->get('text_no_start_date');
            die();
        }

        if (isset($this->request->get['end_date']) && $this->request->get['end_date']) {
            $end_date_time = explode(' ', $this->request->get['end_date']);
            $enddate = explode('/', $end_date_time[0]);
            $endtime = explode(':', $end_date_time[1]);
            $data['enddate'] = date('Y-m-d H:i:s', mktime($endtime[0], $endtime[1], 0, $enddate[0], $enddate[1], $enddate[2]));
        } else {
            echo $this->language->get('text_no_end_date');
            die();
        }
        header('Content-Type: text/xml');
        if (!empty($data))
            $orders = $this->model_export->getOrders($data);

        //Generate the xml from the order data to export into the shipstation
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<Orders>' . "\n";
        if (!empty($orders)) {
            foreach ($orders as $order) {
                //Get order details from the order id
                $order_data = $this->model_export->getOrderData($order);
                if (!empty($order_data)) {
                    $xml .= '<Order>' . "\n";
                    $xml .= '<OrderNumber><![CDATA[' . $order_data['OrderNumber'] . ']]></OrderNumber>' . "\n";
                    $xml .= '<OrderDate>' . $order_data['OrderDate'] . '</OrderDate>' . "\n";
                    $xml .= '<OrderStatus><![CDATA[' . $order_data['OrderStatus'] . ']]></OrderStatus>' . "\n";
                    $xml .= '<LastModified>' . $order_data['LastModified'] . '</LastModified>' . "\n";
                    $xml .= '<ShippingMethod><![CDATA[' . $order_data['ShippingMethod'] . ']]></ShippingMethod>' . "\n";
                    $xml .= '<OrderTotal>' . $order_data['OrderTotal'] . '</OrderTotal>' . "\n";
                    $xml .= '<TaxAmount>' . $order_data['TaxAmount'] . '</TaxAmount>' . "\n";
                    $xml .= '<ShippingAmount>' . $order_data['ShippingAmount'] . '</ShippingAmount>' . "\n";
                    $xml .= '<CustomerNotes><![CDATA[' . $order_data['CustomerNotes'] . ']]></CustomerNotes>' . "\n";
                    $xml .= '<InternalNotes><![CDATA[' . $order_data['InternalNotes'] . ']]></InternalNotes>' . "\n";
                    /**
                     * Get the customer Email
                     * Get the customer billing and shipping address
                     */
                    $xml .= '<Customer>' . "\n";
                    $xml .= '<CustomerCode>' . $order_data['Customer']['CustomerCode'] . '</CustomerCode>' . "\n";
                    
                    $xml .= '<BillTo>' . "\n";
                    $xml .= '<Name><![CDATA[' . $order_data['Customer']['BillTo']['Name'] . ']]></Name>' . "\n";
                    $xml .= '<Company><![CDATA[' . $order_data['Customer']['BillTo']['Company'] . ']]></Company>' . "\n";
                    $xml .= '<Phone>' . $order_data['Customer']['BillTo']['Phone'] . '</Phone>' . "\n";
                    $xml .= '<Email>' . $order_data['Customer']['BillTo']['Email'] . '</Email>' . "\n";
                    $xml .= '</BillTo>' . "\n";
                    
                    $xml .= '<ShipTo>' . "\n";
                    $xml .= '<Name><![CDATA[' . $order_data['Customer']['ShipTo']['Name'] . ']]></Name>' . "\n";
                    $xml .= '<Company><![CDATA[' . $order_data['Customer']['ShipTo']['Company'] . ']]></Company>' . "\n";
                    $xml .= '<Address1><![CDATA[' . $order_data['Customer']['ShipTo']['Address1'] . ']]></Address1>' . "\n";
                    $xml .= '<Address2><![CDATA[' . $order_data['Customer']['ShipTo']['Address2'] . ']]></Address2>' . "\n";
                    $xml .= '<City><![CDATA[' . $order_data['Customer']['ShipTo']['City'] . ']]></City>' . "\n";
                    $xml .= '<State><![CDATA[' . $order_data['Customer']['ShipTo']['State'] . ']]></State>' . "\n";
                    $xml .= '<PostalCode><![CDATA[' . $order_data['Customer']['ShipTo']['PostalCode'] . ']]></PostalCode>' . "\n";
                    $xml .= '<Country><![CDATA[' . $order_data['Customer']['ShipTo']['Country'] . ']]></Country>' . "\n";
                    $xml .= '<Phone>' . $order_data['Customer']['ShipTo']['Phone'] . '</Phone>' . "\n";
                    $xml .= '</ShipTo>' . "\n";

                    $xml .= '</Customer>' . "\n";
                    $xml .= '<Items>' . "\n";
                    /**
                     * Get the Order itme details
                     * Get the order item options used
                     */
                    foreach ($order_data['Items'] as $item) {
                        $xml .= '<Item>' . "\n";
                        $xml .= '<SKU><![CDATA[' . $item['SKU'] . ']]></SKU>' . "\n";
                        $xml .= '<Name><![CDATA[' . $item['Name'] . ']]></Name>' . "\n";
                        $xml .= '<ImageUrl><![CDATA[' . $item['ImageUrl'] . ']]></ImageUrl>' . "\n";
                        $xml .= '<Weight>' . $item['Weight'] . '</Weight>' . "\n";
                        $xml .= '<WeightUnits>' . $item['WeightUnits'] . '</WeightUnits>' . "\n";
                        $xml .= '<Quantity>' . $item['Quantity'] . '</Quantity>' . "\n";
                        $xml .= '<UnitPrice>' . $item['UnitPrice'] . '</UnitPrice>' . "\n";
                        if ($item['Options']) {
                            $xml .= '<Options>' . "\n";
                            foreach ($item['Options'] as $option) {
                                $xml .= '<Option>' . "\n";
                                $xml .= '<Name><![CDATA[' . $option['Name'] . ']]></Name>' . "\n";
                                $xml .= '<Value><![CDATA[' . $option['Value'] . ']]></Value>' . "\n";
                                $xml .= '<Weight>' . $option['Weight'] . '</Weight>' . "\n";
                                $xml .= '</Option>' . "\n";
                            }
                            $xml .= '</Options>' . "\n";
                        }
                        $xml .= '</Item>' . "\n";
                    }
                    $xml .= '</Items>' . "\n";
                    $xml .= '</Order>' . "\n";
                }
            }
        }
        $xml .= '</Orders>';
        echo $xml;
    }
}