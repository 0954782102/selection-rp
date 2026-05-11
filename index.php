<?php

$phpVersion = phpversion();
if (version_compare($phpVersion, '7.0.0', '<'))
{
	die("PHP 7.0.0 or newer is required. $phpVersion does not meet this requirement. Please ask your host to upgrade PHP.");
}

$dir = __DIR__;
// If Ukrainian language is installed in XenForo, set the visitor language cookie automatically.
$languageCookieName = 'language_id';
if (!isset($_COOKIE[$languageCookieName])) {
    $config = [];
    @include($dir . '/src/config.php');
    if (!empty($config['db']['host']) && !empty($config['db']['username']) && !empty($config['db']['dbname'])) {
        $dbPort = !empty($config['db']['port']) ? intval($config['db']['port']) : 3306;
        $mysqli = @new mysqli($config['db']['host'], $config['db']['username'], $config['db']['password'], $config['db']['dbname'], $dbPort);
        if ($mysqli && $mysqli->connect_errno === 0) {
            $languageId = null;
            $query = "SELECT language_id FROM xf_language WHERE language_code IN ('uk-UA', 'uk') OR title LIKE '%Ukrain%' OR title LIKE '%Україн%' LIMIT 1";
            if ($result = $mysqli->query($query)) {
                $row = $result->fetch_assoc();
                if ($row && !empty($row['language_id'])) {
                    $languageId = intval($row['language_id']);
                }
                $result->free();
            }
            $mysqli->close();
            if ($languageId) {
                setcookie($languageCookieName, $languageId, time() + 31536000, '/', '', false, false);
                $_COOKIE[$languageCookieName] = $languageId;
            }
        }
    }
}
require($dir . '/src/XF.php');

XF::start($dir);

if (\XF::requestUrlMatchesApi())
{
	\XF::runApp('XF\Api\App');
}
else
{
	\XF::runApp('XF\Pub\App');
}