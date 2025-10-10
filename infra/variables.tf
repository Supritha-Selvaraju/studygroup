variable "location" {
  type    = string
  default = "southindia"    # change if you prefer another region
}

variable "resource_group_name" {
  type    = string
  default = "studygroup-rg"
}

variable "mysql_server_name" {
  type    = string
  default = "studygroup-mysql"
}

variable "mysql_admin_user" {
  type    = string
  default = "dbadmin"
}

variable "mysql_admin_password" {
  type      = string
  sensitive = true
  description = "Provide via TF_VAR_mysql_admin_password or a secrets file (do NOT commit)."
}

variable "mysql_database_name" {
  type    = string
  default = "studygroup_db"
}

variable "app_service_name" {
  type    = string
  default = "studygroup-web"
}

variable "app_service_sku" {
  type    = string
  default = "B1"   # Basic - change to S1 or P1 for production
}
