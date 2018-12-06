<?php

namespace Deployer;

require 'recipe/common.php';
require 'tasks.php';

// Project name
set('application', 'goatparser');

// Project repository
set('repository', 'anton_bagaiev@bitbucket.org:anton_bagaiev/goat-parser.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
set('shared_files', []);
set('shared_dirs', []);

// Writable dirs by web server 
set('writable_dirs', []);


// Hosts

host('goatparser.dgf.cloud')
    ->user('h39414')
    ->set('deploy_path', '/home/h39414/data/apps/{{application}}');

// Tasks

desc('Deploy your project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

// [Optional] Change public_html dir after successful deploy
//after('success', 'public_html');

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
