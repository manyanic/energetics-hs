<?php
namespace Deployer;

require 'recipe/common.php';

// Project name
set('application', '');

// Project repository
set('repository', '');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
set('shared_files', ['wp-config.php','.htaccess']);
set('shared_dirs', ['wp-content/uploads']);
set('writable_dirs', ['wp-content/uploads']);


set('allow_anonymous_stats', false);

// Hosts
host('18.194.49.253')
    ->set('deploy_path', '');


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
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');