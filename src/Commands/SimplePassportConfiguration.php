<?php

namespace Heloufir\SecurityStarter\Commands;

use Illuminate\Console\Command;

class SimplePassportConfiguration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'starter:config {--T|trust : If added to the command, the configuration is done without asking you to perform actions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simple passport auto. configuration provided by security-starter package';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->option('trust')) {
            $this->step();
        } else {
            if ($this->confirm('You wan\'t to publish the SimplePassport provider?')) {
                $this->step(1);
            }
            if ($this->confirm('You wan\'t to publish the SecurityStarter provider?')) {
                $this->step(2);
            }
            if ($this->confirm('You wan\'t to customize SecurityStarter tables names?')) {
                $this->step(3);
            }
            if ($this->confirm('You wan\'t to migrate laravel/passport and heloufir/simple-passport migrations?')) {
                try {
                    $this->step(4);
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                    $this->line('');
                    $this->error('>>>>>> Please fix the above error, and execute the starter:config command again!');
                    $this->line('');
                    return false;
                }
            }
            if ($this->confirm('You wan\'t to install laravel/passport keys?')) {
                $this->step(5);
            }
        }
        $this->line('');
        $this->line('***************************************************************************************************************************');
        $this->line('Almost Done! You still need to do the following steps:');
        $this->line('');
        $this->line('  1. Add Laravel\Passport\HasApiTokens trait to the User model');
        $this->line('  2. Add Heloufir\SecurityStarter\Core\UserProfiles trait to the User model');
        $this->line('  3. Add \'roles\' => \Heloufir\SecurityStarter\Http\Middleware\RoleMiddleware::class to the $routeMiddleware in Kernel');
        $this->line('***************************************************************************************************************************');
        $this->line('');
        return true;
    }

    /**
     * Configuration steps
     *
     * @param int $step
     *      The configuration step to do
     *      >> Default value is -1, in this case all steps are executed
     *
     * @author EL OUFIR Hatim <eloufirhatim@gmail.com>
     */
    private function step(int $step = -1)
    {
        switch ($step) {
            case 1:
                $this->call('vendor:publish', [
                    '--provider' => 'Heloufir\SimplePassport\SimplePassportServiceProvider'
                ]);
                $this->call('config:cache');
                break;
            case 2:
                $this->call('vendor:publish', [
                    '--provider' => 'Heloufir\SecurityStarter\SecurityStarterServiceProvider'
                ]);
                $this->call('config:cache');
                break;
            case 3:
                $profiles = $this->ask('Name of the "profiles" table? (default: ' . config('security-starter.tables.profiles') . ')') ?? config('security-starter.tables.profiles');
                $roles = $this->ask('Name of the "roles" table? (default: ' . config('security-starter.tables.roles') . ')') ?? config('security-starter.tables.roles');
                $profileRole = $this->ask('Name of the "profile_roles" table? (default: ' . config('security-starter.tables.associations.profile_roles') . ')') ?? config('security-starter.tables.associations.profile_roles');
                $userProfiles = $this->ask('Name of the "user_profiles" table? (default: ' . config('security-starter.tables.associations.user_profiles') . ')') ?? config('security-starter.tables.associations.user_profiles');
                $this->updateConfigFile('security-starter.tables.profiles', $profiles);
                $this->updateConfigFile('security-starter.tables.roles', $roles);
                $this->updateConfigFile('security-starter.tables.associations.profile_roles', $profileRole);
                $this->updateConfigFile('security-starter.tables.associations.user_profiles', $userProfiles);
                $this->call('config:cache');
                break;
            case 4:
                $this->call('migrate');
                break;
            case 5:
                $this->call('passport:install');
                break;
            case -1:
                $this->step(1);
                $this->step(2);
                $this->step(4);
                $this->step(5);
                break;
            default:
                break;
        }
    }

    /**
     * Update configuration file
     *
     * @param string $old
     *      The old value to update
     * @param string $new
     *      The new value to set
     *
     * @author EL OUFIR Hatim <eloufirhatim@gmail.com>
     */
    private function updateConfigFile(string $old, string $new)
    {
        $content = file_get_contents(config_path('security-starter.php'));
        $search = '/\=\>\s*\'' . config($old) . '\'\,/m';
        $replace = '=> \'' . $new . '\',';
        $content = preg_replace($search, $replace, $content);
        file_put_contents(config_path('security-starter.php'), $content);
    }
}
