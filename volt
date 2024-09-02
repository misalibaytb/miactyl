#!/usr/bin/env php
<?php
$args = $argv;
array_shift($args);
switch ($args[0]) {
    case 'down':
        echo "App is entering maintenance mode...\n";
        // write maintenance = true to .env
        $env = parse_ini_file('.env');
        $env['MAINTENANCE'] = 'true';
        $envContent = '';
        foreach ($env as $key => $value) {
            $envContent .= "{$key} = {$value}\n";
        }
        file_put_contents('.env', $envContent);
        break;
    case 'up':
        echo "App is leaving maintenance mode...\n";
        // write maintenance = false to .env
        $env = parse_ini_file('.env');
        $env['MAINTENANCE'] = 'false';
        $envContent = '';
        foreach ($env as $key => $value) {
            $envContent .= "{$key} = {$value}\n";
        }
        file_put_contents('.env', $envContent);
        break;
    case 'migration':
        switch ($args[1]) {
            case 'down':
                echo "Rolling back migration...\n";
                // rollback migration
                break;
            default:
                echo "Pushing migration...\n";
                $files = glob('database/migrations/*.php');
                foreach ($files as $file) {
                    echo "Processing file: $file\n";
                    require $file; // class <unknown class not matching file name> extends Migration { up() {} down() {} }
                    $declaredClasses = get_declared_classes();
                    $migrationClass = end($declaredClasses);
                    $migration = new $migrationClass;
                    $migration->up();
                    echo "Migration done.\n";
                }
                break;
        }
        break;
    default:
        echo "Unknown command: {$args[0]}\n";
        break;
}
