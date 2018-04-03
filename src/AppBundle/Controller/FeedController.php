<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Feed;
use AppBundle\Form\FeedType;

/**
 * Feed controller.
 *
 * @Route("/")
 */
class FeedController extends Controller
{
    /**
     * Lists all Feed entities.
     *
     * @Route("/", name="feed_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $feeds = $em->getRepository('AppBundle:Feed')->findAll();

        return $this->render('feed/index.html.twig', array(
            'feeds' => $feeds,
        ));
    }

    /**
     * Creates a new Feed entity.
     *
     * @Route("/new", name="feed_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $feed = new Feed();
        $form = $this->createForm('AppBundle\Form\FeedType', $feed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($feed);
            $em->flush();

            return $this->redirectToRoute('feed_show', array('id' => $feed->getId()));
        }

        return $this->render('feed/new.html.twig', array(
            'feed' => $feed,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Feed entity.
     *
     * @Route("/{id}", name="feed_show")
     * @Method("GET")
     */
    public function showAction(Feed $feed)
    {
        $deleteForm = $this->createDeleteForm($feed);
        $data = [
            'feed' => $feed,
            'delete_form' => $deleteForm->createView()
        ];

        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $feed->getUrl());
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
        $feed = curl_exec($curl_handle);
        curl_close($curl_handle);
        $xml = simplexml_load_string($feed);

        $data['items'] = [];
        if ($xml) {
            $items = $xml->channel->item;
            $data['items'] = $items;
        }

        return $this->render('feed/show.html.twig', $data);
    }

    /**
     * Displays a form to edit an existing Feed entity.
     *
     * @Route("/{id}/edit", name="feed_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Feed $feed)
    {
        $deleteForm = $this->createDeleteForm($feed);
        $editForm = $this->createForm('AppBundle\Form\FeedType', $feed);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($feed);
            $em->flush();

            return $this->redirectToRoute('feed_index');
        }

        return $this->render('feed/edit.html.twig', array(
            'feed' => $feed,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Feed entity.
     *
     * @Route("/{id}", name="feed_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Feed $feed)
    {
        $form = $this->createDeleteForm($feed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($feed);
            $em->flush();
        }

        return $this->redirectToRoute('feed_index');
    }

    /**
     * Creates a form to delete a Feed entity.
     *
     * @param Feed $feed The Feed entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Feed $feed)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('feed_delete', array('id' => $feed->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
