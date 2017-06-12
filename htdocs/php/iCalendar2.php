<?php

class iCalendar2
{

    protected $properties = array();
    private $available_properties = array(
        'vizita_id',
        'nume_vizitator',
        'prenume_vizitator',
        'cnp_vizitator',
        'cod_detinut',
        'relatie',
        'natura_vizita',
        'data_vizita',
        'ora',
        'poza'
    );

    public function __construct($props)
    {
        $this->set($props);
    }

    public function set($key, $val = false)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->set($k, $v);
            }
        } else {
            if (in_array($key, $this->available_properties)) {
                $this->properties[$key] = $this->escape_string($val);
            }
        }
    }

    public function to_string()
    {
        $rows = $this->build_props();
        return implode("\r\n", $rows);
    }

    private function build_props()
    {
// Build ICS properties - add header
        $ics_props = array(
            'BEGIN ICALENDAR'
        );
// Build ICS properties - add header
        $props = array();
        foreach ($this->properties as $k => $v) {
            $props[strtoupper($k)] = $v;
        }
// Set some default values
        $props['UID'] = uniqid();
// Append properties
        foreach ($props as $k => $v) {
            $ics_props[] = "$k:$v";
        }
// Build ICS properties - add footer
        $ics_props[] = 'END ICALENDAR';
        return $ics_props;
    }

    private function escape_string($str)
    {
        return preg_replace('/([\,;])/', '\\\$1', $str);
    }
}



