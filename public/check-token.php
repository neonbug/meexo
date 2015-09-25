<?php
if (!array_key_exists('laravel_session', $_COOKIE) || !array_key_exists('XSRF-TOKEN', $_COOKIE))
{
	exit(json_encode(false));
}
exit(json_encode(true));
