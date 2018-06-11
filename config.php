<?php

/**
 * This is the main configuration file of IMS Admin App which loads configuration
 * of specific environment
 */

switch($_SERVER["SERVER_NAME"])
{
    case "local.ims.com": // For local machine
        require_once 'ims_env_configurations/local.php';
        break;
    case "testing.esmactech.com": // For Dev Server
        require_once 'ims_env_configurations/dev.php';
        break;
    default:
        require_once 'ims_env_configurations/live.php';
        break;
}