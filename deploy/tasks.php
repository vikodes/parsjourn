<?php

namespace Deployer;

// Custom tasks

desc('Update public_html link');
task('public_html', function() {
    run('ln -sf /home/h39414/data/apps/{{application}}/current/web/ /home/h39414/data/www/{{application}}.dgf.cloud');
});

desc('Update engine vendors');
task('deploy:vendors', function () {
    run('cd {{release_path}}/web/silex && {{bin/composer}} {{composer_options}}');
});