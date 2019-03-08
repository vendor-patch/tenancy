<?php declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Identification\Support;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Tenancy\Identification\Contracts\ResolvesTenants;

abstract class DriverProvider extends EventServiceProvider
{
    protected $drivers = [];

    protected $configs = [];

    public function register()
    {
        $this->app->resolving(ResolvesTenants::class, function (ResolvesTenants $resolver) {
            foreach ($this->drivers as $contract => $method) {
                $resolver->registerDriver($contract, $method);
            }
        });

        foreach ($this->configs as $config) {
            $configPath = basename($config);
            $configName = basename($config, '.php');

            $this->publishes([$config => config_path($configPath)], [$configName, "tenancy"]);

            $this->mergeConfigFrom($config, $configName);
        }
    }
}
