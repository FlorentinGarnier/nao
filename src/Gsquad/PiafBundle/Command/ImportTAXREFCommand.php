<?php
/**
 * Created by PhpStorm.
 * User: florentingarnier
 * Date: 11/11/2016
 * Time: 21:44
 */

namespace Gsquad\PiafBundle\Command;


use Gsquad\PiafBundle\Entity\Piaf;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportTAXREFCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        //Name and description for bin/console command
        $this
            ->setName('gsquad:import:taxref')
            ->setDescription('Import Piafs from TAXREF CSV file')
            ->setHelp('This command allows you to import TAXREF classe AVES')
            ->addArgument('filePath', InputArgument::REQUIRED, 'The path of the CSV File')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        // Showing when the script is launched
        $now = new \DateTime();
        $output->writeln('<comment>Début : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        // Importing CSV on DB via Doctrine ORM
        $csvFile = $input->getArgument('filePath');

        $header = null;
        $data = [];

        if (($handle = fopen($csvFile, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 0, ';')) !== FALSE) {
                //TAXREF est en ISO-8859-1 motherfucker
                $row = array_map('utf8_encode', $row);
                if(!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        $size = count($data);
        $em = $this->getContainer()->get('doctrine')->getManager();

        $i = 1;
        $batchSize = 20;

        $progress = new ProgressBar($output, $size);

        foreach ($data as $item) {

            $piaf = new Piaf();
            $piaf->setRegne($item['REGNE']);
            $piaf->setPhylum($item['PHYLUM']);
            $piaf->setClasse($item['CLASSE']);
            $piaf->setOrdre($item['ORDRE']);
            $piaf->setFamily($item['FAMILLE']);
            $piaf->setHabitat($item['HABITAT']);
            $piaf->setCdNom($item['CD_NOM']);
            $piaf->setCdTaxSup($item['CD_TAXSUP']);
            $piaf->setCdRef($item['CD_REF']);
            $piaf->setRang($item['RANG']);
            $piaf->setLbNom($item['LB_NOM']);
            $piaf->setLbAuteur($item['LB_AUTEUR']);
            $piaf->setNomComplet($item['NOM_COMPLET']);
            $piaf->setNomValide($item['NOM_VALIDE']);
            $piaf->setFr($item['FR']);
            $piaf->setGf($item['GF']);
            $piaf->setMar($item['MAR']);
            $piaf->setGua($item['GUA']);
            $piaf->setSm($item['SM']);
            $piaf->setSb($item['SB']);
            $piaf->setSpm($item['SPM']);
            $piaf->setMay($item['MAY']);
            $piaf->setEpa($item['EPA']);
            $piaf->setReu($item['REU']);
            $piaf->setTaaf($item['TAAF']);
            $piaf->setNc($item['NC']);
            $piaf->setWf($item['WF']);
            $piaf->setPf($item['PF']);
            $piaf->setCli($item['CLI']);

            $piaf->setNameLatin($item['NOM_VERN']);
            $piaf->setNameVern($item['NOM_VERN']);
            $piaf->setNameVernEng($item['NOM_VERN_ENG']);


            $em->persist($piaf);

            // Each 20 users persisted we flush everything
            if (($i % $batchSize) === 0) {

                $em->flush();
                // Detaches all objects from Doctrine for memory save
                $em->clear();

                // Advancing for progress display on console
                $progress->advance($batchSize);

                $now = new \DateTime();
                $output->writeln(' de piafs importé ... | ' . $now->format('d-m-Y G:i:s'));

            }

            $i++;
        }

        $em->flush();
        $em->clear();

        //ending progress bar
        $progress->finish();






        // Showing when the script is over
        $now = new \DateTime();
        $output->writeln('<comment>Fin : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }



}