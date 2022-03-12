<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\QuoteBundle\Controller;

use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\ExchangeFormType;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\Request\CreateExchangeRequest;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\Request\DeleteExchangeRequest;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\Request\IndexExchangeRequest;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\Request\ReadExchangeRequest;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\Request\UpdateExchangeRequest;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\UseCase\CreateExchangeUseCase;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\UseCase\DeleteExchangeUseCase;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\UseCase\IndexExchangeUseCase;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\UseCase\ReadExchangeUseCase;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\UseCase\UpdateExchangeUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ExchangeController extends AbstractController
{
    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_CURRENCY_INDEX')", statusCode=401)
     */
    public function index(Request $request, IndexExchangeUseCase $useCase): Response
    {
        $template = $request->isXmlHttpRequest() ? '@FlexPHPQuote/exchange/_ajax.html.twig' : '@FlexPHPQuote/exchange/index.html.twig';

        $request = new IndexExchangeRequest($request->request->all(), (int)$request->query->get('page', 1));

        $response = $useCase->execute($request);

        return $this->render($template, [
            'exchanges' => $response->exchanges,
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_CURRENCY_CREATE')", statusCode=401)
     */
    public function new(): Response
    {
        $form = $this->createForm(ExchangeFormType::class);

        return $this->render('@FlexPHPQuote/exchange/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_CURRENCY_CREATE')", statusCode=401)
     */
    public function create(Request $request, CreateExchangeUseCase $useCase, TranslatorInterface $trans): Response
    {
        $form = $this->createForm(ExchangeFormType::class);
        $form->handleRequest($request);

        $request = new CreateExchangeRequest($form->getData());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.created', [], 'exchange'));

        return $this->redirectToRoute('flexphp.quote.exchanges.index');
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_CURRENCY_READ')", statusCode=401)
     */
    public function read(ReadExchangeUseCase $useCase, string $id): Response
    {
        $request = new ReadExchangeRequest($id);

        $response = $useCase->execute($request);

        if (!$response->exchange->id()) {
            throw $this->createNotFoundException();
        }

        return $this->render('@FlexPHPQuote/exchange/show.html.twig', [
            'exchange' => $response->exchange,
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_CURRENCY_UPDATE')", statusCode=401)
     */
    public function edit(ReadExchangeUseCase $useCase, string $id): Response
    {
        $request = new ReadExchangeRequest($id);

        $response = $useCase->execute($request);

        if (!$response->exchange->id()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(ExchangeFormType::class, $response->exchange);

        return $this->render('@FlexPHPQuote/exchange/edit.html.twig', [
            'exchange' => $response->exchange,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_CURRENCY_UPDATE')", statusCode=401)
     */
    public function update(Request $request, UpdateExchangeUseCase $useCase, TranslatorInterface $trans, string $id): Response
    {
        $form = $this->createForm(ExchangeFormType::class);
        $form->submit($request->request->get($form->getName()));
        $form->handleRequest($request);

        $request = new UpdateExchangeRequest($id, $form->getData());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.updated', [], 'exchange'));

        return $this->redirectToRoute('flexphp.quote.exchanges.index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_CURRENCY_DELETE')", statusCode=401)
     */
    public function delete(DeleteExchangeUseCase $useCase, TranslatorInterface $trans, string $id): Response
    {
        $request = new DeleteExchangeRequest($id);

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.deleted', [], 'exchange'));

        return $this->redirectToRoute('flexphp.quote.exchanges.index');
    }
}
