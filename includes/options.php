<?php
function xpress_options() {
	return TitanFramework::getInstance( Core_Xpress::$plugin_slug );
}

function xpress_get_option( $slug ) {
	return xpress_options()->getOption( $slug );
}
