<?php

/**
 * This include enforces FORCE_SECURE* configuration for the transport protocol and session cookie flags,
 * and configures session cookies for joy and prosperity
 */


ini_set('session.hash_function', 'sha256');
ini_set('session.hash_bits_per_character', '5');


$requiresHTTPS = defined('FORCE_SECURE_CC') && FORCE_SECURE_CC;

// make session cookie httpOnly & secure (if necessary)
$sessionCookieParams = session_get_cookie_params();
session_set_cookie_params (
	$sessionCookieParams['lifetime'],
	$sessionCookieParams['path'],
	$sessionCookieParams['domain'],
	$requiresHTTPS,
	true // HttpOnly
);

if ($requiresHTTPS) {
	if (PROTOCOL != 'https://') {
		// Ensure we are redirecting to the SSL URL
		header('Location: ' . Jadu_Service_Container::getInstance()->getRequest()->getUri()->toHttps(), true, 301);
		exit;
	}

	/**
	 * if the whole site is to be served over HTTPS (flagged by FORCE_SECURE),
	 * or the Control Centre is on a different domain from the site,
	 * set HSTS header
	 */
	if ((defined('FORCE_SECURE') && FORCE_SECURE) || CC_DOMAIN !== DOMAIN) {
		$hstsMaxAge = (defined('HSTS_MAX_AGE')) ? trim(HSTS_MAX_AGE) : '';
		if ($hstsMaxAge === '') {
			$hstsMaxAge = 2592000;
		}
		if (ctype_digit((string)$hstsMaxAge)) {
			header('Strict-Transport-Security: max-age=' . $hstsMaxAge);
		}
	}
}
