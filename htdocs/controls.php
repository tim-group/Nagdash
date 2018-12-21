<div class="btn-group">
<?php
echo "<a href='#' onClick=\"nagios_action('{$tag}', '{$host}', '{$service}', 'ack'); return false;\" class='btn btn-mini'> <i class='icon-check'></i> Ack </a>";
$action = (!isset($service['is_enabled'])) ? "disable" : "enable";
$text   = (!isset($service['is_enabled'])) ? "Silence" : "Unsilence";
$control = "<a href='#' onClick=\"nagios_action('{$tag}', '{$host}', '{$service}', '{$action}'); return false;\" class='btn btn-mini'>";
$control .= "<i class='icon-volume-off'></i> {$text}</a>";
echo $control;
echo "<a href='#' onClick=\"nagios_action('{$tag}', '{$host}', '{$service}', 'recheck'); return false;\" class='btn btn-mini'> <i class='icon-refresh'></i>Recheck</a>";
?>
<a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#">
<i class="icon-time"></i> Downtime <span class="caret"></span></a>
<ul class="dropdown-menu pull-right">
<?php
$control = "";
 $timespans = array("10 minutes" => 10, "30 minutes" => 30, "60 minutes" => 60, "2 hours" => 120, "12 hours" => 720, "1 day" => 1440, "7 days" => 10080);
    foreach ($timespans as $name => $minutes) {
        $control .= "<li><a href='#' onClick=\"nagios_action('{$tag}', '{$host}', '{$service}', 'downtime', '{$minutes}'); return false;\" >{$name}</a></li>";
    }
echo $control;
?>
</ul>

<?php
$host_parts = explode('.', $host);
if (strpos($host_parts[0], 'app') !== false && sizeof($host_parts) > 2) {
   $status = "<a class='btn btn-mini' target='_blank' href='https://status-pages-{$host_parts[2]}.timgroup.com/{$host_parts[0]}:8000'>Status</a>";
   echo $status;
}

$logs = "<a class=\"btn btn-mini\" href=\"https://kibana.timgroup.com/app/kibana#/discover?query:(match:('@source_host':(query:{$host},type:phrase)))))=&_g=()&_a=(columns:!(_source),filters:!(('\$state':(store:appState),meta:(alias:!n,disabled:!f,index:'logstash-*',key:'@source_host',negate:!f,value:{$host}),query:(match:('@source_host':(query:{$host},type:phrase))))),index:'logstash-*',interval:auto,query:'',sort:!('@timestamp',desc))\" target=\"_blank\">Logs</a>";
echo $logs;
?>

</div>
