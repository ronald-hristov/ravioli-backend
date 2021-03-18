<?php


namespace App\DBAL;


class UserRole extends EnumType
{
    const NAME = 'UserRole';

    const MEMBER = 'member';
    const ADMIN = 'admin';

    protected $values = [self::MEMBER, self::ADMIN];
}