<?php 

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    const EDIT = 'USER_READ';
    const UPDATE = 'USER_UPDATE';
    const DELETE = 'USER_DELETE';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if(!in_array($attribute, [self::READ, self::UPDATE, self::DELETE])){
            return false;
        }
        if(!$subject instanceof User){
            return false;
        }
    }

    protected function voteOnAttribute($attribute, $product, TokenInterface $token):bool
    {
        $user = $token->getUser();
        if(!$user instanceof UserInterface)return false;

        if($this->security->isGranted('ROLE_ADMIN'))return true;

        switch($attribute){
            case self::READ:
                return $this->canEdit();
                break;
            case self::UPDATE:
                return $this->canUpdate();
                break;
            case self::DELETE:
                return $this->canDelete();

        }
    }

    private function canRead(){
        return $this->security->isGranted('ROLE_USER');
    }
    private function canUpdate(){
        return $this->security->isGranted('ROLE_GESTION');
    }
    private function canDelete(){
        return $this->security->isGranted('ROLE_ADMIN');
    }

}
