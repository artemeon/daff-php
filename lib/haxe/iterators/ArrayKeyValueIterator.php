<?php
/**
 * Generated by Haxe 4.3.1
 */

namespace haxe\iterators;

use \php\Boot;

class ArrayKeyValueIterator {
	/**
	 * @var mixed[]|\Array_hx
	 */
	public $array;

	/**
	 * @param mixed[]|\Array_hx $array
	 * 
	 * @return void
	 */
	public function __construct ($array) {
		#D:\tools\haxe\std/haxe/iterators/ArrayKeyValueIterator.hx:31: characters 3-21
		$this->array = $array;
	}
}

Boot::registerClass(ArrayKeyValueIterator::class, 'haxe.iterators.ArrayKeyValueIterator');
