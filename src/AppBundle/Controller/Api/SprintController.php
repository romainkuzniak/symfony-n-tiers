<?php

namespace AppBundle\Controller\Api;

use AppBundle\Exception\SprintAlreadyClosedException;
use AppBundle\Exception\SprintNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Romain Kuzniak <romain.kuzniak@turn-it-up.org>
 */
class SprintController extends Controller
{
    /**
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function closeAction($id)
    {
        try {
            $report = $this->get('service.sprint')->closeSprint($id);
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse($report);
        } catch (SprintNotFoundException $snfe) {
            throw new NotFoundHttpException();
        } catch (SprintAlreadyClosedException $sace) {
            return new JsonResponse('Sprint already closed', 400);
        }
    }

    /**
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction($id)
    {
        $sprint = $this->get('service.sprint')->get($id);

        $response = new JsonResponse();
        $response->setContent($this->get('jms_serializer')->serialize($sprint, 'json'));

        return $response;
    }
}
