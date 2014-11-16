<?php
class ContentPortDateUtils 
{
	public static function get_due_date($order_date, $date_val, $date_unit) {
        // Only process when we have a valid
        if($order_date != '')
        {
			// Only process if we have valid values
			if($date_val != '' && $date_unit != '') 
			{
				// The date interval
				$date_interval = 'P';
				if($date_unit == 'H'){
				  $date_interval = 'PT';
				}
				$date_interval = $date_interval . $date_val . $date_unit;
				// Order date
				$order_date_time = DateTime::createFromFormat(ContentPort::$date_format, $order_date);
				// Due date
				$due_date_time = $order_date_time->add(new DateInterval($date_interval));
				return $due_date_time->format(ContentPort::$date_format);
			}
        }
	}

}
?>
