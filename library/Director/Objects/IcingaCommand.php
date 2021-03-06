<?php

namespace Icinga\Module\Director\Objects;

use Icinga\Module\Director\IcingaConfig\IcingaConfigHelper as c;

class IcingaCommand extends IcingaObject
{
    protected $table = 'icinga_command';

    protected $type = 'CheckCommand';

    protected $defaultProperties = array(
        'id'                    => null,
        'object_name'           => null,
        'methods_execute'       => null,
        'command'               => null,
        'timeout'               => null,
        'zone_id'               => null,
        'object_type'           => null,
    );

    protected $supportsCustomVars = true;

    protected $supportsFields = true;

    protected $supportsImports = true;

    protected $supportsArguments = true;

    protected static $pluginDir;

    protected function renderMethods_execute()
    {
        // Execute is a reserved word in SQL, column name was prefixed
        return c::renderKeyValue('execute', $this->methods_execute);
    }

    public function mungeCommand($value)
    {
        if (is_array($value)) {
            $value = implode(' ', $value);
        } elseif (is_object($value)) {
            // {  type => Function } -> really??
            return null;
            return $value;
        }

        if (self::$pluginDir !== null) {
            if (($pos = strpos($value, self::$pluginDir)) === 0) {
                $value = substr($value, strlen(self::$pluginDir) + 1);
            }
        }

        return $value;
    }

    protected function renderCommand()
    {
        $command = $this->command;
        $prefix = '';
        if (preg_match('~^([A-Z][A-Za-z0-9]+\s\+\s)(.+?)$~', $command, $m)) {
            $prefix  = $m[1];
            $command = $m[2];
        } elseif ($command[0] !== '/') {
            $prefix = 'PluginDir + ';
            $command = '/' . $command;
        }
        $parts = preg_split('/\s+/', $command, -1, PREG_SPLIT_NO_EMPTY);
        array_unshift($parts, c::alreadyRendered($prefix . c::renderString(array_shift($parts))));
        
        return c::renderKeyValue('command', c::renderArray($parts));
    }

    protected function renderTimeout()
    {
        return $this->renderPropertyAsSeconds('timeout');
    }

    public static function setPluginDir($pluginDir)
    {
        self::$pluginDir = $pluginDir;
    }
}
