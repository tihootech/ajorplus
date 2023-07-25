<?php

function faDateToEn($strDate, $format='Y-m-d')
{
    return Morilog\Jalali\Jalalian::fromFormat($format, $strDate)->toCarbon()->format($format);
}
