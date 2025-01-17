<?php

declare(strict_types=1);

namespace muqsit\dimensionportals\config;

final class ConfigurationHelper{

	/**
	 * @param mixed[] $data
	 * @param string $key
	 * @return mixed
	 *
	 * @phpstan-param array<string, mixed> $data
	 */
	public static function read(array &$data, string $key) : mixed{
		if(!isset($data[$key])){
			throw new BadConfigurationException("Cannot find required key '{$key}'");
		}

		$value = $data[$key];
		unset($data[$key]);
		return $value;
	}

	/**
	 * @param mixed[] $data
	 * @param string $key
	 * @param int $min
	 * @param int $max
	 * @return int
	 *
	 * @phpstan-param array<string, mixed> $data
	 */
	public static function readInt(array &$data, string $key, int $min = PHP_INT_MIN, int $max = PHP_INT_MAX) : int{
		$value = self::read($data, $key);
		if(!is_int($value)){
			throw new BadConfigurationException("Expected value of key '{$key}' to be an integer, got " . gettype($value) . (is_scalar($value) ? " ({$value})" : ""));
		}

		if($value < $min || $value > $max){
			throw new BadConfigurationException("Expected value of key '{$key}' to be between {$min} and {$max}, got {$value}");
		}

		return $value;
	}

	/**
	 * @param mixed[] $data
	 * @param string $key
	 * @return string
	 *
	 * @phpstan-param array<string, mixed> $data
	 */
	public static function readString(array &$data, string $key) : string{
		$value = self::read($data, $key);
		if(!is_string($value)){
			throw new BadConfigurationException("Expected value of key '{$key}' to be a string, got " . gettype($value) . (is_scalar($value) ? " ({$value})" : ""));
		}

		return $value;
	}

	/**
	 * @param mixed[] $data
	 * @param string $key
	 * @return mixed[]
	 *
	 * @phpstan-param array<string, mixed> $data
	 * @phpstan-return array<string, mixed>
	 */
	public static function readMap(array &$data, string $key) : array{
		$value = self::read($data, $key);
		if(!is_array($value)){
			throw new BadConfigurationException("Expected value of key '{$key}' to be a map, got " . gettype($value) . (is_scalar($value) ? " ({$value})" : ""));
		}

		/** @phpstan-var array<string, mixed> $value */
		return $value;
	}

	/**
	 * @param array<string|int, mixed> $data
	 */
	public static function checkForUnread(array $data) : void{
		$keys = array_keys($data);
		if(count($keys) > 0){
			throw new BadConfigurationException("Unrecognized keys: '" . implode("', '", $keys) . "'");
		}
	}
}