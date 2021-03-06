<?php

namespace Sebaks\Crud\Controller;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\Controller\AbstractActionController;
use T4webDomainInterface\Service\CreatorInterface;
use Sebaks\Crud\View\Model\CreateViewModelInterface;

class CreateController extends AbstractActionController
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var CreatorInterface
     */
    private $creator;

    /**
     * @var CreateViewModelInterface
     */
    private $viewModel;

    /**
     * @var string
     */
    private $redirectTo;

    /**
     * @param array $data
     * @param CreatorInterface $creator
     * @param CreateViewModelInterface $viewModel
     * @param null $redirectTo
     */
    public function __construct(
        array $data,
        CreatorInterface $creator,
        CreateViewModelInterface $viewModel,
        $redirectTo = null)
    {
        $this->data = $data;
        $this->creator = $creator;
        $this->viewModel = $viewModel;
        $this->redirectTo = $redirectTo;
    }

    /**
     * Execute the request
     *
     * @param  MvcEvent $e
     * @return CreateViewModelInterface|\Zend\Http\Response
     */
    public function onDispatch(MvcEvent $e)
    {
        $entity = $this->creator->create($this->data);

        if ($entity) {
            if ($this->redirectTo) {
                return $this->redirect()->toRoute($this->redirectTo);
            }

            $this->viewModel->setEntity($entity);
        } else {
            $this->viewModel->setErrors($this->creator->getErrors());
            $this->viewModel->setInputData($this->data);
        }

        $e->setResult($this->viewModel);

        return $this->viewModel;
    }
}
