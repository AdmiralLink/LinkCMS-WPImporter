# LinkCMS WPImporter

## Info

A module to do a very basic import of WordPress content. Assumes WP content is using Classic Mode - has not been tested with blocks. Requires Pages and Posts modules to be installed to import that type of content.

## Instructions
- Requires direct database access to Wordpress. Insert these items with the Wordpress credentials as "database" in module.json after version:

```
    "database": {
        "hostname": "localhost",
        "dbname": "databasename",
        "user": "root",
        "password": "root",
        "postsTable": "wp_posts"
    }
```
- Go to the page in manage, select your content and hit import.