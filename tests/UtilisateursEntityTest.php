<?php

namespace App\Tests;

use App\Entity\Utilisateurs;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UtilisateursEntityTest extends KernelTestCase
{
    private const Email_Constraint_Message = 'veuillez saisir une adresse mail correcte';
    private const Password_Constraint_Message="Un mot de passe valide aura: de 8 à 15 caractères, au moins une lettre minuscule, au moins une lettre majuscule, au moins un chiffre et au moins un caractère spécial";
    private const Not_Blank_Message="Veuillez saisir une valeur";
    private const Invalid_Email_Value ="red-men@gmail";
    private const Valid_Email_Value ="red-men@gmail.com";
    private const Valid_Password_Value = "Test@123";
    private ValidatorInterface $validator;

    protected function setup(): void
    {
        $kernel =self::bootKernel();

        $this->validator = $kernel->getContainer()->get('validator');
    }
    
    public function testUserEntityIsValid(): void
    {
        $user = new Utilisateurs();

        $user ->setEmail(self::Valid_Email_Value)
        ->setPassword(self::Valid_Password_Value);

        $this->getValidationErrors($user,0);
    }

    public function testUserEntityIsNotValidBecouseNoEmailEntred():void
    {
        $user= new Utilisateurs();

        $user->setPassword(self::Valid_Password_Value);

        $errors =$this->getValidationErrors($user,1);

        $this->assertEquals(self::Not_Blank_Message,$errors[0]->getMessage());
    }
    public function testUserEntityIsNotValidBecouseNoPasswordEntred():void
    {
        $user= new Utilisateurs();

        $user->setEmail(self::Valid_Email_Value);

        $errors =$this->getValidationErrors($user,1);

        $this->assertEquals(self::Not_Blank_Message,$errors[0]->getMessage());
    }
    
    public function testUserEntityIsNotValidBecouseAnInvalidEmailHasBeenEntered():void
    {
        $user= new Utilisateurs();

        $user->setEmail(self::Invalid_Email_Value)
        ->setPassword(self::Valid_Password_Value);

        $errors =$this->getValidationErrors($user,1);

        $this->assertEquals(self::Email_Constraint_Message,$errors[0]->getMessage());
    }

    /**
     * @dataProvider provideInvalidPassword
     */
    public function testUserEntityIsNotValidBecouseAnInvalidPasswordHasBeenEntered(string $invalidPassword):void
    {
        $user= new Utilisateurs();

        $user->setEmail(self::Valid_Email_Value)
        ->setPassword($invalidPassword);

        $errors =$this->getValidationErrors($user,1);

        $this->assertEquals(self::Password_Constraint_Message,$errors[0]->getMessage());
    }

    public function provideInvalidPassword(): array
    {
        return[
           "No_Special_Character"=> ['Test123'],//no special character
           "No_Number"=>['Test@azer'],//no number
           "No_8_Characters"=>['Test@1'],// no 8 characters
           "No_Uppercase"=>['test@123'],// no uppercase
           "No_Lowercase"=>['TEST@123']// no lowercase
        ];
    }

    private function getValidationErrors(Utilisateurs $user , int $numberoffExpectedErrors): ConstraintViolationList
    {
        $errors = $this->validator->validate($user);

        $this->assertCount($numberoffExpectedErrors,$errors);

        return $errors;
    }
}
