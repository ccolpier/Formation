<?php

namespace Model;
use \Entity\MissingPass;
use \Entity\Member;

abstract class MissingPassManager extends \OCFram\Manager{
    public function save(MissingPass $missingPass)
    {
        if ($missingPass->isValid())
        {
            $missingPass->isNew() ? $this->add($missingPass) : $this->modify($missingPass);
        }
        else
        {
            throw new \RuntimeException('Le member doit �tre valid� pour �tre enregistr�');
        }
    }
    /**
     * Retourne un code
     * @return string
     */
    public static function generate($length = 8){
        $chars = array_merge(range('a', 'z') , range('A', 'Z') , range('0', '9'));
        $arrLength = count($chars);
        $code = '';
        for($i = 0; $i < $length; $i++){
            $code .= $chars[rand(0, $arrLength - 1)];
        }
        return $code;
    }

    /**
     * Ajoute un missingPass pour le membre. Refresh le code si d�j� �xistant
     * @param Member $member
     * @return MissingPass
     */
    abstract public function add(Member $member);

    /**
     * R�cup�re le missingPass du membre si il �xiste
     * @param Member $member
     * @return MissingPass
     */
    abstract public function get(Member $member);

    /**
     * Met � jour le code du missingPass du membre
     * @return MissingPass
     */
    abstract public function refresh(Member $member);

    /**
     * Efface le code de manque pour un membre
     * @param Member $member
     * @return NULL
     */
    abstract public function delete(Member $member);
}