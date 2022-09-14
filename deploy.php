<?php
namespace Deployer;

require 'recipe/symfony.php';

// Config

set('repository', 'git@github.com:alborcode/fisioterapia_cftic.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('demofisioterapia.alborcode.es')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '~/fisioterapia_cftic');

// Hooks

after('deploy:failed', 'deploy:unlock');
