<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once '../config.php';
require_once '../phplib/NagiosApi.php';
require_once '../phplib/NagiosLivestatus.php';
require_once '../phplib/utils.php';

$supported_methods = ["ack", "unack", "downtime", "enable", "disable", "recheck"];

if (!isset($_POST['nag_host'])) {
    echo "Are you calling this manually? This should be called by Nagdash only.";
} else {
    $nagios_instance = $_POST['nag_host'];
    $action = $_POST['action'];

    $details = [
        "host" => $_POST['hostname'],
        "service" => ($_POST['service']) ? $_POST['service'] : null
    ];

    switch ($action) {
        case "ack":
            $details["author"] = function_exists("nagdash_get_user") ? nagdash_get_user() : "Nagdash";
            $details["comment"] = "{$action} from Nagdash";
            break;
        case "downtime":
            $details["author"] = function_exists("nagdash_get_user") ? nagdash_get_user() : "Nagdash";
            $details["comment"] = "{$action} from Nagdash";
            $details["duration"] = ($_POST['duration']) ? ($_POST['duration'] * 60) : null;
            break;
        case "recheck":
            $details["forced"] = true;
            break;
    }

    if (!in_array($action, $supported_methods)) {
        echo "Nagios-api does not support this action ({$action}) yet. ";
    } else {

        foreach ($nagios_hosts as $host) {
            if ($host['tag'] == $nagios_instance) {
                $nagios_api = NagdashHelpers::get_nagios_api_object($api_type,
                    $host["hostname"], $host["port"], $host["protocol"], $host["url"]);
            }
        }

        switch ($action) {
        case "ack":
            $ret = $nagios_api->acknowledge($details);
            break;
        case "unack":
            $ret = $nagios_api->remove_acknowledgement($details);
            break;
        case "downtime":
            $ret =  $nagios_api->setDowntime($details);
            break;
        case "enable":
            $ret = $nagios_api->enableNotifications($details);
            break;
        case "disable":
            $ret = $nagios_api->disableNotifications($details);
            break;
        case "recheck":
            $ret = $nagios_api->scheduleCheck($details);
            break;
        }

        echo $ret["details"];

    }
}


