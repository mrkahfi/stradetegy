<?php

namespace Jariff\MemberBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Jariff\ProjectBundle\Controller\BaseController;
use Jariff\AdminBundle\Entity\Inbound;

/**
 * Esign controller.
 */
class EsignController extends BaseController
{
    /**
     * @Route("/test-get-esign/{token}", name="test_esign_get")
     */
    public function testGetAction($token)
    {
        $entity = $this->repo('JariffAdminBundle:Lead')->findOneByToken($token);

        if (!$entity) {
            return $this->errorRedirect('Data invalid');
        }

        $this->echoSignGet($entity->getSubscription());
        die();
    }

    public function echoSignGet($entity){

        $params = $this->container->getParameter('jariff_admin');
        $S      = new \SOAPClient($params['echosign_url']);
        $r      = $S->getDocumentInfo(array(
                    'apiKey'      => $params['echosign_api_key'],
                    'documentKey' => $entity->getDocumentKey(),
                ));
        // var_dump($r);
        // var_dump($r->documentInfo->status);
        var_dump(array_pop($r->documentInfo->events->DocumentHistoryEvent));
        // OUT_FOR_SIGNATURE
        // SIGNED

        $documentKey = $r->documentInfo->documentKey;
        $latestDocumentKey = $r->documentInfo->latestDocumentKey;
        $r      = $S->getDocumentUrls(array(
                    'apiKey'      => $params['echosign_api_key'],
                    'documentKey' => $documentKey,
                    'options' => array(
                        'versionKey' => $latestDocumentKey
                        ),
                ));
        // var_dump($r);
        // var_dump($r->getDocumentUrlsResult->urls->DocumentUrl->url);
        die();
    }

    /**
     * @Route("/test-send-esign/{token}", name="test_esign_send")
     */
    public function testSendAction($token)
    {
        $entity = $this->repo('JariffMemberBundle:Pending')->findOneByToken($token);

        if (!$entity) {
            return $this->errorRedirect('Data invalid');
        }

        $this->echoSignSend($entity);
        die();
    }

    public function echoSignSend($entity){
        $content   = $this->renderView('JariffMemberBundle:Esign:subscription-agreement.html.twig', array(
            'entity'  => $entity
        ));

        $dir = $this->get('kernel')->getRootDir().'/contracts/'.$entity->getLead()->getDateCreate()->format('Y/m/d/');
        $filename = $dir.$entity->getLead()->getToken().'.pdf';

        if(!is_dir($dir)){
            if(!mkdir($dir, 0755, true)) {
                throw new \Exception('Failed to create directory '.$dir);
            }
        }

        file_put_contents($filename, $this->get('knp_snappy.pdf')->getOutputFromHtml($content));

        $params = $this->container->getParameter('jariff_admin');
        $S      = new \SOAPClient($params['echosign_url']);
        $r      = $S->sendDocument(array(
                    'apiKey'               => $params['echosign_api_key'],
                    'documentCreationInfo' => array(
                        'recipients' => array(
                            'RecipientInfo' => array(
                                'email' => $entity->getLead()->getContact()->first()->getEmail(),
                                'role'  => 'SIGNER'
                            )
                        ),
                        'ccs'           => 'ardianys@outlook.com',
                        'name'          => "Stradetegy New Contract",
                        // 'message'    => '',
                        'signatureType' => 'ESIGN',
                        'signatureFlow' => 'SENDER_SIGNATURE_NOT_REQUIRED',
                        'fileInfos'     => array(
                            'FileInfo' => array(
                                'file'     => file_get_contents($filename),
                                'fileName' => 'Contract #'.$entity->getId().'.pdf',
                            ),
                        ),
                    ),
                ));

        $entity->setDocumentKey($r->documentKeys->DocumentKey->documentKey);
        $this->em()->flush();
        // var_dump($r->documentKeys->DocumentKey->documentKey);
        return true;
    }

    /**
     * display pdf contract
     * 
     * @Route("/pdf/{token}", name="pending_pdf")
     * @Method("GET")
     */
    public function pdfAction($token)
    {
        $entity = $this->repo('JariffMemberBundle:Pending')->findOneByToken($token);

        if (!$entity) {
            return $this->errorRedirect('Data invalid');
        }

        $html = $this->renderView('JariffMemberBundle:Esign:subscription-agreement.html.twig', array(
            'entity'  => $entity
        ));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="'.$token.'.pdf"'
            )
        );

    }
}
