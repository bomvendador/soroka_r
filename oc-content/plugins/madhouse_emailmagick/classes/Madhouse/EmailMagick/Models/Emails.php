<?php

/**
 * Model layer for Emails.
 * @since 1.00
 */
class Madhouse_EmailMagick_Models_Emails
{

	/**
     * Singleton.
     * @var Madhouse_EmailMagick_Models_Emails
     */
    private static $instance;

    public static function newInstance()
    {
        if( !self::$instance instanceof self ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

	/**
	 * List all email datas stored in the database.
	 * @return Array 	the complete list of datas for emails and footer. Ready to use.
	 */
	public function listAll()
	{
		// Retrieve email datas from database.
		$datas = json_decode(osc_get_preference("email_datas", mdh_current_preferences_section()), true);

		if(! is_array($datas)) {
			// TODO. Handle error.
		}

		// Retrieve all emails from database;
		$emails = Page::newInstance()->listAll(true);

		$activeLocales = Madhouse_Utils_Collections::getFieldsFromList(osc_get_locales(), "pk_c_code");

		// Complete the list of emails with new mails (not already in the JSON).
		foreach($emails as $e) {
			if(! in_array($e["s_internal_name"], Madhouse_Utils_Collections::getFieldsFromList($datas["emails"], "s_internal_name"))) {
				array_push(
					$datas["emails"],
					Madhouse_EmailMagick_Utils::newEmptyEmail($e["s_internal_name"])
				);
			}
		}

		// Complete, filter and sort emails.
		$datas["emails"] = Madhouse_Utils_Collections::sortListByField(
			array_map(
				function($e) use($emails, $activeLocales) {
					$row = Madhouse_Utils_Collections::findByField($emails, "s_internal_name", $e["s_internal_name"]);

					$e["pk_i_id"] = $row["pk_i_id"];

					// Compute the filling rate.
					$e["i_filled"] = Madhouse_EmailMagick_Utils::computeFillingRate($e);

					// Is the email still exists ? (in oc_t_pages)
					if(! in_array($e["s_internal_name"], Madhouse_Utils_Collections::getFieldsFromList($emails, "s_internal_name"))) {
						$e["b_deleted"] = true;
					}

					// Add a locale for locales that have not been provided.
					foreach($activeLocales as $al) {
						$el = Madhouse_Utils_Collections::findByField($e["locales"], "fk_c_locale_code", $al);
						if(is_null($el)) {
							array_push($e["locales"], Madhouse_EmailMagick_Utils::newEmptyLocale($al));
						}
					}

					$e["locales"] = array_values(array_filter(
						$e["locales"],
						function($v) use ($activeLocales) {
							return (in_array($v["fk_c_locale_code"], $activeLocales));
						}
					));

					return $e;
				},
				$datas["emails"]
			),
			"s_internal_name"
		);

		// Filter the footer datas (only active locales are left).
		$datas["footer"]["locales"] =
			array_values(array_filter(
				$datas["footer"]["locales"],
				function($v) use ($activeLocales) {
					return (in_array($v["fk_c_locale_code"], $activeLocales));
				}
			)
		);

		// Add a locale for locales that have not been provided.
		foreach($activeLocales as $al) {
			$fl = Madhouse_Utils_Collections::findByField($datas["footer"]["locales"], "fk_c_locale_code", $al);
			if(is_null($fl)) {
				array_push($datas["footer"]["locales"], Madhouse_EmailMagick_Utils::newEmptyLocale($al));
			}
		}

		return $datas;
	}

	/**
	 * Generate and updates mails.
	 * @param  String $template HTML template to serve as a base.
	 * @param  Array $data      New datas for emails and footer to use
	 *                          to generate and update emails in oc_t_pages.
	 * @return int 				Number of locales updated.
	 */
	public function update($template, $id, $newDatas, $nEmail)
	{
		$m = new Mustache_Engine();
		$activeLocales = Madhouse_Utils_Collections::getFieldsFromList(osc_get_locales(), "pk_c_code");

		if($nEmail["s_internal_name"] == "exemple_page") {
			mdh_error_log(array(
				$nEmail
			));
		}

		$i = 0;
		foreach ($nEmail["locales"] as $l) {
			// Find the correct footer content.
			$footer = Madhouse_Utils_Collections::findByField(
				$newDatas["footer"]["locales"],
				"fk_c_locale_code",
				$l["fk_c_locale_code"]
			);


			if(in_array($l["fk_c_locale_code"], $activeLocales) && ! Madhouse_EmailMagick_Utils::isLocaleEmpty($l)) {
				// Updates the description using DAO method.
				Page::newInstance()->updateDescription(
					$id,
					$l["fk_c_locale_code"],
					$l["s_title"],
					$m->render(
						$template,
						array(
							"CONTENT" => $l["s_text"],
							"TITLE"   => $l["s_title"],
							"EXCERPT" => $l["s_excerpt"],
							"FOOTER" => $footer["s_text"],
						)
					)
				);
				$i++;
			}
		}

		return $i;
	}
}

?>