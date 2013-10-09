<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Widget_Tags_Cloud extends Model_Widget_Decorator {
	
	protected $_data = array(
		'min_size' => 8,
		'max_size' => 50,
		'order_by' => 'name_asc'
	);
	
	public function fetch_data()
	{
		$tags = $this->get_tags();

		$fmax = $this->max_size;
		$fmin = $this->min_size;
		$tmin = min($tags);
		$tmax = max($tags);
		
		$cloud = array();

		foreach ($tags as $word => $frequency) 
		{
			if ($frequency > $tmin) 
			{
				$font_size = floor(($frequency - $tmin) / ($tmax - $tmin) * ($fmax - $fmin) + $fmin);
				$r = $g = 0; $b = floor( 255 * ($frequency / $tmax) );
				$color = '#' . sprintf('%02s', dechex($r)) . sprintf('%02s', dechex($g)) . sprintf('%02s', dechex($b));
			}
			else 
			{
				$font_size = 0;
			}

			if ($font_size >= $fmin) 
			{
				$cloud[$word] = array(
					'count' => $frequency,
					'size' => $font_size,
					'color' => $color
				);
			}
		}
	
		return array(
			'tags' => $cloud
		);
	}
	
	public function get_tags()
	{
		$query = DB::select()
			->from(Model_Tag::tableName());
		
		switch($this->order_by)
		{
			case 'name_asc':
				$query->order_by( 'name', 'asc' );
				break;
			case 'name_desc':
				$query->order_by( 'name', 'desc' );
				break;
			case 'count_asc':
				$query->order_by( 'count', 'asc' );
				break;
			case 'count_desc':
				$query->order_by( 'count', 'desc' );
				break;
			default:
				$query->order_by( 'name', 'asc' );
				break;
		}
		
		return $query
			->execute()
			->as_array('name', 'count');
	}
}