<?php

namespace LinkCMS\Modules\WPImporter;

use \Flight;
use LinkCMS\Actor\Core;
use LinkCMS\Actor\Display;
use LinkCMS\Actor\Notify;
use LinkCMS\Actor\Route as Router;
use LinkCMS\Controller\Content as ContentController;
use LinkCMS\Model\Content as ContentModel;
use LinkCMS\Modules\WPImporter\Model\ImportContent;

class Actor {
    public static function register() {
        Router::add_route(['LinkCMS\Modules\WPImporter\Route', 'do_routes']);
        Display::add_template_directory(__DIR__.'/templates');
        Core::add_menu_item('import', 'WP Importer', '/manage/wpimporter', false, 30);
    }

    public static function import($databaseInfo) {
        if ($databaseInfo) {
            $required = ['hostname', 'dbname', 'user', 'password', 'postsTable'];
            foreach ($required as $item) {
                if (!isset($databaseInfo->{$item})) {
                    throw new \Exception('Missing database information for WP Importer: ' . $item);
                }
            }
            $postTypes = '';
            $pdo = new \PDO('mysql:host=' . $databaseInfo->hostname .';dbname=' . $databaseInfo->dbname, $databaseInfo->user, $databaseInfo->password);
            if (isset($_POST['type-pages'])) {
                $postTypes = 'post_type = "page"'; 
            } 
            if (isset($_POST['type-posts'])) {
                if ($postTypes) {
                    $postTypes .= ' OR ';
                }
                $postTypes .= 'post_type = "post"';
            }
            if ($postTypes) {
                $queryString = 'SELECT * FROM ' . $databaseInfo->postsTable . ' WHERE post_status = "publish" AND (' . $postTypes . ')';
                $query = $pdo->query($queryString);
                $results = $query->fetchAll();
                if ($results) {
                    foreach ($results as $post) {
                        $content = [new ImportContent($post['post_content'])];
                        $importData = [
                            'excerpt' => $post['post_excerpt'],
                            'slug' => $post['post_name'],
                            'title' => $post['post_title'],
                            'type' => $post['post_type'],
                            'template' => $post['post_type'],
                            'status' => 'published',
                            'pubDate' => strtotime($post['post_date']),
                            'draftModifiedDate' => time(),
                            'publishedModifiedDate' => time(),
                            'draftContent' => $content,
                            'publishedContent' => $content
                        ];
                        $content = new ContentModel($importData);
                        ContentController::save($content);
                    }
                    Core::add_message('Content imported successfully', 'success');
                } else {
                    Core::add_message('No content to import', 'warning');
                }
                Flight::redirect('/manage/wpimporter');
            } else {
                Flight::redirect('/manage/wpimporter');
            }
        } else {
            new \Exception('No database information provided in module.json');
        }
    }
}
