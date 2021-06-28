<?php
	if (!function_exists('e')) {
		function e($string) {
			if ($string) {
				return htmlspecialchars($string);
			}
		}
	}

	if (!function_exists('get_session')) {
		function get_session($key) {
			if ($key) {
				return !empty($_SESSION[$key]) ? e($_SESSION[$key])	: null;
			}
		}
	}

	if (!function_exists('is_logged_in')) {
		function is_logged_in() {
			return isset($_SESSION['user_id']) || isset($_SESSION['pseudo']);
		}
	}

?>
