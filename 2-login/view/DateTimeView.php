<?php

namespace view;

class DateTimeView {

	public function show() {
		$timezone = date_default_timezone_set('Europe/Stockholm');
		$timeString = date('l\, \t\h\e jS \of F Y\, \T\h\e \t\i\m\e \i\s H:i:s');

		return '<p>' . $timeString . '</p>';
	}
}