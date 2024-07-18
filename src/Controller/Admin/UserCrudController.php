<?php

namespace App\Controller\Admin;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

Use App\Entity\User;
Use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
Use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
Use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
Use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
Use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
Use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PasswordField;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;


Use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController {

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher) {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getEntityFqcn(): string {
        return User::class;
    }

    public static function getSubscribedEvents() {
        return [
            BeforeEntityPersistedEvent::class => ['hashPassword'],
            BeforeEntityUpdatedEvent::class => ['hashPassword'],
        ];
    }

    public function hashPassword($event) {
        $entity = $event->getEntityInstance();
        if ($entity instanceof User) {
            $entity->setPassword(
                $this->passwordHasher->passwordHasher($entity, $entity->getPassword())
            );
        }
    }
    public function configureFields(string $pageName): iterable {
        $roles = [
            'User' => 'ROLE_USER',
            'Admin' => 'ROLE_ADMIN',
        ];
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('firstName'),
            TextField::new('lastName'),
            EmailField::new('email'),
            TextField::new('phoneNumber'),
            TextField::new('password')->hideOnIndex(),
            ChoiceField::new('roles')
                ->allowMultipleChoices()
                ->setChoices($roles)
                ->renderExpanded()
                ->renderAsBadges(),
            TextField::new('billingAdress'),
            TextField::new('shippingAddress'),
            BooleanField::new('isActive')->hideOnForm(),
        ];
    }

}
