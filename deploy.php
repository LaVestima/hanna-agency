<?php
namespace Deployer;

require 'recipe/symfony.php';

// Project name
set('application', 'my_project');

// Project repository
set('repository', 'https://github.com/LaVestima/hanna-agency.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);


// Hosts

host('project.com')
    ->set('deploy_path', '~/{{application}}');    
    
// Tasks

task('build', function () {
    run('pwd {{release_path}}');
    //run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');

task('test', function () {
    writeln('Test task');
});

task('database:rebuild', function () {
    if (askConfirmation('Rebuild database?', false)) {
        run('php {{release_path}}/{{bin_dir}}/console doctrine:database:drop --force --env={{env}}');
        run('php {{release_path}}/{{bin_dir}}/console doctrine:database:create --env={{env}}');
        run('php {{release_path}}/{{bin_dir}}/console doctrine:schema:create --env={{env}}');
        run('php {{release_path}}/{{bin_dir}}/console doctrine:fixtures:load --no-interaction --env={{env}}');
    }
})->desc('Rebuild database')->onlyOn('test');
