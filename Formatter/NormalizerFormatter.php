<?php

namespace Lexik\Bundle\MonologBrowserBundle\Formatter;

use Monolog\Formatter\NormalizerFormatter as BaseFormatter;

class NormalizerFormatter extends BaseFormatter
{
    protected function normalize($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => &$value) {
                if (is_array($value)) {
                    $value = json_encode($value);
                }
            }
        }

        $data = parent::normalize($data);

        return $data;
    }
}
