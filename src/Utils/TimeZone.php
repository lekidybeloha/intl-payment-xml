<?php
	namespace Utils;

	class TimeZone
	{
		/**
		 * Class InHeritance Method
		 */
		public static function setTimeZone ( $timezone = "UTC" )
		{
			$timezones = self::getAllTimezones();
			if ( $timezone != 'UTC' && in_array( $timezone, $timezones ) )
			{
				date_default_timezone_set($timezone);
			}
		}

		private static function getAllTimezones (  )
		{
			return timezone_identifiers_list();
		}

	}