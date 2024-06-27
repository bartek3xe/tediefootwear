<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'create:user')]
class CreateUserCommand extends Command
{
    private bool $isSamePasswords = false;

    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly UserRepository $userRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('manager', 'm', InputOption::VALUE_NONE, 'Role manager');
        $this->addOption('admin', 'a', InputOption::VALUE_NONE, 'Role admin');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = new QuestionHelper();
        $role = ['ROLE_USER'];
        $role = $input->getOption('manager') ? ['ROLE_MANAGER'] : $role;
        $role = $input->getOption('admin') ? ['ROLE_ADMIN'] : $role;
        $email = $helper->ask($input, $output, new Question('Enter email: '));

        if ($user = $this->userRepository->findOneBy(['email' => $email])) {
            $output->writeln('You are going to update already existing user...');
            $this->updateUser($user, $email, $role, $helper, $input, $output);

            return self::SUCCESS;
        }

        $this->createNewUser($email, $role, $input, $output);

        return self::SUCCESS;
    }

    private function updateUser(
        User $user,
        string $email,
        array $role,
        QuestionHelper $helper,
        InputInterface $input,
        OutputInterface $output
    ): void {
        $emailQuestion = new ChoiceQuestion(
            sprintf('Do you want to change address email for %s?', $user->getEmail()),
            ['y', 'n'],
            'n'
        );

        if ($this->isPositive($emailQuestion, $helper, $input, $output)) {
            $email = $helper->ask($input, $output, new Question('Enter email: '));
        }

        $password         = null;
        $passwordQuestion = new ChoiceQuestion(
            sprintf('Do you want to change password for %s?', $user->getEmail()),
            ['y', 'n'],
            'n'
        );

        if ($this->isPositive($passwordQuestion, $helper, $input, $output)) {
            while (!$this->isSamePasswords) {
                $password = $this->setPassword($input, $output);
            }
        }

        $user = $this->prepareUser($email, $password, $role, $user);
        $this->saveUser($user);

        $output->writeln("User has been added properly.");
    }

    private function createNewUser(
        string $email,
        array $role,
        InputInterface $input,
        OutputInterface $output
    ): void {
        while (!$this->isSamePasswords) {
            $password = $this->setPassword($input, $output);
        }

        if (!empty($password)) {
            $user = $this->prepareUser($email, $password, $role);
            $this->saveUser($user);
        }

        $output->writeln("User has been added properly.");
    }

    private function setPassword(InputInterface $input, OutputInterface $output): string
    {
        $password = $this->askAboutPassword('Enter password: ', $input, $output);
        $rePassword = $this->askAboutPassword('Re-Enter password: ', $input, $output);

        $this->isSamePasswords = $password == $rePassword;

        if (!$this->isSamePasswords) {
            $output->writeln('Incorrect passwords');
        }

        return password_hash($password, PASSWORD_DEFAULT);
    }

    private function askAboutPassword(
        string $question,
        InputInterface $input,
        OutputInterface $output
    ): string {
        $helper = new QuestionHelper();
        $question = new Question($question);
        $question->setHidden(true);

        return $helper->ask($input, $output, $question);
    }

    private function prepareUser(
        ?string $email,
        ?string $password,
        ?array $role,
        User $user = new User()
    ): User {
        $email && $user->setEmail($email);
        $password && $user->setPassword($password);
        $role && $user->setRoles($role);

        return $user;
    }

    private function saveUser(User $user): void
    {
        $em = $this->doctrine->getManager();
        $em->persist($user);
        $em->flush();
    }

    private function isPositive(
        Question $question,
        QuestionHelper $helper,
        InputInterface $input,
        OutputInterface $output
    ): bool {
        $answer = $helper->ask($input, $output, $question);

        return $answer === 'y';
    }
}
