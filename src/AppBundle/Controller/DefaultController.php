<?php

namespace AppBundle\Controller;

use AppBundle\Entity\File as FileEntity;
use AppBundle\Entity\Import;
use AppBundle\Entity\Report;
use AppBundle\Form\File as FileForm;
use AppBundle\Form\Mapping;
use AppBundle\Service\FileUploader;
use AppBundle\Utils\CsvManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, FileUploader $fileUploader)
    {
        $fileEntity = new FileEntity();

        $form = $this->createForm(FileForm::class, $fileEntity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newFile = $fileUploader->upload($fileEntity->getFile());

            $csvManager = new CsvManager($this->get('logger'));
            $csvManager->parseFile($newFile->getPathname());

            if ($csvManager->isColumnNumberValid()) {
                return $this->redirect('mapping');
            } else {
                $form->get('file')->addError(new FormError("Invalid number of column"));
            }
        }

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/mapping")
     */
    public function mappingAction(Request $request, KernelInterface $kernel)
    {
        ini_set('max_execution_time', $this->getParameter('max_import_time'));
        if ($this->tableNotExist()) {
            $application = new Application($kernel);
            $application->setAutoExit(false);

            $input = new ArrayInput(array(
                'command' => 'app:create-table-user',
            ));
            $application->run($input, new NullOutput());
        }

        $form = $this->createForm(Mapping::class, null, ['userTable' => $this->getParameter('user_table')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $import = new Import($form->getData(), $this->get('database_connection'), $this->get('logger'));
            $import->run();

            $report = new Report($import);

            $csvManager = new CsvManager($this->get('logger'));
            $csvManager->clearCache();

            return $this->render('default/report.html.twig', [
                'report' => $report->generateReport()
            ]);

        }

        return $this->render('default/mapping.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function tableNotExist()
    {
        return empty($this->get('database_connection')->fetchAll('SHOW TABLES LIKE "' . $this->getParameter('user_table') . '"'));
    }

}
