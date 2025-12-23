<?php session_start();
require "../config/db.php";
define("BASE_URL", "http://localhost/brand-elevate");
define("BASE_PATH", $_SERVER['DOCUMENT_ROOT'] . "/public");
define("SITE_NAME", "TEST");
define("API_KEY", "your-openai-key-here");

define('GEMINI_API_KEY', 'AIzaSyB-pnJPyproKPFGRwhwVHAH9yeQ7AViY8s');

// OpenAI-compatible Gemini endpoint (v1beta openai compat)
define('GEMINI_OPENAI_ENDPOINT', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent');

// Default model — change to the model name available for your account (e.g. "gemini-pro", "gemini-2.1-pro", "gemini-1.5")
define('GEMINI_MODEL', 'gemini-pro');

// Request defaults
define('GEMINI_TEMPERATURE', 0.7);
define('GEMINI_MAX_TOKENS', 700);

define('PERPLEXITY_API_KEY', "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");
define('PERPLEXITY_API_ENDPOINT','https://api.perplexity.ai/chat/completions');
?>

 