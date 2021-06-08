<?php

namespace App\Custom\Services;

class DataTableService
{
	/* Me: DT request transform methods. Should produce array with keys [columns, order, start, length, search] */

	public static function rearrangeColumns($data)
	{
		$columns = isset($data['columns']) ? $data['columns'] : []; // format: ['id', 'data', 'search']
		$new_arr = $help_unique = [];

		if (is_array($columns))
			foreach ($columns as $key => $column) {
				$t['id'] = (int) $key;
				if(is_array($column)) {
					foreach ($column as $attr => $value)
						if ($attr == 'search') 
							$t[$attr] = isset($value['value']) && is_string($value['value'])
								? str_replace(['%', '_'], ['\%', '\_'], $value['value'])
								: null;
						elseif ($attr == 'data' && !empty($value) && is_string($value) && !in_array($value, $help_unique, true)) {
							$t[$attr] = $value;
							$help_unique[] = $value;
						}
						elseif ($attr == 'searchable' || $attr == 'orderable')
							$t[$attr] = $value === 'true' ?: false;
				
					if(count($t) === 5)
						$new_arr[] = $t;
				}

				$t = [];
			}

		return $new_arr;
	}

	public static function rearrangeOrder($data, $columns=null)
	{
		$order = isset($data['order']) ? $data['order'] : [];
		if (!$columns)
			$columns = self::rearrangeColumns($data);

		$new_arr = $help_unique = [];

		if (is_array($order))
			foreach($order as $sortby)
				if(isset($sortby['column']) && isset($sortby['dir']) && in_array($sortby['dir'], ['asc', 'desc'], true)) {
					$column = current(array_filter($columns, function ($value) use ($sortby) {
					    return $value['id'] == $sortby['column'];
					}));
					if($column && $column['orderable'] && !in_array($column['data'], $help_unique, true)) {
						$new_arr[] = ['data' => $column['data'], 'dir' => $sortby['dir']];
						$help_unique[] = $column['data'];
					}
				}

		return $new_arr;
	}

	public static function rearrange($data)
	{
		$new_data=[];

		foreach ($data as $key => $val)
			$new_data[$key] = $val;

		$new_data['columns'] = self::rearrangeColumns($data);
		$new_data['order'] = self::rearrangeOrder($data, $new_data['columns']);
		$new_data['search'] = isset($data['search']['value']) && is_string($data['search']['value'])
			? str_replace(['%', '_'], ['\%', '\_'], $data['search']['value'])
			: null;
		$new_data['start'] = isset($data['start']) && ctype_digit($data['start']) ? $data['start'] : 0;
		$new_data['length'] = isset($data['length']) && ctype_digit($data['length']) ? $data['length'] : 10;

		return $new_data;
	}
}