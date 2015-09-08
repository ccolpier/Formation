<?php
namespace Field;

class DateField extends \OCFram\Field
{
    const MAXCHARLEN = 10; //10 caractères = assez pour AAAA-MM-DD
    public function buildWidget()
    {
        $widget = '';

        if (!empty($this->errorMessage))
        {
            $widget .= '<br/><li>'.$this->errorMessage.'<li/>';
        }

        $widget .= '<label>'.$this->label.'</label><input type="date" name="'.$this->name.'"';

        if (!empty($this->value))
        {
            $widget .= ' value="'.htmlspecialchars($this->value).'"';
        }

        $widget .= ' maxlength="'.self::MAXCHARLEN.'" />';

        return $widget;
    }
}