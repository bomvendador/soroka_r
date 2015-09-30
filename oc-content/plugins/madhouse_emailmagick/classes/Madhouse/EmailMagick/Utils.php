<?php

class Madhouse_EmailMagick_Utils
{
	/**
	 * Returns a new empty locale.
	 * @param  String $locale Code for that locale (ex. fr_FR, en_US, etc.)
	 * @return Array          a new empty locale, as it is in the model.
	 */
	public static function newEmptyLocale($locale)
	{
		return array(
			"fk_c_locale_code" => $locale,
			"s_title" => "",
			"s_excerpt" => "",
			"s_text" => ""
		);
	}

	/**
	 * Returns a new empty email.
	 * @param  String $name internal name for this new email.
	 * @return Array        a new empty email, as it is in the model.
	 */
	public static function newEmptyEmail($name)
	{
		return array(
			"s_internal_name" => $name,
			"locales" => array_map(
				function($v) {
					return Madhouse_EmailMagick_Utils::newEmptyLocale($v["pk_c_code"]);
				},
				osc_get_locales()
			)
		);
	}

	/**
	 * Merge two sets of locales.
	 * 	- If it does not exist in $new, keep the $old one.
	 * 	- Otherwise, take the new one.
	 * @param  Array<Locale> $old the old set of locales.
	 * @param  Array<Locale> $new the new set of locales.
	 * @return Array<Locale>      the merged set of locales.
	 */
	public static function mergeLocales($old, $new)
	{
		$res = array();
		foreach ($old as $o) {
			$n = Madhouse_Utils_Collections::findByField($new, "fk_c_locale_code", $o["fk_c_locale_code"]);
			if(is_null($n)) {
				// No new value, keep the old one.
				array_push($res, $o);
			} else {
				// New value for this locale.
				array_push($res, $n);
			}
		}

		foreach ($new as $n) {
			$o = Madhouse_Utils_Collections::findByField($old, "fk_c_locale_code", $n["fk_c_locale_code"]);
			if(is_null($o)) {
				// New locale in $new that is not in $old, add it.
				array_push($res, $n);
			}
		}

		return $res;
	}

	/**
	 * Is the email locale empty ?
	 * @param  Locale  $l Locale to check.
	 * @return boolean    true if both the title and text are empty, false otherwise.
	 */
	public static function isLocaleEmpty($l)
	{
		return (empty($l["s_title"]) && empty($l["s_text"]));
	}

	/**
	 * Computes the filling rate (percentage) of an email.
	 * @param  Email $e  The email to compute the filling rate for.
	 * @return Int    	 An integer, from 0 to 100%.
	 */
	public static function computeFillingRate($e)
	{
		$activeLocales = Madhouse_Utils_Collections::getFieldsFromList(osc_get_locales(), "pk_c_code");

		// Make some calculation on ACTIVES locales.
		// (non-active locales are not considered, see array_filter)
		$filled = array_map(
			function($l) {
				$sum = 0;
				if(! empty($l["s_title"])) {
					$sum += 0.5;
				}
				if(! empty($l["s_text"])) {
					$sum += 0.5;
				}

				return $sum;
			},
			array_values(
				array_filter(
					$e["locales"],
					function($l) use ($activeLocales) {
						if(! in_array($l["fk_c_locale_code"], $activeLocales)) {
							return false;
						}
						return true;
					}
				)
			)
		);

		return ceil(array_sum($filled) * 100 / count($filled));
	}
}

?>