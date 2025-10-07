#!/bin/bash
set -e

# --- CONFIG ---
LOCAL_DB="studygroup_db"
LOCAL_USER="root"
LOCAL_PASS="$LOCAL_DB_PASSWORD"
CONTAINER_NAME="mysql"

AZURE_HOST="studygroup-mysql.mysql.database.azure.com"
AZURE_USER="Supritha_S"
AZURE_PASS="$AZURE_DB_PASSWORD"
AZURE_SSL="--ssl"

# --- EXPORT FROM DOCKER MYSQL ---
echo "📤 Exporting database from Docker MySQL..."
docker exec -i $CONTAINER_NAME mysqldump -u$LOCAL_USER -p$LOCAL_PASS $LOCAL_DB > dump.sql

# --- IMPORT INTO AZURE MYSQL ---
echo "📥 Importing into Azure MySQL..."
mysql -h $AZURE_HOST -u $AZURE_USER -p$AZURE_PASS $AZURE_SSL < dump.sql

echo "✅ Database sync completed successfully!"
