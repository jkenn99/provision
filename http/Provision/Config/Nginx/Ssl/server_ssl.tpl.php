<?php include(provision_class_directory('Provision_Config_Nginx_Server') . '/server.tpl.php'); ?>

#######################################################
###  nginx default ssl server
#######################################################

<?php
$satellite_mode = drush_get_option('satellite_mode');
if (!$satellite_mode && $server->satellite_mode) {
  $satellite_mode = $server->satellite_mode;
}

$nginx_has_http2 = drush_get_option('nginx_has_http2');
if (!$nginx_has_http2 && $server->nginx_has_http2) {
  $nginx_has_http2 = $server->nginx_has_http2;
}

$nginx_has_realip = drush_get_option('nginx_has_realip');
if (!$nginx_has_realip && $server->nginx_has_realip) {
  $nginx_has_realip = $server->nginx_has_realip;
}

$ssl_args = "ssl";
if ($nginx_has_http2) {
  $ssl_args .= " http2";
}
if ($http_ssl_proxy_type == Provision_Service_http_public::HOSTING_SERVER_PROXY_PROXYPROTOCOL) {
  $ssl_args .= " proxy_protocol";
}

if ($satellite_mode == 'boa') {
  $ssl_listen_ip = "*";
}
?>

server {
<?php if ($satellite_mode == 'boa'): ?>
  listen       <?php print "{$ssl_listen_ip}:{$http_ssl_port} {$ssl_args}"; ?>;
<?php else: ?>
<?php foreach ($server->ip_addresses as $ip) :?>
  listen       <?php print "{$ip}:{$http_ssl_port} {$ssl_args}"; ?>;
<?php endforeach; ?>
<?php endif; ?>
<?php if ($nginx_has_realip && $http_ssl_proxy_type == Provision_Service_http_public::HOSTING_SERVER_PROXY_XFORWARDEDFOR): ?>
  real_ip_header X-Forwarded-For;
<?php elseif ($nginx_has_realip && $http_ssl_proxy_type == Provision_Service_http_public::HOSTING_SERVER_PROXY_PROXYPROTOCOL): ?>
  real_ip_header proxy_protocol;
<?php endif; ?>
  server_name  _;
  location / {
<?php if ($satellite_mode == 'boa'): ?>
    root   /var/www/nginx-default;
    index  index.html index.htm;
<?php elseif ($http_proxy_type > Provision_Service_http_public::HOSTING_SERVER_PROXY_NONE): ?>
    return 204;
    access_log off;
<?php else: ?>
    return 404;
<?php endif; ?>
  }
}
