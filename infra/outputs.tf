output "resource_group" {
  value = azurerm_resource_group.rg.name
}

output "mysql_fqdn" {
  value = azurerm_mysql_flexible_server.mysql.fqdn
}

output "mysql_admin_user" {
  value = azurerm_mysql_flexible_server.mysql.administrator_login
}

output "app_default_hostname" {
  value = azurerm_linux_web_app.webapp.default_site_hostname
}
