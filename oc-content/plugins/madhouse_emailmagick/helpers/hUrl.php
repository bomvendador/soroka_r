<?php

function mdh_emailmagick_url()
{
	return osc_route_admin_url(mdh_current_plugin_name());
}

function mdh_emailmagick_do_url()
{
	return osc_route_admin_url(mdh_current_plugin_name() . "_do");
}

?>