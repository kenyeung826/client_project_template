<?php
$basePath = realpath(__DIR__.'/../');
require $basePath.'/vendor/autoload.php';
\Core\Application\Application::defineBasePath($basePath);
\Core\Application\Application::loadDotEnv($basePath);

// =============================================================================
// Maintenance
// =============================================================================
$maintenanceFlag = getenv('maintenance_flag', true) ?? false;
if ($maintenanceFlag) {
    http_response_code(503);
    header('Content-Type: application/json');
    echo json_encode([
        'error' => [
            'code' => 21,
            'message' => 'This API instance is currently down for maintenance. Please try again later.',
        ],
    ]);
    exit;
}

// =============================================================================
// Error Reporting
// =============================================================================
$errorReport = getenv('error_reporting', true) ?: false;
$errorReporting = E_ALL;
$displayErrors = 1;
if (empty($errorReport)) {
    $displayErrors = $errorReporting = 0;
}
error_reporting($errorReporting);
ini_set('display_errors', $displayErrors);

// =============================================================================
// Timezone
// =============================================================================
$timezone =  getenv('timezone', true) ?: 'Asia/Hong_Kong';
date_default_timezone_set($timezone);

$app = null;
try {
    $defaultSettings = \Core\Application\Schema::get($basePath)->value([]);
    $app = new \Core\Application\Application([
        "settings" => $defaultSettings,
        "basePath" => $basePath
    ]);
}catch (\Core\Exception\CoreException $e) {
    http_response_code($e->getStatusCode());
    header('Content-Type: application/json');
    echo json_encode([
        'error' => [
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
        ],
    ]);
    exit;
}

require realpath(__DIR__) . '/route.php';

return $app;