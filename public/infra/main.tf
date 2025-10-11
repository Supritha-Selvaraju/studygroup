resource "azurerm_resource_group" "rg" {
  name     = var.resource_group_name
  location = var.location
}

# MySQL Flexible Server (public access; if you want private networking see MS docs)
resource "azurerm_mysql_flexible_server" "mysql" {
  name                = var.mysql_server_name
  resource_group_name = azurerm_resource_group.rg.name
  location            = azurerm_resource_group.rg.location

  administrator_login    = var.mysql_admin_user
  administrator_password = var.mysql_admin_password

  sku_name  = "GP_Standard_D2ds_v4"
  version   = "8.0"
  storage_mb = 32768

  backup_retention_days = 7
  public_network_access_enabled = true
}

resource "azurerm_mysql_flexible_database" "studydb" {
  name                = var.mysql_database_name
  resource_group_name = azurerm_resource_group.rg.name
  server_name         = azurerm_mysql_flexible_server.mysql.name
  charset             = "utf8mb4"
  collation           = "utf8mb4_general_ci"
}

# App Service Plan (Linux)
resource "azurerm_app_service_plan" "plan" {
  name                = "${var.app_service_name}-plan"
  location            = azurerm_resource_group.rg.location
  resource_group_name = azurerm_resource_group.rg.name
  kind = "Linux"
  reserved = true

  sku {
    tier = "Basic"
    size = var.app_service_sku
  }
}

# Linux Web App (PHP)
resource "azurerm_linux_web_app" "webapp" {
  name                = var.app_service_name
  resource_group_name = azurerm_resource_group.rg.name
  location            = azurerm_resource_group.rg.location
  service_plan_id     = azurerm_app_service_plan.plan.id

  site_config {
    php_version = "8.2"
  }

  app_settings = {
    "WEBSITE_RUN_FROM_PACKAGE" = "0"
    "DB_HOST"  = azurerm_mysql_flexible_server.mysql.fqdn
    "DB_NAME"  = azurerm_mysql_flexible_database.studydb.name
    "DB_USER"  = "${var.mysql_admin_user}@${azurerm_mysql_flexible_server.mysql.name}"
    "DB_PASS"  = var.mysql_admin_password
  }

  identity {
    type = "SystemAssigned"
  }
}
