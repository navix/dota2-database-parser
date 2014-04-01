<?php

namespace Parser\Entities;

use ArrayAccess;


abstract class EntityAbstract implements ArrayAccess {

	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->$offset = $value;
		} else {
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