# easybroker_sync
Wordpress Plugin to sync EasyBroker data to Wordpress

## Run cron in background
```
wp cron event list
wp cron event run easybroker_sync_cron_hook
```

## monitor on docker container
```
tail -f /opt/bitnami/wordpress/wp-content/debug.log
```