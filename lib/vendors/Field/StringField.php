<?php
namespace Field;

class StringField extends \OCFram\Field
{
    protected $maxLength;

    public function buildWidget()
    {
        $widget = '';

        if (!empty($this->errorMessage))
        {
            $widget .= '<br/><li>'.$this->errorMessage.'<li/>';
        }

        $widget .= '<label>'.$this->label.'</label><input type="text" name="'.$this->name.'" id="form'.$this->name.'"';

        if (!empty($this->value))
        {
            $widget .= ' value="'.htmlspecialchars($this->value).'"';
        }

        if (!empty($this->maxLength))
        {
            $widget .= ' maxlength="'.$this->maxLength.'"';
        }

        return $widget .= ' />';
    }

    public function setMaxLength($maxLength)
    {
        $maxLength = (int) $maxLength;

        if ($maxLength > 0)
        {
            $this->maxLength = $maxLength;
        }
        else
        {
            throw new \RuntimeException('La longueur maximale doit �tre un nombre sup�rieur � 0');
        }
    }
}