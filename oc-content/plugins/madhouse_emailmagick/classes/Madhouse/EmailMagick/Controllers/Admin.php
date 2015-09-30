<?php

class Madhouse_EmailMagick_Controllers_Admin extends AdminSecBaseModel
{
	public function __construct()
	{
		parent::__construct();
		$this->ajax = true;
		$this->model = Page::newInstance();
	}

	/**
	 * Prepare datas for displaying the settings page.
	 * @return void.
	 */
	public function show()
	{
		// HTML Template.
		View::newInstance()->_exportVariableToView("template", osc_get_preference("email_template", mdh_current_preferences_section()));

		// Email datas.
		View::newInstance()->_exportVariableToView(
			"datas",
			Madhouse_EmailMagick_Models_Emails::newInstance()->listAll()
		);

		// Actives locales.
		View::newInstance()->_exportVariableToView("active_locales", osc_get_locales());
	}

	/**
	 * Generates emails from the templates & datas.
	 *  - For each emails and for each active locale :
	 *  	- Check if the emails is empty or does not exists anymore. If so, do nothing.
	 *  	- Combines templates and datas to produce the s_title and s_text fields.
	 * 	- Saves those settings for next run.
	 * @return void.
	 */
	public function update()
	{
		// Decode and transform into a PHP array (true as a second parameter).
		$data = json_decode(Params::getParam("email_datas", false, false), true);
		if(! is_array($data)) {
			mdh_handle_error(sprintf(__("Given JSON datas are not correct (Error #%d)", mdh_current_plugin_name()), json_last_error()), mdh_emailmagick_url());
		}

		// Get the HTML template.
		$template = Params::getParam("email_template", false, false);

		// This is current emails datas (stored in the database).
		$currentDatas = json_decode(osc_get_preference("email_datas", mdh_current_preferences_section()), true);

		// Create a new array, merge of the old and the new one.
		$newDatas = array(
			"emails" => array(),
			"footer" => array(
				"locales" => Madhouse_EmailMagick_Utils::mergeLocales($currentDatas["footer"]["locales"], $data["footer"]["locales"])
			)
		);

		// Iterate through new submitted emails data to update.
		$i = 0;
		foreach($data["emails"] as $e) {
			// Get the old email datas, as they are right now.
			$oe = Madhouse_Utils_Collections::findByField($currentDatas["emails"], "s_internal_name", $e["s_internal_name"]);
			if(is_null($oe)) {
				// Not found in old emails. => new email has been installed.
				$locales = $e["locales"];
			} else {
				// Found in old emails. Merge with new locales.
				$locales = Madhouse_EmailMagick_Utils::mergeLocales($oe["locales"], $e["locales"]);
			}

			// Email exists, create in order to be filled and pushed to the new datas (at the end).
			$nEmail = array(
				"s_internal_name" => $e["s_internal_name"],
				"locales" => $locales
			);

			// Get the email from the database.
			$email = Page::newInstance()->findByInternalName($e["s_internal_name"]);

			if($email !== false && count($email) > 0) {
				// Update the email.
				$updated = Madhouse_EmailMagick_Models_Emails::newInstance()->update(
					$template,
					$email["pk_i_id"],
					$newDatas,
					$nEmail
				);

				$i += $updated;
			}

			array_push($newDatas["emails"], $nEmail);
		}

		// Saves the settings.
		Madhouse_Utils_Controllers::doSettingsPost(
			array(
				"email_template",
				"email_datas"
			),
			array(
				"email_template" => $template,
				"email_datas" => json_encode($newDatas)
			),
			mdh_emailmagick_url(),
			null,
			sprintf(__("Sucessfully updated %d emails (out of %d)!", mdh_current_plugin_name()), $i, count($data["emails"]) * count(osc_get_locales()))
		);
	}

	public function init()
	{
		$data = array_map(
			function($v) {
				return array(
					"s_internal_name" => $v["s_internal_name"],
					"locales" => array_map(
						function($k, $v) {
							return array(
								"fk_c_locale_code" => $k,
								"s_title" => $v["s_title"],
								"s_excerpt" => "",
								"s_text" => $v["s_text"]
							);
						},
						array_keys($v["locale"]),
						array_values($v["locale"])
					)
				);
			},
			$this->model->listAll(true)
		);

		View::newInstance()->_exportVariableToView("datas", json_encode(
			array(
				"emails" => $data,
				"footer" => array(
					"locales" => array_map(
						function($v) {
							return array(
								"fk_c_locale_code" => $v["pk_c_code"],
								"s_text" => ""
							);
						},
						OSCLocale::newInstance()->listAll()
					)
				)
			),
			JSON_PRETTY_PRINT
		));
	}

	public function doModel()
	{
        parent::doModel();

        switch (Params::getParam("route")) {
        	case mdh_current_plugin_name() . '_init':
        		$this->init();
        		break;
        	case mdh_current_plugin_name() . '_do':
        		$this->update();
        		break;
        	default:
        		$this->show();
        		break;
        }
    }
}

?>