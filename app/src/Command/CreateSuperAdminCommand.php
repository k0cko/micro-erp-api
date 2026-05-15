<?php

namespace App\Command;

use App\Entity\User;
use App\Enum\UserRole;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Ask;
use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-super-admin',
    description: 'Creates a super admin user.',
    help: 'This command allows you to create a super admin user. You will be prompted to enter the username, password, first name, and last name of the super admin.',
)]
class CreateSuperAdminCommand
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ){}

    public function __invoke(
        #[Argument]
        #[Ask('Enter the username: ')]
        string $username,
        #[Argument]
        #[Ask('Enter the password: ', hidden: true)]
        string $password,
        #[Argument]
        #[Ask('Enter the first name: ')]
        string $firstName,
        #[Argument]
        #[Ask('Enter the last name: ')]
        string $lastName,
        InputInterface $input,
        OutputInterface $output,
    ) {
        $errors = [];
        if (strlen($username) > 30) {
            $errors[] = 'Username cannot exceed 30 characters.';
        }
        if (!preg_match('/^[A-Za-z0-9]+(?:[_-][A-Za-z0-9]+)*$/', $username)) {
            $errors[] = 'Username can only contain letters, numbers, underscores, and hyphens, and cannot start or end with an underscore or hyphen.';
        }
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long.';
        }
        if (strlen($firstName) > 64) {
            $errors[] = 'First name cannot exceed 64 characters.';
        }
        if (strlen($lastName) > 64) {
            $errors[] = 'Last name cannot exceed 64 characters.';
        }
        if ($this->userRepository->existsByUsername($username)) {
            $errors[] = 'A user with the username "' . $username . '" already exists.';
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                $output->writeln('<error>' . $error . '</error>');
            }
            return ConsoleCommand::FAILURE;
        }

        $helper = new QuestionHelper();

        $question = new ConfirmationQuestion('Are you sure you want to create a super admin user with the following details?' . PHP_EOL .
            'Username: ' . $username . PHP_EOL .
            'First Name: ' . $firstName . PHP_EOL .
            'Last Name: ' . $lastName . PHP_EOL .
            'Confirm (yes/no): ', false);

        $confirmed = $helper->ask($input, $output, $question);

        if (!$confirmed) {
            $output->writeln('<comment>Super admin creation cancelled.</comment>');
            return ConsoleCommand::SUCCESS;
        }

        /** @todo Avoid "limbo" object by extracting a PasswordHasher service wrapper */
        $hashedPassword = $this->passwordHasher->hashPassword(new User('', '', '', '', UserRole::SuperAdmin), $password);

        $superAdmin = User::createSuperAdmin($username, $hashedPassword, $firstName, $lastName);

        $this->entityManager->persist($superAdmin);
        $this->entityManager->flush();

        $output->writeln('<info>Super admin user "' . $username . '" created successfully with ID ' . $superAdmin->getId() . '.</info>');

        return ConsoleCommand::SUCCESS;
    }
}