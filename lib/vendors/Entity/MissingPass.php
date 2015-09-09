<?php
namespace Entity;

use \OCFram\Entity;
use \Entity\Member;

class MissingPass extends Entity
{
    /**
     * @var $member Member
     */
    protected $member,
        $code;

    const MEMBER_INVALIDE = 1;
    const CODE_INVALIDE = 2;

    public function isValid()
    {
        return !(empty($this->member) || empty($this->code));
    }

    public function setMember(Member $member)
    {
        if(!$member instanceof Member) {
            $this->erreurs[] = self::MEMBER_INVALIDE;
        }
        $this->member = $member;
    }

    public function setAuteur($code)
    {
        if (!is_string($code) || empty($code))
        {
            $this->erreurs[] = self::CODE_INVALIDE;
        }

        $this->code = $code;
    }

    public function id(){
        return $this->id;
    }

    public function name(){
        return $this->member()->nickname();
    }

    public function member()
    {
        return $this->member;
    }

    public function code()
    {
        return $this->code;
    }
}