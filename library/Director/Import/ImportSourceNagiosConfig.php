<?php

namespace Icinga\Module\Director\Import;

use Icinga\Module\Director\Web\Form\QuickForm;
use Icinga\Module\Director\Web\Hook\ImportSourceHook;
use Icinga\Web\Form;

class ImportSourceNagiosConfig extends ImportSourceHook
{
    public function fetchData()
    {
        return array();
    }

    public function listColumns()
    {
        return array_keys((array) current($this->fetchData()));
    }

    public static function addSettingsFormFields(QuickForm $form)
    {
        $form->addElement('text', 'nagios_config', array(
            'label'       => 'Nagios config',
            'description' => 'Path to nagios.cfg, often /etc/nagios/nagios.cfg',
            'required'    => true,
        ));
        $form->addElement('select', 'core_type', array(
            'label'    => 'Core Type',
            'multiOptions' => array(
                null      => '- please choose -',
                'nagios3' => 'Nagios 3.x',
                'icinga1' => 'Icinga 1.x',
            ),
            'required' => true,
        ));
        $form->addElement('select', 'core_type', array(
            'label'    => 'Object Type',
            'multiOptions' => array(
                null        => '- please choose -',
                'host'      => 'Hosts',
                'hostgroup' => 'Hostgroups',
                'service'   => 'Services',
            ),
            'required' => true,
        ));
        return $form;
    }
}
