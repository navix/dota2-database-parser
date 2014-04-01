<?php

namespace Parser\Entities;

use ArrayAccess;


abstract class EntityAbstract implements ArrayAccess {

	public static function initFromArray($array) {
		$return = [];
		foreach ($array as $line) {
			$class = get_called_class();
			$object = new $class();
			foreach ($line as $key => $value)
				$object->$key = $value;
			$return[] = $object;
		}
		return $return;
	}


	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->$offset = $value;
		}
		else {
			$this->$offset = $value;
		}
	}


	public function offsetExists($offset) {
		return isset($this->$offset);
	}


	public function offsetUnset($offset) {
		unset($this->$offset);
	}


	public function offsetGet($offset) {
		return isset($this->$offset) ? $this->$offset : null;
	}

}