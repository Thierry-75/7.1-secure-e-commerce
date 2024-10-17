<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:create-gestionnaire',description: 'Creation compte Gestionnaire')]
class CreateGestionnaireCommand extends Command
{

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct('app:create-gestionnaire');
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email',InputArgument::OPTIONAL,'Email - obligatoire')
            ->addArgument('password',InputArgument::OPTIONAL,'Mot de passe obligatoire');
    }
    protected function execute(InputInterface $input,OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $io = new SymfonyStyle($input,$output);
        $email = $input->getArgument('email');
        if(!$email){
            $question = new Question('Quelle est votre adresse courriel ?');
            $email = $helper->ask($input,$output,$question);
        }
        $plainPassword = $input->getArgument('password');
        if(!$plainPassword){
            $question = new Question('Quel est votre mot de passe ?');
            $plainPassword = $helper->ask($input,$output,$question);
        }

        $user = (new User())->setEmail($email)
                            ->setPlainpassword($plainPassword)
                            ->setRoles(['ROLE_USER','ROLE_GESTION'])
                            ->setCreateAt(new \DateTimeImmutable());

        $this->em->persist($user);
        $this->em->flush();
        $io->success('le compte gestionnaire a été créé !');
        return Command::SUCCESS;
    }
}