<?php

namespace LinkCMS\Modules\WPImporter;

use \Flight;
use LinkCMS\Actor\Display;
use LinkCMS\Actor\Core;
use LinkCMS\Actor\User as User;
use LinkCMS\Model\User as UserModel;

class Route {
    public static function do_routes() {
        Flight::route('GET /manage/wpimporter', function() {
            User::is_authorized(UserModel::USER_LEVEL_AUTHOR);
            $core = Core::load();
            foreach($core->modules->active as $module) {
                if ($module->name == 'WP Importer') {
                    $database = (isset($module->database)) ? $module->database : false;
                }
            }
            Display::load_page('wpimporter/manage/index.twig', ['db' => $database]);
        });
        
        Flight::route('POST /manage/wpimporter', function() {
            User::is_authorized(UserModel::USER_LEVEL_AUTHOR);
            $core = Core::load();
            foreach($core->modules->active as $module) {
                if ($module->name == 'WP Importer') {
                    $database = (isset($module->database)) ? $module->database : false;
                }
            }
            Actor::import($database);
        });
    }
}